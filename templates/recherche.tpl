{include file="_head.tpl"}

<h2>Recherche</h2>

<form method="get" action="">
	<fieldset>
		<legend>Recherche multi-critère</legend>
		<dl class="searchWithDetails">
			<dt><label for="f_categorie">Catégorie</label></dt>
			<dd>
				<select name="c" id="f_categorie">
					<option></option>
					{foreach from=$categories item="c"}
					<option value="{$c.id}"{if $c.id == $categorie} selected="selected"{/if}>{$c.nom}</option>
					{/foreach}
				</select>
			</dd>
			{foreach from=$details key="cat" item="cat_details"}
				{foreach from=$cat_details key="nom" item="valeurs"}
					<dt data-category="{$cat}">
						{$nom}
					</dt>
					<dd data-category="{$cat}">
						<select name="d[{$nom}]">
							<option></option>
							{foreach from=$valeurs item="valeur"}
							<option{if isset($_GET['d'][$nom]) && $_GET['d'][$nom] == $valeur} selected="selected"{/if}>{$valeur}</option>
							{/foreach}
						</select>
					</dd>
				{/foreach}
			{/foreach}
		</dl>
		<p class="submit"><input type="submit" value="Rechercher" /></p>
	</fieldset>
</form>

<script type="text/javascript">
{literal}
var select = document.getElementById('f_categorie');
var updateForm = function() {
	var elements = document.querySelectorAll('.searchWithDetails [data-category]');

	for (var i = 0; i < elements.length; i++) {
		elements[i].style.display = elements[i].getAttribute('data-category') == select.value ? 'block' : 'none';
	}
};
select.onchange = updateForm;

updateForm();
{/literal}
</script>

{if !empty($encheres)}
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
{else}
	<h2>Aucun résultat</h2>
{/if}

{include file="_foot.tpl"}
