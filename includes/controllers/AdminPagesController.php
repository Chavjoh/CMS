<?php
/**
 * BackEnd Pages Controller
 * Manage  Pages of this CMS Engine.
 *
 * @version 1.0
 */

class AdminPagesController extends BackEndController
{
	/**
	 * Current module edited
	 *
	 * @var AbstractModule
	 */
	protected $moduleObjectEdited = null;

	/**
	 * Construct controller variable
	 *
	 * @see BackEndController::__construct()
	 */
	public function __construct(array $arguments)
	{
		parent::__construct($arguments);

		$this->urlController .= 'Pages/';
	}

	/**
	 * Pages list
	 */
	public function index()
	{
		$this->templateFile = 'pageList.tpl';
		$this->smarty->assign('pageList', PageModel::getPageList());
	}

	/**
	 * Create a new page
	 *
	 * @throws PDOException Database error when inserting page
	 */
	public function create()
	{
		// When a page is created
		if (count($_POST) > 0)
		{
			try
			{
				PageModel::createPage(
					1, // TODO: Change this to real value
					(isset($_POST['alias_page'])) ? $_POST['alias_page'] : '',
					(isset($_POST['title_page'])) ? $_POST['title_page'] : '',
					(isset($_POST['description_page'])) ? $_POST['description_page'] : '',
					(isset($_POST['keywords_page'])) ? $_POST['keywords_page'] : '',
					(isset($_POST['robots_page'])) ? $_POST['robots_page'] : '',
					(isset($_POST['author_page'])) ? $_POST['author_page'] : ''
				);
			}
			catch (InvalidDataException $e)
			{
				Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
			}

			// Redirect to the page list
			$this->header[] = 'Location:'.$this->urlController;
		}
		// When we have to show the template to create a page
		else
		{
			$this->templateFile = 'pageFormInformation.tpl';
			$this->smarty->assign('action', $this->urlController.'create/');
		}
	}

	/**
	 * Edit page settings and content
	 *
	 * @throws PDOException Database error when editing page
	 * @throws ArgumentMissingException Missing page ID
	 */
	public function edit()
	{
		if (count($this->arguments) < 1)
			throw new ArgumentMissingException(__METHOD__, "Page ID is required to edit it.");

		// Get the page ID to edit
		$id_page = intval($this->arguments[0]);

		// When a page is edited
		if (count($_POST) > 0)
		{
			try
			{
				PageModel::editPage(
					$id_page,
					1, // TODO: Change this to real value
					(isset($_POST['alias_page'])) ? $_POST['alias_page'] : '',
					(isset($_POST['title_page'])) ? $_POST['title_page'] : '',
					(isset($_POST['description_page'])) ? $_POST['description_page'] : '',
					(isset($_POST['keywords_page'])) ? $_POST['keywords_page'] : '',
					(isset($_POST['robots_page'])) ? $_POST['robots_page'] : '',
					(isset($_POST['author_page'])) ? $_POST['author_page'] : ''
				);
			}
			catch (InvalidDataException $e)
			{
				Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
			}

			// Redirect to the page list
			$this->header[] = 'Location:'.$this->urlController.'edit/'.$id_page;
		}
		// When we have to show the template to edit the page
		else
		{
			list($moduleList, $settingsList) = ModuleModel::getModuleListByPage($id_page);

			$this->templateFile = 'pageEdit.tpl';
			$this->smarty->assign('id_page', $id_page);
			$this->smarty->assign('action', $this->urlController.'edit/'.$id_page);
			$this->smarty->assign('moduleList', $moduleList);
			$this->smarty->assign('settingsList', $settingsList);
			$this->smarty->assign('wrapperList', WrapperModel::getWrapperList());

			try
			{
				$this->smarty->assign('page', PageModel::getPage($id_page));
			}
			catch (InvalidDataException $e)
			{
				Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
			}
		}
	}

	/**
	 * Delete a page
	 *
	 * @throws PDOException Database error when deleting page
	 * @throws ArgumentMissingException Missing page ID
	 */
	public function delete()
	{
		if (count($this->arguments) < 1)
			throw new ArgumentMissingException(__METHOD__, "Page ID is required to delete it.");

		// Get the page ID to delete
		$id_page = intval($this->arguments[0]);

		try
		{
			// Get page instance and delete it
			$page = PageModel::getPage($id_page);
			$page->delete();

			Logger::logMessage(new LoggerMessage("Page successfully deleted.", LoggerSeverity::SUCCESS));
		}
		catch (InvalidDataException $e)
		{
			Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
		}

		// Redirect to the user list page
		$this->header[] = 'Location:'.$this->urlController;
	}

