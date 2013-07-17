<?php

class CmsException extends Exception
{
	protected $method;

	public function __construct($method, $message = "", $code = 0, Exception $previous = Null)
	{
		if (DEBUG)
			$message = "[$method] $message";

		parent::__construct($message, $code, $previous);
	}
}

/**
 * Class FatalErrorException
 */
class FatalErrorException extends CmsException {};

/**
 * Class DatabaseConnexionException
 */
class DatabaseConnexionException extends FatalErrorException {};

/**
 * Class UnrecoverableException
 */
class UnrecoverableException extends CmsException {};

/**
 * Class ClassNotFoundException
 */
class ClassNotFoundException extends UnrecoverableException {};

/**
 * Class FileNotFoundException
 */
class FileNotFoundException extends CmsException {};

/**
 * Class RecoverableException
 */
class RecoverableException extends CmsException {};

/**
 * Class UniqueConstraintException
 */
class UniqueConstraintException extends RecoverableException {};

/**
 * Class InvalidDataException
 */
class InvalidDataException extends RecoverableException {};

/**
 * Class ArgumentMissingException
 */
class ArgumentMissingException extends RecoverableException {};

/**
 * Class InvalidDerivationException
 */
class InvalidDerivationException extends RecoverableException {};

/**
 * Class InvalidLoginPasswordException
 */
class InvalidLoginPasswordException extends RecoverableException {};

?>