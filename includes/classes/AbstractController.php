<?php
/**
 * Abstract version of the Controller. 
 * Implements default values from META data.
 * 
 * @version 1.0
 */

abstract class AbstractController implements Controller
{
	/**
	 * Arguments list passed to the Controller
	 * 
	 * @var array
	 */
	protected $arguments;
	
	/**
	 * Template file for Smarty
	 * 
	 * @var string
	 */
	protected $templateFile;

	/**
	 * Skin path
	 *
	 * @var string
	 */
	protected $skinPath;
	
	/**
	 * Smarty object for template management
	 * 
	 * @var Smarty
	 */
	protected $smarty;

	/**
	 * Header array
	 *
	 * @var string
	 */
	protected $header = array();
	
	/**
	 * @see Controller::index()
	 */
	public function index(array $arguments)
	{
		$this->arguments = $arguments;
		$this->smarty = SmartyLib::getInstance('.');
	}
	
	/**
	 * @see Controller::getPageName()
	 */
	public function getPageName()
	{
		return ConfigurationManager::get('meta_name');
	}
	
	/**
	 * @see Controller::getPageDescription()
	 */
	public function getPageDescription()
	{
		return ConfigurationManager::get('meta_description');
	}
	
	/**
	 * @see Controller::getPageKeywords()
	 */
	public function getPageKeywords()
	{
		return ConfigurationManager::get('meta_keywords');
	}

	/**
	 * @see Controller::getPageRobots()
	 */
	public function getPageRobots()
	{
		return ConfigurationManager::get('meta_robots');
	}

	/**
	 * @see Controller::getPageAuthor()
	 */
	public function getPageAuthor()
	{
		return ConfigurationManager::get('meta_author');
	}
	
	/**
	 * @see Controller::getPageContent()
	 */
	public function getPageContent()
	{
		if (!empty($this->templateFile) AND !empty($this->skinPath))
		{
			$this->smarty->assign('skinPath', Server::getBaseUrl().$this->skinPath);
			$this->smarty->setTemplateDir($this->skinPath.'templates'.DS);
			return $this->smarty->fetch($this->templateFile);
		}
		else
			return '';
	}
	
	/**
	 * @see Controller::getHeaders()
	 */
	public function getHeaders()
	{
		return $this->header;
	}
	
	/**
	 * @see Controller::getMethodAvailable()
	 */
	public static function getMethodAvailable()
	{
		return array('index');
	}

	/**
	 * @see Controller:getMethodPosition()
	 */
	public function getMethodPosition(array $arguments)
	{
		return 0;
	}

	/**
	 * @see Controller::getScriptList()
	 */
	public function getScriptList()
	{
		return array();
	}

	/**
	 * @see Controller::getStylesheetList()
	 */
	public function getStylesheetList()
	{
		return array();
	}
}

?>