	/**
	 * Add a module to a page
	 *
	 * @throws ArgumentMissingException Missing page ID
	 */
	public function addModule()
	{
		if (count($this->arguments) < 1)
			throw new ArgumentMissingException(__METHOD__, "Page ID is required to add a module.");

		$id_page = intval($this->arguments[0]);

		// When a module is added
		if (count($_POST) > 0)
		{
			ModulePageModel::createModulePage(
				intval($_POST['module']),
				$id_page
			);

			// Redirect to the page edition
			$this->header[] = 'Location:'.$this->urlController.'edit/'.$id_page;
		}
		// When we have to show the template to add a module
		else
		{
			$this->templateFile = 'pageAddModule.tpl';
			$this->smarty->assign('moduleList', ModuleModel::getModuleList());
		}
	}

	/**
	 * Edit a module in a page
	 *
	 * @throws ArgumentMissingException Missing page ID and module position
	 */
	public function editModule()
	{
		if (count($this->arguments) < 2)
			throw new ArgumentMissingException(__METHOD__, "Page ID and module position are required to change module order.");

		$id_page = intval($this->arguments[0]);
		$positionModule = intval($this->arguments[1]);

		try
		{
			// Get the ModuleModel and ModulePageModel
			list($module, $settings) = ModuleModel::getModuleBy($id_page, $positionModule);

			// Load module classes
			$module->loadModule();

			// Get the module key
			$keyModule = $module->get('key_module');

			// Create an object of the module
			$this->moduleObjectEdited = new $keyModule($module, $settings);

			// When an edit form of a module
			if (count($_POST) > 0)
				$this->moduleObjectEdited->saveForm();
		}
		catch (InvalidDataException $e)
		{
			Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
		}
	}

	/**
	 * Increase the order of a module in a page
	 *
	 * @throws ArgumentMissingException Missing page ID and module position
	 */
	public function upModule()
	{
		if (count($this->arguments) < 2)
			throw new ArgumentMissingException(__METHOD__, "Page ID and module position are required to change module order.");

		$id_page = intval($this->arguments[0]);
		$positionModule = intval($this->arguments[1]);

		try
		{
			// Get the ModuleModel and ModulePageModel
			list($module, $settings) = ModuleModel::getModuleBy($id_page, $positionModule);

			// Change order of the module in the page
			$settings->changeOrder('up');

			// Redirect to the page edition
			$this->header[] = "Location:".$this->urlController.'edit/'.$id_page;
		}
		catch (InvalidDataException $e)
		{
			Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
		}
	}

	/**
	 * Decrease the order of a module in a page
	 *
	 * @throws ArgumentMissingException Missing page ID and module position
	 */
	public function downModule()
	{
		if (count($this->arguments) < 2)
			throw new ArgumentMissingException(__METHOD__, "Page ID and module position are required to change module order.");

		$id_page = intval($this->arguments[0]);
		$positionModule = intval($this->arguments[1]);

		try
		{
			// Get the ModuleModel and ModulePageModel
			list($module, $settings) = ModuleModel::getModuleBy($id_page, $positionModule);

			// Change order of the module in the page
			$settings->changeOrder('down');

			// Redirect to the page edition
			$this->header[] = "Location:".$this->urlController.'edit/'.$id_page;
		}
		catch (InvalidDataException $e)
		{
			Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
		}
	}

	/**
	 * Delete a module in a page
	 *
	 * @throws ArgumentMissingException Missing page ID and module position
	 */
	public function deleteModule()
	{
		if (count($this->arguments) < 2)
			throw new ArgumentMissingException(__METHOD__, "Page ID and module position are required to delete a module.");

		$id_page = intval($this->arguments[0]);
		$positionModule = intval($this->arguments[1]);

		try
		{
			// Get the ModuleModel and ModulePageModel
			list($module, $settings) = ModuleModel::getModuleBy($id_page, $positionModule);

			// Delete the module in the page (corresponding to ModulePageModel)
			$settings->delete();

			// Redirect to the page edition
			$this->header[] = 'Location:'.$this->urlController.'edit/'.$id_page;
		}
		catch (InvalidDataException $e)
		{
			Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
		}
	}

	/**
	 * @see AbstractController::getPageName()
	 */
	public function getPageName()
	{
		return 'Pages - '.parent::getPageName();
	}

	/**
	 * @see AbstractController::getPageContent()
	 */
	public function getPageContent()
	{
		// When a module is edited
		if (!is_null($this->moduleObjectEdited))
			return $this->moduleObjectEdited->getEditForm();

		// Normal page load
		else
			return parent::getPageContent();
	}

	/**
	 * @see AbstractController::getMethodAvailable()
	 */
	public static function getMethodAvailable()
	{
		return array_merge(parent::getMethodAvailable(), array(
			'edit',
			'create',
			'delete',
			'addModule',
			'editModule',
			'upModule',
			'downModule',
			'deleteModule'
		));
	}

	/**
	 * @see AbstractController::getMethodPosition()
	 */
	public static function getMethodPosition(array $arguments)
	{
		// If we manage a module, the method position change
		if (count($arguments) > 1 AND strpos($arguments[1], "Module") !== false)
			return 1;

		return 0;
	}
}

?>