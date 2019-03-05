<?php

namespace Projet;

use ReflectionClass;
use ReflectionProperty;

use RuntimeException;

use DateTime;
use stdClass;

use KD2\Form;

abstract class Entity
{
	const DEFAULT_ANNOTATIONS = [
		'null'   => false,
		'unique' => false,
		'trim'   => false,
		'default'=> null,
	];

	protected $modified = [];
	protected $fields = [];
	protected $exists = false;
	protected $id;

	public function __set($key, $value): void
	{
		$annotations =& $this->fields[$key];

		if (null !== $annotations)
		{
			$value = $this->_getFieldValue($value, $annotations);

			if ($annotations->field == 'password')
			{
				$value = password_hash($value, PASSWORD_DEFAULT);
			}
		}

		$this->$key = $value;
		$this->modified[$key] = $value;
	}

	public function set($key, $value = null): void
	{
		if (is_array($key))
		{
			foreach ($key as $_key => $value)
			{
				$this->set($_key, $value);
			}

			return;
		}

		$rules = $this->_getFieldRules($this->fields[$key]);

		if (!Form::validate([$key => $rules], $errors, [$key => $value]))
		{
			throw new User_Exception($this->_getErrorMessage($errors));
		}

		if ($this->fields[$key]->unique)
		{
			$db = DB::getInstance();

			if ($db->test($this->table, $db->quoteIdentifier($key) . ' = ?', $value))
			{
				throw new User_Exception($this->getErrorValidationMessage('unique', $this->fields[$key]->name));
			}
		}

		$this->__set($key, $value);
	}

	public function __isset($key)
	{
		return property_exists($this, $key);
	}

	public function __get($key)
	{
		return $this->$key;
	}

	public function __construct(int $id = null)
	{
		$this->_findAnnotations();

		if (null !== $id)
		{
			$data = DB::getInstance()->first('SELECT * FROM ' . $this->table . ' WHERE id = ?', $id);

			if (!$data)
			{
				throw new \RuntimeException(sprintf('No entry #%d found in \'%s\'', $id, $this->table));
			}

			foreach ($data as $key => $value)
			{
				if (!property_exists($this, $key))
				{
					throw new \LogicException(sprintf('Column \'%s\' is not an object property', $key));
				}

				if (array_key_exists($key, $this->fields))
				{
					$this->$key = $this->_getFieldValue($value, $this->fields[$key]);
				}
				else
				{
					$this->$key = $value;
				}
			}

			$this->exists = true;
		}
	}

	protected function selfCheck(): void
	{
		foreach ($this->fields as $key=>$annotations)
		{
			$rules = $this->_getFieldRules($this->fields[$key]);

			if (!Form::validate([$key => $rules], $errors, [$key => $this->$key]))
			{
				throw new User_Exception($this->_getErrorMessage($errors));
			}
		}
	}

	public function save(): bool
	{
		$this->selfCheck();

		$db = DB::getInstance();

		if (!$this->exists)
		{
			if ($db->insert($this->table, $this->modified))
			{
				$this->id = $db->lastInsertRowId();
				$this->modified = [];
				$this->exists = true;
				return true;
			}
		}
		else
		{
			if ($db->update($this->table, $this->modified, 'id = :id', ['id' => (int) $this->id]))
			{
				$this->modified = [];
				return true;
			}
		}

		return false;
	}

	public function delete()
	{
		if (!$this->exists)
		{
			throw new \LogicException('Can not delete an object that has not been saved');
		}

		if (DB::getInstance()->delete($this->table, 'id = ?' , $this->id))
		{
			$this->exists = false;
			return true;
		}

		return false;
	}

	public function getFields()
	{
		return $this->fields;
	}

	protected function _getFieldValue($value, stdClass $annotations)
	{
		if ($annotations->trim)
		{
			$value = trim($value);
		}

		switch ($annotations->var)
		{
			case 'integer':
			case 'int':
				return (int) $value;
			case 'DateTime':
				return is_object($value) && $value instanceof DateTime ? $value : new DateTime($value);
			default:
				return $value;
		}
	}

	protected function _getFieldRules(stdClass $annotations)
	{
		$rules = ['required' => []];

		switch ($annotations->field)
		{
			case 'email':
				$rules['email'] = [];
				break;
			case 'number':
				$rules['numeric'] = [];
				 break;
		}

		return $rules;
	}

	static public function getErrorValidationMessage(string $rule, string $name)
	{
		switch ($rule)
		{
			case 'required':
				return sprintf('Le champ "%s" est requis.', $name);
			case 'email':
				return sprintf('Le champ "%s" doit être une adresse e-mail valide.', $name);
			case 'date_format':
				return sprintf('Format de date invalide dans le champ %s.', $name);
			case 'numeric':
				return sprintf('Le champ %s doit être un nombre.', $name);
			default:
				return sprintf('Erreur "%s" dans le champ "%s"', $rule, $name);
		}
	}

	protected function _getErrorMessage(array $errors)
	{
		$messages = [];

		foreach ($errors as $error)
		{
			$messages = self::getErrorValidationMessage($error['rule'], $this->fields[$error['name']]->name);
		}

		return implode("\n", $messages);
	}

	/**
	 * Finds all properties of the object and their annotations
	 */
	protected function _findAnnotations(): void
	{
		$class = new ReflectionClass(static::class);
		$properties = $class->getProperties(ReflectionProperty::IS_PROTECTED);

		foreach ($properties as $property)
		{
			$annotations = $this->_parsePropertyAnnotations($property);

			if (null === $annotations)
			{
				continue;
			}

			$this->fields[$property->getName()] = $annotations;
		}
	}

	protected function _parsePropertyAnnotations(ReflectionProperty $property)
	{
		$comment = $property->getDocComment();

		// Parse annotations
		if (!$comment || !preg_match_all('/@(\w+)(?:\s+(.+))?$/msU', $comment, $matches))
		{
			return null;
		}

		$result = array_combine($matches[1], $matches[2]);

		if (empty($result['field']))
		{
			return null;
		}

		foreach ($result as $key => &$value)
		{
			if ($value === '')
			{
				$value = true;
			}
		}

		unset($key, $value);

		$result = array_merge(self::DEFAULT_ANNOTATIONS, $result);

		return (object) $result;
	}

	public function toArray()
	{
		$out = [];

		foreach ($this->fields as $key => $value)
		{
			$out[$key] = $this->$key;
		}

		return $out;
	}

	public function getFormFields()
	{
		$form = [];

		foreach ($this->fields as $key => $annotations)
		{
			$form[$key] = [
				'input' => $annotations->field,
				'name'  => $annotations->name,
				'null'  => $annotations->null,
			];
		}

		return $form;
	}

	static public function create()
	{
		$name = static::class;
		$obj = new $name;

		foreach ($obj->getFields() as $key => $annotations)
		{
			if (array_key_exists($key, $_POST))
			{
				$obj->set($key, $_POST[$key]);
			}
		}

		return $obj;
	}

	static public function list(string $order = 'id'): array
	{
		$out = [];
		$self = static::class;
		$obj = new $self;
		$list = DB::getInstance()->get('SELECT * FROM ' . $obj->table . ' ORDER BY ' . $order);

		foreach ($list as $row)
		{
			$obj = new $self;

			foreach ($row as $key=>$value)
			{
				$obj->$key = $value;
			}

			$out[] = $obj;
		}

		return $out;
	}
}