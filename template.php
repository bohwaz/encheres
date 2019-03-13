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
$user_is_admin = !empty($user->admin);

$tpl->assign('user', $user ? $user->toArray() : null);
$tpl->assign('is_admin', $user_is_admin);
$tpl->assign('is_logged', (bool) $user);

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
	$tpl->assign('url_self', $_SERVER['REQUEST_URI']);
	$tpl->assign('id', $params['id']);
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

$tpl->register_modifier('image_url', function ($id) {
	$path = sprintf(IMAGE_PATH, $id);
	$path = str_replace(ROOT . '/www', '', $path);
	return $path;
});

$tpl->register_modifier('image_thumb_url', function ($id) {
	$path = sprintf(THUMBNAIL_PATH, $id);
	$path = str_replace(ROOT . '/www', '', $path);
	return $path;
});
