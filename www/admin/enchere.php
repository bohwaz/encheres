<?php

use Projet\Enchere;

const REQUIRE_ADMIN = true;

require __DIR__ . '/_inc.php';

$enchere = new Enchere($_GET['id']);

if (form('edit'))
{
	$enchere->updateFromForm();
	$enchere->save();
	redirect('/admin/enchere.php?id=' . $enchere->id);
}

$tpl->assign('enchere', $enchere);
$tpl->assign('mises', $enchere->listMises());

$tpl->assign('fields', $enchere->getFormFields());
$tpl->display('admin/enchere.tpl');
