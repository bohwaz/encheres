<?php declare(strict_types=1);

namespace Projet;

class Categories
{
	public function add(string $nom): bool
	{
		return DB::getInstance()->insert('categories', [
			'nom' => trim($nom)
		]);
	}

	public function list(): array
	{
		return DB::getInstance()->get('SELECT * FROM categories ORDER BY nom;');
	}

	public function delete(int $id): bool
	{
		return DB::getInstance()->delete('categories', 'id = ?', $id);
	}
}