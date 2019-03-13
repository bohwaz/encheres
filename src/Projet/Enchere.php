<?php declare(strict_types=1);

namespace Projet;

class Enchere extends Entity
{
	protected $table = 'encheres';

	/**
	 * @var int
	 */
	protected $id;

	/**
	 * @var int
	 * @field select
	 * @references produits id nom
	 * @name Produit
	 */
	protected $produit;

	/**
	 * @var int
	 * @field number
	 * @name Prix public du produit
	 */
	protected $prix_public;

	/**
	 * @var int
	 * @field number
	 * @name Coût de la mise
	 */
	protected $cout_mise;

	/**
	 * @var date
	 * @field datetime
	 * @name Date de début de l'enchère
	 */
	protected $date_debut;

	/**
	 * @var date
	 * @field datetime
	 * @name Date de fin de l'enchère
	 */
	protected $date_fin;

	static public function list(string $order = 'id'): array
	{
		return self::populateFromQuery('SELECT *, p.nom AS nom FROM __table INNER JOIN produits AS p ON p.id = __table.produit ORDER BY __table.' . $order);
	}

	static public function listEncheresCourantes(): array
	{
		return self::populateFromQuery('SELECT * FROM liste_encheres_courantes;');
	}
}