<?php
/**
 * Cache for Bind Array
 *
 * @version 1.0
 */

class BindArrayCache
{
	/**
	 * Cache for the bind arrays
	 *
	 * @var null|array
	 */
	protected static $cache = null;

	/**
	 * Get the cache of the bind array indicated
	 *
	 * @param string $table Table name of the bind array
	 * @return array|null [Bind array of the table name AND array of primary keys] OR [NULL if it not exists]
	 */
	public static function get($table)
	{
		// Load the cache if necessary
		self::checkLoad();

		// Return the bind array cached if exists
		if (self::exist($table))
			return self::$cache[$table];
		else
			return null;
	}

	/**
	 * Set the cache of the bind array indicated
	 *
	 * @param string $table Table name of the bind array
	 * @param array $bindArray New bind array
	 */
	public static function set($table, $bindArray)
	{
		// Load the cache if necessary
		self::checkLoad();

		// Update the bind array
		self::$cache[$table] = $bindArray;

		// Update the cache file
		self::updateCacheFile();
	}

	/**
	 * Indicates if the bind array cached for the table indicated exists
	 *
	 * @param string $table Table name of the bind array
	 * @return bool True if the cache exists, False otherwise
	 */
	public static function exist($table)
	{
		// Load the cache if necessary
		self::checkLoad();

		// Indicates if the cache for this table exists
		if (isset(self::$cache[$table]))
			return true;
		else
			return false;
	}

	/**
	 * Indicates if the cache is loaded
	 *
	 * @return bool True if the cache is loaded, False otherwise
	 */
	public static function isLoaded()
	{
		if (self::$cache == null)
			return false;
		else
			return true;
	}

	/**
	 * Load the cache of bind arrays from the file
	 */
	protected static function load()
	{
		$fileName = PATH_CACHE."bindArray.cache";

		if (file_exists($fileName))
		{
			self::$cache = unserialize(file_get_contents($fileName));
		}
		else
		{
			self::$cache = array();
			self::updateCacheFile();
		}
	}

	/**
	 * Load the cache if necessary
	 */
	protected static function checkLoad()
	{
		if (!self::isLoaded())
			self::load();
	}

	/**
	 * Update the cache file with the current cached bind arrays
	 */
	protected static function updateCacheFile()
	{
		file_put_contents(PATH_CACHE."bindArray.cache", serialize(self::$cache));
	}
}
?>