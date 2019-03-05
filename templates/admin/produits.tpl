{include file="_head.tpl" title="Catégories"}

<table class="adminList">
	{foreach from=$list item="item"}
	<tr>
		<th>{$item.nom}</th>
		<td><a href="{$www_url}produit.php?id={$item.id}">Propriétés</a></td>
		<td><a href="?delete={$item.id}">Supprimer</a></td>
	</tr>
	{/foreach}
</table>


{form_errors}
{form legend="Ajouter un produit" submit="Ajouter" fields=$fields id="add"}

{include file="_foot.tpl"}
