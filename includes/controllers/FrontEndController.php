<?php

/**
 * Abstract FrontEnd Default Controller
 *
 * @package CMS
 * @subpackage Controller
 * @author Chavjoh
 * @since 1.0.0
 */
abstract class FrontEndController extends AbstractController
{
	/**
	 * @see AbstractController::__construct()
	 */
	public function __construct(array $arguments)
	{
		parent::__construct($arguments);
		$this->skinPath = PATH_SKIN.TemplateModel::getActivePath(TemplateSide::FRONTEND).DS;
	}
}
