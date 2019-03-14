<?php

require __DIR__ . '/../../bootstrap.php';

use Projet\Membre;

if (!empty($_GET['refresh']))
{
	// Rafraîchir la session
	Membre::refresh();

    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

    header('Content-Type: image/gif');
    echo base64_decode("R0lGODlhAQABAIAAAP///////yH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==");

    exit;
}

$form_rules = [
	'email' => 'required|email',
	'passe' => 'required',
];

if (form('login', $form_rules))
{
	if (Membre::login(form_field('email'), form_field('passe')))
	{
		redirect();
	}
	else
	{
		form_error('Adresse e-mail ou mot de passe invalide.');
	}
}

$form_fields = [
	'email' => ['input' => 'email', 'name' => 'Adresse e-mail', 'null' => false],
	'passe' => ['input' => 'password', 'name' => 'Mot de passe', 'null' => false],
];

$tpl->assign('fields', $form_fields);
$tpl->display('admin/login.tpl');
