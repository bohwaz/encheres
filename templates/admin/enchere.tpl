{include file="_head.tpl" title="Enchère: %s"|args:$enchere.id}

<div class="adminColumns">

	<div class="adminColumn">
		<h2>Mises</h2>

		<table class="adminList">
			{foreach from=$mises item="item"}
			<tr>
				<th>{$item.montant|raw|money}</th>
				<td>{$item.statut}</td>
			</tr>
			{foreachelse}
			<tr>
				<th>Aucune mise</th>
			</tr>
			{/foreach}
		</table>

	</div>

	<div class="adminColumn">
		{form_errors}
		{form legend="Modifier l'enchère" submit="Modifier" fields=$fields id="edit" source=$enchere}
	</div>
</div>


{include file="_foot.tpl"}
