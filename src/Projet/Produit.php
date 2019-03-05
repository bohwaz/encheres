<?php declare(strict_types=1);

namespace Projet;

use KD2\Image;

class Produit extends Entity
{
	protected $table = 'produits';

	/**
	 * @var int
	 */
	protected $id;

	/**
	 * @var string
	 * @field tinytext
	 * @name Nom du produit
	 */
	protected $nom;

	/**
	 * @var string
	 * @field longtext
	 * @name Description du produit
	 */
	protected $description;

	/**
	 * @var string
	 * @null
	 */
	protected $image;

	/**
	 * @var int
	 */
	protected $categorie;

	public function addImage(string $path): int
	{
		if (!$this->id)
		{
			throw new RuntimeException('Object must be saved to DB first');
		}

		$hash = sha1_file($path, true);
		$db = DB::getInstance();

		$db->begin();

		try {
			$db->insert('images', [
				'produit' => $this->id,
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

	public function deleteImage(int $id_image): int
	{
		return DB::getInstance()->delete('images', 'id = ?', $id_image);
	}

	public function listImages(): array
	{
		return DB::getInstance()->get('SELECT * FROM images WHERE produit = ? ORDER BY id;', $this->id);
	}
}