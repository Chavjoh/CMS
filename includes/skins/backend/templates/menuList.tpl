{assign var=createLink value=Server::getBaseUrl()|cat:'admin/Menus/create/'}
{assign var=editLink value=Server::getBaseUrl()|cat:'admin/Menus/edit/'}
{assign var=deleteLink value=Server::getBaseUrl()|cat:'admin/Menus/delete/'}
{assign var=manageLink value=Server::getBaseUrl()|cat:'admin/MenusItems/'}

<h1> <img src="{$skinPath}images/menus.png" alt="Menus" /> Menus </h1>

<div class="action_bar">
	<a href="{$createLink}" class="right nyroModal">
		<img src="{$skinPath}images/add.png" alt="Add" />
		Add menu
	</a>
	<hr class="clear" />
</div>

<table class="manager cellpadding10">
	<thead>
	<tr>
		<th>Name</th>
		<th style="width: 250px" class="actions">Actions</th>
	</tr>
	</thead>
	<tbody>

	{foreach item=menu from=$menuList}
		<tr>
			<td>{Security::out($menu->get('name_menu'))}</td>
			<td class="actions">
				<a href="{$manageLink|cat:$menu->get('id_menu')}">
					<img src="{$skinPath}images/edit.png" alt="Edit" />
					MANAGE
				</a>
				<a href="{$editLink|cat:$menu->get('id_menu')}" class="nyroModal">
					<img src="{$skinPath}images/edit.png" alt="Edit" />
					EDIT
				</a>
				<a href="{$deleteLink|cat:$menu->get('id_menu')}" onclick="return confirm('Are you sure to want to delete this menu ?')">
					<img src="{$skinPath}images/delete.png" alt="Delete" />
					DELETE
				</a>
			</td>
		</tr>
		{foreachelse}
		<tr>
			<td colspan="2"><em>No menu is currently registered</em></td>
		</tr>
	{/foreach}

	</tbody>
</table>