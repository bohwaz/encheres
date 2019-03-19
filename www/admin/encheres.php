<?php

use Projet\Enchere;

const REQUIRE_ADMIN = true;

require __DIR__ . '/_inc.php';

if (form('add'))
{
	$p = Enchere::createFromForm();
	$p->save();
	redirect('/admin/encheres.php');
}
elseif (!empty($_GET['delete']))
{
	$p = new Enchere($_GET['delete']);
	$p->delete();

	redirect('/admin/encheres.php');
}

$tpl->assign('list', Enchere::list('date_fin'));

$tpl->assign('fields', (new Enchere)->getFormFields());
$tpl->display('admin/encheres.tpl');
