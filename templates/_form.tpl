<form method="post" action="{$url_self}" enctype="multipart/form-data">
	<fieldset>
		<legend>{$legend}</legend>
		<dl>
			{foreach from=$fields key="key" item="field"}
			<dt><label for="f_{$key}">{$field.name}</label></dt>
			<dd>
				{if $field.input == 'longtext'}<textarea{elseif $field.input == 'select'}<select{else}<input type="{$field.input}"{/if}
				name="{$key}" id="f_{$key}" {if !$field.null}required="required"{/if}
				{if $field.input == 'select'}
					/>
					{foreach from=$field.values key="k" item="v"}
					<option value="{$k}"{if $k == $field.value} selected="selected"{/if}>{$v}</option>
					{/foreach}
				</select>
				{else}
					{if isset($field.min)}min="{$field.min}"{/if}
					{if isset($field.max)}max="{$field.max}"{/if}
					{if isset($field.step)}step="{$field.step}"{/if}
					{if $field.input == 'longtext'}>{$field.value}</textarea>{else}value="{$field.value}" />{/if}
				{/if}
			</dd>
			{/foreach}
		</dl>
		<p class="submit">
			{$csrf|raw}
			<input type="submit" name="{$id}" value="{$submit}" />
		</p>
	</fieldset>
</form>
