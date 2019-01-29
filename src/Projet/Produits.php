<?php declare(strict_types=1);

namespace Projet;

class Produits
{
	public function listeEncheresCourantes(): array
	{
		return DB::getInstance()->get('SELECT * FROM liste_encheres_courantes;');
	}
}