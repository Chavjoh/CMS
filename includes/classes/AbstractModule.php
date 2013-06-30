<?php
/**
 * Abstract version of a Module.
 *
 * @version 1.0
 */

abstract class AbstractModule
{
	protected $module, $settings = null, $templateFile = "";

	/**
	 * Creation of a module
	 *
	 * @param ModuleModel $module ModuleModel associated
	 * @param ModulePageModel $settings Settings for the module
	 */
	public function __construct(ModuleModel $module, ModulePageModel $settings = null)
	{
		$this->module = $module;
		$this->path = Server::getBaseUrl().PATH_MODULE.$this->module->get('key_module').DS;

		// Load the template
		$this->smarty = SmartyLib::getInstance($this->path);
		$this->smarty->assign('module', $this->module);

		// Integrate the settings if defined
		if (isset($settings))
			$this->settings = $settings;
	}

	/**
	 * HTML content to show when the module is called
	 *
	 * @return string HTML content
	 */
	public function getContent()
	{
		if (isset($this->settings))
			$this->smarty->assign('settings', $this->settings->getData(true));

		if (!empty($this->templateFile))
			return $this->smarty->fetch($this->templateFile);
		else
			return '<div class="alert alert-error"> ['.$this->module->get('name_module').'] Module content is missing </div> <br />';
	}

	/**
	 * HTML edit form to show when the module is edited
	 *
	 * @return string HTML edit form
	 */
	public function getEditForm()
	{
		if (isset($this->settings))
			$this->smarty->assign('settings', $this->settings->getData(false));

		if (!empty($this->templateFile))
			return $this->smarty->fetch($this->templateFile);
		else
			return 'Sorry, this module doesn\'t have an edit form.';
	}

	/**
	 * Save form settings after an edition of the module
	 */
	abstract public function saveForm();

	/**
	 * Icon path of the module
	 *
	 * @return string Path of the icon
	 */
	public function getIconPath()
	{
		return $this->path.'images/logo.png';
	}

	/**
	 * List of all javascript scripts needed to run the module
	 *
	 * @return array List of string representing the path of each script
	 */
	public function getScriptList()
	{
		return array();
	}

	/**
	 * List of all stylesheet needed to run the module
	 *
	 * @return array List of string representing the path of each stylesheet
	 */
	public function getStylesheetList()
	{
		return array();
	}

	/**
	 * ModuleModel of the current module
	 *
	 * @return ModuleModel Current module
	 */
	public function getModuleModel()
	{
		return $this->module;
	}
}

?>