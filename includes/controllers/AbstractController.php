<?php

/**
 * Abstract version of the Controller.
 *
 * Implements default values from META data.
 *
 * @package CMS
 * @subpackage Controller
 * @author Chavjoh
 * @since 1.0.0
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
	 * Current URL for this controller
	 *
	 * @var string
	 */
	protected $urlController;
	
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
	 * Set arguments array, smarty object and URL controller.
	 *
	 * @see Controller::__construct()
	 */
	public function __construct(array $arguments)
	{
		$this->arguments = $arguments;
		$this->smarty = SmartyLib::getInstance('.');
		$this->urlController = Server::getBaseUrl();
	}

	/**
	 * @see Controller::index()
	 */
	public abstract function index();
	
	/**
	 * @see Controller::getPageName()
	 */
	public function getPageName()
	{
		return Configuration::get('meta_name');
	}
	
	/**
	 * @see Controller::getPageDescription()
	 */
	public function getPageDescription()
	{
		return Configuration::get('meta_description');
	}
	
	/**
	 * @see Controller::getPageKeywords()
	 */
	public function getPageKeywords()
	{
		return Configuration::get('meta_keywords');
	}

	/**
	 * @see Controller::getPageRobots()
	 */
	public function getPageRobots()
	{
		return Configuration::get('meta_robots');
	}

	/**
	 * @see Controller::getPageAuthor()
	 */
	public function getPageAuthor()
	{
		return Configuration::get('meta_author');
	}
	
	/**
	 * @see Controller::getPageContent()
	 */
	public function getPageContent()
	{
		if (!empty($this->templateFile) AND !empty($this->skinPath))
		{
			$this->smarty->assign('urlController', $this->urlController);
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
	public static function getMethodPosition(array $urlExplode)
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

	/**
	 * Retrieve the skin path of current page
	 *
	 * @return string Skin path
	 */
	public function getSkinPath()
	{
		return $this->skinPath;
	}
}
