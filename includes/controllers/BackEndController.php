<?php

/**
 * Abstract BackEnd Default Controller
 *
 * @package CMS
 * @subpackage Controller
 * @author Chavjoh
 * @since 1.0.0
 */
abstract class BackEndController extends AbstractController
{
	/**
	 * Set URL controller and skin path
	 *
	 * @see AbstractController::__construct()
	 */
	public function __construct(array $arguments)
	{
		parent::__construct($arguments);
		$this->urlController .= URL_ADMIN.'/';
		$this->skinPath = PATH_SKIN.TemplateModel::getActivePath(TemplateSide::BACKEND).DS;
	}

	/**
	 * @see AbstractController::getPageName()
	 */
	public function getPageName()
	{
		return 'Administration - '.parent::getPageName();
	}
}
