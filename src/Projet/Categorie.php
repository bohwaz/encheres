<?php declare(strict_types=1);

namespace Projet;

class Categorie extends Entity
{
	protected $table = 'categories';

	/**
	 * @var int
	 * @primary
	 */
	protected $id;

	/**
	 * @var string
	 * @unique
	 * @field text
	 * @name Nom de la catégorie
	 */
	protected $nom;

	public function listDetails(): array
	{
		return DB::getInstance()->getAssoc('SELECT id, nom FROM categories_details WHERE categorie = ?;', $this->id);
	}


	static public function listAllPossibleDetails(): array
	{
		$details = DB::getInstance()->get('SELECT cd.categorie AS cid, cd.nom, pd.valeur FROM produits_details pd INNER JOIN categories_details cd ON cd.id = pd.detail GROUP BY cd.categorie, cd.nom, pd.valeur ORDER BY cd.nom, pd.valeur;');

		$out = [];

		foreach ($details as $detail)
		{
			if (!isset($out[$detail->cid])) {
				$out[$detail->cid] = [];
			}

			if (!isset($out[$detail->cid][$detail->nom])) {
				$out[$detail->cid][$detail->nom] = [];
			}

			$out[$detail->cid][$detail->nom][] = $detail->valeur;
		}

		return $out;
	}
}