{assign var=pageEditLink value=Server::getBaseUrl()|cat:$smarty.const.URL_ADMIN:'/Pages/':$id_page:'/'}
{assign var=addModuleLink value=$pageEditLink|cat:'addModule/'}

<h1> <img src="{$skinPath}images/pages.png" alt="Pages" /> Page - {Security::out($page->get('title_page'))} </h1>

<div class="action_bar">
	<a href="{$addModuleLink}" class="right nyroModal">
		<img src="{$skinPath}images/add.png" alt="Add module" />
		Add module
	</a>
	<hr class="clear" />
</div>

<div class="bigleft">

	<h2>Modules</h2>

	<table class="biglist cellpadding10">

	{foreach from=$moduleList item=module key=index name=foreachModule}

		{assign var=orderModulePage value=$settingsList[$index]->get('order_module_page')}

		<tr>
			<td>
				{if !$smarty.foreach.foreachModule.first}
					<a href="{$pageEditLink}upModule/{$orderModulePage}">
						<img src="{$skinPath}images/up.png" alt="Up" />
					</a>
				{/if}

				{if !$smarty.foreach.foreachModule.last}
					<a href="{$pageEditLink}downModule/{$orderModulePage}">
						<img src="{$skinPath}images/down.png" alt="Down" />
					</a>
				{/if}

				<a href="{$pageEditLink}deleteModule/{$orderModulePage}" onclick="return confirm('Are you sure to want to delete this module ?')">
					<img src="{$skinPath}images/delete_module.png" alt="Delete" />
				</a>
			</td>
			<td>
				<h3>{Security::out($module->get('name_module'))}</h3>
				Order {$orderModulePage}
			</td>
			<td class="actions">
				<a href="{$pageEditLink}editModule/{$orderModulePage}" class="nyroModal">
					<img src="{$skinPath}images/edit_module.png" alt="Edit" /><br />
					EDIT
				</a>
			</td>
		</tr>

	{foreachelse}
		<tr>
			<td><em>No module is enabled on this page now.</em></td>
		</tr>
	{/foreach}
	</table>

	<h2>Wrappers</h2>


	<table class="biglist cellpadding10">

		{foreach from=$wrapperList item=wrapper}

			<tr>
				<td>
					<strong> Key : </strong> <br />
					{Security::out($wrapper->get('key_wrapper'))}
				</td>
				<td>
					<h3>{$wrapper->get('name_wrapper')}</h3>
					{Security::out($wrapper->get('description_wrapper'))}
				</td>
			</tr>

		{foreachelse}

			<tr>
				<td><em>No wrapper is enabled on the CMS.</em></td>
			</tr>

		{/foreach}
	</table>
</div>

<div class="bigright">
	<h2>General informations</h2>
	{include file='pageFormInformation.tpl' action='' page=$page}
</div>

<hr class="clear" />