<h1> <img src="{$skinPath}images/settings.png" alt="Param&egrave;tres" /> Settings </h1>

<div class="action_bar">
	Meta Data Settings
</div>

<form action="{Server::getCurrentURL()}" method="post">
	<table class="col3 cellpadding10">
		<tr>
			<td>
				<div>Website title</div>
				<input type="text" name="md_title" value="{Security::out(Configuration::get('meta_name'))}" tabindex="1" />
			</td>
			<td>
				<div>Favicon</div>
				<input type="text" name="md_favicon" value="{Security::out(Configuration::get('meta_favicon'))}" tabindex="13" />
			</td>
			<td>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td>
				<div>Website description</div>
				<input type="text" name="md_description" value="{Security::out(Configuration::get('meta_description'))}" tabindex="5" />
			</td>
			<td>
				<div>Robots</div>
				<input type="text" name="md_robots" value="{Security::out(Configuration::get('meta_robots'))}" tabindex="15" />
			</td>
			<td>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td>
				<div>Keywords</div>
				<input type="text" name="md_keywords" value="{Security::out(Configuration::get('meta_keywords'))}" tabindex="10" />
			</td>
			<td>
				<div>Author</div>
				<input type="text" name="md_author" value="{Security::out(Configuration::get('meta_author'))}" tabindex="20" />
			</td>
			<td>
				<input type="submit" name="md_save" value="Save Meta Data Settings" tabindex="25" class="btn btn-success" />
			</td>
		</tr>
	</table>
</form>

<div class="action_bar">
	Database Settings
</div>

<table class="col3 cellpadding10">
	<tr>
		<td>
			<div>Driver</div>
			<input type="text" name="db_driver" value="{$smarty.const.DB_DRIVER}" tabindex="100" disabled="disabled" />
		</td>
		<td>
			<div>User</div>
			<input type="text" name="db_user" value="{$smarty.const.DB_USER}" tabindex="115" disabled="disabled" />
		</td>
		<td>
			<div>Port</div>
			<input type="text" name="db_port" value="{$smarty.const.DB_PORT}" tabindex="130" disabled="disabled" />
		</td>
	</tr>
	<tr>
		<td>
			<div>Database</div>
			<input type="text" name="db_name" value="{$smarty.const.DB_NAME}" tabindex="105" disabled="disabled" />
		</td>
		<td>
			<div>Password</div>
			<input type="password" name="db_password" value="" tabindex="120" disabled="disabled" />
		</td>
		<td>
			<!-- <input type="submit" name="db_save" value="Save Database Settings" tabindex="135" class="btn btn-success" /> -->
		</td>
	</tr>
	<tr>
		<td>
			<div>Prefix</div>
			<input type="text" name="db_prefix" value="{$smarty.const.DB_PREFIX}" tabindex="110" disabled="disabled" />
		</td>
		<td>
			<div>Host</div>
			<input type="text" name="db_host" value="{$smarty.const.DB_HOST}" tabindex="125" disabled="disabled" />
		</td>
		<td>
			&nbsp;
		</td>
	</tr>
</table>

<div class="action_bar">
	CMS Settings
</div>

<table class="col3 cellpadding10">
	<tr>
		<td>
			<div>Administration Access Suffix</div>
			<input type="text" name="cms_adminsuffix" value="{$smarty.const.URL_ADMIN}" tabindex="200" disabled="disabled" />
		</td>
		<td>
			&nbsp;
		</td>
		<td>
			<!-- <input type="submit" name="cms_save" value="Save CMS Settings" tabindex="205" class="btn btn-success" /> -->
		</td>
	</tr>
</table>