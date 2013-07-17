<div class="ajaxHeader">
	<strong>Templates</strong>
	{if isset($template)}
		Edit a template.
	{else}
		Register a new template.
	{/if}
</div>

<form method="post" action="{$action}">
	<div>Template name</div>
	<input type="text" name="name_template" {if isset($template)}value="{Security::out($template->get('name_template'))}" {/if}/>

	<div>Folder name (located in folder skins)</div>
	<input type="text" name="path_template" {if isset($template)}value="{Security::out($template->get('path_template'))}" {/if}/>

	{if !isset($template)}
		<div>Side</div>
		<select name="side_template">
			<option value="FRONTEND">User (FRONTEND)</option>
			<option value="BACKEND">Administration (BACKEND)</option>
		</select>
	{/if}

	<div style="margin-bottom:10px">
		<label>
			<input type="checkbox" name="active_template" value="1" {if isset($template)}{if $template->isActiveTemplate()}checked{/if}{/if}>
			Active
		</label>
	</div>

	<button type="submit" class="btn btn-success">Save</button>
</form>