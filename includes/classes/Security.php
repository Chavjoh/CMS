<?php
/**
 * Security central manager
 *
 * @version 1.0
 */

class Security {
    /**
     * Input security procedure
     *
     * @param string $data : ingoing data to secure
     * @return secured data
     */
    public static function in($data)
    {
        if (ctype_digit($data))
            return intval($data);
        else
            return trim($data);
    }

    /**
     * Output security procedure
     *
     * @param string $data : outgoing data to secure
     * @return secured data
     */
    public static function out($data)
    {
		if (is_array($data))
		{
			foreach ($data as $key => $value)
				$data[Security::out($key)] = Security::out($value);

			return $data;
		}
		else
		{
			return nl2br(htmlentities($data, ENT_QUOTES, "UTF-8"));
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
        return sha1($password);
    }
}
?>