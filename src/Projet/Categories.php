<?php declare(strict_types=1);

namespace Projet;

class Categories
{
	use Editable;

	/**
	 * @var int
	 * @primary
	 */
	public $id;

	/**
	 * @var string
	 * @unique
	 * @field text
	 */
	public $nom;
}