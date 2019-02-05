<?php declare(strict_types=1);

namespace Projet;

use DateTime;
use stdClass;

class Mises
{
	public function listForEnchereAndUser(int $enchere, int $utilisateur): array
	{
		return DB::getInstance()->get('SELECT * FROM mes_mises WHERE enchere = ? AND utilisateur = ?;', $enchere, $utilisateur);
	}

	public function add(int $enchere, int $utilisateur, int $montant): bool
	{
		return $this->addRange($enchere, $utilisateur, $montant, $montant);
	}

	public function addRange(int $enchere, int $utilisateur, int $start, int $end): bool
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

	public function getWinner(int $enchere): ?stdClass
	{
		return DB::getInstance()->first('SELECT * FROM mise_gagnante WHERE enchere = ?;', $enchere);
	}
}