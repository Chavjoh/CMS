<?php
/**
 * BackEnd Not Found Controller
 * When a controller for BackEnd is not found
 *
 * @version 1.0
 */

class AdminNotFoundController extends BackEndController
{
	/**
	 * Default method called by Dispatcher
	 */
	public function index() {}

	/**
	 * @see AbstractController::getPageName()
	 */
	public function getPageName()
	{
		return 'Page not found - '.parent::getPageName();
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