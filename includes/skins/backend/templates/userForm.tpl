<div class="ajaxHeader">
	<strong>User</strong>
	{if isset($id_user)}
		Edit a CMS user account.
	{else}
		Add a new CMS user account.
	{/if}
</div>

{* Check $id_user if defined (edit), pass if not defined (create) *}
{if !isset($id_user) OR $id_user > 0}
	<form method="post" action="{$action}">
		Login : <br />
		<input type="text" name="login_user" {if isset($user)}value="{Security::out($user->get('login_user'))}"{/if} /> <br />
		Password :<br />
		{if isset($id_user)}
			<div class="note">(keep blank if you don't change it) </div>
		{/if}
		<input type="password" name="password_user" value="" /> <br />
		Name : <br />
		<input type="text" name="name_user" {if isset($user)}value="{Security::out($user->get('name_user'))}"{/if} /> <br />
		Surname : <br />
		<input type="text" name="surname_user" {if isset($user)}value="{Security::out($user->get('surname_user'))}"{/if} /> <br />
		<button type="submit" class="btn btn-success">Save</button>
	</form>
{else}
	<div class="alert alert-error">
		<strong>User ID is missing.</strong> <br />
		Please specify the ID of the user you want to edit.
	</div>
{/if}
