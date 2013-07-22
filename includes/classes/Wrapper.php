<?php

/**
 * Wrapper Interface
 *
 * @package CMS
 * @subpackage Wrapper
 * @author Chavjoh
 * @since 1.0.0
 */
interface Wrapper
{
	/**
	 * Get a value from the wrapper.
	 *
	 * @param string $key Key associated with the value
	 * @return string Value associated to the key
	 */
	static function get($key);

	/**
	 * Load the data associated with the wrapper
	 */
	static function load();
}
