{assign var=editLink value=Server::getBaseUrl()|cat:'admin/Templates/edit/'}
{assign var=deleteLink value=Server::getBaseUrl()|cat:'admin/Templates/delete/'}

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
		{foreach from=$list item=template}
		<tr>
			<td>{Security::out($template['name_template'])}</td>
			<td>{Security::out($template['path_template'])}</td>
			<td>{$template['type_template']}</td>
			<td>{if $template['active_template']==1}YES{/if}</td>
			<td class="actions">
				<a href="{$editLink|cat:$template['id_template']}" class="nyroModal">
					<img src="{$skinPath}images/edit.png" alt="Edit" />
					EDIT
				</a>
				<a href="{$deleteLink|cat:$template['id_template']}" onclick="return confirm('Are you sure to want to delete this template ?')">
					<img src="{$skinPath}images/delete.png" alt="Delete" />
					DELETE
				</a>
			</td>
		</tr>
		{/foreach}
	</tbody>
</table>