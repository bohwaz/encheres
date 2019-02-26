<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>{$site_title}</title>
	<link rel="stylesheet" type="text/css" href="{$www_url}static/screen.css" />
</head>

<body>

<header class="head">
	<h1><a href="{$www_url}">{$site_title}</a></h1>
	<h2>Jusqu'à 90% de réduction sur la high tech&nbsp;!</h2>

	<nav>
		<ul>
			<li class="search"><form method="get" action="{$www_url}recherche.php"><label><b>&#x1F50D;</b><input type="search" name="q" placeholder="Recherche" size="10" id="searchField" /></label><noscript><input type="submit" value="OK" /></noscript></form></li>
			<li><a href="{$www_url}encheres.php">Enchères en cours</a></li>
			<li><a href="{$www_url}encheres.php?ended">Enchères terminées</a></li>
			<li><a href="{$www_url}faq.php">Comment ça marche&nbsp;?</a></li>
		{if $is_logged}
			<li class="credit"><b>Mon crédit&nbsp;: <strong>{$user.credit|money}</strong></b></li>
			<li><a href="{$www_url}admin/compte.php">Créditer mon compte</a></li>
			<li><a href="{$www_url}admin/logout.php">Déconnexion</a></li>
			{if $is_admin}
				<li><a href="{$www_url}admin/">Admin</a></li>
			{/if}
		{else}
			<li><a href="{$www_url}register.php">Inscription</a></li>
			<li><a href="{$www_url}admin/login.php">Connexion</a></li>
		{/if}
		</ul>
	</nav>
</header>

<main>
