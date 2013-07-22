<?php

/**
 * Singleton class for PDO
 *
 * @package CMS
 * @subpackage Database
 * @author Chavjoh
 * @since 1.0.0
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
	 * @throw DatabaseConnexionException PDO Exception
	 */
	protected static function createDatabaseLink()
	{
		try {
			// Creates Data Source Name (contains the information required to connect to the database)
			$dsn = DB_DRIVER.':host='.DB_HOST.';dbname='.DB_NAME.';port='.DB_PORT;

			// Creates PDO object used as a link to the database
			static::$database = new PDO($dsn, DB_USER, DB_PASSWORD, array(
				PDO::ATTR_TIMEOUT => "15",
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			));

			// Charset UTF8 for all the database table
			static::$database->exec("SET CHARACTER SET utf8");
		}
		catch (PDOException $e) {
			throw new DatabaseConnexionException(__METHOD__, $e->getMessage());
		}
	}
}
