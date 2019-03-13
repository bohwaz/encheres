<?php declare(strict_types=1);

namespace Projet;

use KD2\ErrorManager as EM;
use KD2\Form;

spl_autoload_register(function (string $class): void {
	if (strpos($class, 'Projet\\') === 0)
	{
		$parent =  __DIR__ . '/src';
	}
	else
	{
		$parent = __DIR__ . '/vendor';
	}

	$path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
	$path = $parent . '/' . $path . '.php';

	if (!file_exists($path))
	{
		throw new \Exception('Cannot require: ' . $path);
	}

	require $path;
});

class User_Exception extends \RuntimeException {}

define('ROOT', __DIR__);

// Activation du gestionnaire d'erreur
EM::enable(EM::DEVELOPMENT);

if (!file_exists(ROOT . '/config.local.php'))
{
	echo "Le fichier config.local.php à la racine n'existe pas. Merci de bien vouloir le créer et le renseigner à partir du fichier config.dist.php.\n";
	exit;
}

require ROOT . '/config.local.php';

Form::tokenSetSecret(SECRET_KEY);

require ROOT . '/template.php';

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

	if (!isset($_POST[$id]))
	{
		return;
	}

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
