<?php

require __DIR__ . '/_inc.php';

use Projet\Membre;

$form_rules = [
	'amount' => 'numeric|required',
];

if (form('credit', $form_rules))
{
	$user->addCredit((int) form_field('amount') * 100);
	$user::updateLoggedUser($user);
	redirect('/admin/compte.php');
}

$form_fields = [
	'amount' => ['input' => 'number', 'name' => 'Montant à créditer', 'min' => 25, 'max' => 500, 'step' => 5, 'null' => false],
];

$tpl->assign('credit_fields', $form_fields);
$tpl->display('admin/compte.tpl');
