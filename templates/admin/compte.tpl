{include file="_head.tpl" title="Gérer mon compte"}

<section class="account">
	<article>
		<h2>Mon compte</h2>
		<ul>
			<li>E-Mail : {$user.email}</li>
			<li>Crédit : {$user.credit|raw|money}</li>
		</ul>
	</article>
</section>

{form_errors}
{form legend="Créditer" submit="Créditer mon compte" fields=$credit_fields id="credit"}

{include file="_foot.tpl"}
