<?php

/**
 * Abstract version of a Wrapper.
 *
 * @package CMS
 * @subpackage Wrapper
 * @author Chavjoh
 * @since 1.0.0
 */
abstract class AbstractWrapper implements Wrapper
{
	/**
	 * All data retrieved with the wrapper
	 *
	 * @var null|array
	 */
	protected static $data = null;

	/**
	 * @see Wrapper::get()
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
}
