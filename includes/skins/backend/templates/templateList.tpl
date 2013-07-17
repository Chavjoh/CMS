{assign var=editLink value=$urlController|cat:'edit/'}
{assign var=deleteLink value=$urlController|cat:'delete/'}

<h1> <img src="{$skinPath}images/templates.png" alt="Templates" /> Templates </h1>

<div class="action_bar">
	<a href="{Server::getBaseUrl()}admin/Templates/create" class="nyroModal right">
		<img src="{$skinPath}images/add.png" alt="Add" />
		Add template
	</a>
	<hr class="clear" />
</div>

<table class="manager cellpadding10">
	<thead>
		<tr>
			<th>Name</th>
			<th style="width: 200px">Path</th>
			<th style="width: 100px">Side</th>
			<th style="width: 50px">Active</th>
			<th style="width: 200px" class="actions">ACTIONS</th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$templateList item=template}
		<tr>
			<td>{Security::out($template->get('name_template'))}</td>
			<td>{Security::out($template->get('path_template'))}</td>
			<td>{$template->get('type_template')}</td>
			<td>{if $template->get('active_template')==1}YES{/if}</td>
			<td class="actions">
				<a href="{$editLink|cat:$template->get('id_template')}" class="nyroModal">
					<img src="{$skinPath}images/edit.png" alt="Edit" />
					EDIT
				</a>
				<a href="{$deleteLink|cat:$template->get('id_template')}" onclick="return confirm('Are you sure to want to delete this template ?')">
					<img src="{$skinPath}images/delete.png" alt="Delete" />
					DELETE
				</a>
			</td>
		</tr>
		{/foreach}
	</tbody>
</table>