<?php

use Projet\Produit;

const REQUIRE_ADMIN = true;

require __DIR__ . '/_inc.php';

if (form('add'))
{
	$p = Produit::create();
	$p->save();
	redirect('/admin/produits.php');
}
elseif (!empty($_GET['delete']))
{
	$p = new Produit($_GET['delete']);
	$p->delete();

	redirect('/admin/categories.php');
}

$tpl->assign('list', Produit::list('id'));

$tpl->assign('fields', (new Produit)->getFormFields());
$tpl->display('admin/produits.tpl');
