<?php

/**
 * Specialized class for CMS Exception.
 * Contains the class and method as first argument, to show to the user when debugging
 *
 * @package CMS
 * @subpackage Exception
 * @author Chavjoh
 * @since 1.0.0
 */
class CmsException extends Exception
{
	protected $method;

	public function __construct($method, $message = "", $code = 0, Exception $previous = Null)
	{
		// We can't re-implement getMessage function, so we modify $message here to make the same effect
		if (DEBUG)
			$message = "[$method] $message";

		// Call parent constructor
		parent::__construct($message, $code, $previous);
	}
}

/**
 * File not found
 *
 * @package CMS
 * @subpackage Exception
 * @author Chavjoh
 * @since 1.0.0
 */
class FileNotFoundException extends CmsException {};

/**
 * Some data are invalid
 *
 * @package CMS
 * @subpackage Exception
 * @author Chavjoh
 * @since 1.0.0
 */
class InvalidDataException extends CmsException {};

/**
 * Class ArgumentMissingException
 *
 * @package CMS
 * @subpackage Exception
 * @author Chavjoh
 * @since 1.0.0
 */
class ArgumentMissingException extends CmsException {};

/**
 * Class InvalidLoginPasswordException
 *
 * @package CMS
 * @subpackage Exception
 * @author Chavjoh
 * @since 1.0.0
 */
class InvalidLoginPasswordException extends CmsException {};

/**
 * Class not found
 *
 * @package CMS
 * @subpackage Exception
 * @author Chavjoh
 * @since 1.0.0
 */
class ClassNotFoundException extends CmsException {};



/**
 * Fatal error in CMS
 *
 * @package CMS
 * @subpackage Exception
 * @author Chavjoh
 * @since 1.0.0
 */
class FatalErrorException extends CmsException {};

/**
 * Cannot connect to the database
 *
 * @package CMS
 * @subpackage Exception
 * @author Chavjoh
 * @since 1.0.0
 */
class DatabaseConnexionException extends FatalErrorException {};

/**
 * Class InvalidDerivationException
 *
 * @package CMS
 * @subpackage Exception
 * @author Chavjoh
 * @since 1.0.0
 */
class InvalidDerivationException extends FatalErrorException {};
