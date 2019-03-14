<?php

use Projet\Enchere;

require __DIR__ . '/../bootstrap.php';

$tpl->assign('encheres', Enchere::listEncheresCourantes());

$tpl->display('encheres.tpl');
