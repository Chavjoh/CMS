<?php
/**
 * BackEnd Users Controller
 * Manage users allowed to access to the administration interface
 *
 * @version 1.0
 */

class AdminUsersController extends AbstractController
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
		$this->templateFile = 'userList.tpl';

		$userList = UserModel::getUserList();
		$this->smarty->assign('userList', $userList);
	}

	/**
	 * Create a new user
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 */
	public function create(array $arguments)
	{
		parent::index($arguments);

		// When a user is created
		if (count($_POST) > 0)
		{
			// Create the user
			$user = UserModel::createUser(
				$_POST['login_user'],
				Security::passwordHash($_POST['password_user']),
				$_POST['name_user'],
				$_POST['surname_user']
			);

			// Redirect to the user list page
			$this->header[] = "Location:" . Server::getBaseUrl() . URL_ADMIN . '/Users/';
		}
		// When we have to show the template to create a user
		else
		{
			$this->skinPath = PATH_SKIN.TEMPLATE_BACKEND.DS;
			$this->smarty->assign('action', Server::getBaseUrl().URL_ADMIN.'/Users/create/');
			$this->templateFile = 'userForm.tpl';
		}
	}

	/**
	 * Edit an user
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 */
	public function edit(array $arguments)
	{
		parent::index($arguments);

		// Get the user ID to edit
		$id_user = (isset($arguments[0])) ? intval($arguments[0]) : 0;

		// When a user is edited
		if (count($_POST) > 0)
		{
			// Get user instance
			$user = UserModel::getUser($id_user);

			// Edit fields
			$user->set('login_user', $_POST['login_user']);
			$user->set('name_user', $_POST['name_user']);
			$user->set('surname_user', $_POST['surname_user']);

			// Edit password only if needed
			if (strlen($_POST['password_user']) > 0)
				$user->set('password_user', Security::passwordHash($_POST['password_user']));

			// Save modifications
			if ($user->update())
			{
				// If the user edit itself 
				if ($_SESSION['id_user'] == $id_user)
					Login::updateSessionInformation($_POST['login_user'], $_POST['name_user'], $_POST['surname_user']);
			}

			// Redirect to the user list page
			$this->header[] = "Location:" . Server::getBaseUrl() . URL_ADMIN . '/Users/';
		}
		// When we have to show the template to edit the user
		else
		{
			$this->skinPath = PATH_SKIN.TEMPLATE_BACKEND.DS;
			$this->smarty->assign('id_user', $id_user);
			$this->smarty->assign('action', Server::getBaseUrl().URL_ADMIN.'/Users/edit/'.$id_user);
			$this->smarty->assign('user', UserModel::getUser($id_user));
			$this->templateFile = 'userForm.tpl';
		}
	}

	/**
	 * Delete an user
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 */
	public function delete(array $arguments)
	{
		parent::index($arguments);

		// Get the user ID to delete
		$id_user = (isset($arguments[0])) ? intval($arguments[0]) : 0;

		// Get user instance
		$user = UserModel::getUser($id_user);

		// Delete menu
		if ($user->delete())
		{
			// If the user delete itself
			if ($_SESSION['id_user'] == $id_user)
				Login::disconnect();
		}

		// Redirect to the user list page
		$this->header[] = "Location:" . Server::getBaseUrl() . URL_ADMIN . '/Users/';
	}

	/**
	 * @see AbstractController::getPageName()
	 */
	public function getPageName()
	{
		return 'Users - Administration - '.parent::getPageName();
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