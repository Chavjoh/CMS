<?php

/**
 * Cache with line by line file (KEY=VALUE for each line)
 *
 * @package CMS
 * @subpackage Cache
 * @author Chavjoh
 * @since 1.0.0
 */
abstract class KeyValueCache extends AbstractCache
{
	/**
	 * Load the cache from the file
	 */
	public static function load()
	{
		static::setCacheFile();

		if (file_exists(static::$cacheFile))
		{
			static::initialization(false);

			// Get and split "KEY=VALUE" line in an array from the file
			$strings = array_map(
				function($line) { return explode('=', trim($line)); },
				file(static::$cacheFile)
			);

			// Save each line
			foreach ($strings AS $value)
				static::add($value[0], $value[1]);
		}
		else
			static::initialization(true);
	}

	/**
	 * Update the cache file with the current cached values
	 */
	public static function updateCacheFile()
	{
		static::setCacheFile();

		// Prepare cache file content
		$fileContent = "";

		// Create cache file content with cache array
		foreach (static::getArray() AS $key => $value)
			$fileContent .= $key.'='.$value.PHP_EOL;

		// Write / overwrite cache file
		file_put_contents(static::$cacheFile, $fileContent);
	}
}
