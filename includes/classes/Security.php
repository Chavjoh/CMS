<?php

/**
 * Security level for parsing with manager
 *
 * @package CMS
 * @subpackage Security
 * @author Chavjoh
 * @since 1.0.0
 */
class SecurityLevel extends Enumeration
{
	const __default = self::NORMAL;

	/**
	 * For text only. Protection against HTML and JS.
	 */
	const NORMAL = 0;

	/**
	 * Allow HTML. Protection against HTML and JS
	 */
	const ALLOW_HTML = 1;

	/**
	 * Allow HTML and JS. No protection activated
	 */
	const ALLOW_JS = 2;
}

/**
 * Security central manager
 *
 * @package CMS
 * @subpackage Security
 * @author Chavjoh
 * @since 1.0.0
 */
class Security
{
    /**
     * Clean data
     *
     * @param string $data Ingoing data to clean
     * @return Cleaned data
     */
    public static function clean($data)
    {
        return trim($data);
    }

	/**
	 * Output security procedure
	 *
	 * @param string|array $data Outgoing data to secure
	 * @param int $level Security level provided by SecurityLevel class
	 * @return array|string Secured data
	 */
	public static function out($data, $level = SecurityLevel::__default)
    {
		// If we receive an array, we apply this function for each element of it
		if (is_array($data))
		{
			foreach ($data as $key => $value)
				$data[Security::out($key, $level)] = Security::out($value, $level);

			return $data;
		}
		// Security for current data string
		else
		{
			// Depending of the security level indicated
			switch ($level)
			{
				case SecurityLevel::NORMAL;
					$data = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", htmlentities($data, ENT_QUOTES | ENT_HTML5, "UTF-8"));
					break;

				case SecurityLevel::ALLOW_JS:
					$data = htmlentities($data, ENT_QUOTES | ENT_HTML5, "UTF-8");
					break;

				case SecurityLevel::ALLOW_HTML:
					$data = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $data);
					break;
			}

			return nl2br($data);
		}
    }

    /**
     * Password hash protection
     *
     * @param string password : ingoing password to compute
     * @return hashed password
     */
    public static function passwordHash($password)
    {
        return sha1(PASSWORD_SALT.$password);
    }
}
