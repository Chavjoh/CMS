<?php

/**
 * Interface for cache classes
 *
 * @package CMS
 * @subpackage Cache
 * @author Chavjoh
 * @since 1.0.0
 */
interface Cache
{
	static function get($key);
	static function getArray();
	static function set($key, $value);
	static function setArray(array $array);
	static function add($key, $value);
	static function exist($key);
	static function load();
	static function setCacheFile();
	static function updateCacheFile();
}
