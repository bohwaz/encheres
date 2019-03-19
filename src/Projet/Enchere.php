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
	 * @field money
	 * @name Prix public du produit
	 */
	protected $prix_public;

	/**
	 * @var int
	 * @field money
	 * @name Coût de la mise
	 */
	protected $cout_mise;

	/**
	 * @var DateTime
	 * @field datetime
	 * @name Date de début de l'enchère
	 */
	protected $date_debut;

	/**
	 * @var DateTime
	 * @field datetime
	 * @name Date de fin de l'enchère
	 */
	protected $date_fin;

	static public function list(string $order = 'id'): array
	{
		return self::populateFromQuery('SELECT __table.*, p.nom AS nom FROM __table INNER JOIN produits AS p ON p.id = __table.produit ORDER BY __table.' . $order);
	}

	public function getWinner(): ?Mise
	{
		$id = DB::getInstance()->firstColumn('SELECT id FROM mise_gagnante WHERE enchere = ? LIMIT 1;', $this->id);
		return $id ? new Mise($id) : null;
	}

	static public function listEncheresCourantes(): array
	{
		return self::populateFromQuery('SELECT * FROM liste_encheres WHERE NOW() BETWEEN date_debut AND date_fin;');
	}

	static public function listEncheresTerminees(): array
	{
		return self::populateFromQuery('SELECT * FROM liste_encheres WHERE NOW() > date_fin;');
	}

	public function listMises(): array
	{
		return Mise::populateFromQuery('SELECT * FROM mises_statuts WHERE enchere = ?;', $this->id);
	}

	public function listMisesForUser(Membre $membre): array
	{
		return Mise::populateFromQuery('SELECT * FROM mises_statuts WHERE enchere = ? AND montant IN (SELECT montant FROM mises WHERE membre = ? AND enchere = ?);', $this->id, $membre->id, $this->id);
	}

	public function selfCheck(): void
	{
		parent::selfCheck();

		if ($this->date_debut >= $this->date_fin) {
			throw new User_Exception('La date de fin ne peut être avant la date de début');
		}
	}
}