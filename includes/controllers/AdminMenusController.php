<?php
/**
 * BackEnd Menus Controller
 * Manage menus showed in the CMS
 *
 * @version 1.0
 */

class AdminMenusController extends BackEndController
{
	/**
	 * Construct controller variable
	 *
	 * @see BackEndController::__construct()
	 */
	public function __construct(array $arguments)
	{
		parent::__construct($arguments);

		$this->urlController .= 'Menus/';
	}

	/**
	 * List of menus
	 */
	public function index()
	{
		$this->templateFile = 'menuList.tpl';
		$this->smarty->assign('menuList', MenuModel::getMenuList());
	}

	/**
	 * Create a new menu
	 *
	 * @throws PDOException Database error when inserting menu
	 */
	public function create()
	{
		// When a menu is created
		if (count($_POST) > 0)
		{
			try
			{
				MenuModel::createMenu(
					(isset($_POST['key_menu'])) ? $_POST['key_menu'] : '',
					(isset($_POST['name_menu'])) ? $_POST['name_menu'] : ''
				);
			}
			catch (InvalidDataException $e)
			{
				Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
			}

			// Redirect to the menu list
			$this->header[] = "Location:".$this->urlController;
		}

		// When we have to show the template to create a menu
		else
		{
			$this->templateFile = 'menuForm.tpl';
			$this->smarty->assign('action', $this->urlController.'create/');
		}
	}

	/**
	 * Edit a menu
	 *
	 * @throws ArgumentMissingException Missing menu ID
	 * @throws PDOException Database error when updating menu
	 */
	public function edit()
	{
		if (count($this->arguments) < 1)
			throw new ArgumentMissingException(__METHOD__, "Menu ID is required to edit it.");

		// Get the menu ID to edit
		$id_menu = intval($this->arguments[0]);

		// When a menu is edited
		if (count($_POST) > 0)
		{
			try
			{
				MenuModel::editMenu(
					$id_menu,
					(isset($_POST['key_menu'])) ? $_POST['key_menu'] : '',
					(isset($_POST['name_menu'])) ? $_POST['name_menu'] : ''
				);
			}
			catch (InvalidDataException $e)
			{
				Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
			}

			// Redirect to the menu list
			$this->header[] = "Location:" . Server::getBaseUrl() . URL_ADMIN . '/Menus/';
		}

		// When we have to show the template to edit the menu
		else
		{
			$this->templateFile = 'menuForm.tpl';
			$this->smarty->assign('id_menu', $id_menu);
			$this->smarty->assign('action', $this->urlController.'edit/'.$id_menu);

			try
			{
				$this->smarty->assign('menu', MenuModel::getMenu($id_menu));
			}
			catch (InvalidDataException $e)
			{
				Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
			}
		}
	}

	/**
	 * Delete a menu
	 *
	 * @throws ArgumentMissingException Missing menu ID
	 * @throws PDOException Database error when deleting menu
	 */
	public function delete()
	{
		if (count($this->arguments) < 1)
			throw new ArgumentMissingException(__METHOD__, "Menu ID is required to delete it.");

		// Get the menu ID to delete
		$id_menu = (isset($this->arguments[0])) ? intval($this->arguments[0]) : 0;

		try
		{
			// Get menu instance and delete it
			$menu = MenuModel::getMenu($id_menu);
			$menu->delete();

			Logger::logMessage(new LoggerMessage("Menu successfully deleted.", LoggerSeverity::SUCCESS));
		}
		catch (InvalidDataException $e)
		{
			Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
		}

		// Redirect to the menu list
		$this->header[] = "Location:".$this->urlController;
	}

	/**
	 * @see AbstractController::getPageName()
	 */
	public function getPageName()
	{
		return 'Menus - '.parent::getPageName();
	}

	/**
	 * @see AbstractController::getMethodAvailable()
	 */
	public static function getMethodAvailable()
	{
		return array_merge(parent::getMethodAvailable(), array('edit', 'create', 'delete'));
	}
}

?>