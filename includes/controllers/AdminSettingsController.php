<?php

/**
 * BackEnd Settings Controller
 *
 * Manage settings of this CMS Engine.
 *
 * @package CMS
 * @subpackage Controller
 * @author Chavjoh
 * @since 1.0.0
 */
class AdminSettingsController extends BackEndController
{
	/**
	 * Show all settings and save them if necessary
	 */
	public function index()
	{
		$this->templateFile = 'settings.tpl';

		if (isset($_POST['md_save']))
			$this->saveMetaSettings();
	}

	/**
	 * Save new settings for Meta Data
	 */
	private function saveMetaSettings()
	{
		Configuration::setAutoCommit(false);

		Configuration::set('meta_name', (isset($_POST['md_title'])) ? $_POST['md_title'] : '');
		Configuration::set('meta_description', (isset($_POST['md_description'])) ? $_POST['md_description'] : '');
		Configuration::set('meta_keywords', (isset($_POST['md_keywords'])) ? $_POST['md_keywords'] : '');
		Configuration::set('meta_favicon', (isset($_POST['md_favicon'])) ? $_POST['md_favicon'] : '');
		Configuration::set('meta_robots', (isset($_POST['md_robots'])) ? $_POST['md_robots'] : '');
		Configuration::set('meta_author', (isset($_POST['md_author'])) ? $_POST['md_author'] : '');

		Configuration::commit();
		Configuration::setAutoCommit(true);
	}

	/**
	 * @see AbstractController::getPageName()
	 */
	public function getPageName()
	{
		return Language::get(__CLASS__.'.PageTitle').' - '.parent::getPageName();
	}
}
