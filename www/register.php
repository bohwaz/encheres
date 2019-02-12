<?php

require __DIR__ . '/../bootstrap.php';

use Projet\Membre;

$form_rules = [
	'email' => 'required|email',
	'passe' => 'required|confirmed',
];

if (form('register', $form_rules))
{
	try {
		$m = new Membre;
		$m->set('email', form_field('email'));
		$m->set('passe', form_field('passe'));
		$m->save();

		$m::login($m->email, form_field('passe'));
		redirect();
	}
	catch (User_Exception $e)
	{
		form_error($e->getMessage());
	}
}

$form_fields = [
	'email' => ['input' => 'email', 'name' => 'Adresse e-mail', 'null' => false],
	'passe' => ['input' => 'password', 'name' => 'Mot de passe', 'null' => false],
	'passe_confirmed' => ['input' => 'password', 'name' => 'Mot de passe (confirmation)', 'null' => false],
];

$tpl->assign('fields', $form_fields);
$tpl->display('register.tpl');
