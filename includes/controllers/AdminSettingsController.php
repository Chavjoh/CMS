<?php
/**
 * BackEnd Settings Controller
 * Manage  settings of this CMS Engine.
 *
 * @version 1.0
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
		ConfigurationManager::setAutoCommit(false);

		ConfigurationManager::set('meta_name', (isset($_POST['md_title'])) ? $_POST['md_title'] : '');
		ConfigurationManager::set('meta_description', (isset($_POST['md_description'])) ? $_POST['md_description'] : '');
		ConfigurationManager::set('meta_keywords', (isset($_POST['md_keywords'])) ? $_POST['md_keywords'] : '');
		ConfigurationManager::set('meta_favicon', (isset($_POST['md_favicon'])) ? $_POST['md_favicon'] : '');
		ConfigurationManager::set('meta_robots', (isset($_POST['md_robots'])) ? $_POST['md_robots'] : '');
		ConfigurationManager::set('meta_author', (isset($_POST['md_author'])) ? $_POST['md_author'] : '');

		ConfigurationManager::commit();
		ConfigurationManager::setAutoCommit(true);
	}

	/**
	 * @see AbstractController::getPageName()
	 */
	public function getPageName()
	{
		return 'Settings - '.parent::getPageName();
	}
}

?>