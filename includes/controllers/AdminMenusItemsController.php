<?php

/**
 * BackEnd Menu Items Controller
 *
 * Manage menu items showed in the CMS
 *
 * @package CMS
 * @subpackage Controller
 * @author Chavjoh
 * @since 1.0.0
 */
class AdminMenusItemsController extends BackEndController
{
	/**
	 * Current menu ID for items
	 *
	 * @var string
	 */
	protected $id_menu;

	/**
	 * Construct controller variable
	 *
	 * @see BackEndController::__construct()
	 * @throws ArgumentMissingException Missing menu ID
	 */
	public function __construct(array $arguments)
	{
		parent::__construct($arguments);

		if (count($this->arguments) == 0)
			throw new ArgumentMissingException(__METHOD__, "Menu ID is required to manage items.");

		$this->id_menu = intval($this->arguments[0]);
		$this->urlController .= 'MenusItems/'.$this->id_menu.'/';
	}

	/**
	 * List of menu items
	 */
	public function index()
	{
		$this->templateFile = 'menuItemList.tpl';

		$this->smarty->assign('id_menu', $this->id_menu);
		$this->smarty->assign('menuItemList', MenuItemModel::getMenuItemList($this->id_menu));
	}

	/**
	 * Create a new menu item
	 *
	 * @throws PDOException Database error when inserting menu item
	 */
	public function create()
	{
		// When a menu item is created
		if (count($_POST) > 0)
		{
			try
			{
				MenuItemModel::createMenuItem(
					$this->id_menu,
					(isset($_POST['id_page'])) ? intval($_POST['id_page']) : 0,
					(isset($_POST['name_menu_item'])) ? $_POST['name_menu_item'] : ''
				);
			}
			catch (InvalidDataException $e)
			{
				Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
			}

			// Redirect to the menu item list
			$this->header[] = "Location:".$this->urlController;
		}

		// When we have to show the template to create a menu
		else
		{
			$this->templateFile = 'menuItemForm.tpl';

			$this->smarty->assign('action', $this->urlController.'create/');
			$this->smarty->assign('pageList', PageModel::getPageList());
		}
	}

	/**
	 * Edit a menu item
	 *
	 * @throws ArgumentMissingException Missing menu ID
	 * @throws PDOException Database error when updating menu item
	 */
	public function edit()
	{
		if (count($this->arguments) < 2)
			throw new ArgumentMissingException(__METHOD__, "Item ID is required to edit it.");

		// Get the menu item ID to edit
		$id_menu_item = intval($this->arguments[1]);

		// When a menu item is edited
		if (count($_POST) > 0)
		{
			try
			{
				MenuItemModel::editMenuItem(
					$id_menu_item,
					$this->id_menu,
					(isset($_POST['id_page'])) ? intval($_POST['id_page']) : 0,
					(isset($_POST['name_menu_item'])) ? $_POST['name_menu_item'] : ''
				);
			}
			catch (InvalidDataException $e)
			{
				Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
			}

			// Redirect to the menu item list
			$this->header[] = "Location:".$this->urlController;
		}

		// When we have to show the template to edit the menu item
		else
		{
			$this->templateFile = 'menuItemForm.tpl';

			$this->smarty->assign('id_menu_item', $id_menu_item);
			$this->smarty->assign('action', $this->urlController.'edit/'.$id_menu_item);
			$this->smarty->assign('pageList', PageModel::getPageList());

			try
			{
				$this->smarty->assign('menuItem', MenuItemModel::getMenuItem($id_menu_item));
			}
			catch (InvalidDataException $e)
			{
				Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
			}
		}
	}

	/**
	 * Delete a menu item
	 *
	 * @throws ArgumentMissingException Missing menu ID
	 * @throws PDOException Database error when deleting menu item
	 */
	public function delete()
	{
		if (count($this->arguments) < 2)
			throw new ArgumentMissingException(__METHOD__, "Item ID is required to delete it.");

		try
		{
			// Get and delete menu item
			$menuItem = MenuItemModel::getMenuItem(intval($this->arguments[1]));
			$menuItem->delete();

			Logger::logMessage(new LoggerMessage("Menu item successfully deleted.", LoggerSeverity::SUCCESS));
		}
		catch (InvalidDataException $e)
		{
			Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
		}

		// Redirect to the menu item list page
		$this->header[] = "Location:".$this->urlController;
	}

	/**
	 * Order up for a menu item
	 *
	 * @throws ArgumentMissingException Missing menu ID
	 * @throws PDOException Database error when changing menu item order
	 */
	public function up()
	{
		if (count($this->arguments) < 2)
			throw new ArgumentMissingException(__METHOD__, "Item ID is required to change his order.");

		try
		{
			// Get menu item and change his order
			$item = MenuItemModel::getMenuItem(intval($this->arguments[1]));
			$item->changeOrder('up');
		}
		catch (InvalidDataException $e)
		{
			Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
		}

		// Redirect to the page list
		$this->header[] = "Location:".$this->urlController;
	}

	/**
	 * Order down for a menu item
	 *
	 * @throws ArgumentMissingException Missing menu ID
	 * @throws PDOException Database error when changing menu item order
	 */
	public function down()
	{
		if (count($this->arguments) < 2)
			throw new ArgumentMissingException(__METHOD__, "Item ID is required to change his order.");

		try
		{
			// Get menu item and change his order
			$item = MenuItemModel::getMenuItem(intval($this->arguments[1]));
			$item->changeOrder('down');
		}
		catch (InvalidDataException $e)
		{
			Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
		}

		// Redirect to the page list
		$this->header[] = "Location:".$this->urlController;
	}

	/**
	 * @see AbstractController::getPageName()
	 */
	public function getPageName()
	{
		return 'Menus Items - '.parent::getPageName();
	}

	/**
	 * @see AbstractController::getMethodAvailable()
	 */
	public static function getMethodAvailable()
	{
		return array_merge(parent::getMethodAvailable(), array('edit', 'create', 'delete', 'up', 'down'));
	}

	/**
	 * @see AbstractController::getMethodPosition()
	 */
	public static function getMethodPosition(array $urlExplode)
	{
		// Change position because we have the menu ID before arguments
		return parent::getMethodPosition($urlExplode) + 1;
	}
}
