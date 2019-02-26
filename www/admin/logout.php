<?php

use Projet\Membre;

require __DIR__ . '/../../bootstrap.php';

Membre::logout();

redirect();