<?php
/**
 * FrontEnd Default Controller
 * Manage virtual page of the website
 *
 * @version 1.0
 */

class PageController extends FrontEndController
{
	private $moduleList = array();
	private $moduleSettings = array();
	private $moduleObject = array();
	private $page = null;
	private $alias;

	/**
	* Show page
	*/
	public function index()
	{
		// TODO: Define default page.
		$this->alias = (isset($this->arguments[0])) ? $this->arguments[0] : 'home';
		$this->page = PageModel::getPageByAlias($this->alias);

		// If page exists in database, gather modules list
		if (!is_null($this->page))
		{
			list($this->moduleList, $this->moduleSettings) = ModuleModel::getModuleListByPage($this->page->get('id_page'));

			foreach ($this->moduleList AS $index => $module)
			{
				$moduleKey = $module->get('key_module');

				try {
					$module->loadModule();
					$this->moduleObject[] = new $moduleKey($module, $this->moduleSettings[$index]);
				}
				catch (Exception $e)
				{
					// TODO: Process exception
				}
			}
		}
	}

	/**
	 * Return the current page alias
	 *
	 * @return string Current page alias
	 */
	public function getCurrentAlias()
	{
		return $this->alias;
	}

	/**
	 * @see AbstractController::getPageName()
	 */
	public function getPageName()
	{
		if (!is_null($this->page))
			return $this->page->get('title_page').' - '.parent::getPageName();
		else
			return parent::getPageName();
	}

	/**
	 * @see AbstractController::getPageDescription()
	 */
	public function getPageDescription()
	{
		if (!is_null($this->page) AND strlen($this->page->get('description_page')) > 0)
			return $this->page->get('description_page');
		else
			return parent::getPageDescription();
	}

	/**
	 * @see AbstractController::getPageKeywords()
	 */
	public function getPageKeywords()
	{
		if (!is_null($this->page) AND strlen($this->page->get('keywords_page')) > 0)
			return $this->page->get('keywords_page');
		else
			return parent::getPageKeywords();
	}
	
	/**
	 * @see AbstractController::getPageContent()
	 */
	public function getPageContent()
	{
		// If page has been found and loaded
		if ($this->page != null)
		{
			$content = "";

			foreach ($this->moduleObject AS $module)
				$content .= $module->getContent();

			return $content;
		}

		// If page has not been found, we load the 404 error page
		else
		{
			$this->skinPath = PATH_SKIN.TEMPLATE_FRONTEND.DS; //TODO: Mettre à jour
			$this->templateFile = 'pageNotFound.tpl';
			return parent::getPageContent();
		}
	}

	/**
	 * @see AbstractController::getScriptList()
	 */
	public function getScriptList()
	{
		$scriptList = parent::getScriptList();

		foreach ($this->moduleObject AS $module)
			$scriptList = array_merge($scriptList, $module->getScriptList());

		return $scriptList;
	}

	/**
	 * @see AbstractController::getStylesheetList()
	 */
	public function getStylesheetList()
	{
		$styleList = parent::getStylesheetList();

		foreach ($this->moduleObject AS $module)
			$styleList = array_merge($styleList, $module->getStylesheetList());

		return $styleList;
	}
}

?>