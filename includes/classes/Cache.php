<?php
/**
 * Interface for cache class
 */

interface Cache
{
	static function get($key);
	static function set($key, $value);
	static function exist($key);
	static function setCacheFile();
}

?>