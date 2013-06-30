<?php
/**
 * Interface for Controller
 * 
 * @version 1.0
 */

interface Controller
{
	/**
	 * Default method when a Controller is called by URL
	 *
	 * @param array $arguments Arguments passed by URL
	 */
	public function index(array $arguments);
	
	/**
	 * Retrieve the name of the current page managed by Controller
	 * 
	 * @return string Page name
	 */
	public function getPageName();
	
	/**
	 * Retrieve the description of the current page managed by Controller
	 * 
	 * @return string Page description
	 */
	public function getPageDescription();
	
	/**
	 * Retrieve the keywords list of the current page managed by Controller
	 * 
	 * @return string Page keywords
	 */
	public function getPageKeywords();

	/**
	 * Retrieve the robots instructions of the current page managed by Controller
	 *
	 * @return string Robots instruction
	 */
	public function getPageRobots();

	/**
	 * Retrieve the author information of the current page managed by Controller
	 *
	 * @return string Author information
	 */
	public function getPageAuthor();
	
	/**
	 * Retrieve the content of the current page managed by Controller
	 * 
	 * @return string Page content
	 */
	public function getPageContent();
	
	/**
	 * Retrieve the headers list of the current page managed by Controller
	 *
	 * @return array Headers list to add in the current request
	 */
	public function getHeaders();
	
	/**
	 * List of methods accessible by URL for this controller
	 * 
	 * @return array List of available methods
	 */
	public static function getMethodAvailable();

	/**
	 * Position of the method name in the URL arguments
	 *
	 * @param array $arguments Arguments passed by URL
	 * @return int Position of the method name
	 */
	public function getMethodPosition(array $arguments);

	/**
	 * List of all javascript scripts needed to run the module
	 *
	 * @return array List of string representing the path of each script
	 */
	public function getScriptList();

	/**
	 * List of all stylesheet needed to run the module
	 *
	 * @return array List of string representing the path of each stylesheet
	 */
	public function getStylesheetList();
}

?>