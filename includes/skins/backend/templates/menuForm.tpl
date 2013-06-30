<div class="ajaxHeader">
	<strong>Menu</strong>
	{if isset($id_menu)}
		Edit a menu of the CMS.
	{else}
		Add a new menu to the CMS.
	{/if}
</div>

{if !isset($id_menu) OR $id_menu > 0}

	<form method="post" action="{$action}">
		Key : <br />
		<input type="text" name="key_menu" {if isset($menu)}value="{Security::out($menu->get('key_menu'))}"{/if} /> <br />
		Name : <br />
		<input type="text" name="name_menu" {if isset($menu)}value="{Security::out($menu->get('name_menu'))}"{/if} /> <br />
		<button type="submit" class="btn btn-success">Save</button>
	</form>

{else}

	<div class="alert alert-error">
		<strong>Menu ID is missing.</strong> <br />
		Please specify the ID of the menu you want to edit.
	</div>

{/if}
