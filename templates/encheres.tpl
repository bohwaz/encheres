{include file="_head.tpl"}

<h2>Enchères</h2>

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
			<dt>Fin de l'enchère :</dt>
			<dd>{$e.date_fin|date_format:"%d/%m/%Y %H:%M"}</dd>
			<dt>Prix public :</dt>
			<dd>{$e.prix_public|money}</dd>
			<dt>Coût d'une enchère :</dt>
			<dd>{$e.cout_mise|money}</dd>
		</dl>
	</article>
	{/foreach}
</ul>

{include file="_foot.tpl"}
