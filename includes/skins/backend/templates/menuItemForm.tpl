<div class="ajaxHeader">
	<strong>Menu Item</strong>
	{if isset($id_menu_item)}
		Edit a menu item of the menu selected.
	{else}
		Add a new menu item to the menu selected.
	{/if}
</div>

{if !isset($id_menu_item) OR $id_menu_item > 0}

	<form method="post" action="{$action}">
		Page : <br />
		<select name="id_page">
			{foreach item=page from=$pageList}
				<option value="{$page->get('id_page')}"{if isset($menuItem) && $page->get('id_page') == $menuItem->get('id_page')} selected="selected"{/if}>
					{Security::out($page->get('title_page'))}
				</option>
			{/foreach}
		</select> <br />

		Item name : <br />
		<input type="text" name="name_menu_item" {if isset($menuItem)}value="{Security::out($menuItem->get('name_menu_item'))}"{/if} /> <br />

		<button type="submit" class="btn btn-success">Save</button>
	</form>

{else}

	<div class="alert alert-error">
		<strong>Menu Item ID is missing.</strong> <br />
		Please specify the ID of the menu item you want to edit.
	</div>

{/if}
