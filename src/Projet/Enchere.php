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

	/**
	 * @var int
	 */
	protected $nb_mises;

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
		return self::populateFromQuery('SELECT * FROM liste_encheres_courantes;');
	}

	public function listMises(): array
	{
		return Mise::populateFromQuery('SELECT montant, COUNT(*) AS nb FROM mises WHERE enchere = ? GROUP BY montant ORDER BY montant ASC;', $this->id);
	}

	public function listMisesForUser(Membre $membre): array
	{
		return Mise::populateFromQuery('SELECT * FROM mes_mises WHERE enchere = ? AND utilisateur = ?;', $this->id, $membre->id);
	}

	public function selfCheck(): void
	{
		parent::selfCheck();

		if ($this->date_debut >= $this->date_fin) {
			throw new User_Exception('La date de fin ne peut être avant la date de début');
		}
	}
}