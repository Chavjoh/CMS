<?php
/**
 * Admin Login Controller
 * Manage user access to the BackEnd
 *
 * @version 1.0
 */

class AdminLoginController extends AbstractController
{
    /**
     * Default method called by Dispatcher
     * 
     * @param array $arguments Arguments passed by URL to the present Controller
     */
    public function index(array $arguments)
    {
		parent::index($arguments);
    	
		// Login form
		$this->skinPath = PATH_SKIN.TEMPLATE_BACKEND.DS;
		$this->templateFile = 'login.tpl';

		// Login procedure
		if (isset($_POST['username'])&&isset($_POST['password']))
		{
			try
            {
                Login::connect($_POST['username'],$_POST['password']);
				$this->header[] = 'Location: '.Server::getCurrentUrl();
            }
            catch (Exception $e)
            {
                $this->smarty->assign('error', $e->getMessage());
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
		return 'Login - Administration - '.parent::getPageName();
	}

	/**
	 * @see AbstractController::getMethodAvailable()
	 */
	public static function getMethodAvailable()
	{
		return array_merge(parent::getMethodAvailable(), array('disconnect'));
	}
}

?>