<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>{$site_title}</title>
	<link rel="stylesheet" type="text/css" href="{$www_url}style/screen.css" />
</head>

<body>

<header class="head">
	<h1>{$site_title}</h1>
	<h2>Jusqu'à 90% de réduction sur la high tech&nbsp;!</h2>

	<nav>
		<ul>
			<li class="search"><form method="get" action="{$www_url}recherche.php"><input type="search" name="q" placeholder="Recherche" /><noscript><input type="submit" value="OK" /></noscript></form></li>
			<li><a href="{$www_url}encheres.php">Enchères en cours</a></li>
			<li><a href="{$www_url}encheres.php?ended">Enchères terminées</a></li>
			<li><a href="{$www_url}faq.php">Comment ça marche&nbsp;?</a></li>
		{if $is_logged}
			<li><a href="{$www_url}compte.php">Créditer mon compte</a></li>
			<li><a href="{$www_url}logout.php">Déconnexion</a></li>
			{if $is_admin}
				<li><a href="{$www_url}admin/">Admin</a></li>
			{/if}
		{else}
			<li><a href="{$www_url}register.php">Inscription</a></li>
			<li><a href="{$www_url}login.php">Connexion</a></li>
		{/if}
		</ul>
	</nav>
</header>

<main>
