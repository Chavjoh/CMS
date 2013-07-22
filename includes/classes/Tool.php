<?php
/**
 * Tools methods
 *
 * @package CMS
 * @subpackage System
 * @author Chavjoh
 * @since 1.0.0
 */
class Tool
{
	/**
	 * Choose the singular or plural form depending on the value
	 * 
	 * @param array|integer $value Value used to choose the form
	 * @param string $singular Singular form
	 * @param string $plural Plural form
	 * @return string Choice based on the value
	 */
	public static function plural($value, $singular, $plural)
	{
		if ((is_array($value) && count($value) > 1) OR (is_numeric($value) && $value > 1))
			return $plural;
		else
			return $singular;
	}
}
