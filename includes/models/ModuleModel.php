<?php

/**
 * Module Model
 *
 * @package CMS
 * @subpackage Model
 * @author Chavjoh
 * @since 1.0.0
 */
class ModuleModel extends AbstractModel
{
	/**
	 * Database column
	 *
	 * @var mixed
	 */
	protected $id_module, $name_module, $key_module;

	/**
	 * @see AbstractModel::__construct()
	 */
	public function __construct($id = 0, $data = array())
	{
		$this->table = DB_PREFIX.'module';
		parent::__construct($id, $data);
	}

	/**
	 * Get the path of the module
	 *
	 * @return string Path of the current module
	 */
	public function getPath()
	{
		return PATH_MODULE.$this->key_module.DS;
	}

	/**
	 * Load the module (include)
	 *
	 * @throw FileNotFoundException Module file not found
	 */
	public function loadModule()
	{
		// Create the path to the module file
		$path = $this->getPath().$this->key_module.'.php';

		if (file_exists($path))
			include_once($path);
		else
			throw new FileNotFoundException(__METHOD__, "Module file ($path) not found.");
	}

	/**
	 * Get all module in an array of ModuleModel
	 *
	 * @return array Array of ModuleModel representing all modules of the CMS
	 * @throws PDOException Database error when listing module
	 */
	public static function getModuleList()
	{
		$connexion = PDOLib::getInstance();

		$listModule = array();
		$listModuleQuery = "
		SELECT
			`id_module`,
			`name_module`,
			`key_module`
		FROM
			`".DB_PREFIX."module`
		ORDER BY
			`name_module` ASC";

		// Execute the query and get PDO statement
		$listModuleStatement = $connexion->query($listModuleQuery);

		// Create for each module its object
		while ($row = $listModuleStatement->fetch(PDO::FETCH_ASSOC))
			$listModule[] = new ModuleModel($row['id_module'], $row);

		return $listModule;
	}

	/**
	 * Get all module of a page in an array of ModuleModel
	 *
	 * @param int $id_page ID of the page
	 * @return array Array of ModuleModel corresponding to all modules on the page
	 * @throws PDOException Database error when listing module
	 */
	public static function getModuleListByPage($id_page)
	{
		$connexion = PDOLib::getInstance();

		$listModule = array();
		$listModuleSettings = array();
		$listModuleQuery = "
		SELECT
			`module`.`id_module`,
			`module`.`name_module`,
			`module`.`key_module`,
			`module_page`.`id_page`,
			`module_page`.`data_module_page`,
			`module_page`.`order_module_page`
		FROM
			`".DB_PREFIX."module` AS `module`
		LEFT JOIN
			`".DB_PREFIX."module_page` AS `module_page`
		ON
			`module`.`id_module` = `module_page`.`id_module`
		WHERE
			`module_page`.`id_page` = ?
		ORDER BY
			`module_page`.`order_module_page` ASC";

		// Prepare and execute the query
		$listModuleStatement = $connexion->prepare($listModuleQuery);
		$listModuleStatement->execute(array($id_page));

		// Create for each module in the page its objects (ModuleModel and ModulePageModel)
		while ($row = $listModuleStatement->fetch(PDO::FETCH_ASSOC))
		{
			$listModule[] = new ModuleModel($row['id_module'], $row);
			$listModuleSettings[] = new ModulePageModel(array($row['id_module'], $row['order_module_page']), $row);
		}

		return array($listModule, $listModuleSettings);
	}

	/**
	 * Get a module and the settings associated
	 *
	 * @param int $id_page ID of the page where the module is
	 * @param int $position Order of the module
	 * @return array Array that contains ModuleModel and ModulePageModel
	 * @throws PDOException Database error when loading module and settings
	 * @throws InvalidDataException Invalid page ID or module position
	 */
	public static function getModuleBy($id_page, $position)
	{
		$connexion = PDOLib::getInstance();

		$moduleQuery = "
		SELECT
			`module`.`id_module`,
			`module`.`name_module`,
			`module`.`key_module`,
			`module_page`.`id_page`,
			`module_page`.`data_module_page`,
			`module_page`.`order_module_page`
		FROM
			`".DB_PREFIX."module` AS `module`
		LEFT JOIN
			`".DB_PREFIX."module_page` AS `module_page`
		ON
			`module`.`id_module` = `module_page`.`id_module`
		WHERE
			`module_page`.`id_page` = ?
		AND `module_page`.`order_module_page` = ?";

		// Prepare and execute the query
		$dataStatement = $connexion->prepare($moduleQuery);
		$dataStatement->execute(array($id_page, $position));

		// Get the data row of the module
		$data = $dataStatement->fetch(PDO::FETCH_ASSOC);

		// If the module exists
		if ($dataStatement->rowCount() == 1)
		{
			return array(
				new ModuleModel($data['id_module'], $data),
				new ModulePageModel(array($data['id_module'], $data['order_module_page']), $data)
			);
		}
		else
			throw new InvalidDataException(__METHOD__, "Unknown module in the page and position indicated.");
	}

	/**
	 * Get the ModuleModel of a specific user
	 *
	 * @param int $id_module ID of the module
	 * @return ModuleModel Corresponding ModuleModel
	 * @throws PDOException Database error when loading module
	 */
	public static function getModule($id_module)
	{
		return new ModuleModel($id_module);
	}
}
