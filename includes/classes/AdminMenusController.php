<?php
/**
 * BackEnd Menus Controller
 * Manage menus showed in the CMS
 *
 * @version 1.0
 */

class AdminMenusController extends AbstractController
{
	/**
	 * Default method called by Dispatcher
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 */
	public function index(array $arguments)
	{
		parent::index($arguments);
		$this->skinPath = PATH_SKIN.TEMPLATE_BACKEND.DS;
		$this->templateFile = 'menuList.tpl';

		$menuList = MenuModel::getMenuList();
		$this->smarty->assign('menuList', $menuList);
	}

	/**
	 * Create a new menu
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 */
	public function create(array $arguments)
	{
		parent::index($arguments);

		// When a menu is created
		if (count($_POST) > 0)
		{
			// Create the menu
			$menu = MenuModel::createMenu(
				(isset($_POST['key_menu'])) ? $_POST['key_menu'] : '',
				(isset($_POST['name_menu'])) ? $_POST['name_menu'] : ''
			);

			// Redirect to the menu list page
			$this->header[] = "Location:" . Server::getBaseUrl() . URL_ADMIN . '/Menus/';
		}
		// When we have to show the template to create a menu
		else
		{
			$this->skinPath = PATH_SKIN.TEMPLATE_BACKEND.DS;
			$this->smarty->assign('action', Server::getBaseUrl().URL_ADMIN.'/Menus/create/');
			$this->templateFile = 'menuForm.tpl';
		}
	}

	/**
	 * Edit a menu
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 */
	public function edit(array $arguments)
	{
		parent::index($arguments);

		// Get the menu ID to edit
		$id_menu = (isset($arguments[0])) ? intval($arguments[0]) : 0;

		// When a menu is edited
		if (count($_POST) > 0)
		{
			// Get menu instance
			$menu = MenuModel::getMenu($id_menu);

			// Edit fields
			$menu->set('key_menu', $_POST['key_menu']);
			$menu->set('name_menu', $_POST['name_menu']);

			// Save modifications
			$menu->update();

			// Redirect to the menu list page
			$this->header[] = "Location:" . Server::getBaseUrl() . URL_ADMIN . '/Menus/';
		}
		// When we have to show the template to edit the menu
		else
		{
			$this->skinPath = PATH_SKIN.TEMPLATE_BACKEND.DS;
			$this->smarty->assign('id_menu', $id_menu);
			$this->smarty->assign('action', Server::getBaseUrl().URL_ADMIN.'/Menus/edit/'.$id_menu);
			$this->smarty->assign('menu', MenuModel::getMenu($id_menu));
			$this->templateFile = 'menuForm.tpl';
		}
	}

	/**
	 * Delete a menu
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 */
	public function delete(array $arguments)
	{
		parent::index($arguments);

		// Get the menu ID to delete
		$id_menu = (isset($arguments[0])) ? intval($arguments[0]) : 0;

		// Get menu instance
		$menu = MenuModel::getMenu($id_menu);

		// Delete menu
		$menu->delete();

		// Redirect to the menu list page
		$this->header[] = "Location:" . Server::getBaseUrl() . URL_ADMIN . '/Menus/';
	}

	/**
	 * @see AbstractController::getPageName()
	 */
	public function getPageName()
	{
		return 'Menus - Administration - '.parent::getPageName();
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