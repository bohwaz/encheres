<?php declare(strict_types=1);

namespace Projet;

use KD2\Image;

class Produits
{
	public function add(int $id_categorie, string $nom, string $description): bool
	{
		return DB::getInstance()->insert('produits', [
			'categorie'   => $id_categorie,
			'nom'         => trim($nom),
			'description' => trim($description),
		]);
	}

	public function delete(int $id): bool
	{
		return DB::getInstance()->delete('produits', 'id = ?', $id);
	}

	public function update(int $id, int $categorie, string $nom, string $description): bool
	{
		return DB::getInstance()->update('produits', [
			'categorie'   => $id_categorie,
			'nom'         => trim($nom),
			'description' => trim($description),
		], 'id = :id', ['id' => $id]);
	}

	public function addImage(int $id_produit, string $path): int
	{
		$hash = sha1_file($path, true);
		$db = DB::getInstance();

		$db->begin();

		try {
			$db->insert('images', [
				'produit' => $id_produit,
				'hash'    => $hash,
			]);

			$id = $db->lastInsertRowID();

			$image = new Image($path);
			$image->resize(800);
			$image->save(sprintf(IMAGE_PATH, $id));
			$image->cropResize(300);
			$image->save(sprintf(THUMBNAIL_PATH, $id));
			unset($image);

			$db->commit();

			return $id;
		}
		catch (\Exception $e)
		{
			$db->rollback();
			throw $e;
		}
	}
}