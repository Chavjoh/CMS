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
	 * Create a new user account
	 *
	 * @param string $username User name to access to the account
	 * @param string $password Password to access to the account
	 * @param string $surname Surname of the user
	 * @param string $name Name of the user
	 * @return UserModel User created
	 */
	public static function createUser($username, $password, $surname, $name)
	{
		$user = new UserModel();
		$user->set('login_user', $username);
		$user->set('password_user', Security::passwordHash($password));
		$user->set('surname_user', $surname);
		$user->set('name_user', $name);
		$user->insert();

		return $user;
	}

	/**
	 * Get all users in an array of UserModel
	 *
	 * @return array Array of UserModel representing all users of the CMS
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

		$listUserStatement = $connexion->query($listUserQuery);

		while ($row = $listUserStatement->fetch(PDO::FETCH_ASSOC))
			$listUser[] = new UserModel($row['id_user'], $row);

		return $listUser;
	}

	/**
	 * Get the UserModel of a specific user
	 *
	 * @param int $id_user ID of the user
	 * @return UserModel Corresponding UserModel
	 */
	public static function getUser($id_user)
	{
		return new UserModel($id_user);
	}
}

?>