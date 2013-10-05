<?php
/**
 * Internal configuration of the CMS
 * Be careful ;)
 */

define("CONFIGURATION_FILE", "./includes/configuration.xml");

if (!file_exists(CONFIGURATION_FILE)) {
	throw new Exception("Need XML configuration file.");
}

// Get configuration content in XML
$configurationContentXML = file_get_contents(CONFIGURATION_FILE);

if ($configurationContentXML == "") {
	header('Location: ./install/');
}

// Read XML file
$configurationXML = new SimpleXMLElement($configurationContentXML);

// Session configuration
session_name('Chavjoh_CMS');
session_start();

// Date and time configuration
date_default_timezone_set('Europe/Paris');

// Directory Separator
define('DS', '/');

// Activation of the debug mode
define('DEBUG', (bool) $configurationXML->debug['active']);

/*
 * Admin key to get access to the backend.
 * Special characters and spaces forbidden.
 */
define('URL_ADMIN', $configurationXML->administration->key);

// CMS version
define('VERSION', '1.0.0a');

// Password salt
define('PASSWORD_SALT', $configurationXML->salt['value']);

// Paths for the different parts of the CMS
define('PATH_CLASS', 'includes'. DS .'classes'. DS);
define('PATH_CONTROLLER', 'includes'. DS .'controllers'. DS);
define('PATH_MODEL', 'includes'. DS .'models'. DS);
define('PATH_LANGUAGE', 'includes'. DS .'languages'. DS);
define('PATH_COMPILE', 'includes'. DS .'compiles'. DS);
define('PATH_WRAPPER', 'includes'. DS .'wrappers'. DS);
define('PATH_MODULE', 'includes'. DS .'modules'. DS);
define('PATH_CACHE', 'includes'. DS .'caches'. DS);
define('PATH_SKIN', 'includes'. DS .'skins'. DS);
define('PATH_LOG', 'includes'. DS .'logs'. DS);

// Database access information
define('DB_DRIVER', $configurationXML->database->driver);
define('DB_NAME', $configurationXML->database->name);
define('DB_USER', $configurationXML->database->user);
define('DB_PASSWORD', $configurationXML->database->password);
define('DB_HOST', $configurationXML->database->host);
define('DB_PORT', $configurationXML->database->port);
define('DB_PREFIX', $configurationXML->database->prefix);

// Template information
define('TEMPLATE_FRONTEND', 'frontend');
define('TEMPLATE_BACKEND', 'backend');
define('TEMPLATE_DESIGN', 'templates'.DS.'design.tpl');
define('TEMPLATE_AJAX', 'templates'.DS.'ajax.tpl');
define('TEMPLATE_LANGUAGE', 'languages'.DS);

// Smarty Library
require_once 'includes'.DS.'smarty'.DS.'Smarty.class.php';

// Exceptions list
require_once PATH_CLASS.'Exceptions.php';

//set_error_handler(array('Logger', 'log'));

// Error reporting ( Hard mode here :D )
error_reporting(E_ALL);

// PHP ini configuration
if (DEBUG == true) 
{
	ini_set('display_errors', 'On');
} 
else 
{
	ini_set('display_errors', 'Off');
	ini_set('log_errors', 'On');
	ini_set('error_log', PATH_LOG . 'php.log');
}

?>