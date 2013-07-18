<?php
/**
 * Abstract cache class for associative array
 *
 * @version 1.0
 */

abstract class SerializedArrayCache implements Cache
{
	/**
	 * Cache array
	 *
	 * @var null|array
	 */
	private static $cache = null;

	/**
	 * File path for cache
	 *
	 * @var null|string
	 */
	protected static $cacheFile = null;

	/**
	 * Get the cache of the element
	 *
	 * @param string $key Key to identify the element
	 * @return mixed|null Value or Null if element doesn't exist
	 */
	public static final function get($key)
	{
		// Load the cache if necessary
		self::checkLoad();

		// Return the cached array element if it exists
		if (self::exist($key))
			return self::$cache[$key];
		else
			return null;
	}

	/**
	 * Set the cache of the element indicated
	 *
	 * @param string $key Key to identify the element
	 * @param mixed $value New value corresponding to the key
	 */
	public static final function set($key, $value)
	{
		// Load the cache if necessary
		self::checkLoad();

		// Update the cache
		self::$cache[$key] = $value;

		// Update the cache file
		self::updateCacheFile();
	}

	/**
	 * Indicates if the element cached exists
	 *
	 * @param string $key Key to identify the element
	 * @return bool True if the cache exists, False otherwise
	 */
	public static final function exist($key)
	{
		// Load the cache if necessary
		self::checkLoad();

		// Indicates if the cache for this key exists
		return (isset(self::$cache[$key]));
	}

	/**
	 * Indicates if the cache is loaded
	 *
	 * @return bool True if the cache is loaded, False otherwise
	 */
	public static final function isLoaded()
	{
		return !(self::$cache == null);
	}

	/**
	 * Load the cache from the file
	 */
	protected static final function load()
	{
		static::setCacheFile();

		if (file_exists(self::$cacheFile))
			self::$cache = unserialize(file_get_contents(self::$cacheFile));
		else
		{
			self::$cache = array();
			self::updateCacheFile();
		}
	}

	/**
	 * Load the cache if necessary
	 */
	protected static final function checkLoad()
	{
		if (!self::isLoaded())
			self::load();
	}

	/**
	 * Update the cache file with the current cached values
	 */
	protected static final function updateCacheFile()
	{
		static::setCacheFile();

		// Write / overwrite cache file
		file_put_contents(self::$cacheFile, serialize(self::$cache));
	}
}

?>