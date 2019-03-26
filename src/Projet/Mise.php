<?php declare(strict_types=1);

namespace Projet;

use DateTime;
use stdClass;

class Mise extends Entity
{
	protected $table = 'mises';

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

	static public function addRange(Enchere $enchere, Membre $user, int $start, int $end): bool
	{
		$cost = $enchere->cout_mise * (1 + $start - $end);

		if (!$user->hasEnoughCredit($cost)) {
			throw new User_Exception('Vous ne disposez pas de suffisamment de crédit');
		}

		$db = DB::getInstance();
		$db->begin();

		$values = ['enchere' => $enchere->id, 'utilisateur' => $user->id];
		$st = $db->prepare('INSERT IGNORE INTO mises (enchere, membre, montant, `date`) VALUES (:enchere, :utilisateur, :montant, NOW());');

		$real_cost = 0;

		for ($i = $start; $i <= $end; $i++)
		{
			$values['montant'] = $i;
			$st->execute($values);

			// On ne prend en compte que le coût des mises qui n'existaient pas déjà pour cet utilisateur
			$real_cost += $st->rowCount();
		}

		$user->removeCredit($real_cost * $enchere->cout_mise);

		return $db->commit();
	}
}