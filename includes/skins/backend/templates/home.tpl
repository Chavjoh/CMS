{assign var=baseUrl value=Server::getDirectoryScript()|cat:'/admin/'}

<h1>Administration Interface</h1>

<a href="{$baseUrl}Pages" class="bloc_menu">
	<div> <img src="{$skinPath}images/pages.png" alt="Pages" /> Pages </div>
	Allows you to manage the different pages on your site.
</a>

<a href="{$baseUrl}Menus" class="bloc_menu">
	<div> <img src="{$skinPath}images/menus.png" alt="Menus" /> Menus </div>
	Allows you to manage the menus displayed on your site.
</a>

<a href="{$baseUrl}Templates" class="bloc_menu">
	<div> <img src="{$skinPath}images/templates.png" alt="Templates" /> Templates </div>
	Allows you to manage your website skins.
</a>

<a href="{$baseUrl}Modules" class="bloc_menu">
	<div> <img src="{$skinPath}images/modules.png" alt="Modules" /> Modules </div>
	Allows you to manage and configure specific modules on your site.
</a>

<a href="{$baseUrl}Users" class="bloc_menu">
	<div> <img src="{$skinPath}images/users.png" alt="Utilisateurs" /> Users </div>
	Allows you to manage users with access to the administration.
</a>

<a href="{$baseUrl}Settings" class="bloc_menu">
	<div> <img src="{$skinPath}images/settings.png" alt="Param&egrave;tres" /> Settings </div>
	Allows you to configure the settings of this CMS Engine.
</a>