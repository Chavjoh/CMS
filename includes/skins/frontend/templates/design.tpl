{assign var=websiteName value=Security::out(ConfigurationManager::get('meta_name'))}
{assign var=websiteFavicon value=Security::out(ConfigurationManager::get('meta_favicon'))}
{assign var=websitePage value=Security::out($controller->getPageName())}
{assign var=websiteKeywords value=Security::out($controller->getPageKeywords())}
{assign var=websiteDescription value=Security::out($controller->getPageDescription())}
<!DOCTYPE html>
<html lang="fr">
	<head>
        <meta charset="utf-8" />

        <title>{$websitePage}</title>
        
		<!-- Meta Data -->
		{if !empty($websiteFavicon)}
			<link rel="shortcut icon" type="image/x-icon" href="{$websiteFavicon}" />
		{/if}
		
		{if !empty($websiteDescription)}
			<meta name="description" lang="fr" content="{$websiteDescription}" />
		{/if}
		
		{if !empty($websiteKeywords)}
			<meta name="keywords" lang="fr" content="{$websiteKeywords}" />
		{/if}
		
		<!-- Template CSS -->
		<link href="{$skinPath}plugins/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
		<link href="{$skinPath}fonts/roboto/stylesheet.css" rel="stylesheet" type="text/css" media="all" />
		<link href="{$skinPath}styles/design.css" rel="stylesheet" type="text/css" media="all" />

		<!-- Modules CSS -->
		{foreach from=$controller->getStylesheetList() item="stylesheet"}
			<link href="{$stylesheet}" rel="stylesheet" type="text/css" media="all" />
		{/foreach}

		<!-- Template Scripts -->
		<script type="text/javascript" src="{$skinPath}scripts/jquery.js"></script>
		<script type="text/javascript" src="{$skinPath}scripts/style.js"></script>

		<!-- Modules Scripts -->
		{foreach from=$controller->getScriptList() item="script"}
			<script type="text/javascript" src="{$script}"></script>
		{/foreach}

		<!-- Plugin HTML 5 for IE -->
		<!--[if lte IE 8]>
			<script src="{$skinPath}scripts/html5.js"></script>
		<![endif]-->
	</head>
	
	<body onload="SetCanvasSize()">
		<header>
			<div id="border-top"></div>
			<div id="header-top">
				<div class="left">CMS</div>
				<nav>
				{foreach from=MenuItemModel::getMenuItemList('main') item=menu}
					<a href="{Server::getBaseUrl()}{$menu->get('alias_page')}"{if $menu->get('alias_page') == $controller->getCurrentAlias()} class="active"{/if}>
						{Security::out($menu->get('name_menu_item'))}
					</a>
				{/foreach}
				</nav>
			</div>
		</header>
		<section id="content">
			{$controller->getPageContent()}
		</section>
		<footer>
			<div id="border-bottom"></div>
			<div id="footer-light">
				A Content Management System is an application that allows publishing, editing and modifying content from a central interface.
				Content can be simple text, documents, photos, videos, etc. or just about anything you can think of.
				A major advantage of using a CMS is that it requires almost no technical skill or knowledge to manage.
			</div>
			<div id="footer-dark">
				<div class="right">Created by Johan Chavaillaz and Jason Racine</div>
				<div class="left">Copyright {$websiteName} 2013</div>
			</div>
		</footer>
	</body>
</html>