<?php declare(strict_types=1);

namespace Projet;

use DateTime;
use stdClass;

class Mise extends Entity
{
	/**
	 * @var int
	 */
	protected $id;

	/**
	 * @var int
	 * @references utilisateurs id email
	 */
	protected $utilisateur;

	/**
	 * @var int
	 * @references encheres id id
	 */
	protected $enchere;

	/**
	 * @var int
	 * @field money
	 * @name Montant de la mise
	 */
	protected $montant;

	/**
	 * @var DateTime
	 * @default now
	 * @name Date de la mise
	 */
	protected $date;

	static public function addRange(int $enchere, int $utilisateur, int $start, int $end): bool
	{
		$db = DB::getInstance();
		$db->begin();

		$values = compact('enchere', 'utilisateur', 'montant');
		$st = $db->prepare('INSERT INTO mises VALUES (:enchere, :utilisateur, :montant, NOW());');

		for ($i = $start; $i <= $end; $i++)
		{
			$values['montant'] = $i;
			$st->execute($values);
		}

		$cout_par_mise = $db->firstColumn('SELECT cout_mise FROM encheres WHERE id = ?;', $enchere);
		$cout = $cout_par_mise * min(1, ($end - $start));

		$db->preparedQuery('UPDATE membres SET credit = credit - ? WHERE id = ?;', [$cout, $utilisateur]);

		return $db->commit();
	}
}