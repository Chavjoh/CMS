<?php
/**
 * BackEnd Not Found Controller
 * When a controller for BackEnd is not found
 *
 * @version 1.0
 */

class AdminNotFoundController extends AbstractController
{
	/**
	 * Default method called by Dispatcher
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 */
	public function index(array $arguments)
	{
		parent::index($arguments);
	}

	/**
	 * @see AbstractController::getPageName()
	 */
	public function getPageName()
	{
		return 'Page Not Found - '.parent::getPageName();
	}

	/**
	 * @see Controller::getPageContent()
	 */
	public function getPageContent()
	{
		return "<h1>Page Not Found</h1> Oh snap, it looks like we're missing something ... <br /> ";
	}

	/**
	 * @see Controller::getHeaders()
	 */
	public function getHeaders()
	{
		$headers = array(
			$_SERVER["SERVER_PROTOCOL"]." 404 Not Found",
			"Status: 404 Not Found"
		);

		return array_merge($headers, parent::getHeaders());
	}
}

?>