<h1>Administration Interface</h1>

{if isset($error)}
    <div class="alert">
        {$error}
    </div>
{/if}

<form action="{Server::getCurrentUrl()}" class="login" method="post">
	<fieldset>
		<p>
			You must login ...
		</p>
		<div class="clearfix">
			<input type="text" placeholder="Username" name="username">
		</div>
		<div class="clearfix">
			<input type="password" placeholder="Password" name="password">
		</div>
		<button class="btn btn-primary" type="submit" name="submit">Sign in</button>
	</fieldset>
</form>