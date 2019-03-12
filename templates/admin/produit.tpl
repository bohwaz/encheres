{include file="_head.tpl" title="Catégorie: %s"|args:$produit.nom}

	{form_errors}
<div class="adminColumns">

	<div class="adminColumn">
		<h2>Images</h2>

		<table class="adminList">
			{foreach from=$images item="item"}
			<tr>
				<th><a href="{$item.id|image_url}" target="_blank"><img src="{$item.id|image_thumb_url}" alt="" /></a></th>
				<td>{if $item.id == $produit.image}<strong>Image principale</strong>{else}<a href="?id={$produit.id}&amp;set_image={$item.id}">Image principale</a>{/if}</td>
				<td><a href="?id={$produit.id}&amp;delete_image={$item.id}">Supprimer</a></td>
			</tr>
			{/foreach}
		</table>


		{form legend="Ajouter une image" submit="Ajouter" fields=$image_fields id="add_image"}
	</div>

	<div class="adminColumn">
		<h2>Détails du produit</h2>

		<table class="adminList">
			{foreach from=$details item="detail"}
			<tr>
				<th>{$detail.nom}</th>
				<td>{$detail.valeur}</td>
			</tr>
			{/foreach}
		</table>

		{form legend="Ajouter un détail" submit="Ajouter" fields=$detail_fields id="add_detail"}
	</div>

{include file="_foot.tpl"}
