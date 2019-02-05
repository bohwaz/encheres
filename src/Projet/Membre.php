<?php declare(strict_types=1);

namespace Projet;

use stdClass;

class Membre
{
	use Editable;

	/**
	 * @var integer
	 */
	public $id;

	/**
	 * @var string
	 * @field email
	 * @unique
	 * @name Adresse E-Mail
	 */
	public $email;

	/**
	 * @var string
	 * @field password
	 * @name Mot de passe
	 */
	public $password;

	/**
	 * @var integer
	 * @field number
	 * @name Crédit
	 */
	public $credit;

	/**
	 * @var DateTime
	 * @default now
	 * @name Date d'inscription
	 */
	public $date_inscription;

	/**
	 * @var bool
	 * @field checkbox
	 * @name Administrateur
	 */
	public $admin;

	static public function login(string $email, string $password): bool
	{
		$email = trim($email);
		$password = trim($password);

		$user = DB::getInstance()->first('SELECT * FROM membres WHERE email = ?;', $email);

		if (!$user)
		{
			return false;
		}

		if (!password_verify($password, $user->passe))
		{
			return false;
		}

		session_start();
		$_SESSION['user'] = $user;

		return true;
	}

	static public function logout(): bool
	{
		$_SESSION = [];
		setcookie(session_name(), null, 0);
		return session_destroy();
	}

	static public function getLoggedUser(): ?stdClass
	{
		if (!isset($_COOKIE[session_name()]))
		{
			return null;
		}

		session_start();

		if (empty($_SESSION['user_id']))
		{
			return null;
		}

		return new Membre($_SESSION['user_id']);
	}

	public function __construct(int $id = null)
	{
		$this->_initObjectData($id);
	}

	public function addCredit(int $amount): bool
	{
		return DB::getInstance()->preparedQuery('UPDATE membres SET credit = credit + ? WHERE id = ?;', [$amount, $this->id]);
	}
}
