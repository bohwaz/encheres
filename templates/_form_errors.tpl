<ul class="errors">
{foreach from=$errors item="error"}
	<li>
		{if !is_string($error) && $error.rule == 'csrf'}
			<?php $error = 'Merci de renvoyer le formulaire'; ?>
		{elseif !is_string($error)}
			<?php $error = Projet\Entity::getErrorValidationMessage($error['rule'], $error['name']); ?>
		{/if}
		{$error}
	</li>
{/foreach}
</ul>