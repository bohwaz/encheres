<?php

use Projet\Enchere;

const REQUIRE_ADMIN = true;

require __DIR__ . '/_inc.php';

if (form('add'))
{
	$p = Enchere::create();
	$p->save();
	redirect('/admin/encheres.php');
}
elseif (!empty($_GET['delete']))
{
	$p = new Encheres($_GET['delete']);
	$p->delete();

	redirect('/admin/encheres.php');
}

$tpl->assign('list', Enchere::list('id'));

$tpl->assign('fields', (new Enchere)->getFormFields());
$tpl->display('admin/encheres.tpl');
