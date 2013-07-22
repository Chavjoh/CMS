<?php

/**
 * Cache for Database Bind Array
 *
 * @package CMS
 * @subpackage Cache
 * @author Chavjoh
 * @since 1.0.0
 */
class BindArrayCache extends SerializedArrayCache
{
	/**
	 * Set file cache path.
	 * We use a function because expressions are forbidden in variable declaration
	 */
	public static function setCacheFile()
	{
		self::$cacheFile = PATH_CACHE."bindArray.cache";
	}
}
