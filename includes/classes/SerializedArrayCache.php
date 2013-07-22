<?php

/**
 * Cache with array serialization
 *
 * @package CMS
 * @subpackage Cache
 * @author Chavjoh
 * @since 1.0.0
 */
abstract class SerializedArrayCache extends AbstractCache
{
	/**
	 * Load the cache from the file
	 */
	public static function load()
	{
		static::setCacheFile();

		if (file_exists(static::$cacheFile))
			static::setArray(unserialize(file_get_contents(static::$cacheFile)));
		else
			static::initialization(true);
	}

	/**
	 * Update the cache file with the current cached values
	 */
	public static function updateCacheFile()
	{
		static::setCacheFile();

		// Write / overwrite cache file
		file_put_contents(static::$cacheFile, serialize(static::getArray()));
	}
}
