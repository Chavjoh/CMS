<?php
/**
 * BackEnd Pages Controller
 * Manage  Pages of this CMS Engine.
 *
 * @version 1.0
 */

class AdminPagesController extends AbstractController
{
	/**
	 * Current module edited
	 *
	 * @var AbstractModule
	 */
	protected $moduleObjectEdited = null;

	/**
	 * Current page modified
	 *
	 * @var int
	 */
	protected $id_page = null;

	/**
	 * Current URL for this controller
	 *
	 * @var string
	 */
	protected $urlController;

	/**
	 * Current URL for the page loaded
	 *
	 * @var string
	 */
	protected $urlPage;

	/**
	 * Construct controller variable
	 */
	public function __construct()
	{
		$this->urlController = Server::getBaseUrl().URL_ADMIN.'/Pages/';
	}

	/**
	 * Default method called by Dispatcher
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 */
	public function index(array $arguments)
	{
		parent::index($arguments);
		$this->skinPath = PATH_SKIN.TEMPLATE_BACKEND.DS;
		$this->templateFile = 'pageList.tpl';

		// Get page list
		$pageList = PageModel::getPageList();
		$this->smarty->assign('pageList', $pageList);
	}

	/**
	 * Create a new page
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 */
	public function create(array $arguments)
	{
		parent::index($arguments);

		$this->urlPage = Server::getBaseUrl().URL_ADMIN.'/Pages/create/';

		// When a page is created
		if (count($_POST) > 0)
		{
			// Create the page
			$menu = PageModel::createPage(
				1, // TODO: Change this to real value
				(isset($_POST['alias_page'])) ? $_POST['alias_page'] : '',
				(isset($_POST['title_page'])) ? $_POST['title_page'] : '',
				(isset($_POST['description_page'])) ? $_POST['description_page'] : '',
				(isset($_POST['keywords_page'])) ? $_POST['keywords_page'] : '',
				(isset($_POST['robots_page'])) ? $_POST['robots_page'] : '',
				(isset($_POST['author_page'])) ? $_POST['author_page'] : ''
			);

			// Redirect to the page list
			$this->header[] = "Location:".$this->urlController;
		}
		// When we have to show the template to create a menu
		else
		{
			$this->skinPath = PATH_SKIN.TEMPLATE_BACKEND.DS;
			$this->smarty->assign('action', $this->urlPage);
			$this->templateFile = 'pageFormInformation.tpl';
		}
	}

	/**
	 * Action for editing page settings and content
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 */
	public function edit(array $arguments)
	{
		parent::index($arguments);

		// Get the page ID to edit
		$id_page = (isset($arguments[0])) ? intval($arguments[0]) : 0;

		$this->urlPage = Server::getBaseUrl().URL_ADMIN.'/Pages/edit/'.$id_page;

		// When a page is edited
		if (count($_POST) > 0)
		{
			// Get page instance
			$page = PageModel::getPage($id_page);

			// Edit fields
			$page->set('id_layout', 1); // TODO: Change this to real value
			$page->set('alias_page', $_POST['alias_page']);
			$page->set('title_page', $_POST['title_page']);
			$page->set('description_page', $_POST['description_page']);
			$page->set('keywords_page', $_POST['keywords_page']);
			$page->set('robots_page', $_POST['robots_page']);
			$page->set('author_page', $_POST['author_page']);

			// Save modifications
			$page->update();

			// Redirect to the page list
			$this->header[] = "Location:".$this->urlController;
		}
		// When we have to show the template to edit the page
		else
		{
			list($moduleList, $settingsList) = ModuleModel::getModuleListByPage($id_page);

			$this->skinPath = PATH_SKIN.TEMPLATE_BACKEND.DS;
			$this->smarty->assign('id_page', $id_page);
			$this->smarty->assign('action', $this->urlPage);
			$this->smarty->assign('page', PageModel::getPage($id_page));
			$this->smarty->assign('moduleList', $moduleList);
			$this->smarty->assign('wrapperList', WrapperModel::getWrapperList());
			$this->smarty->assign('settingsList', $settingsList);
			$this->templateFile = 'pageEdit.tpl';
		}
	}

