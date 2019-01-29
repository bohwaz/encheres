<?php declare(strict_types=1);

namespace Projet;

use KD2\DB AS KD2_DB;

class DB extends KD2_DB
{
	static protected $_instance = null;

	static public function getInstance(bool $create = false): DB
	{
		return self::$_instance ?: self::$_instance = new DB;
	}

	private function __clone()
	{
		// Désactiver le clonage, car on ne veut qu'une seule instance
	}

	public function __construct()
	{
		return parent::__construct('mysql', [
			'host' => MYSQL_HOST,
			'user' => MYSQL_USER,
			'password' => MYSQL_PASS,
			'database' => MYSQL_DATABASE,
		]);
	}
}
