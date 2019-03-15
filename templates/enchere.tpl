{include file="_head.tpl"}

<section class="enchere">

	<article class="produit" style="background-image: linear-gradient(to top, rgba(255,255,255,1) 20%,rgba(255,255,255,0) 150%), url('{$produit.image|image_url|escape:url}');">
		<h2>{$produit.nom}</h2>
		<h3>Prix public : {$enchere.prix_public|money}</h3>

		{foreach from=$images item="image"}
			<figure class="image">
				<a href="{$image.id|image_url}" target="_blank"><img src="{$image.id|image_thumb_url}" alt="" /></a>
			</figure>
		{/foreach}

		<dl class="details">
		{foreach from=$details item="detail"}
			<dt>{$detail.nom}</dt>
			<dd>{$detail.valeur}</dd>
		{/foreach}
		</dl>

		<p>{$produit.description|escape|nl2br}</p>
	</article>

	<article class="miser">
		<h2>Temps restant : {$enchere.date_fin|temps_restant}</h2>
		<h3>Fin de l'enchère : {$enchere.date_fin|date_format:"%d/%m/%Y %H:%M"}</h3>
		<h4>Coût d'une enchère : {$enchere.cout_mise|money}</h4>

		{form_errors}
		{form legend="Placer une offre" submit="Placer" fields=$mise_fields id="make_offer"}
	</article>

	<article class="mises">
		<h2>Mes mises</h2>
		<table>
			{foreach from=$mes_mises item="mise"}
			<tr>
				<th>{$mise.montant|money}</th>
				<td>
					{if $mise.statut == 'gagnante'}
						<strong>Mise gagnante&nbsp;!</strong>
					{elseif $mise.statut == 'unique'}
						Mise unique (non gagnante)
					{else}
						Mise non-unique
					{/if}
				</td>
			</tr>
			{/foreach}
		</table>
	</article>

</section>

{include file="_foot.tpl"}
