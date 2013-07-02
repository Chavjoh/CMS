<?php
/**
 * Wrapper Model
 * 
 * @version 1.0
 */

class WrapperModel extends AbstractModel
{
	/**
	 * Database column
	 *
	 * @var mixed
	 */
	protected $id_wrapper, $key_wrapper, $name_wrapper, $description_wrapper, $path_wrapper;

	/**
	 * @see AbstractModel::__construct()
	 */
	public function __construct($id = 0, $data = array())
	{
		$this->table = DB_PREFIX.'user';
		parent::__construct($id, $data);
	}

	/**
	 * Get the path of the wrapper
	 *
	 * @return string Path of the current wrapper
	 */
	public function getPath()
	{
		return PATH_WRAPPER.$this->key_wrapper.DS;
	}

	/**
	 * Load the Wrapper
	 */
	public function loadWrapper()
	{
		$path = $this->getPath().$this->key_wrapper.'.php';

		if (file_exists($path))
			include_once($path);
		else
			throw new Exception("[".__METHOD__."] Wrapper file (".$this->key_wrapper.") not found");
	}

	/**
	 * Get the WrapperModel with his key
	 *
	 * @param string $key Wrapper key
	 * @return WrapperModel WrapperModel associated to the key indicated
	 */
	public static function getWrapper($key)
	{
		$connexion = PDOLib::getInstance();

		$wrapperQuery = "
		SELECT
			`id_wrapper`,
			`key_wrapper`,
			`name_wrapper`,
			`description_wrapper`,
			`path_wrapper`
		FROM
			`".DB_PREFIX."wrapper`
		WHERE
			`key_wrapper` = '".Security::in($key)."'";

		$row = $connexion->query($wrapperQuery)->fetch(PDO::FETCH_ASSOC);
		return new WrapperModel($row['id_wrapper'], $row);
	}

	/**
	 * Get all wrappers in an array of WrapperModel
	 *
	 * @return array Array of WrapperModel representing all wrappers of the CMS
	 */
	public static function getWrapperList()
	{
		$connexion = PDOLib::getInstance();

		$wrapperList = array();
		$wrapperQuery = "
		SELECT
			`id_wrapper`,
			`key_wrapper`,
			`name_wrapper`,
			`description_wrapper`,
			`path_wrapper`
		FROM
			`".DB_PREFIX."wrapper`
		ORDER BY
			`name_wrapper` ASC";

		$wrapperStatement = $connexion->query($wrapperQuery);

		while ($row = $wrapperStatement->fetch(PDO::FETCH_ASSOC))
			$wrapperList[] = new WrapperModel($row['id_wrapper'], $row);

		return $wrapperList;
	}
}

?>