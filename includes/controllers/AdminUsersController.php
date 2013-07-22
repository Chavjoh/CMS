<?php

/**
 * BackEnd Users Controller
 *
 * Manage users allowed to access to the administration interface
 *
 * @package CMS
 * @subpackage Controller
 * @author Chavjoh
 * @since 1.0.0
 */
class AdminUsersController extends BackEndController
{
	/**
	 * Construct controller variable
	 *
	 * @see BackEndController::__construct()
	 */
	public function __construct(array $arguments)
	{
		parent::__construct($arguments);

		$this->urlController .= 'Users/';
	}

	/**
	 * User list
	 */
	public function index()
	{
		$this->templateFile = 'userList.tpl';
		$this->smarty->assign('userList', UserModel::getUserList());
	}

	/**
	 * Create a new user
	 *
	 * @throws PDOException Database error when inserting user
	 */
	public function create()
	{
		// When a user is created
		if (count($_POST) > 0)
		{
			try
			{
				UserModel::createUser(
					(isset($_POST['login_user'])) ? $_POST['login_user'] : '',
					(isset($_POST['password_user'])) ? $_POST['password_user'] : '',
					(isset($_POST['name_user'])) ? $_POST['name_user'] : '',
					(isset($_POST['surname_user'])) ? $_POST['surname_user'] : ''
				);
			}
			catch (InvalidDataException $e)
			{
				Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
			}

			// Redirect to the user list page
			$this->header[] = 'Location:'.$this->urlController;
		}

		// When we have to show the template to create a user
		else
		{
			$this->templateFile = 'userForm.tpl';
			$this->smarty->assign('action', $this->urlController.'create/');
		}
	}

	/**
	 * Edit an user
	 *
	 * @throws PDOException Database error when editing user
	 * @throws ArgumentMissingException Missing user ID
	 */
	public function edit()
	{
		if (count($this->arguments) < 1)
			throw new ArgumentMissingException(__METHOD__, Language::get(__CLASS__.'.ArgumentMissingException.UserID'));

		// Get the user ID to edit
		$id_user = intval($this->arguments[0]);

		// When a user is edited
		if (count($_POST) > 0)
		{
			try
			{
				UserModel::editUser(
					$id_user,
					(isset($_POST['login_user'])) ? $_POST['login_user'] : '',
					(isset($_POST['password_user'])) ? $_POST['password_user'] : '',
					(isset($_POST['surname_user'])) ? $_POST['surname_user'] : '',
					(isset($_POST['name_user'])) ? $_POST['name_user'] : ''
				);
			}
			catch (InvalidDataException $e)
			{
				Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
			}

			// Redirect to the user list page
			$this->header[] = 'Location:'.$this->urlController;
		}

		// When we have to show the template to edit the user
		else
		{
			$this->templateFile = 'userForm.tpl';
			$this->smarty->assign('id_user', $id_user);
			$this->smarty->assign('action', $this->urlController.'edit/'.$id_user);

			try
			{
				// Try to load the user indicated
				$this->smarty->assign('user', UserModel::getUser($id_user));
			}
			catch (InvalidDataException $e)
			{
				Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
			}
		}
	}

	/**
	 * Delete an user
	 *
	 * @throws PDOException Database error when deleting user
	 * @throws ArgumentMissingException Missing user ID
	 */
	public function delete()
	{
		if (count($this->arguments) < 1)
			throw new ArgumentMissingException(__METHOD__, Language::get(__CLASS__.'.ArgumentMissingException.UserID'));

		// Get the user ID to delete
		$id_user = intval($this->arguments[0]);

		try
		{
			// Get user instance and delete it
			$user = UserModel::getUser($id_user);
			$user->delete();

			// If the user delete itself
			if ($_SESSION['id_user'] == $id_user)
				Login::disconnect();

			Logger::logMessage(new LoggerMessage(Language::get(__CLASS__.'.DeleteSuccess'), LoggerSeverity::SUCCESS));
		}
		catch (InvalidDataException $e)
		{
			Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
		}

		// Redirect to the user list page
		$this->header[] = 'Location:'.$this->urlController;
	}

	/**
	 * @see AbstractController::getPageName()
	 */
	public function getPageName()
	{
		return Language::get(__CLASS__.'.PageTitle').' - '.parent::getPageName();
	}

	/**
	 * @see AbstractController::getMethodAvailable()
	 */
	public static function getMethodAvailable()
	{
		return array_merge(parent::getMethodAvailable(), array('edit', 'create', 'delete'));
	}
}
