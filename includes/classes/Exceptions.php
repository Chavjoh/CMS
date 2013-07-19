<?php

/**
 * Specialized class for CMS Exception.
 * Contains the class and method as first argument, to show to the user when debugging
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
 */
class FileNotFoundException extends CmsException {};

/**
 * Some data are invalid
 */
class InvalidDataException extends CmsException {};

/**
 * Class ArgumentMissingException
 */
class ArgumentMissingException extends CmsException {};

/**
 * Class InvalidLoginPasswordException
 */
class InvalidLoginPasswordException extends CmsException {};



/**
 * Fatal error in CMS
 */
class FatalErrorException extends CmsException {};

/**
 * Cannot connect to the database
 */
class DatabaseConnexionException extends FatalErrorException {};

/**
 * Internal class not found
 */
class ClassNotFoundException extends FatalErrorException {};

/**
 * Class InvalidDerivationException
 */
class InvalidDerivationException extends FatalErrorException {};

?>