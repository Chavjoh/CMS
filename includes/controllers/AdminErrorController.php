<?php

/**
 * Admin Error Controller
 *
 * Manage errors occurred during the page load
 *
 * @package CMS
 * @subpackage Controller
 * @author Chavjoh
 * @since 1.0.0
 */
class AdminErrorController extends BackEndController
{
	/**
	 * Exception to manage
	 *
	 * @var CmsException
	 */
	protected $error;

	/**
	 * Random quote when page is loaded by user and not by CMS system
	 *
	 * @var array
	 */
	protected $quote;

	/**
	 * Not called by dispatcher (because error occurred)
	 */
	public function index()
	{
		// Some beautiful quote, because we want to load "error" page by URL
		$this->quote = array(
			"This CMS never has bugs. It just develops random features.",
			"Delay is preferable to error.",
			"A program is a spell cast over a computer, turning input into error messages.",
			"Error is human but a disaster requires a computer."
		);
	}

	/**
	 * Add the exception to manage
	 *
	 * @param CmsException $e Exception to manage
	 */
	public function setError(CmsException $e)
	{
		$this->error = $e;
	}

	/**
	 * @see AbstractController::getPageName()
	 */
	public function getPageName()
	{
		switch (get_class($this->error))
		{
			case "FileNotFoundException";
			case "ClassNotFoundException";
				return Language::get(__CLASS__.'.PageTitle.NotFound').' - '.parent::getPageName();

			default:
				parent::getPageName();
		}
	}

	/**
	 * @see AbstractController::getPageContent()
	 */
	public function getPageContent()
	{
		switch (get_class($this->error))
		{
			case "FileNotFoundException";
			case "ClassNotFoundException";
				$this->templateFile = 'notFound.tpl';
				break;

			case "ArgumentMissingException":
				return '<div class="alert alert-error">'.$this->error->getMessage().'</div>';
				break;

			default:
				return '<blockquote>'.array_rand(array_flip($this->quote), 1).'</blockquote>';
		}

		return parent::getPageContent();
	}

	/**
	 * @see AbstractController::getHeaders()
	 */
	public function getHeaders()
	{
		$newHeaders = array();

		switch (get_class($this->error))
		{
			case "FileNotFoundException";
			case "ClassNotFoundException";
				$newHeaders = array(
					$_SERVER["SERVER_PROTOCOL"]." 404 Not Found",
					"Status: 404 Not Found"
				);
				break;
		}

		return array_merge($newHeaders, parent::getHeaders());
	}
}
