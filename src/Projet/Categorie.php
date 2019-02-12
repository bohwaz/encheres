<?php declare(strict_types=1);

namespace Projet;

class Categorie extends Entity
{
	protected $table = 'categories';

	/**
	 * @var int
	 * @primary
	 */
	public $id;

	/**
	 * @var string
	 * @unique
	 * @field text
	 * @name Nom de la catégorie
	 */
	public $nom;
}