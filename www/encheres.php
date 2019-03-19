<?php

use Projet\Enchere;

require __DIR__ . '/../bootstrap.php';

if (isset($_GET['ended']))
{
	$tpl->assign('encheres', Enchere::listEncheresTerminees());
}
else
{
	$tpl->assign('encheres', Enchere::listEncheresCourantes());
}

$tpl->display('encheres.tpl');
