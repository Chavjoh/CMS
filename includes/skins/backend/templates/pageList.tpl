{assign var=editLink value=Server::getBaseUrl()|cat:'admin/Pages/edit/'}
{assign var=deleteLink value=Server::getBaseUrl()|cat:'admin/Pages/delete/'}

<h1> <img src="{$skinPath}images/pages.png" alt="Pages" /> Pages </h1>

<div class="action_bar">
	<a href="{Server::getBaseUrl()}admin/Pages/create" class="nyroModal right">
		<img src="{$skinPath}images/add.png" alt="Add" />
		Add page
	</a>
	<hr class="clear" />
</div>

<table class="manager cellpadding10">
	<thead>
		<tr>
			<th>Page name</th>
			<th style="width: 200px" class="actions">Actions</th>
		</tr>
	</thead>
	<tbody>
		{foreach item=page from=$pageList}
		<tr>
			<td>{Security::out($page->get('title_page'))}</td>
			<td class="actions">
				<a href="{$editLink|cat:$page->get('id_page')}">
					<img src="{$skinPath}images/edit.png" alt="Edit" />
					EDIT
				</a>
				<a href="{$deleteLink|cat:$page->get('id_page')}" onclick="return confirm('Are you sure to want to delete this page ?')">
					<img src="{$skinPath}images/delete.png" alt="Delete" />
					DELETE
				</a>
			</td>
		</tr>
		{/foreach}
	</tbody>
</table>