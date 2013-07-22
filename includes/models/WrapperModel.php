<?php

/**
 * Wrapper Model
 *
 * @package CMS
 * @subpackage Model
 * @author Chavjoh
 * @since 1.0.0
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
		$this->table = DB_PREFIX.'wrapper';
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
	 * Load the Wrapper (include)
	 *
	 * @throws FileNotFoundException Wrapper file not found
	 */
	public function loadWrapper()
	{
		// Create the path to the wrapper file
		$path = $this->getPath().$this->key_wrapper.'.php';

		if (file_exists($path))
			include_once($path);
		else
			throw new FileNotFoundException(__METHOD__, "Wrapper file (".$this->key_wrapper.") not found.");
	}

	/**
	 * Get the WrapperModel with its key
	 *
	 * @param string $key Wrapper key
	 * @return WrapperModel WrapperModel associated to the key indicated
	 * @throws InvalidDataException Unknown wrapper key
	 * @throws PDOException Database error when loading wrapper
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
			`key_wrapper` = ?";

		// Prepare and execute the query
		$statement = $connexion->prepare($wrapperQuery);
		$statement->execute(array($key));

		// If the wrapper exists
		if ($statement->rowCount() == 1)
		{
			// Get its data row
			$row = $statement->fetch(PDO::FETCH_ASSOC);

			// Return the model associated the the data
			return new WrapperModel($row['id_wrapper'], $row);
		}
		else
			throw new InvalidDataException(__METHOD__, "Unknown wrapper key.");
	}

	/**
	 * Get all wrappers in an array of WrapperModel
	 *
	 * @return array Array of WrapperModel representing all wrappers of the CMS
	 * @throws PDOException Database error when loading wrapper list
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

		// Execute the query and get the PDO statement
		$wrapperStatement = $connexion->query($wrapperQuery);

		// Create for each wrapper its object
		while ($row = $wrapperStatement->fetch(PDO::FETCH_ASSOC))
			$wrapperList[] = new WrapperModel($row['id_wrapper'], $row);

		return $wrapperList;
	}
}

?>