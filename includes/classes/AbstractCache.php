<?php

/**
 * Abstract cache class with basic cache manipulation functions
 *
 * @package CMS
 * @subpackage Cache
 * @author Chavjoh
 * @since 1.0.0
 */
abstract class AbstractCache implements Cache
{
	/**
	 * Cache array.
	 * Be careful, it's ONE variable cache for ALL derived classes (static constraint).
	 *
	 * @var array
	 */
	private static $cache = array();

	/**
	 * File path for cache
	 *
	 * @var null|string
	 */
	protected static $cacheFile = null;

	/**
	 * Initialization of the cache array for the current derived class
	 *
	 * @param bool $updateFileCache Indicates if we create an empty file cache (True)
	 */
	public static function initialization($updateFileCache = false)
	{
		self::setArray(array());

		if ($updateFileCache)
			static::updateCacheFile();
	}

	/**
	 * Get the cache of the element
	 *
	 * @param string $key Key to identify the element
	 * @return mixed|null Value or Null if element doesn't exist
	 */
	public static function get($key)
	{
		// Load the cache if necessary
		static::checkLoad();

		// Return the cached array element if it exists
		if (static::exist($key))
			return self::$cache[get_called_class()][$key];
		else
			return "[$key]";
	}

	/**
	 * Get entire cache array
	 *
	 * @return array Cache array
	 */
	public static function getArray()
	{
		return self::$cache[get_called_class()];
	}

	/**
	 * Set the cache of the element indicated
	 *
	 * @param string $key Key to identify the element
	 * @param mixed $value New value corresponding to the key
	 */
	public static function set($key, $value)
	{
		// Load the cache if necessary
		static::checkLoad();

		// Update the cache
		self::$cache[get_called_class()][$key] = $value;
	}

	/**
	 * Set entire cache array
	 *
	 * @param array $array New cache array
	 */
	public static function setArray(array $array)
	{
		self::$cache[get_called_class()] = $array;
	}

	/**
	 * @see AbstractCache::set()
	 */
	public static function add($key, $value)
	{
		self::set($key, $value);
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
		static::checkLoad();

		// Indicates if the cache for this key exists
		return (isset(self::$cache[get_called_class()][$key]));
	}

	/**
	 * Indicates if the cache is loaded
	 *
	 * @return bool True if the cache is loaded, False otherwise
	 */
	public static final function isLoaded()
	{
		return (isset(self::$cache[get_called_class()]));
	}

	/**
	 * Load the cache if necessary
	 */
	protected static final function checkLoad()
	{
		if (!static::isLoaded())
			static::load();
	}
}
