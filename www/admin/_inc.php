<?php

require __DIR__ . '/../../bootstrap.php';

if (!$user)
{
	redirect('user/login.php');
}