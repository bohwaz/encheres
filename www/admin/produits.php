<?php

use Projet\Produit;
use Projet\Categorie;

const REQUIRE_ADMIN = true;

require __DIR__ . '/_inc.php';

if (form('add'))
{
	$p = Produit::create();
	$p->set('categorie', (int) $_POST['categorie'] ?? null);
	$p->save();
	redirect('/admin/produits.php');
}
elseif (!empty($_GET['delete']))
{
	$p = new Produit($_GET['delete']);
	$p->delete();

	redirect('/admin/produits.php');
}

$tpl->assign('list', Produit::list('id'));

$tpl->assign('fields', (new Produit)->getFormFields() + ['categorie' => ['input' => 'select', 'values' => Categorie::listAssoc('id', 'nom'), 'name' => 'Catégorie']]);
$tpl->display('admin/produits.tpl');
