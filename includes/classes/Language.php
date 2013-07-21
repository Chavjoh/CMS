<?php
/**
 * Language class for CMS translation
 *
 * @version 1.0
 */

class Language extends KeyValueCache
{
	/**
	 * Set file cache path.
	 * We use a function because expressions are forbidden in variable declaration
	 */
	public static function setCacheFile()
	{
		// TODO: Change to real value
		self::$cacheFile = PATH_LANGUAGE."en.txt";
	}
}

?>