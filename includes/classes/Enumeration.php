<?php

/**
 * Enumeration representation.
 *
 * Inspired by SplEnum class (not available in all web hosting).
 *
 * @package CMS
 * @subpackage System
 * @author Chavjoh
 * @since 1.0.0
 */
class Enumeration
{
	/**
	 * Default enumeration value
	 */
	const __default = null;

	/**
	 * Indicates if a constant value exists
	 *
	 * @param string $constant Constant value to check
	 * @return bool True if it exists, False otherwise
	 */
	public static function exist($constant)
	{
		return in_array($constant, self::getConstList());
	}

	/**
	 * Return all defined constants
	 *
	 * @return array List of all constants
	 */
	public static function getConstList()
	{
		$reflect = new ReflectionClass(get_called_class());
		return $reflect->getConstants();
	}

	/**
	 * Get default value
	 *
	 * @return mixed|value Default value
	 */
	public static function getDefault()
	{
		return self::__default;
	}
}
