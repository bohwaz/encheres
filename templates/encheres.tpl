{include file="_head.tpl"}

{if isset($_GET['ended'])}
	<h2>Enchères terminées</h2>
{else}
	<h2>Enchères en cours</h2>
{/if}

<section class="encheres">
	{foreach from=$encheres item="e"}
	<article>
		<header>
			<a href="{$www_url}enchere.php?id={$e.id}">
				<img src="{$e.image|image_thumb_url}" alt="" />
				<h3>{$e.nom}</h3>
				<h4>{$e.nom_categorie}</h4>
			</a>
		</header>
		<dl>
			<dt>Fin de l'enchère&nbsp;:</dt>
			<dd>{$e.date_fin|date_format:"%d/%m/%Y %H:%M"}</dd>
			<dt>Prix public&nbsp;:</dt>
			<dd>{$e.prix_public|raw|money}</dd>
			<dt>Coût d'une enchère&nbsp;:</dt>
			<dd>{$e.cout_mise|raw|money}</dd>
		</dl>
	</article>
	{/foreach}
</ul>

{include file="_foot.tpl"}
