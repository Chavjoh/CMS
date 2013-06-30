<div class="ajaxHeader">
	<strong>Module</strong>
	Add a new module to this page.
</div>

<form method="post" class="centerPaddingModal" action="{Server::getCurrentUrl()}">
	<label>
		Choose the module to add : <br />
		<select name="module">
			{foreach from=$moduleList item=module}
				<option value="{$module->get('id_module')}">{Security::out($module->get('name_module'))}</option>
			{/foreach}
		</select>
	</label>
	<button type="submit" class="btn btn-success">Add the module</button>
</form>