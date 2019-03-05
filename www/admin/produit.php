<?php

use Projet\Produit;

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

$tpl->assign('produit', $produit);
$tpl->assign('images', $produit->listImages());

$tpl->assign('image_fields', [
	'image' => ['input' => 'file', 'name' => 'Image']
]);

$tpl->display('admin/produit.tpl');
