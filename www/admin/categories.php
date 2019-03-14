<?php

use Projet\Categorie;

const REQUIRE_ADMIN = true;

require __DIR__ . '/_inc.php';

if (form('add'))
{
	$categorie = Categorie::createFromForm();
	$categorie->save();
	redirect('/admin/categories.php');
}
elseif (!empty($_GET['delete']))
{
	$categorie = new Categorie($_GET['delete']);
	$categorie->delete();

	redirect('/admin/categories.php');
}

$tpl->assign('list', Categorie::list('id'));

$tpl->assign('fields', (new Categorie)->getFormFields());
$tpl->display('admin/categories.tpl');
