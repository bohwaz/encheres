<?php

use Projet\Enchere;
use Projet\Categorie;

require __DIR__ . '/../bootstrap.php';

$tpl->assign('categorie', null);

if (!empty($_GET['c']) && !empty($_GET['d']))
{
	$categorie = new Categorie($_GET['c']);
	$tpl->assign('categorie', $categorie->id);
	$tpl->assign('encheres', Enchere::searchWithDetails($categorie->id, $_GET['d']));
}


$tpl->assign('categories', Categorie::list('id'));
$tpl->assign('details', Categorie::listAllPossibleDetails());

$tpl->display('recherche.tpl');
