<?php

/**
 * Abstract version of a Module.
 *
 * @package CMS
 * @subpackage Module
 * @author Chavjoh
 * @since 1.0.0
 */
abstract class AbstractModule
{
	/**
	 * ModuleModel corresponding to the current Module
	 *
	 * @var ModuleModel
	 */
	protected $module;

	/**
	 * Settings for the current position of the module.
	 * Null for Module loading in backend panel.
	 *
	 * @var ModulePageModel|null
	 */
	protected $settings = null;

	/**
	 * Template file for module content call
	 *
	 * @var string
	 */
	protected $templateFile = "";

	/**
	 * Initialization of a module to a certain position in the page
	 *
	 * @param ModuleModel $module ModuleModel associated
	 * @param ModulePageModel $settings Settings for the module (Null for BackEnd panel)
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
	 * Return HTML content to show when the module is called
	 *
	 * @return string HTML content
	 */
	public function getContent()
	{
		// Assign settings to the template if they exist
		if (isset($this->settings))
			$this->smarty->assign('settings', $this->settings->getData(true));

		// Show template file if defined
		if (!empty($this->templateFile))
			return $this->smarty->fetch($this->templateFile);

		// Otherwise show an error message
		else
			return '
			<div class="alert alert-error">
				'.Language::get('Module.ContentMissing', array($this->module->get('name_module'))).'
			</div> <br />';
	}

	/**
	 * Return HTML edit form to show when the module is edited
	 *
	 * @return string HTML edit form
	 */
	public function getEditForm()
	{
		// Assign settings to the template if they exist
		if (isset($this->settings))
			$this->smarty->assign('settings', $this->settings->getData(false));

		// Show template file if defined
		if (!empty($this->templateFile))
			return $this->smarty->fetch($this->templateFile);

		// Otherwise show an error message
		else
			return Language::get('Module.EditFormMissing');
	}

	/**
	 * Save form settings after an edition of the module
	 */
	abstract public function saveForm();

	/**
	 * Return icon path of the module
	 *
	 * @return string Path of the icon
	 */
	public function getIconPath()
	{
		return $this->path.'images/logo.png';
	}

	/**
	 * List of all javascripts needed to run the module
	 *
	 * @return array List of string representing the path of each script
	 */
	public function getScriptList()
	{
		return array();
	}

	/**
	 * List of all stylesheets needed to run the module
	 *
	 * @return array List of string representing the path of each stylesheet
	 */
	public function getStylesheetList()
	{
		return array();
	}

	/**
	 * Return the model of the current module
	 *
	 * @return ModuleModel Current module
	 */
	public final function getModuleModel()
	{
		return $this->module;
	}
}
