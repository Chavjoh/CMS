<?php
/**
 * Menu Item Model for Menu Management
 *
 * @version 1.0
 */

class MenuItemModel extends AbstractModel
{
	/**
	 * Database column
	 *
	 * @var mixed
	 */
	protected $id_menu_item, $id_menu, $id_page, $name_menu_item, $order_menu_item, $alias_page;

	/**
	 * User primary key
	 *
	 * @var string
	 */
	protected $primaryKey = 'id_menu_item';

	/**
	 * @see AbstractModel::__construct()
	 */
	public function __construct($id = 0, $data = array())
	{
		$this->table = DB_PREFIX.'menu_item';
		parent::__construct($id, $data);
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
		$orderBorder = MenuItemModel::getMinMaxOrder($this->id_page);

		// Check type
		if (!in_array($type, array('up', 'down')))
			throw new Exception("[".__METHOD__."] Invalid type.");

		// If we are already in the border, we do nothing
		if (	($type == 'down' AND $this->order_menu_item == $orderBorder['max'])
			OR	($type == 'up' AND $this->order_menu_item == $orderBorder['min']))
			return;

		// Calculate the order before of after the current element, depending of type selected
		$otherOrder = ($type == 'up') ? $this->order_menu_item - 1 : $this->order_menu_item + 1;

		// Start a new transaction with the database server
		$this->connexion->beginTransaction();

		// Get the other item ID for the order exchange with the current item
		$otherItemQuery = "SELECT `id_menu_item` FROM `".$this->table."` WHERE `order_menu_item` = '$otherOrder'";
		$id_other = $this->connexion->query($otherItemQuery)->fetchColumn();

		// Query for order modification (elegant way)
		$orderQuery = "
		UPDATE
			`".$this->table."` AS item1
			JOIN `".$this->table."` AS item2 ON
				   ( item1.id_menu_item = ".intval($this->id_menu_item)." AND item2.id_menu_item = ".intval($id_other)." )
				OR ( item1.id_menu_item = ".intval($id_other)." AND item2.id_menu_item = ".intval($this->id_menu_item)." )
		SET
			item1.order_menu_item = item2.order_menu_item,
			item2.order_menu_item = item1.order_menu_item";

		// Execute the query
		$this->connexion->exec($orderQuery);

		// Commit the modifications
		$this->connexion->commit();
	}

	/**
	 * Create a new menu item
	 *
	 * @param int $id_menu ID of the menu related
	 * @param int $id_page ID of the page related
	 * @param string $name_menu_item Name of the menu item
	 * @param int $order_menu_item Order of the menu item
	 * @return MenuItemModel Menu item created
	 */
	public static function createMenuItem($id_menu, $id_page, $name_menu_item, $order_menu_item)
	{
		$menu = new MenuItemModel();
		$menu->set('id_menu', $id_menu);
		$menu->set('id_page', $id_page);
		$menu->set('name_menu_item', $name_menu_item);
		$menu->set('order_menu_item', $order_menu_item);
		$menu->insert();

		return $menu;
	}

	/**
	 * Get all menu items in an array of MenuItemModel
	 *
	 * @param int $id_menu ID of the menu
	 * @return array Array of MenuItemModel representing all menus of the CMS
	 */
	public static function getMenuItemList($id_menu)
	{
		$connexion = PDOLib::getInstance();

		$listItem = array();

		if (is_int($id_menu))
		{
			$listItemQuery = "
			SELECT
				`id_menu_item`,
				`id_menu`,
				`id_page`,
				`name_menu_item`,
				`order_menu_item`
			FROM
				`".DB_PREFIX."menu_item`
			WHERE
				`id_menu` = '".intval($id_menu)."'
			ORDER BY
				`order_menu_item` ASC";
		}
		else
		{
			$listItemQuery = "
			SELECT
				`item`.`id_menu_item`,
				`item`.`id_menu`,
				`item`.`id_page`,
				`item`.`name_menu_item`,
				`item`.`order_menu_item`,
				`page`.`alias_page`
			FROM
				`".DB_PREFIX."menu_item` AS `item`
			LEFT JOIN
				`".DB_PREFIX."menu` AS `menu`
			ON
				`menu`.`id_menu` = `item`.`id_menu`
			LEFT JOIN
				`".DB_PREFIX."page` AS `page`
			ON
				`page`.`id_page` = `item`.`id_page`
			WHERE
				`menu`.`key_menu` = '".Security::in($id_menu)."'
			ORDER BY
				`item`.`order_menu_item` ASC";
		}

		$listItemStatement = $connexion->query($listItemQuery);

		while ($row = $listItemStatement->fetch(PDO::FETCH_ASSOC))
			$listItem[] = new MenuItemModel($row['id_menu_item'], $row);

		return $listItem;
	}

	/**
	 * Get the MenuItemModel of a specific menu item
	 *
	 * @param int $id_menu_item ID of the menu item
	 * @return MenuItemModel Corresponding MenuItemModel
	 */
	public static function getMenuItem($id_menu_item)
	{
		return new MenuItemModel($id_menu_item);
	}

	/**
	 * Get the MIN and MAX order of the items in a menu
	 *
	 * @param int $id_menu Menu ID
	 * @return array Min and Max value in an associative array
	 */
	public static function getMinMaxOrder($id_menu)
	{
		$connexion = PDOLib::getInstance();

		$orderQuery = "
		SELECT
			MIN(`order_menu_item`) AS `min`,
			MAX(`order_menu_item`) AS `max`
		FROM
			`".DB_PREFIX."menu_item`
		WHERE
			`id_menu` = '".intval($id_menu)."'";

		return $connexion->query($orderQuery)->fetch(PDO::FETCH_ASSOC);
	}
}

?>