	/**
	 * Action for deleting a page
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 */
	public function delete(array $arguments)
	{
		parent::index($arguments);

		// Get the page ID to delete
		$id_page = (isset($arguments[0])) ? intval($arguments[0]) : 0;

		// Get page instance
		$page = PageModel::getPage($id_page);

		// Delete page
		$page->delete();

		// Redirect to the page list page
		$this->header[] = "Location:".$this->urlController;
	}

	/**
	 * Action for adding a module to a page
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 */
	public function addModule(array $arguments)
	{
		parent::index($arguments);

		$this->id_page = intval($arguments[0]);

		if (count($_POST) > 0)
		{
			ModulePageModel::createModulePage(intval($_POST['module']), $this->id_page);

			// Redirect to the page edition
			$this->header[] = "Location:".$this->urlController.'edit/'.$this->id_page;
		}
		else
		{
			$moduleList = ModuleModel::getModuleList();

			$this->skinPath = PATH_SKIN.TEMPLATE_BACKEND.DS;
			$this->smarty->assign('moduleList', $moduleList);
			$this->templateFile = 'pageAddModule.tpl';
		}
	}

	/**
	 * Action for editing a module in a page
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 * @throws Exception URL arguments missing or incorrect
	 */
	public function editModule(array $arguments)
	{
		parent::index($arguments);

		if (count($arguments) < 2)
			throw new Exception("[".__METHOD__."] URL arguments are missing.");

		$this->id_page = intval($arguments[0]);
		$positionModule = intval($arguments[1]);

		list($module, $settings) = ModuleModel::getModuleBy($this->id_page, $positionModule);

		if (is_null($module) OR is_null($settings))
			throw new Exception("[".__METHOD__."] One or more of these items are incorrect: Module, Page, Position");

		$module->loadModule();
		$keyModule = $module->get('key_module');

		$this->moduleObjectEdited = new $keyModule($module, $settings);

		if (count($_POST) > 0)
			$this->moduleObjectEdited->saveForm();
	}

	/**
	 * Action to increase the order of a module in a page
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 * @throws Exception URL arguments missing or incorrect
	 */
	public function upModule(array $arguments)
	{
		parent::index($arguments);

		if (count($arguments) < 2)
			throw new Exception("[".__METHOD__."] URL arguments are missing.");

		$this->id_page = intval($arguments[0]);
		$positionModule = intval($arguments[1]);

		list($module, $settings) = ModuleModel::getModuleBy($this->id_page, $positionModule);
		$settings->changeOrder('up');

		// Redirect to the page edition
		$this->header[] = "Location:".$this->urlController.'edit/'.$this->id_page;
	}

	/**
	 * Action to decrease the order of a module in a page
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 * @throws Exception URL arguments missing or incorrect
	 */
	public function downModule(array $arguments)
	{
		parent::index($arguments);

		if (count($arguments) < 2)
			throw new Exception("[".__METHOD__."] URL arguments are missing.");

		$this->id_page = intval($arguments[0]);
		$positionModule = intval($arguments[1]);

		list($module, $settings) = ModuleModel::getModuleBy($this->id_page, $positionModule);
		$settings->changeOrder('down');

		// Redirect to the page edition
		$this->header[] = "Location:".$this->urlController.'edit/'.$this->id_page;
	}

	/**
	 * Action to delete a module in a page
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 * @throws Exception URL arguments missing or incorrect
	 */
	public function deleteModule(array $arguments)
	{
		parent::index($arguments);

		if (count($arguments) < 2)
			throw new Exception("[".__METHOD__."] URL arguments are missing.");

		$this->id_page = intval($arguments[0]);
		$positionModule = intval($arguments[1]);

		list($module, $settings) = ModuleModel::getModuleBy($this->id_page, $positionModule);
		$settings->delete();

		// Redirect to the page edition
		$this->header[] = "Location:".$this->urlController.'edit/'.$this->id_page;
	}

	/**
	 * @see AbstractController::getPageName()
	 */
	public function getPageName()
	{
		return 'Pages - Administration - '.parent::getPageName();
	}

	/**
	 * @see AbstractController::getPageContent()
	 */
	public function getPageContent()
	{
		if (!is_null($this->moduleObjectEdited))
			return $this->moduleObjectEdited->getEditForm();
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
	public function getMethodPosition(array $arguments)
	{
		if (count($arguments) > 1 AND strpos($arguments[1], "Module") !== false)
			return 1;

		return 0;
	}
}

?>