<?php
/**
 * Module Page Model
 *
 * @version 1.0
 */

class ModulePageModel extends AbstractModel
{
	/**
	 * Database column
	 *
	 * @var mixed
	 */
	protected $id_module, $id_page, $order_module_page, $data_module_page;

	/**
	 * @see AbstractModel::__construct()
	 */
	public function __construct($id = null, $data = array())
	{
		$this->table = DB_PREFIX.'module_page';
		parent::__construct($id, $data);
	}

	/**
	 * Get settings of the module unserialized
	 *
	 * @param bool $activeWrapper Replace Wrapper tag with real value, by default True
	 * @return array Module settings
	 */
	public function getData($activeWrapper = true)
	{
		$data = unserialize($this->data_module_page);

		if ($activeWrapper)
		{
			foreach ($data AS $dataKey => $dataValue)
			{
				if (preg_match_all("/\{\*(.*?)\:\:(.*?)\*\}/", $dataValue, $matches) > 0)
				{
					foreach ($matches[0] AS $index => $match)
					{
						$wrapperKey = trim($matches[1][$index]);
						$wrapperElement = trim($matches[2][$index]);

						$wrapper = WrapperModel::getWrapper($wrapperKey);
						$wrapper->loadWrapper();
						$element = $wrapperKey::get($wrapperElement);
						$data[$dataKey] = str_replace(trim($match), $element, $dataValue);
					}
				}
			}
		}

		return $data;
	}

	/**
	 * Change order of the current element
	 *
	 * @param string $type Change type ('up' or 'down')
	 * @throws Exception Invalid type
	 */
	public function changeOrder($type)
	{
		// Border of order (min and max)
		$orderBorder = ModulePageModel::getMinMaxOrder($this->id_page);

		// Check type
		if (!in_array($type, array('up', 'down')))
			throw new Exception("[".__METHOD__."] Invalid type.");

		// If we are already in the border, we do nothing
		if (	($type == 'down' AND $this->order_module_page == $orderBorder['max'])
			OR	($type == 'up' AND $this->order_module_page == $orderBorder['min']))
			return;

		// Settings order to exchange with the upper element
		$order = ($type == 'up') ? $this->order_module_page - 1 : $this->order_module_page;

		// Start a new transaction with the database server
		$this->connexion->beginTransaction();

		// Queries for order modification
		$orderQueries = "
		UPDATE
			`".DB_PREFIX."module_page`
		SET
			`order_module_page` = '".intval($orderBorder['max'] + 1)."'
		WHERE
			`id_page` = '".intval($this->id_page)."'
		AND `order_module_page` = '".$order."';

		UPDATE
			`".DB_PREFIX."module_page`
		SET
			`order_module_page` = (`order_module_page` - 1)
		WHERE
			`id_page` = '".intval($this->id_page)."'
		AND `order_module_page` = '".($order + 1)."';

		UPDATE
			`".DB_PREFIX."module_page`
		SET
			`order_module_page` = '".($order + 1)."'
		WHERE
			`id_page` = '".intval($this->id_page)."'
		AND `order_module_page` = '".intval($orderBorder['max'] + 1)."'; ";

		// Execute the query
		$this->connexion->exec($orderQueries);

		// Commit the modifications
		$this->connexion->commit();
	}

	/**
	 * Create a new ModulePageModel.
	 * Corresponding to add a new Module to a Page.
	 *
	 * @param int $id_module Module ID to add to the page
	 * @param int $id_page Page ID to add the module
	 * @return ModulePageModel Object ModulePageModel created
	 */
	public static function createModulePage($id_module, $id_page)
	{
		$modulePage = new ModulePageModel();
		$modulePage->set('id_module', $id_module);
		$modulePage->set('id_page', $id_page);
		$modulePage->set('order_module_page', self::getLastOrder($id_page) + 1);
		$modulePage->set('data_module_page', serialize(array()));
		$modulePage->insert();

		return $modulePage;
	}

	/**
	 * Get the MIN and MAX order of the module in a page
	 *
	 * @param int $id_page Page ID
	 * @return array Min and Max value in an associative array
	 */
	public static function getMinMaxOrder($id_page)
	{
		$connexion = PDOLib::getInstance();

		$orderQuery = "
		SELECT
			MIN(`order_module_page`) AS `min`,
			MAX(`order_module_page`) AS `max`
		FROM
			`".DB_PREFIX."module_page`
		WHERE
			`id_page` = '".intval($id_page)."'";

		return $connexion->query($orderQuery)->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 * Get the last order of a module in a page
	 *
	 * @param int $id_page Page ID for the last order
	 * @return int Last order discovered
	 */
	public static function getLastOrder($id_page)
	{
		$connexion = PDOLib::getInstance();

		$lastOrderQuery = "
		SELECT
			`order_module_page`
		FROM
			`".DB_PREFIX."module_page`
		WHERE
			`id_page` = '".intval($id_page)."'
		ORDER BY
			`order_module_page` DESC
		LIMIT 1";

		$lastOrderStatement = $connexion->query($lastOrderQuery);

		if ($lastOrderStatement->rowCount() == 1)
		{
			$lastOrder = $lastOrderStatement->fetch(PDO::FETCH_ASSOC);
			return $lastOrder['order_module_page'];
		}
		else
			return 0;
	}
}

?>