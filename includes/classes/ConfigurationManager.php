<?php
/**
 * Website parameters management
 * 
 * @version 1.0
 */

class ConfigurationManager
{
	/**
	 * Store all the information about the settings
	 *
	 * @var array
	 */
	protected static $configurationList = null;

	/**
	 * Autocommit indication
	 *
	 * @var bool
	 */
	protected static $autoCommit = true;

	/**
	 * Store all settings modification done
	 *
	 * @var array
	 */
	protected static $modificationList = array();

	/**
	 * Check if settings are loaded.
	 * Load settings if necessary
	 */
	public static function checkLoaded()
	{
		// Load configuration list if necessary
		if (self::$configurationList == null)
			self::load();
	}

	/**
	 * Load settings from database
	 */
	public static function load()
	{
		$PDO = PDOLib::getInstance();
		$result = $PDO->query("SELECT `key_setting`, `value_setting` FROM `".DB_PREFIX."setting`");

		self::$configurationList = array();

		foreach  ($result as $row) {
			self::$configurationList[$row['key_setting']] = $row['value_setting'];
		}
	}

	/**
	 * Get a setting value
	 *
	 * @param string $key Setting key
	 * @return mixed Setting value
	 */
	public static function get($key)
	{
		static::checkLoaded();

		// Returns the value for the specified configuration key if it exists
		if (isset(self::$configurationList[$key]))
			return self::$configurationList[$key];
		else
			return $key;
	}

	/**
	 * Modify a setting value
	 *
	 * @param string $key Setting key
	 * @param mixed $value New setting value
	 *
	 * @throws Exception Exception if key doesn't exist
	 */
	public static function set($key, $value)
	{
		static::checkLoaded();

		// Verify existence of the specified configuration for setting
		if (!isset(self::$configurationList[$key]))
			throw new Exception("[".__CLASS__."] Configuration key specified does not exist. It's therefore impossible to set.");

		static::$modificationList[$key] = 'S';

		if (static::$autoCommit)
			static::commit();

		self::$configurationList[$key] = $value;
	}

	/**
	 * Add a setting
	 *
	 * @param string $key Setting key
	 * @param mixed $value Setting value
	 *
	 * @throws Exception Exception if key already exists
	 */
	public static function add($key, $value)
	{
		static::checkLoaded();

		// Verify lack of the specified configuration for deleting
		if (isset(self::$configurationList[$key]))
			throw new Exception("[".__CLASS__."] Specified configuration key already exists. It's therefore impossible to add.");

		static::$modificationList[$key] = 'A';

		if (static::$autoCommit)
			static::commit();

		self::$configurationList[$key] = $value;
	}

	/**
	 * Remove a setting
	 *
	 * @param string $key Setting key
	 *
	 * @throws Exception Exception if key doesn't exist
	 */
	public static function remove($key)
	{
		static::checkLoaded();

		// Verify existence of the specified configuration for deleting
		if (!isset(self::$configurationList[$key]))
			throw new Exception("[".__CLASS__."] Configuration key specified does not exist. It's therefore impossible to remove.");

		$PDO = PDOLib::getInstance();

		static::$modificationList[$key] = 'R';

		if (static::$autoCommit)
			static::commit();

		unset(self::$configurationList[$key]);
	}

	/**
	 * Set autocommit status
	 *
	 * @param bool $bool Autocommit status
	 */
	public static function setAutoCommit($bool)
	{
		if ($bool)
			static::$autoCommit = true;
		else
			static::$autoCommit = false;
	}

	/**
	 * Update changed values
	 *
	 * @return int Number of changes
	 */
	public static function commit()
	{
		if (empty(static::$modificationList))
			return 0;

		$PDO = PDOLib::getInstance();
		$PDO->beginTransaction();

		// Prepared queries
		$addStatement = $PDO->prepare("INSERT INTO `".DB_PREFIX."setting` (`key_setting`, `value_setting`) VALUES (:key, :value)");
		$setStatement = $PDO->prepare("UPDATE `".DB_PREFIX."setting` SET `value_setting` = :value WHERE `key_setting` = :key");
		$removeStatement = $PDO->prepare("DELETE FROM `".DB_PREFIX."setting` WHERE `key_setting` = :key");

		// Number of changes
		$modification = 0;

		// For each modification
		foreach (static::$modificationList AS $key => $action)
		{
			switch ($action)
			{
				// Add
				case 'A':
					$addStatement->bindValue(':key', $key, PDO::PARAM_STR);
					$addStatement->bindValue(':value', static::$configurationList[$key], PDO::PARAM_STR);
					$addStatement->execute();
					$modification += $addStatement->rowCount();
					break;

				// Set
				case 'S':
					$setStatement->bindValue(':key', $key, PDO::PARAM_STR);
					$setStatement->bindValue(':value', static::$configurationList[$key], PDO::PARAM_STR);
					$setStatement->execute();
					$modification += $setStatement->rowCount();
					break;

				// Remove
				case 'R':
					$removeStatement->bindValue(':key', $key, PDO::PARAM_STR);
					$removeStatement->execute();
					$modification += $removeStatement->rowCount();
					break;
			}
		}

		$PDO->commit();
		static::$modificationList = array();
		return $modification;
	}
}

?>