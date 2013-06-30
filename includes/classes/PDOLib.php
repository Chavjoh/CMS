<?php
/**
 * Singleton class for PDO
 * 
 * @version 1.0
 */

class PDOLib
{
	/**
	 * PDO object for database interaction
	 * 
	 * @var PDO
	 */
	protected static $database;
	
	/**
	 * Retrieve the PDO object for database interaction
	 *
	 * @return PDO PDO object
	 */
	public static function getInstance()
	{
		// If we're calling this method for the first time
		if (static::$database == null)
			static::createDatabaseLink();
	
		// Return the instance of PDO to communicate with the database
		return static::$database;
	}
	
	/**
	 * Create a new PDO object with his configuration
	 *
	 * @return PDO PDO object created and configured
	 */
	protected static function createDatabaseLink()
	{
		try {
			// Creates Data Source Name (contains the information required to connect to the database)
			$dsn = DB_DRIVER.':host='.DB_HOST.';dbname='.DB_NAME.';charset=utf-8';//.';port='.DB_PORT;
				
			// Creates PDO object used as a link to the database
			static::$database = new PDO( $dsn, DB_USER, DB_PASSWORD, array(
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			));

			static::$database->exec("SET CHARACTER SET utf8");
		}
		catch ( Exception $e ) {
			echo " [PDOLib] ".$e->getMessage();
		}
	}
}
?>