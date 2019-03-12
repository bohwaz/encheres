<?php

use Projet\Produit;
use Projet\ProduitDetail;
use Projet\Categorie;

const REQUIRE_ADMIN = true;

require __DIR__ . '/_inc.php';

$produit = new Produit($_GET['id']);

if (form('add_image', ['image' => 'required|file']))
{
	$produit->addImage($_FILES['image']['tmp_name']);
	redirect('/admin/produit.php?id=' . $produit->id);
}
elseif (!empty($_GET['delete_image']))
{
	$produit->deleteImage($_GET['delete_image']);
	redirect('/admin/produit.php?id=' . $produit->id);
}
elseif (form('add_detail'))
{
	$produit->addDetail($_POST['detail'], $_POST['valeur']);
	redirect('/admin/produit.php?id=' . $produit->id);
}
elseif (!empty($_GET['set_image']))
{
	$produit->image = (int) $_GET['set_image'];
	$produit->save();
	redirect('/admin/produit.php?id=' . $produit->id);
}

$categorie = new Categorie($produit->categorie);

$tpl->assign('produit', $produit);
$tpl->assign('images', $produit->listImages());
$tpl->assign('details', $produit->listDetails());

$tpl->assign('image_fields', [
	'image' => ['input' => 'file', 'name' => 'Image']
]);

$tpl->assign('detail_fields', [
	'detail' => ['input' => 'select', 'values' => $categorie->listDetails(), 'name' => 'Type'],
	'valeur' => ['input' => 'text', 'name' => 'Valeur'],
]);

$tpl->display('admin/produit.tpl');
