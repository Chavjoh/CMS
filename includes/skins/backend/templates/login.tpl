<h1>{TemplateLanguage::get('Admin.Login.Title')}</h1>

{if isset($error)}
    <div class="alert">
        {$error}
    </div>
{/if}

<form action="{Server::getCurrentUrl()}" class="login" method="post">
	<fieldset>
		<p>
			{TemplateLanguage::get('Admin.Login.Text')}
		</p>
		<div class="clearfix">
			<input type="text" placeholder="{TemplateLanguage::get('Admin.Login.Username')}" name="username">
		</div>
		<div class="clearfix">
			<input type="password" placeholder="{TemplateLanguage::get('Admin.Login.Password')}" name="password">
		</div>
		<button class="btn btn-primary" type="submit" name="submit">{TemplateLanguage::get('Admin.Login.SignIn')}</button>
	</fieldset>
</form>