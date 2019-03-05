<?php

use KD2\Smartyer;
use KD2\Form;
use Projet\Membre;

// Initialisation gestionnaire de templates

$tpl = new Smartyer;
$tpl->setTemplatesDir(ROOT . '/templates');
$tpl->setCompiledDir(ROOT . '/cache/compiled');

$tpl->assign('www_url', '/');
$tpl->assign('site_title', SITE_TITLE);

$user = Membre::getLoggedUser();
$user_is_admin = (bool) $user->admin;

$tpl->assign('user', $user ? $user->toArray() : null);
$tpl->assign('is_admin', $user_is_admin);
$tpl->assign('is_logged', (bool) $user);

$form_errors = [];

// Fonctions utilitaires

function redirect($uri = '/')
{
	header('Location: ' . $uri);
	exit;
}

function form($id, array $rules = [])
{
	if ($_SERVER['REQUEST_METHOD'] != 'POST') return;

	global $tpl, $form_errors;
	return Form::check($id, $rules, $form_errors);
}

function form_fields(array $fields = [])
{
	extract($_POST);
	return compact($fields);
}

function form_field($name)
{
	return $_POST[$name] ?? null;
}

function form_error($message)
{
	global $form_errors;
	$form_errors[] = $message;
}

$tpl->register_function('form', function ($params, $tpl) {
	foreach ($params['fields'] as $key => &$field)
	{
		if (isset($params['source'][$key]))
		{
			$field['value'] = $params['source'][$key];
		}

		if (isset($_POST[$key]))
		{
			$field['value'] = $_POST[$key];
		}
	}

	$tpl->assign($params);
	$tpl->assign('url_self', $_SERVER['PHP_SELF']);
	$tpl->assign('csrf', Form::tokenHTML($params['id']));
	return $tpl->fetch('_form.tpl');
});

$tpl->register_function('form_errors', function ($params, $tpl) {
	global $form_errors;

	if (!count($form_errors))
	{
		return '';
	}

	$tpl->assign('errors', $form_errors);
	return $tpl->fetch('_form_errors.tpl');
});

$tpl->register_modifier('money', function ($amount) {
	return sprintf('%d,%02d €', $amount / 100, $amount % 100);
});
