<?php
/**
 * Server information and URL handling
 *
 * @author		Chavjoh
 * @link		www.chavjoh.ch
 * @version 	1.0
 */

class Server
{
	/**
	 * Retrieve server IP.
	 * Example : 127.0.0.1
	 *
	 * @return string IP
	 */
	public static function getIp()
	{
		return $_SERVER['SERVER_ADDR'];
	}

	/**
	 * Retrieve current protocol used to load the page.
	 * Example : https
	 *
	 * @return string Protocol
	 */
	public static function getProtocol()
	{
		return strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === FALSE ? 'http' : 'https';
	}
	
	/**
	 * Retrieve host of current website.
	 * Example : www.example.com
	 *
	 * @return string Host
	 */
	public static function getHost()
	{
		return $_SERVER['HTTP_HOST'];
	}
	
	/**
	 * Retrieve address of current script.
	 * Example : /directory/index.php
	 *
	 * @return string Current script address
	 */
	public static function getScriptName()
	{
		return $_SERVER['SCRIPT_NAME'];
	}
	
	/**
	 * Retrieve parameters in current URL.
	 * Example : param1=value1&param2=value2
	 *
	 * @return string Parameters in URL
	 */
	public static function getParameters()
	{
		return $_SERVER['QUERY_STRING'];
	}
	
	/**
	 * Retrieve current URL.
	 * Example : http://localhost/directory/virtual_directory/index.php?param1=value1&param2=value2
	 *
	 * @return string Current URL
	 */
	public static function getCurrentUrl()
	{
		return self::getProtocol().'://'.self::getHost().$_SERVER['REQUEST_URI'];
	}
	
	/**
	 * Retrieve directory of current script.
	 * Example : /directory
	 *
	 * @return string Directory Script
	 */
	public static function getDirectoryScript()
	{
		return dirname(self::getScriptName());
	}
	
	/**
	 * Retrieve base URL of current script.
	 * Example : http://localhost/directory/
	 *
	 * @return string Base URL
	 */
	public static function getBaseUrl()
	{
		return self::getProtocol().'://'.self::getHost().self::getDirectoryScript().'/';
	}
}

?>