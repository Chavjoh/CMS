<?php
/**
 * User Model
 * 
 * @version 1.0
 */

class UserModel extends AbstractModel
{
	/**
	 * Database column
	 *
	 * @var mixed
	 */
	protected $id_user, $login_user, $password_user, $name_user, $surname_user;

	/**
	 * @see AbstractModel::__construct()
	 */
	public function __construct($id = 0, $data = array())
	{
		$this->table = DB_PREFIX.'user';
		parent::__construct($id, $data);
	}

	/**
	 * @see AbstractModel::set()
	 * @throws InvalidDataException Empty login or invalid login format
	 */
	public function set($field, $value)
	{
		switch ($field)
		{
			case 'login_user':
				if (empty($value))
					throw new InvalidDataException(__METHOD__, "Invalid user login (cannot be empty).");
				else if (!preg_match('/^[a-zA-Z0-9]*$/i', $value))
					throw new InvalidDataException(__METHOD__, "Invalid user login (only characters and number without space.");
				break;
		}

		parent::set($field, $value);
	}

	/**
	 * Create a new user account
	 *
	 * @param string $username User name to access to the account
	 * @param string $password Password to access to the account
	 * @param string $surname Surname of the user
	 * @param string $name Name of the user
	 * @return UserModel User created
	 * @throws InvalidDataException Empty login, invalid login format or not unique login
	 * @throws PDOException Database error when inserting user
	 */
	public static function createUser($username, $password, $surname, $name)
	{
		$user = new UserModel();
		$user->set('login_user', $username);
		$user->set('password_user', Security::passwordHash($password));
		$user->set('surname_user', $surname);
		$user->set('name_user', $name);

		try
		{
			$user->insert();
		}
		catch (PDOException $e)
		{
			if ($e->errorInfo[1] == 1062)
				throw new InvalidDataException(__METHOD__, "Invalid user login (must be unique).");
			else
				throw $e;
		}

		return $user;
	}

	/**
	 * Edit user information
	 *
	 * @param int $id_user User ID
	 * @param string $username User name to access to the account
	 * @param string $password Password to access to the account
	 * @param string $surname Surname of the user
	 * @param string $name Name of the user
	 * @return UserModel User edited
	 * @throws InvalidDataException Invalid user ID, empty login, invalid login format or not unique login
	 * @throws PDOException Database error when editing user
	 */
	public static function editUser($id_user, $username, $password, $surname, $name)
	{
		// Get user instance and edit it
		$user = UserModel::getUser($id_user);
		$user->set('login_user', $username);
		$user->set('surname_user', $surname);
		$user->set('name_user', $name);

		// Edit password only if needed
		if (!empty($password))
			$user->set('password_user', Security::passwordHash($password));

		try
		{
			$user->update();
		}
		catch (PDOException $e)
		{
			if ($e->errorInfo[1] == 1062)
				throw new InvalidDataException("Invalid user login (must be unique).");
			else
				throw $e;
		}

		// If the user edit itself
		if ($_SESSION['id_user'] == $id_user)
		{
			Login::updateSessionInformation(
				$user->get('login_user'),
				$user->get('name_user'),
				$user->get('surname_user')
			);
		}

		return $user;
	}

	/**
	 * Get all users in an array of UserModel
	 *
	 * @return array Array of UserModel representing all users of the CMS
	 * @throws PDOException Database error when loading user list
	 */
	public static function getUserList()
	{
		$connexion = PDOLib::getInstance();

		$listUser = array();
		$listUserQuery = "
		SELECT
			`id_user`,
			`login_user`,
			`password_user`,
			`name_user`,
			`surname_user`
		FROM
			`".DB_PREFIX."user`
		ORDER BY
			`name_user` ASC,
			`surname_user` ASC";

		// Execute the query and get the PDO statement
		$listUserStatement = $connexion->query($listUserQuery);

		// Create for each user its object
		while ($row = $listUserStatement->fetch(PDO::FETCH_ASSOC))
			$listUser[] = new UserModel($row['id_user'], $row);

		return $listUser;
	}

	/**
	 * Get the UserModel of a specific user
	 *
	 * @param int $id_user ID of the user
	 * @return UserModel Corresponding UserModel
	 * @throws InvalidDataException Invalid user ID
	 * @throws PDOException Database error when loading user
	 */
	public static function getUser($id_user)
	{
		return new UserModel($id_user);
	}
}

?>