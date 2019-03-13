<?php

use Projet\DB;

require __DIR__ . '/bootstrap.php';

DB::getInstance()->import(__DIR__ . '/schema.sql');
