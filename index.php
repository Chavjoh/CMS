<?php
/**
 * Content Management System
 * Modular and multifunctional CMS
 * 
 * Release : 1.0.0 (format: <major>.<minor>.<patch>)
 * 
 * @copyright Copyright (c) 2013
 * @author Chavjoh
 * @link http://www.chavjoh.ch
 * @license -
 */

// Include configuration and autoloader
require_once './includes/configuration.php';
require_once PATH_CLASS.'Autoloader.php';

try
{
	// Handle request and dispatch it to the appropriate controller
	$dispatcher = new Dispatcher($_SERVER['REQUEST_URI']);
	$dispatcher->dispatch();

	// Don't show the page if we make a redirection
	if (!$dispatcher->isRedirectHeaders())
	{
		// Check request type to select appropriate template
		$httpRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : '';
		$template = (strtolower($httpRequest) === 'xmlhttprequest') ? TEMPLATE_AJAX : TEMPLATE_DESIGN;

		// Create main template for design and display it
		$smarty = SmartyLib::getInstance($dispatcher->getController()->getSkinPath());
		$smarty->assign('controller', $dispatcher->getController());
		$smarty->assign('skinPath', Server::getBaseUrl().$dispatcher->getController()->getSkinPath());
		$smarty->display($dispatcher->getController()->getSkinPath().$template);
	}
}
catch (FatalErrorException $e)
{
	echo '<h1> Fatal Error </h1>';
	echo $e->getMessage();
}/*
catch (Exception $e){
	echo $e->getMessage();
}*/

?>