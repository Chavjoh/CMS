{assign var=websiteName value=Security::out(ConfigurationManager::get('meta_name'))}
{assign var=websiteFavicon value=Security::out(ConfigurationManager::get('meta_favicon'))}
{assign var=websitePage value=Security::out($controller->getPageName())}
{assign var=websiteKeywords value=Security::out($controller->getPageKeywords())}
{assign var=websiteRobots value=Security::out($controller->getPageRobots())}
{assign var=websiteAuthor value=Security::out($controller->getPageAuthor())}
{assign var=websiteDescription value=Security::out($controller->getPageDescription())}
{assign var=baseUrl value=Server::getDirectoryScript()|cat:$smarty.const.DS|cat:$smarty.const.URL_ADMIN|cat:$smarty.const.DS}
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

		{if !empty($websiteRobots)}
		<meta name="robots" lang="fr" content="{$websiteRobots}" />
		{/if}

		{if !empty($websiteAuthor)}
		<meta name="author" lang="fr" content="{$websiteAuthor}" />
		{/if}

		<meta name="generator" content="CMS ({$smarty.const.VERSION})" />
		
		<!-- Plugin Bootstrap -->
		<link href="{$skinPath}plugins/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
		
		<!-- Fonts -->
		<link href="{$skinPath}fonts/roboto/stylesheet.css" rel="stylesheet" type="text/css" media="all" />
		
		<!-- General Design -->
		<link href="{$skinPath}styles/design.css" rel="stylesheet" type="text/css" media="all" />
		<link href="{$skinPath}styles/pages.css" rel="stylesheet" type="text/css" media="all" />
		
		<!-- Plugin jQuery -->
		<script type="text/javascript" src="{$skinPath}scripts/jquery.js"></script>
		
		<!-- Plugin NyroModal -->
		<link rel="stylesheet" href="{$skinPath}plugins/nyromodal/styles/nyroModal.css" type="text/css" media="screen" />
		<script type="text/javascript" src="{$skinPath}plugins/nyromodal/js/jquery.nyroModal.custom.js"></script>
		<!--[if IE 6]>
			<script type="text/javascript" src="{$skinPath}plugins/nyromodal/js/jquery.nyroModal-ie6.min.js"></script>
		<![endif]-->
		
		<!-- Plugin HTML 5 for IE -->
		<!--[if lte IE 8]>
			<script src="{$skinPath}scripts/html5.js"></script>
		<![endif]-->

		<!-- Nyromodal activation -->
		<script type="text/javascript" src="{$skinPath}scripts/global.js"></script>
	</head>
	<body>
		<div id="main">
			<header>
				{if Login::isLogged()}
				<div id="user">
					{Security::out($smarty.session.name)} {Security::out($smarty.session.surname)}
					<a href="{Server::getDirectoryScript()}/admin/Login/disconnect"><img src="{$skinPath}images/logout.png" alt="Logout" class="logout" /> </a>
				</div>
				{/if}
				<img src="{$skinPath}images/header_logo.png" alt="Logo" class="logo" />
				<div id="alpha"> {$smarty.const.VERSION} </div>
			</header>

			{if Login::isLogged()}
			<nav>
				<a href="{$baseUrl}" title="Home sweet home">Home</a>
				<a href="{$baseUrl}Pages">Pages</a>
				<a href="{$baseUrl}Menus">Menus</a>
				<a href="{$baseUrl}Templates">Templates</a>
				<a href="{$baseUrl}Modules">Modules</a>
				<a href="{$baseUrl}Users">Users</a>
				<a href="{$baseUrl}Settings">Settings</a>
			</nav>
			{/if}

			<section id="logger">
				{foreach from=Logger::getListMessage() item="message"}
					<div class="{$message->getSeverityClass()}">
						{$message->getMessage()}
					</div>
				{/foreach}
			</section>

			<section id="content">
				{$controller->getPageContent()}
			</section>
		</div>
	</body>

</html>