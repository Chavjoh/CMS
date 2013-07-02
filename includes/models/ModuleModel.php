<?php
/**
 * Module Model
 * 
 * @version 1.0
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
	 * Get all module in an array of ModuleModel
	 *
	 * @return array Array of ModuleModel representing all modules of the CMS
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

		$listModuleStatement = $connexion->query($listModuleQuery);

		while ($row = $listModuleStatement->fetch(PDO::FETCH_ASSOC))
			$listModule[] = new ModuleModel($row['id_module'], $row);

		return $listModule;
	}

	/**
	 * Get all module of a page in an array of ModuleModel
	 *
	 * @param int $id_page ID of the page
	 * @return array Array of ModuleModel corresponding to all modules on the page
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
			`module_page`.`id_page` = '".intval($id_page)."'
		ORDER BY
			`module_page`.`order_module_page` ASC";

		$listModuleStatement = $connexion->query($listModuleQuery);

		while ($row = $listModuleStatement->fetch(PDO::FETCH_ASSOC))
		{
			$listModule[] = new ModuleModel($row['id_module'], $row);
			$listModuleSettings[] = new ModulePageModel(array($row['id_module'], $row['order_module_page']), $row);
		}

		return array($listModule, $listModuleSettings);
	}

	/**
	 * Get the ModuleModel of a specific user
	 *
	 * @param int $id_module ID of the module
	 * @return ModuleModel Corresponding ModuleModel
	 */
	public static function getModule($id_module)
	{
		return new ModuleModel($id_module);
	}

	/**
	 * Get a module and the settings associated
	 *
	 * @param int $id_page ID of the page where the module is
	 * @param int $position Order of the module
	 * @return array Array that contains ModuleModel and ModulePageModel
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
			`module_page`.`id_page` = '".intval($id_page)."'
		AND `module_page`.`order_module_page` = '".intval($position)."'";

		$dataStatement = $connexion->query($moduleQuery);
		$data = $dataStatement->fetch(PDO::FETCH_ASSOC);

		if ($dataStatement->rowCount() > 0)
			return array(
				new ModuleModel($data['id_module'], $data),
				new ModulePageModel(array($data['id_module'], $data['order_module_page']), $data)
			);
		else
			return array(null, null);
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
	 */
	public function loadModule()
	{
		include_once($this->getPath().$this->key_module.'.php');
	}
}

?>