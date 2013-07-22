<?php

/**
 * Admin Login Controller
 *
 * Manage user access to the BackEnd
 *
 * @version 1.0
 */
class AdminLoginController extends BackEndController
{
    /**
     * Login page
     */
    public function index()
    {
		$this->templateFile = 'login.tpl';

		// Login procedure
		if (isset($_POST['username']) AND isset($_POST['password']))
		{
			try
            {
                Login::connect($_POST['username'], $_POST['password']);

				// Redirect to BackEnd home
				$this->header[] = 'Location: '.$this->urlController;
            }
            catch (InvalidLoginPasswordException $e)
            {
				Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
            }
		}
    }

	/**
	 * Disconnect user
	 */
	public function disconnect()
	{
		Login::disconnect();

		// Redirect to BackEnd home (login page)
		$this->header[] = 'Location:'.$this->urlController;
	}
	
    /**
     * @see AbstractController::getPageName()
     */
	public function getPageName()
	{
		return Language::get(__CLASS__.'.PageTitle').' - '.parent::getPageName();
	}

	/**
	 * @see AbstractController::getMethodAvailable()
	 */
	public static function getMethodAvailable()
	{
		return array_merge(parent::getMethodAvailable(), array('disconnect'));
	}
}
