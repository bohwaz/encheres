<?php

namespace Projet;

use ReflectionClass;
use ReflectionProperty;

use RuntimeException;

use DateTime;
use stdClass;

use KD2\Form;

class Entity
{
	const DEFAULT_ANNOTATIONS = [
		'null'   => false,
		'unique' => false,
		'trim'   => false,
	];

	protected $modified = [];
	protected $fields = [];
	protected $exists = false;
	protected $id;

	public function __set($key, $value)
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

				$this->$key = $this->_getFieldValue($value, $this->fields[$key]);
			}

			$this->exists = true;
		}
	}

	public function save(): bool
	{
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

		if (DB::getInstance()->delete($this->table, 'id' , $this->id))
		{
			$this->exists = false;
			return true;
		}

		return false;
	}

	public function list($page = 1)
	{
		$per_page = 50;
		$begin = ($page - 1) * $per_page;

		return DB::getInstance()->get('SELECT * FROM ' . $this->table . ' ORDER BY id DESC LIMIT ?,?;', $begin, $per_page);
	}

	public function setAll(array $fields): void
	{
		foreach ($fields as $key=>&$value)
		{
			$rules = $this->_getFieldRules($annotations);

			if (!Form::validate($key, $errors, $fields))
			{
				throw new User_Exception($this->_getErrorMessage($errors));
			}

			$this->__set($key, $value);
		}
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
		$rules = ['required'];

		switch ($annotations->field)
		{
			case 'email':
				$rules[] = 'email';
				break;
			case 'number':
				$rules[] = 'numeric';
				 break;
		}

		return $rules;
	}

	protected function _getErrorValidationMessage(string $rule, stdClass $field)
	{
		switch ($rule)
		{
			case 'required':
				return sprintf('Le champ "%s" est requis.', $field->name);
			case 'email':
				return sprintf('Le champ "%s" doit être une adresse e-mail valide.', $field->name);
			case 'date_format':
				return sprintf('Format de date invalide dans le champ %s.', $field->name);
			case 'numeric':
				return sprintf('Le champ %s doit être un nombre.', $field->name);
			default:
				return sprintf('Erreur "%s" dans le champ "%s"', $rule, $field->name);
		}
	}

	protected function _getErrorMessage(array $errors, array $fields)
	{
		$messages = [];

		foreach ($errors as $error)
		{
			$messages = $this->_getErrorValidationMessage($error['rule'], $fields[$error['name']]);
		}

		return implode("\n", $messages);
	}

	/**
	 * Finds all properties of the object and their annotations
	 */
	protected function _findAnnotations(): void
	{
		$class = new ReflectionClass($this);
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
}