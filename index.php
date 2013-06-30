<?php
/**
 * Content Management System
 * Modular and multifunctional CMS
 * 
 * Release :	1.0.0 (format: <major>.<minor>.<patch>)
 * 
 * @copyright	Copyright (c) 2013
 * @author		Chavaillaz Johan && Jason Racine
 * @link		-
 * @license		-
 */

// Include configuration and autoloader
require_once('./includes/configuration.php');
require_once(PATH_CLASS.'Autoloader.php');

try {
	// Handle request and dispatch it to the appropriate controller
	$dispatcher = new Dispatcher($_SERVER['REQUEST_URI']);
	$dispatcher->dispatch();

	// Check request type to select appropriate template
	$template = TEMPLATE_DESIGN;
	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
	{
		$template = TEMPLATE_AJAX;
	}

	// Create main template for design and display it
	$smarty = SmartyLib::getInstance($dispatcher->getSkinPath());
	$smarty->assign('controller', $dispatcher->getController()); 
	$smarty->assign('skinPath', Server::getBaseUrl().$dispatcher->getSkinPath());
	$smarty->display($dispatcher->getSkinPath().$template);
}
catch (Exception $e){
	echo $e->getMessage();
	exit();
}

?>