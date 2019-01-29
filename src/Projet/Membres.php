<?php declare(strict_types=1);

namespace Projet;

use stdClass;

class Membres
{
	public function login(string $email, string $password): bool
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

	public function logout(): bool
	{
		$_SESSION = [];
		setcookie(session_name(), null, 0);
		return session_destroy();
	}

	public function create(string $email, string $password, bool $admin = false): bool
	{
		return DB::getInstance()->insert('membres', [
			'email' => trim($email),
			'passe' => password_hash($password, PASSWORD_DEFAULT),
			'admin' => (int) $admin,
		]);
	}

	public function register(string $email, string $password): bool
	{
		$this->create($email, $password, false);

		mail($email, sprintf('Bienvenue sur %s !', SITE_TITLE),
			sprintf("Bienvenue sur %s !\n\nVous allez pouvoir profiter du meilleur de la high tech !", SITE_TITLE),
			sprintf("From: %s\r\n", SITE_EMAIL)
		);
	}

	public function getLoggedUser(): ?stdClass
	{
		if (!isset($_COOKIE[session_name()]))
		{
			return false;
		}

		session_start();

		return $_SESSION['user'] ?? null;
	}

	public function addCredit(int $user_id, int $amount): bool
	{
		return DB::getInstance()->preparedQuery('UPDATE membres SET montant = montant + ? WHERE id = ?;', [$amount, $user_id]);
	}
}
