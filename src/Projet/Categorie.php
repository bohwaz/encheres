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
}