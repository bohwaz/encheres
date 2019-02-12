<?php declare(strict_types=1);

namespace Projet;

use DateTime;

class Encheres
{
	public function listeEncheresCourantes(): array
	{
		return DB::getInstance()->get('SELECT * FROM liste_encheres_courantes;');
	}

	public function add(int $id_produit, int $cout_mise, int $prix_public, DateTime $debut, DateTime $fin): bool
	{
	}
}