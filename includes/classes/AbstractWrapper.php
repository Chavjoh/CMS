<?php
/**
 * Abstract version of a Wrapper.
 *
 * @version 1.0
 */

abstract class AbstractWrapper
{
	/**
	 * Contains all the data can be retrieved with the wrapper
	 *
	 * @var null|array
	 */
	protected static $data = null;

	/**
	 * Get a value from the wrapper.
	 *
	 * @param string $key Key associated with the value
	 * @return string Value associated to the key
	 */
	public static function get($key)
	{
		if (is_null(self::$data))
			static::load();

		if (isset(self::$data[$key]))
			return self::$data[$key];
		else
			return '';
	}

	/**
	 * Load the data associated with the wrapper
	 */
	abstract protected static function load();
}

?>