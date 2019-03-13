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
	 * @field text
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
	 * @field select
	 * @references categories id nom
	 * @name Catégorie
	 */
	protected $categorie;

	public function addImage(string $path): int
	{
		if (!$this->id)
		{
			throw new RuntimeException('Object must be saved to DB first');
		}

		$hash = sha1_file($path);
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

			$path = sprintf(IMAGE_PATH, $id);

			if (!is_dir(dirname($path)))
			{
				mkdir(dirname($path), 0777, true);
			}

			$image->save($path);
			$image->cropResize(200);
			$image->save(sprintf(THUMBNAIL_PATH, $id));
			unset($image);

			$db->commit();

			return (int) $id;
		}
		catch (\Exception $e)
		{
			$db->rollback();
			throw $e;
		}
	}

	public function deleteImage(int $id_image): bool
	{
		@unlink(sprintf(IMAGE_PATH, $id_image));
		@unlink(sprintf(THUMBNAIL_PATH, $id_image));
		return (bool) DB::getInstance()->delete('images', 'id = ?', $id_image);
	}

	public function listImages(): array
	{
		return DB::getInstance()->get('SELECT * FROM images WHERE produit = ? ORDER BY id;', $this->id);
	}

	public function delete()
	{
		foreach ($this->listImages() as $img)
		{
			$this->deleteImage($img->id);
		}

		return parent::delete();
	}

	public function listDetails(): array
	{
		return DB::getInstance()->get('SELECT pd.*, cd.nom
			FROM produits_details AS pd
			INNER JOIN categories_details AS cd ON pd.detail = cd.id
			WHERE pd.produit = ?;', $this->id);
	}

	public function addDetail(int $id_detail, string $valeur)
	{
		return DB::getInstance()->preparedQuery('REPLACE INTO produits_details (produit, detail, valeur) VALUES (?, ?, ?);',
			[$this->id, $id_detail, $valeur]);
	}
}