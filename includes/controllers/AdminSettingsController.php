<?php
/**
 * BackEnd Settings Controller
 * Manage  settings of this CMS Engine.
 *
 * @version 1.0
 */

class AdminSettingsController extends AbstractController
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

		ConfigurationManager::set('meta_name', $_POST['md_title']);
		ConfigurationManager::set('meta_description', $_POST['md_description']);
		ConfigurationManager::set('meta_keywords', $_POST['md_keywords']);
		ConfigurationManager::set('meta_favicon', $_POST['md_favicon']);
		ConfigurationManager::set('meta_robots', $_POST['md_robots']);
		ConfigurationManager::set('meta_author', $_POST['md_author']);

		ConfigurationManager::commit();
		ConfigurationManager::setAutoCommit(true);
	}

	/**
	 * @see AbstractController::getPageName()
	 */
	public function getPageName()
	{
		return 'Settings - Administration - '.parent::getPageName();
	}
}

?>