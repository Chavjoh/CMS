<?php
/**
 * BackEnd Menu Items Controller
 * Manage menu items showed in the CMS
 *
 * @version 1.0
 */

class AdminMenusItemsController extends AbstractController
{
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
		$this->urlController = Server::getBaseUrl().URL_ADMIN.'/MenusItems/';
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
		$this->templateFile = 'menuItemList.tpl';

		// Get menu ID
		$id_menu = (isset($arguments[0])) ? intval($arguments[0]) : 0;

		// Get menu item list
		$menuItemList = MenuItemModel::getMenuItemList($id_menu);
		$this->smarty->assign('menuItemList', $menuItemList);
		$this->smarty->assign('id_menu', $id_menu);
	}

	/**
	 * Create a new menu item
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 */
	public function create(array $arguments)
	{
		parent::index($arguments);

		// Get menu ID
		$id_menu = (isset($arguments[0])) ? intval($arguments[0]) : 0;

		// Get the min and max order item in this menu
		$orderBorder = MenuItemModel::getMinMaxOrder($id_menu);

		// When a menu item is created
		if (count($_POST) > 0)
		{
			// Create the menu item
			$menu = MenuItemModel::createMenuItem(
				$id_menu,
				(isset($_POST['id_page'])) ? intval($_POST['id_page']) : 0,
				(isset($_POST['name_menu_item'])) ? $_POST['name_menu_item'] : '',
				($orderBorder['max'] + 1)
			);

			// Redirect to the menu item list page
			$this->header[] = "Location:" . Server::getBaseUrl() . URL_ADMIN . '/MenusItems/'.$id_menu;
		}
		// When we have to show the template to create a menu
		else
		{
			$this->skinPath = PATH_SKIN.TEMPLATE_BACKEND.DS;
			$this->smarty->assign('action', Server::getBaseUrl().URL_ADMIN.'/MenusItems/'.$id_menu.'/create/');
			$this->smarty->assign('pageList', PageModel::getPageList());
			$this->templateFile = 'menuItemForm.tpl';
		}
	}

	/**
	 * Edit a menu item
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 */
	public function edit(array $arguments)
	{
		parent::index($arguments);

		// Get the menu ID
		$id_menu = (isset($arguments[0])) ? intval($arguments[0]) : 0;

		// Get the menu item ID to edit
		$id_menu_item = (isset($arguments[1])) ? intval($arguments[1]) : 0;

		// When a menu item is edited
		if (count($_POST) > 0)
		{
			// Get menu item instance
			$menu = MenuItemModel::getMenuItem($id_menu_item);

			// Edit fields
			$menu->set('id_page', intval($_POST['id_page']));
			$menu->set('name_menu_item', $_POST['name_menu_item']);

			// Save modifications
			$menu->update();

			// Redirect to the menu item list page
			$this->header[] = "Location:" . Server::getBaseUrl() . URL_ADMIN . '/MenusItems/'.$id_menu;
		}
		// When we have to show the template to edit the menu item
		else
		{
			$this->skinPath = PATH_SKIN.TEMPLATE_BACKEND.DS;
			$this->smarty->assign('id_menu_item', $id_menu_item);
			$this->smarty->assign('action', Server::getBaseUrl().URL_ADMIN.'/MenusItems/'.$id_menu.'/edit/'.$id_menu_item);
			$this->smarty->assign('menuItem', MenuItemModel::getMenuItem($id_menu_item));
			$this->smarty->assign('pageList', PageModel::getPageList());
			$this->templateFile = 'menuItemForm.tpl';
		}
	}

	/**
	 * Delete a menu item
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 */
	public function delete(array $arguments)
	{
		parent::index($arguments);

		// Get the menu ID to delete
		$id_menu = (isset($arguments[0])) ? intval($arguments[0]) : 0;

		// Get the menu item ID to delete
		$id_menu_item = (isset($arguments[1])) ? intval($arguments[1]) : 0;

		// Get menu item instance
		$menuItem = MenuItemModel::getMenuItem($id_menu_item);

		// Delete menu item
		$menuItem->delete();

		// Redirect to the menu item list page
		$this->header[] = "Location:" . Server::getBaseUrl() . URL_ADMIN . '/MenusItems/'.$id_menu;
	}

	/**
	 * Order up for a menu item
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 * @throws Exception URL arguments missing or incorrect
	 */
	public function up(array $arguments)
	{
		parent::index($arguments);

		if (count($arguments) < 2)
			throw new Exception("[".__METHOD__."] URL arguments are missing.");

		// Get the menu ID and item ID
		$id_menu = intval($arguments[0]);
		$id_menu_item = intval($arguments[1]);

		$item = MenuItemModel::getMenuItem($id_menu_item);
		$item->changeOrder('up');

		// Redirect to the page list
		$this->header[] = "Location:".$this->urlController.$id_menu;
	}

	/**
	 * Order down for a menu item
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 * @throws Exception URL arguments missing or incorrect
	 */
	public function down(array $arguments)
	{
		parent::index($arguments);

		if (count($arguments) < 2)
			throw new Exception("[".__METHOD__."] URL arguments are missing.");

		// Get the menu ID and item ID
		$id_menu = intval($arguments[0]);
		$id_menu_item = intval($arguments[1]);

		$item = MenuItemModel::getMenuItem($id_menu_item);
		$item->changeOrder('down');

		// Redirect to the page list
		$this->header[] = "Location:".$this->urlController.$id_menu;
	}

	/**
	 * @see AbstractController::getPageName()
	 */
	public function getPageName()
	{
		return 'Menus Items - Administration - '.parent::getPageName();
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
	public function getMethodPosition(array $arguments)
	{
		// Change position because we have the menu ID before arguments
		return 1;
	}
}

?>