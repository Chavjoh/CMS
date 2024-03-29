<?php

/**
 * Autoloader for classes and interfaces
 *
 * @package CMS
 * @subpackage System
 * @author Chavjoh
 * @since 1.0.0
 */
class Autoloader
{
	/**
	 * Autoload method
	 * 
	 * @param string $class Name of the class to load
	 * @throws Exception When file or class in file is not found
	 */
	public static function load($class)
	{
		// Check class or interface existence
		if (class_exists($class, false) OR interface_exists($class, false))
			return;

		// Define path to find the class
		if (preg_match("/Controller$/i", $class))
			$pathClass = PATH_CONTROLLER;
		else if (preg_match("/Model$/i", $class))
			$pathClass = PATH_MODEL;
		else
			$pathClass = PATH_CLASS;

		// Create file path
		$file = $pathClass.$class.'.php';
		
		// Check file existence
		if (!file_exists($file))
			throw new FileNotFoundException(__METHOD__, Language::get(__CLASS__.'.FileNotFoundException', array($file)));
		else
		{
			// Import file
			require_once $file;

			// Check presence of the class or interface in the file 
			if (!class_exists($class, FALSE) AND !interface_exists($class, FALSE))
				throw new ClassNotFoundException(__METHOD__, Language::get(__CLASS__.'.ClassNotFoundException', array($class)));
		}
	}
}

// Set the autoloader
spl_autoload_extensions('.php');
spl_autoload_register(array('Autoloader', 'load'));
