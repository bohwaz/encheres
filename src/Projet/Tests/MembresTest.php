<?php

namespace Projet\Test;

use KD2\Test;

use Projet\Membre;
use Projet\DB;

class MembresTest
{
	public function setUp()
	{
		DB::getInstance()->begin();
	}

	public function tearDown()
	{
		DB::getInstance()->rollback();
	}

	public function testMembre()
	{
		$m = new Membre;
		$m->email = 'test@test.tld';
		$m->passe = 'abcd';

		Test::strictlyEquals('test@test.tld', $m->email);
		Test::assert($m->passe !== 'abcd');
		Test::assert($m->save());

		Test::assert(null !== $m->id);
		Test::assert($m->id > 0);

		Test::strictlyEquals(0, $m->credit);

		Test::assert($m->addCredit(100));

		Test::strictlyEquals(100, $m->credit);

		Test::assert($m->delete());
	}
}