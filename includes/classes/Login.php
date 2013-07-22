<?php

/**
 * Login
 *
 * @package CMS
 * @subpackage Login
 * @author Chavjoh
 * @since 1.0.0
 */
class Login 
{
	/**
	 * Indicate if the current user is logged in the BackEnd
	 *
	 * @return bool True if the current user is logged, False otherwise
	 */
	public static function isLogged()
	{
	    if (isset($_SESSION['login']))
			return true;
		else
			return false;
	}

	/**
	 * Connect a user to the BackEnd
	 *
	 * @param string $user User name
	 * @param string $password User password
	 * @throws InvalidLoginPasswordException Bad login or password
	 */
	public static function connect($user, $password)
	{
		$DB = PDOLib::getInstance();

		$query = $DB->prepare("
		SELECT 
			`id_user`,
			`login_user`, 
			`password_user`, 
			`name_user`, 
			`surname_user`
		FROM 
			`".DB_PREFIX."user`
		WHERE 
			`login_user` = ?
		AND `password_user` = ?");

		$query->execute(array(
			$user,
			Security::passwordHash($password)
		));

		// Successful login
		if ($query->rowCount() == 1)
		{
			$result = $query->fetch();

			// Set the session parameters
			$_SESSION['id_user'] = $result['id_user'];
			static::updateSessionInformation($result['login_user'], $result['name_user'], $result['surname_user']);
		}

		// Bad login
		else
			throw new InvalidLoginPasswordException(__METHOD__, Language::get('Login.InvalidLoginPasswordException'));
	}

	/**
	 * Update session information for current user
	 *
	 * @param string $login User login
	 * @param string $name User name
	 * @param string $surname User surname
	 */
	public static function updateSessionInformation($login, $name, $surname)
	{
		$_SESSION['login'] = $login;
		$_SESSION['name'] = $name;
		$_SESSION['surname'] = $surname;
	}

	/**
	 * Disconnect user from BackEnd
	 */
	public static function disconnect()
	{
		// Unset session variables
		session_unset();
	}
}