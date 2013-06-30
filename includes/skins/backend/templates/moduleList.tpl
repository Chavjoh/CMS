<h1> <img src="{$skinPath}images/modules.png" alt="Modules" /> Modules </h1>

<div class="action_bar">
	&nbsp;
</div>

{foreach item=module from=$moduleList}
	<div class="adminModule">

		<div>
			<img src="{$moduleObject[$module->get('key_module')]->getIconPath()}" alt="{Security::out($module->get('name_module'))}" />
			<span>{Security::out($module->get('name_module'))}</span>
		</div>
	</div>
{/foreach}