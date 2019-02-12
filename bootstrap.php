<?php declare(strict_types=1);

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

const ROOT = __DIR__;

// Activation du gestionnaire d'erreur
EM::enable(EM::DEVELOPMENT);

require ROOT . '/config.local.php';

Form::tokenSetSecret(SECRET_KEY);

require ROOT . '/template.php';