<?php

/**
 * BackEnd Default Controller
 *
 * Manage index page of admin backend
 *
 * @package CMS
 * @subpackage Controller
 * @author Chavjoh
 * @since 1.0.0
 */
class AdminDefaultController extends BackEndController
{
    /**
     * BackEnd home page
     */
    public function index()
    {
		$this->templateFile = 'home.tpl';
    }

	/**
	 * @see AbstractController::getPageName()
	 */
	public function getPageName()
	{
		return 'Home - '.parent::getPageName();
	}
}
