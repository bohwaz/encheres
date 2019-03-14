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
	$source = isset($params['source']) ? is_object($params['source']) : null;

	foreach ($params['fields'] as $key => &$field)
	{
		$field['value'] = null;

		if (isset($_POST[$key]))
		{
			$field['value'] = $_POST[$key];
		}
		elseif (isset($params['source']) && is_object($params['source']) && isset($params['source']->$key))
		{
			$field['value'] = $params['source']->$key;
		}
		elseif (isset($params['source']) && is_array($params['source']) && isset($params['source'][$key]))
		{
			$field['value'] = $params['source'][$key];
		}

		if ($field['input'] == 'money') {
			$field['input'] = 'number';
			$field['min'] = '0.01';
			$field['step'] = '0.01';

			if (!isset($_POST[$key]))
			{
				$field['value'] = sprintf('%d.%02d', $field['value'] / 100, $field['value'] % 100);
			}
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
