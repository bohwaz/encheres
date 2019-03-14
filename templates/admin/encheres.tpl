{include file="_head.tpl" title="Enchères"}

<table class="adminList">
	{foreach from=$list item="item"}
	<tr>
		<th>{$item.nom}</th>
		<td>{$item.nb_mises} mises</td>
		<td>Fin : {$item.date_fin|date_format:"%d/%m/%Y %H:%M"}</td>
		<td><a href="{$www_url}admin/enchere.php?id={$item.id}">Gérer</a></td>
		<td><a href="?delete={$item.id}">Supprimer</a></td>
	</tr>
	{/foreach}
</table>


{form_errors}
{form legend="Créer une enchère" submit="Ajouter" fields=$fields id="add"}

{include file="_foot.tpl"}
