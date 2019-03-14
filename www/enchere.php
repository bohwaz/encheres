<?php

use Projet\Enchere;
use Projet\Produit;
use Projet\Mise;

require __DIR__ . '/../bootstrap.php';

if (!$user)
{
	redirect('/admin/login.php');
}

$enchere = new Enchere($_GET['id']);

if (form('make_offer', ['min' => 'numeric|min:0.01|lte:max', 'max' => 'numeric|min:0.01|gte:min']))
{
	Mise::addRange($enchere->id, $user->id, intval(form_field('min')*100), intval(form_field('max')*100));
	redirect('/enchere.php?id=' . $enchere->id);
}

$produit = new Produit($enchere->produit);

$fields = [
	'min' => ['input' => 'money', 'name' => 'De', 'null' => false, 'min' => 0.01, 'value' => 1],
	'max' => ['input' => 'money', 'name' => 'À', 'null' => false, 'min' => 0.01, 'value' => 9],
];

$tpl->assign('mise_fields', $fields);

$tpl->assign('enchere', $enchere);
$tpl->assign('produit', $produit);
$tpl->assign('images', $produit->listImages());
$tpl->assign('details', $produit->listDetails());

$tpl->display('enchere.tpl');
