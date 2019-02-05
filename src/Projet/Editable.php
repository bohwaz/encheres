<?php

namespace Projet;

use ReflectionClass;
use RuntimeException;
use DateTime;
use stdClass;

use KD2\Form;

trait Editable
{
	public function __set($key, $value)
	{
		throw new RuntimeException('Variable modification is not allowed');
	}

	protected function _getFieldAnnotations(ReflectionProperty $property)
	{
		$comment = $property->getDocComment();

		// Parse annotations
		if (!preg_match_all('/@(\w+)(?:\s+(.+))?$/m', $comment, $matches))
		{
			throw new RuntimeException('Cannot edit a regular property: ' . $property->getName());
		}

		$result = array_combine($matches[1], $matches[2]);

		if (empty($result['field']))
		{
			return null;
		}

		foreach ($result as $key => &$value)
		{
			if (trim($value) == '')
			{
				$value = true;
			}
		}

		unset($key, $value);

		return (object) $result;
	}

	protected function _getFieldValue($value, stdClass $annotations)
	{
		switch ($annotations->var)
		{
			case 'integer':
			case 'int':
				return (int) $value;
			case 'DateTime':
				return new DateTime($value);
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

	protected function _initObjectData(int $id = null)
	{
		if (!$id)
		{
			return;
		}

		$table = get_class($this);

		$data = DB::getInstance()->first('SELECT * FROM ' . $table . ' WHERE id = ?', $id);
		$fields = $this->getAdminFields();

		foreach ($data as $key => $value)
		{
			$annotations = $fields[$key];
			$this->$key = $this->_getFieldValue($value, $annotations);;
		}
	}

	public function getEditableFields()
	{
		$class = new ReflectionClass($this);
		$properties = $class->getProperties(ReflectionProperty::IS_PUBLIC);
		$fields = [];

		foreach ($properties as $property)
		{
			$annotations = $this->_getFieldAnnotations($property);

			if (!$annotations)
			{
				continue;
			}

			$fields[$property->getName()] = $annotations;
		}

		return $fields;
	}

	public function setEditableFields(array $fields)
	{
		$available_fields = $this->getAdminFields();
		$class = new ReflectionClass($this);
		$data = [];

		foreach ($fields as $key=>&$value)
		{
			if (!isset($available_fields[$key]))
			{
				continue;
			}

			$annotations = $available_fields[$key];
			$value = trim($value);

			$rules = $this->_getFieldRules($annotations);

			if (!Form::validate($key, $errors, $fields))
			{
				throw new User_Exception($this->_getErrorMessage($errors));
			}

			// Reflection: get field validator, validate
			$value = $this->_getFieldValue($value, $annotations);

			if ($annotations->field == 'password')
			{
				$value = password_hash($value, PASSWORD_DEFAULT);
			}

			$this->$key = $value;
			$data[$key] = $value;
		}

		$table = get_class($this);
		$db = DB::getInstance();

		if (null === $this->id)
		{
			return $db->insert($table, $fields);
		}
		else
		{
			return $db->update($table, $fields, 'id = :id', ['id' => (int) $this->id]);
		}
	}

	public function delete()
	{
		if (!$this->id)
		{
			return true;
		}

		return DB::getInstance()->delete(get_class($this), 'id' , $this->id);
	}

	public function list($page = 1)
	{
		$per_page = 50;
		$begin = ($page - 1) * $per_page;
		$table = get_class($this);

		return DB::getInstance()->get('SELECT * FROM ' . $table . ' ORDER BY id DESC LIMIT ?,?;', $begin, $per_page);
	}
}