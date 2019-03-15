<?php declare(strict_types=1);

namespace Projet;

use stdClass;

class Membre extends Entity
{
	static protected $logged_user;

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
	 * @field money
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
	 * @default 0
	 */
	protected $admin = 0;

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

		if (!isset($_SESSION))
		{
			session_start();
		}

		$_SESSION['user'] = (array) $user;

		return true;
	}

	static public function logout(): bool
	{
		$_SESSION = [];
		setcookie(session_name(), '', 0);
		self::$logged_user = null;
		return session_destroy();
	}

	static public function getLoggedUser(): ?Membre
	{
		if (null !== self::$logged_user)
		{
			return self::$logged_user;
		}

		if (!isset($_COOKIE[session_name()]))
		{
			return null;
		}

		if (!isset($_SESSION))
		{
			session_start();
		}

		if (empty($_SESSION['user']))
		{
			return null;
		}

		$membre = new Membre;
		$membre->exists = true;

		foreach ($_SESSION['user'] as $key=>$value)
		{
			$membre->$key = $value;
		}

		self::$logged_user = $membre;

		return $membre;
	}

	static public function updateLoggedUser(Membre $user): void
	{
		self::$logged_user = $user;
		$_SESSION['user'] = $user->toArray();
		$_SESSION['user']['id'] = $user->id;
	}

	public function addCredit(int $amount): bool
	{
		$this->__set('credit', $this->credit + $amount);
		return $this->save();
	}

	static public function refresh(): void
	{
		if (!isset($_COOKIE[session_name()]))
		{
			return;
		}

		if (!isset($_SESSION))
		{
			session_start();
		}
	}
}
