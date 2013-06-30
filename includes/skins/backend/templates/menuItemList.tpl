{assign var=createLink value=Server::getBaseUrl()|cat:'admin/MenusItems/':$id_menu:'/create/'}
{assign var=editLink value=Server::getBaseUrl()|cat:'admin/MenusItems/':$id_menu:'/edit/'}
{assign var=deleteLink value=Server::getBaseUrl()|cat:'admin/MenusItems/':$id_menu:'/delete/'}
{assign var=upLink value=Server::getBaseUrl()|cat:'admin/MenusItems/':$id_menu:'/up/'}
{assign var=downLink value=Server::getBaseUrl()|cat:'admin/MenusItems/':$id_menu:'/down/'}

<h1> <img src="{$skinPath}images/menus.png" alt="Menus" /> Menu Items </h1>

{if $id_menu > 0}

	<div class="action_bar">
		<a href="{$createLink}" class="right nyroModal">
			<img src="{$skinPath}images/add.png" alt="Add" />
			Add menu item
		</a>
		<hr class="clear" />
	</div>

	<table class="manager cellpadding10">
		<thead>
		<tr>
			<th>Name</th>
			<th style="text-align: center;" colspan="2">Order</th>
			<th style="width: 200px" class="actions">Actions</th>
		</tr>
		</thead>
		<tbody>

		{foreach item=menuItem from=$menuItemList name="foreachItem"}
			<tr>
				<td>{Security::out($menuItem->get('name_menu_item'))}</td>
				<td style="width: 75px">
					{if !$smarty.foreach.foreachItem.first}
						<a href="{$upLink}{$menuItem->get('id_menu_item')}">
							<img src="{$skinPath}images/up.png" alt="Up" />
							UP
						</a>
					{/if}
				</td>
				<td style="width: 75px">
					{if !$smarty.foreach.foreachItem.last}
						<a href="{$downLink}{$menuItem->get('id_menu_item')}">
							<img src="{$skinPath}images/down.png" alt="Down" />
							DOWN
						</a>
					{/if}
				</td>
				<td class="actions">
					<a href="{$editLink}{$menuItem->get('id_menu_item')}" class="nyroModal">
						<img src="{$skinPath}images/edit.png" alt="Edit" />
						EDIT
					</a>
					<a href="{$deleteLink}{$menuItem->get('id_menu_item')}" onclick="return confirm('Are you sure to want to delete this menu item ?')">
						<img src="{$skinPath}images/delete.png" alt="Delete" />
						DELETE
					</a>
				</td>
			</tr>
			{foreachelse}
			<tr>
				<td colspan="2"><em>No item is currently registered for this menu</em></td>
			</tr>
		{/foreach}

		</tbody>
	</table>

{else}

	<div class="alert alert-error">
		<strong>Menu ID is missing.</strong> <br />
		Please specify the ID of the menu to manage its items.
	</div>

{/if}