{include file="_head.tpl" title="Catégorie: %s"|args:$produit.nom}

<h2>Images</h2>

<table class="adminList">
	{foreach from=$images item="item"}
	<tr>
		<th><a href="{$item.id|image_url}" target="_blank"><img src="{$item.id|image_thumb_url}" alt="" /></a></th>
		<td><a href="?id={$produit.id}&amp;delete_image={$item.id}">Supprimer</a></td>
	</tr>
	{/foreach}
</table>


{form_errors}
{form legend="Ajouter une image" submit="Ajouter" fields=$image_fields id="add_image"}

{include file="_foot.tpl"}
