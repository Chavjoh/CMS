<?php

/**
 * Language class for CMS translation
 *
 * @package CMS
 * @subpackage Language
 * @author Chavjoh
 * @since 1.0.0
 */
abstract class LanguageCache extends KeyValueCache
{
	/**
	 * @see AbstractCache::get()
	 */
	public static function get($key)
	{
		// Get list of all arguments with current function call
		$argumentList = func_get_args();

		// Get translation from cache
		$translation = parent::get($key);

		// If we ask for word replacement in translation
		if (count($argumentList) > 1)
		{
			// Get values ​​to replace found items
			if (!is_array($argumentList[1]))
				$values = array($argumentList[1]);
			else
				$values = $argumentList[1];

			// Search all %NUMBER elements
			preg_match_all("/%[0-9]*/i", $translation, $matches);

			// For each element found
			foreach ($matches[0] AS $index => $match)
			{
				// If a value exists for it, replaces it
				if (isset($values[$index]))
					$translation = str_replace($match, $values[$index], $translation);
			}
		}

		return $translation;
	}
}
