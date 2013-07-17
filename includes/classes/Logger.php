<?php

/**
 * Enumeration of all Severity possible for LoggerMessage
 *
 * @version 1.0
 */
class LoggerSeverity extends Enumeration
{
	const __default = self::NOTICE;

	const NOTICE = 0;
	const SUCCESS = 1;
	const WARNING = 2;
	const ERROR = 3;
}

/**
 * Class representing a message to show to the user
 *
 * @version 1.0
 */
class LoggerMessage
{
	/**
	 * Message to show to the user
	 *
	 * @var string
	 */
	protected $message;

	/**
	 * Severity of the message
	 *
	 * @var int
	 */
	protected $severity;

	/**
	 * Construct a message to show to the user
	 *
	 * @param string $message Message to show
	 * @param int $severity Severity of the message (provided by LoggerSeverity)
	 */
	public function __construct($message, $severity = null)
	{
		$this->message = $message;

		if (in_array($severity, LoggerSeverity::getConstList()))
			$this->severity = $severity;
		else
			$this->severity = LoggerSeverity::getDefault();
	}

	/**
	 * Message to show to the user
	 *
	 * @return string Message
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * Severity of the message.
	 * If not indicated, default severity defined in LoggerSeverity
	 *
	 * @return int Severity
	 */
	public function getSeverity()
	{
		return $this->severity;
	}

	/**
	 * Get bootstrap class corresponding to the severity
	 *
	 * @return string CSS Class
	 */
	public function getSeverityClass()
	{
		switch ($this->severity)
		{
			case LoggerSeverity::NOTICE:
				return 'alert alert-info';
			case LoggerSeverity::SUCCESS:
				return 'alert alert-success';
			case LoggerSeverity::WARNING:
				return 'alert';
			case LoggerSeverity::ERROR:
				return 'alert alert-error';
			default:
				return '';
		}
	}
}

/**
 * Logger for message to the user and error backup
 *
 * @version 1.0
 */
class Logger
{
	/**
	 * Log a message to show to the user
	 *
	 * @param LoggerMessage $message Message to show
	 */
	public static function logMessage(LoggerMessage $message)
	{
		if (!isset($_SESSION[__CLASS__]))
			$_SESSION[__CLASS__] = array();

		// Add the message to the session, to be show in the future
		array_push($_SESSION[__CLASS__], serialize($message));
	}

	/**
	 * Get message list to show to the user and delete them in memory.
	 *
	 * @return array List of LoggerMessage
	 */
	public static function getListMessage()
	{
		if (!isset($_SESSION[__CLASS__]))
			$_SESSION[__CLASS__] = array();

		// Prepare message list
		$list = array();

		// Retrieve message
		foreach ($_SESSION[__CLASS__] AS $message)
			array_push($list, unserialize($message));

		// Clear the session from old message
		$_SESSION[__CLASS__] = array();

		return $list;
	}

	public static function errorHandler($errorNumber, $errorString, $errorFile, $errorLine)
	{

	}

	public static function exceptionHandler($message, Exception $e)
	{

	}
}

?>