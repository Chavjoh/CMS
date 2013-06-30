<div class="ajaxHeader">
	<strong>Templates</strong>
	{if isset($formId)}
		Edit a template.
	{else}
		Register a new template.
	{/if}
</div>

<form method="post" action="{$action}">
	<input type="hidden" name="id" {if $formId|default:FALSE}value="{Security::out($formId)}"{/if}/>
	<div>Name</div>
	<input type="text" name="name" {if $formName|default:FALSE}value="{Security::out($formName)}"{/if}/>
	<div>Folder (located in skins)</div>
	<input type="text" name="path" {if $formPath|default:FALSE}value="{Security::out($formPath)}"{/if}/>

	{if $formSide|default:FALSE}
		<input type="hidden" name="side" value="{Security::out($formSide)}"/>
	{else}
		<div>Side</div>
		<select name="side">
			<option value="FRONTEND" {if $formSide|default:FALSE}{if $formSide=='FRONTEND'}selected="selected"{/if}{/if}>User (frontend)</option>
			<option value="BACKEND"{if $formSide|default:FALSE}{if $formSide=='BACKEND'}selected="selected"{/if}{/if}>Administration (backend)</option>
		</select>
	{/if}
	<div style="margin-bottom:10px">
		<input type="checkbox" name="active" value="1" {if $formActive|default:FALSE}{if $formActive=='1'}checked{/if}{/if}>
		Active
	</div>
	<button type="submit" class="btn btn-success">Save</button>
</form>