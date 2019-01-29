<?php

namespace Projet\Test;

use KD2\Test;

class MembresTest
{
	public function testCreate()
	{
		$m = new Membres;
		Test::assert($m->create('test@test.tld', 'abcd'));
	}
}