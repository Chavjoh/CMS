<?php
/**
 * Dispatcher (Router) class. 
 * Handle request and dispatch it to the appropriate controller
 * 
 * @version 1.0
 */

class Dispatcher
{
	/**
	 * URL to compute with Dispatcher
	 * 
	 * @var string
	 */
	private $url;
	
	/**
	 * Indication if we are in the backend
	 * 
	 * @var boolean
	 */
	private $isAdminSection;
	
	/**
	 * Controller object of the current page
	 * 
	 * @var AbstractController
	 */
	private $controller = null;
	
	/**
	 * Create new Dispatcher with URL in parameter
	 * 
	 * @param string $url Current URL to compute with Dispatcher
	 */
	public function __construct($url)
	{
		// Delete base url to compute correctly the called controller address
		$this->url = substr( $url, strlen(Server::getDirectoryScript()) );
	}

	/**
	 * Indicate if a redirection occur
	 *
	 * @return bool True if the Controller make a redirection, False otherwise
	 */
	public function isRedirectHeaders()
	{
		foreach ($this->controller->getHeaders() AS $header)
		{
			if (preg_match('/^Location\:/', $header))
				return true;
		}

		return false;
	}
	
	/**
	 * Dispatch request to the appropriate controller and method
	 */
	public function dispatch()
	{
		$this->url = trim($this->url, '/');

		// Get URL elements of current page
		if (empty($this->url))
			$url = array();
		else
			$url = explode('/', $this->url);

		// If we are on admin side
		if (isset($url[0]) AND $url[0] == URL_ADMIN)
		{
			// Delete the first "admin" indication parameter
			array_shift($url);

			$this->dispatchBackEnd($url);
		}
		else
			$this->dispatchFrontEnd($url);

		// Get headers from current controller
		$headers = $this->controller->getHeaders();

		// If controller has send some headers
		if (count($headers) > 0)
		{
			foreach ($headers AS $value)
				header($value);
		}
	}

	/**
	 * Dispatch user query in FrontEnd Controllers
	 *
	 * @param array $urlExplode Array of url composition
	 *
	 * @throws Exception
	 */
	private function dispatchFrontEnd($urlExplode)
	{
		$this->isAdminSection = false;

		$this->controller = new PageController($urlExplode);

		// Check controller type
		if (!($this->controller instanceof AbstractController))
			throw new InvalidDerivationException("Controller must be derivated from AbstractController.");

		// Call the index method with arguments
		$this->controller->index();
	}

	/**
	 * Dispatch user query in BackEnd Controllers
	 *
	 * @param array $urlExplode Array of url composition
	 *
	 * @throws Exception
	 */
	private function dispatchBackEnd($urlExplode)
	{
		$this->isAdminSection = true;

		// Get controller name
		if (Login::isLogged())
			$controller = isset($urlExplode[0]) ? $urlExplode[0] . 'Controller' : 'DefaultController';
		else
			$controller = 'LoginController';

		// Delete the URL argument computed (class name)
		array_shift($urlExplode);

		// Add prefix
		$controller = 'Admin'.$controller;

		try
		{
			// Call autoloader to search class
			class_exists($controller);

			// Get the position of the method in the URL
			$positionMethod = abs($controller::getMethodPosition($urlExplode));

			// Get method name of controller
			$method = isset($urlExplode[$positionMethod]) ? $urlExplode[$positionMethod] : '';

			// Check availability of the method called
			if (!in_array($method, $controller::getMethodAvailable()))
			{
				$method = 'index';
				$arguments = $urlExplode;
			}
			else
			{
				// Delete the URL argument processed (method name)
				unset($urlExplode[$positionMethod]);
				$arguments = array_values($urlExplode);
			}

			// Create controller instance and call appropriate method
			$this->controller = new $controller($arguments);
			$this->controller->$method();
		}
		catch (FatalErrorException $e)
		{
			throw $e;
		}
		catch (CmsException $e)
		{
			$this->controller = new AdminErrorController(array());
			$this->controller->setError($e);
		}

		// Check controller type
		if (!($this->controller instanceof AbstractController))
			throw new InvalidDerivationException("Controller must be derivated from AbstractController.");
	}
	
	/**
	 * Retrieve the Controller object
	 * 
	 * @return AbstractController Current page Controller
	 */
	public function getController()
	{
		return $this->controller;
	}
}

?>