<?php
/**
 * Content Management System
 * Installation script
 *
 * @author Chavjoh
 * @license Creative Commons Attribution-ShareAlike 3.0 Unported
 */

/**
 * Retrieve a GET parameter without error
 *
 * @param string $name Name of the parameter, corresponding to $_GET[$name]
 * @return string Value of the parameter
 */
function getParameter($name)
{
	return (isset($_GET[$name])) ? $_GET[$name] : '';
}

/**
 * Recursive CHMOD
 *
 * @param string $path Folder path to set CHMOD
 * @param string $chmod Value of CHMOD
 * @param array $errorList List of files with error
 */
function chmod_recursive($path, $chmod, &$errorList = array())
{
	if (!is_writable($path) && !chmod($path, $chmod))
		$errorList[] = $path;

	// Need directory for recursive chmod
	if (is_dir($path))
	{
		$directory = opendir($path);

		// Read each file of the directory
		while (($file = readdir($directory)) !== false)
		{
			// Skip self and parent directories
			if ($file != '.' && $file != '..')
			{
				$fullPath = $path.'/'.$file;
				chmod_recursive($fullPath, $chmod);
			}
		}

		closedir($directory);
	}
}

/**
 * Create a random string
 *
 * @param int $size Size of the string
 * @return string Random string generated
 */
function random($size)
{
	$string = "";
	$list = "abcdefghijklmnpqrstuvwxy-_[]!#@*:.,";
	srand((double)microtime()*1000000);

	for ($i = 0; $i < $size; $i++) {
		$string .= $list[rand() % strlen($list)];
	}

	return $string;
}

// Get type of call
$type = getParameter('type');

/**
 * To check requirements
 */
if ($type == "REQUIREMENT")
{
	if (version_compare(phpversion(), '5.4.0', '<')) {
		echo 'You need the at least PHP version 5.4 !';
	}
	else {
		echo 'SUCCESS';
	}
}
/**
 * To check permissions
 */
else if ($type == "CHMOD")
{
	$chmodList = array(
		'../includes/caches' => 0777,
		'../includes/compiles' => 0777,
		'../includes/modules' => 0777,
		'../includes/skins' => 0777,
		'../includes/wrappers' => 0777,
		'../includes/configuration.xml' => 0777
	);

	$errorList = array();

	foreach ($chmodList AS $path => $chmod)	{
		chmod_recursive($path, $chmod, $errorList);
	}

	if (count($errorList) > 0) {
		echo 'Permissions cannot be set, please do it manually. ';
		echo 'The following files/folders need to be set CHMOD 777 : ';
		foreach ($errorList AS $path) {
			echo '<br /> - '.$path;
		}
	}
	else {
		echo 'SUCCESS';
	}
}
/**
 * To check database connexion
 */
else if ($type == "DATABASE")
{
	$name = getParameter('name');
	$host = getParameter('host');
	$port = getParameter('port');
	$user = getParameter('user');
	$password = getParameter('password');

	try {
		error_reporting(0);

		$database = new PDO(
			'mysql:host='.$host.':'.$port.';dbname='.$name,
			$user,
			$password,
			array(
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			)
		);

		echo 'SUCCESS';
	}
	catch(PDOException $e) {
		echo 'Unable to connect to database.';
	}
}
/**
 * To make CMS installation
 */
else if ($type == "INSTALL")
{
	require('../includes/classes/Enumeration.php');
	require('../includes/classes/Security.php');

	// Data retrieved
	$data = array(
		'information-name'			=> getParameter('information-name'),
		'information-description'	=> getParameter('information-description'),
		'information-keywords'		=> getParameter('information-keywords'),

		'database-name'				=> getParameter('database-name'),
		'database-prefix'			=> getParameter('database-prefix'),
		'database-user'				=> getParameter('database-user'),
		'database-password'			=> getParameter('database-password'),
		'database-host'				=> getParameter('database-host'),
		'database-port'				=> getParameter('database-port'),

		'administration-key'		=> getParameter('administration-key'),
		'administration-user'		=> getParameter('administration-user'),
		'administration-password'	=> getParameter('administration-password'),
		'administration-salt'		=> random(20)
	);

	// Define password salt (for password generation)
	define("PASSWORD_SALT", $data['administration-salt']);

	// Check administration key given
	if (!preg_match('/^[a-zA-Z0-9]*$/i', $data['administration-key'])) {
		die('Invalid administration key.');
	}

	// Database connexion
	try {
		$PDO = new PDO(
			'mysql:host='.$data['database-host'].':'.$data['database-port'].';dbname='.$data['database-name'],
			$data['database-user'],
			$data['database-password'],
			array(
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			)
		);
	}
	catch(PDOException $e) {
		die('Unable to connect to database.');
	}

	// SQL files to execute
	$fileSQL = array(
		'./database/structure.sql',
		'./database/data.sql'
	);

	// Execute each SQL files
	foreach ($fileSQL AS $file)
	{
		if (!file_exists($file)) {
			die('Unable to find SQL files ('.$file.').');
		}

		$query = file_get_contents($file);
		$query = str_replace("[[prefix]]", $data['database-prefix'], $query);
		$query = str_replace("[[information-name]]", $data['information-name'], $query);
		$query = str_replace("[[information-description]]", $data['information-description'], $query);
		$query = str_replace("[[information-keywords]]", $data['information-keywords'], $query);
		$query = str_replace("[[administration-user]]", $data['administration-user'], $query);
		$query = str_replace("[[administration-password]]", Security::passwordHash($data['administration-password']), $query);

		try {
			$PDO->exec($query);
		} catch (PDOException $e) {
			die('Unable to execute SQL from file ('.$file.') on the database server.');
		}
	}

	// Create configuration XMl file
	$configurationXML = new SimpleXMLElement("<configuration></configuration>");

	// Debug
	$debugXML = $configurationXML->addChild('debug');
	$debugXML->addAttribute('active', '0');

	// Administration
	$administrationXML = $configurationXML->addChild('administration');
	$administrationXML->addChild('key', $data['administration-key']);

	// Salt
	$saltXML = $configurationXML->addChild('salt');
	$saltXML->addAttribute('value', $data['administration-salt']);

	// Database
	$databaseXML = $configurationXML->addChild('database');
	$databaseXML->addChild('driver', 'mysql');
	$databaseXML->addChild('name', $data['database-name']);
	$databaseXML->addChild('user', $data['database-user']);
	$databaseXML->addChild('password', $data['database-password']);
	$databaseXML->addChild('host', $data['database-host']);
	$databaseXML->addChild('port', $data['database-port']);
	$databaseXML->addChild('prefix', $data['database-prefix']);

	// Save XML file
	file_put_contents('../includes/configuration.xml', $configurationXML->asXML());

	echo 'SUCCESS';
}