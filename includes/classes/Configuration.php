<?php

/**
 * Website configuration management
 *
 * @package CMS
 * @subpackage System
 * @author Chavjoh
 * @since 1.0.0
 */
class Configuration
{
	/**
	 * Store all information about settings
	 *
	 * @var array
	 */
	protected static $configurationList = null;

	/**
	 * Autocommit flag
	 *
	 * @var bool
	 */
	protected static $autoCommit = true;

	/**
	 * Store all modifications done
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
		// Get all settings from database
		$result = PDOLib::getInstance()->query("SELECT `key_setting`, `value_setting` FROM `".DB_PREFIX."setting`");

		// Prepare configuration list
		self::$configurationList = array();

		// Add each settings in cache
		foreach ($result as $setting)
			self::$configurationList[$setting['key_setting']] = $setting['value_setting'];
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

		// Otherwise return an empty string
		else
			return '';
	}

	/**
	 * Modify a setting value
	 *
	 * @param string $key Setting key
	 * @param mixed $value New setting value
	 * @throws InvalidDataException If key doesn't exist
	 */
	public static function set($key, $value)
	{
		static::checkLoaded();

		// Verify existence of the specified configuration for setting
		if (!isset(self::$configurationList[$key]))
			throw new InvalidDataException(__METHOD__, "Configuration key specified does not exist.");

		// Save modification for database update
		static::$modificationList[$key] = 'S';

		// Update value in cache
		self::$configurationList[$key] = $value;

		// If we update database at each set function call
		if (static::$autoCommit)
			static::commit();
	}

	/**
	 * Add a setting
	 *
	 * @param string $key Setting key
	 * @param mixed $value Setting value
	 * @throws InvalidDataException If key doesn't exist
	 */
	public static function add($key, $value)
	{
		static::checkLoaded();

		// Verify lack of the specified configuration for deleting
		if (isset(self::$configurationList[$key]))
			throw new InvalidDataException(__METHOD__, "Configuration key specified does not exist.");

		// Save modification for database update
		static::$modificationList[$key] = 'A';

		// Add value in cache
		self::$configurationList[$key] = $value;

		// If we update database at each add function call
		if (static::$autoCommit)
			static::commit();
	}

	/**
	 * Remove a setting
	 *
	 * @param string $key Setting key
	 * @throws InvalidDataException If key doesn't exist
	 */
	public static function remove($key)
	{
		static::checkLoaded();

		// Verify existence of the specified configuration for deleting
		if (!isset(self::$configurationList[$key]))
			throw new InvalidDataException(__METHOD__, "Configuration key specified does not exist.");

		// Save modification for database update
		static::$modificationList[$key] = 'R';

		// Remove value in cache
		unset(self::$configurationList[$key]);

		// If we update database at each remove function call
		if (static::$autoCommit)
			static::commit();
	}

	/**
	 * Set autocommit status
	 *
	 * @param bool $bool Autocommit status
	 */
	public static function setAutoCommit($bool)
	{
		static::$autoCommit = (bool) $bool;
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

		// Get database link and create a transaction to ensure data integrity
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

		// Commit transaction
		$PDO->commit();

		// Clear modification list
		static::$modificationList = array();

		// Returns number of database modification made
		return $modification;
	}
}
