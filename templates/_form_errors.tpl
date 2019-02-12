<ul class="errors">
{foreach from=$errors item="error"}
	<li>
		{if !is_string($error)}
			<?php $error = Projet\Entity::getErrorValidationMessage($error['rule'], $error['name']); ?>
		{/if}
		{$error}
	</li>
{/foreach}
</ul>