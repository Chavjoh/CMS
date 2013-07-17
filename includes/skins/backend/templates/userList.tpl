{assign var=createLink value=$urlController|cat:'create/'}
{assign var=editLink value=$urlController|cat:'edit/'}
{assign var=deleteLink value=$urlController|cat:'delete/'}

<h1> <img src="{$skinPath}images/users.png" alt="Users" /> Users </h1>

<div class="action_bar">
	<a href="{$createLink}" class="right nyroModal">
		<img src="{$skinPath}images/add.png" alt="Add" />
		Add user
	</a>
	<hr class="clear" />
</div>

<table class="manager cellpadding10">
	<thead>
	<tr>
		<th>Username</th>
		<th style="width: 200px" class="actions">Actions</th>
	</tr>
	</thead>
	<tbody>

	{foreach item=user from=$userList}
		<tr>
			<td>{$user->get('login_user')}</td>
			<td class="actions">
				<a href="{$editLink|cat:$user->get('id_user')}" class="nyroModal">
					<img src="{$skinPath}images/edit.png" alt="Edit" />
					EDIT
				</a>
				<a href="{$deleteLink|cat:$user->get('id_user')}" onclick="return confirm('Are you sure to want to delete this user ?')">
					<img src="{$skinPath}images/delete.png" alt="Delete" />
					DELETE
				</a>
			</td>
		</tr>
	{foreachelse}
		<tr>
			<td colspan="2"><em>No user is currently registered</em></td>
		</tr>
	{/foreach}

	</tbody>
</table>