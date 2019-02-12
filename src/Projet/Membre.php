<?php declare(strict_types=1);

namespace Projet;

use stdClass;

class Membre extends Entity
{
	protected $table = 'membres';

	/**
	 * @var string
	 * @field email
	 * @unique
	 * @name Adresse E-Mail
	 */
	protected $email;

	/**
	 * @var string
	 * @field password
	 * @name Mot de passe
	 */
	protected $passe;

	/**
	 * @var integer
	 * @field number
	 * @name Crédit
	 */
	protected $credit = 0;

	/**
	 * @var DateTime
	 * @default now
	 * @name Date d'inscription
	 */
	protected $date_inscription;

	/**
	 * @var bool
	 * @field checkbox
	 * @name Administrateur
	 */
	protected $admin;

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

	public function addCredit(int $amount): bool
	{
		$this->__set('credit', $this->credit + $amount);
		return $this->save();
	}
}
