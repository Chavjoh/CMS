<?php
/**
 * Autoloader for classes and interfaces
 * 
 * @version 1.0
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
		if (class_exists($class, FALSE) OR interface_exists($class, FALSE))
			return;
		
		// Create file path
		$file = PATH_CLASS.$class.'.php';
		
		// Check file existence
		if (!file_exists($file))
			throw new Exception("[".__CLASS__."] File [$file] not found.");
		else
		{
			// Import file
			require_once $file;

			// Check presence of the class or interface in the file 
			if (!class_exists($class, FALSE) AND !interface_exists($class, FALSE))
				throw new Exception("[".__CLASS__."] Class [$class] not found.");
		}
	}
}

// Set the autoloader
spl_autoload_extensions('.php');
spl_autoload_register(array('Autoloader', 'load'));

?>