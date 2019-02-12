<form method="post" action="{$url_self}">
	<fieldset>
		<legend>{$legend}</legend>
		<dl>
			{foreach from=$fields key="key" item="field"}
			<dt><label for="f_{$key}">{$field.name}</label></dt>
			<dd>
				{if $field.input == 'longtext'}<textarea{else}<input type="{$field.input}"{/if}
				name="{$key}" {if !$field.null}required="required"{/if}
				{if $field.input == 'longtext'}>{$field.value}</textarea>{else}value="{$field.value}" />{/if}
			</dd>
			{/foreach}
		</dl>
		<p class="submit">
			{$csrf|raw}
			<input type="submit" value="{$submit}" />
		</p>
	</fieldset>
</form>
