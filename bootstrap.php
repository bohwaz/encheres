<?php declare(strict_types=1);

namespace Projet;

use KD2\ErrorManager as EM;
use KD2\Smartyer;

spl_autoload_register(function (string $class): void {
	if (strpos($class, 'Projet\\') === 0)
	{
		$parent =  __DIR__ . '/src';
	}
	else
	{
		$parent = __DIR__ . '/vendor';
	}

	$path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
	$path = $parent . $path . '.php';

	require $path;
});

class User_Exception extends \RuntimeException {}

const ROOT = __DIR__;

// Activation du gestionnaire d'erreur
EM::enable(EM::DEVELOPMENT);

require ROOT . '/config.local.php';

$tpl = new Smartyer;
$tpl->setTemplatesDir(ROOT . '/templates');
$tpl->setCompiledDir(ROOT . '/cache/compiled');

$tpl->assign('www_url', '/');
$tpl->assign('site_title', SITE_TITLE);

