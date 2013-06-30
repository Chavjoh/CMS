<?php
/**
 * Login
 * 
 * @version 1.0
 */

class Login 
{
	public static function isLogged()
	{
	    if (isset($_SESSION['login']))
			return true;
		else
			return false;
	}
	
	public static function connect($in_user, $in_password)
	{
		$DB = PDOLib::getInstance();

		// Input security
		$user = Security::in($in_user);
		$password = Security::passwordHash(Security::in($in_password));

		// Query
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
		$query->execute(array($user, $password));

		// Successful login
		if ($query->rowCount() == 1)
		{
			$result = $query->fetch();

			// Set the session parameters
			$_SESSION['id_user'] = $result['id_user'];
			$_SESSION['login'] = $result['login_user'];
			$_SESSION['name'] = $result['name_user'];
			$_SESSION['surname'] = $result['surname_user'];
		}

		// Failed login
		else
			throw new Exception("<strong>Error :</strong> Bad login or password");
	}
	
	public static function updateSessionInformation($login, $name, $surname)
	{
		$_SESSION['login'] = $login;
		$_SESSION['name'] = $name;
		$_SESSION['surname'] = $surname;
	}

	public static function disconnect()
	{
		// Unset session variables
		session_unset();

		// Redirect to login page
		header('Location: '.Server::getDirectoryScript().DS.URL_ADMIN);
	}
}

?>