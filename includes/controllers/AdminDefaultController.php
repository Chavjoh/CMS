<?php
/**
 * BackEnd Default Controller
 * Manage index page of admin backend
 *
 * @version 1.0
 */

class AdminDefaultController extends AbstractController
{
    /**
     * Default method called by Dispatcher
     * 
     * @param array $arguments Arguments passed by URL to the present Controller
     */
    public function index(array $arguments)
    {
    	parent::index($arguments);
		$this->skinPath = PATH_SKIN.TEMPLATE_BACKEND.DS;
		$this->templateFile = 'home.tpl';
    }

	/**
	 * @see AbstractController::getPageName()
	 */
	public function getPageName()
	{
		return 'Home - Administration - '.parent::getPageName();
	}
}

?>