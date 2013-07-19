<?php
/**
 * Singleton class for Smarty
 *
 * @author		Chavjoh
 * @link		www.chavjoh.ch
 * @version 	1.0
 */

class SmartyLib
{
	/**
	 * Smarty object for template management
	 * 
	 * @var Smarty
	 */
	protected static $smarty = null;
	
	/**
	 * Retrieve the Smarty object for template management
	 *
	 * @param string $templateDirectory Template include directory
	 * @param integer $cache Representing the validity term in seconds of the cache (-1 for an infinie period)
	 * @return Smarty Smarty object for template management
	 */
	public static function getInstance($templateDirectory, $cache = 0)
	{
		static::createSmarty();
		
		// Active cache if requested
		static::smartyCache($cache);
		
		// Set the template include directory
		static::$smarty->setTemplateDir($templateDirectory);

		// Return the instance of Smarty to manage templates
		return static::$smarty;
	}
	
	/**
	 * Create a new Smarty object with his configuration
	 */
	protected static function createSmarty()
	{
		static::$smarty = new Smarty();
		
		// Sets folder paths templates
		static::$smarty->setCompileDir(PATH_COMPILE);
		
		// Sets folder path caches
		static::$smarty->setCacheDir(PATH_CACHE);
		
		// Made the compilation when a change is made in the debug mode
		static::$smarty->setCompileCheck(DEBUG);
		
		// Register special function for Smarty
		static::$smarty->registerPlugin("function", "plural", array('SmartyLib', 'pluginPlural'));
	}
	
	/**
	 * Activation of caching for the smarty instance if requested
	 *
	 * @param Integer $cache Representing the validity term in seconds of the cache (-1 for an inifinie period)
	 */
	protected static function smartyCache($cache)
	{
		// Active Smarty cache for a specified time or endless
		if ($cache > 0 OR $cache == -1)
		{
			static::$smarty->setCaching(true);
			static::$smarty->setCacheLifetime($cache);
		}
		else
			static::$smarty->setCaching(false);
	}
	
	/**
	 * Plugin for plural form in Smarty templates
	 * 
	 * @param array $parameters Parameters received from plugin call in template
	 * @param Smarty $smarty Presently Smarty object
	 * @return string Singular or plural form depending on the value passed by parameter
	 */
	public static function pluginPlural($parameters, $smarty)
	{
		return Tool::plural($parameters['value'], $parameters['singular'], $parameters['plural']);
	}
}

?>