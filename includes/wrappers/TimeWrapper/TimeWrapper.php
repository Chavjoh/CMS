<?php
/**
 * Class TimeWrapper
 *
 * Used to retrieve current UTC time
 *
 * @version 1.0
 */

class TimeWrapper extends AbstractWrapper
{
	protected static function load()
	{
		self::$data['current_utc'] = file_get_contents("http://www.timeapi.org/utc/now?\a%20\b%20\d%20\I:\M:\S%20\Z%20\Y");
	}
}

