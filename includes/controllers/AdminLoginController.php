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
				$this->header[] = 'Location: '.Server::getCurrentUrl();
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
	}
	
    /**
     * @see AbstractController::getPageName()
     */
	public function getPageName()
	{
		return 'Login - '.parent::getPageName();
	}

	/**
	 * @see AbstractController::getMethodAvailable()
	 */
	public static function getMethodAvailable()
	{
		return array_merge(parent::getMethodAvailable(), array('disconnect'));
	}
}
