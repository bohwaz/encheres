<?php

require __DIR__ . '/../../bootstrap.php';

if (!$user)
{
	redirect('user/login.php');
}

if (defined('REQUIRE_ADMIN') && REQUIRE_ADMIN && !$user->admin)
{
	die('Accès interdit'); // FIXME page d'erreur propre
}