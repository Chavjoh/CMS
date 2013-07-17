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
	protected $id_menu_item, $id_menu, $id_page, $name_menu_item, $order_menu_item;

	/**
	 * Value stored in this model for optimization
	 *
	 * @var mixed
	 */
	protected $alias_page;

	/**
	 * @see AbstractModel::__construct()
	 */
	public function __construct($id = 0, $data = array())
	{
		$this->table = DB_PREFIX.'menu_item';
		parent::__construct($id, $data);
	}

	/**
	 * @see AbstractModel::set()
	 * @throws InvalidDataException Invalid name
	 */
	public function set($field, $value)
	{
		switch ($field)
		{
			case 'name_menu_item':
				if (empty($value))
					throw new InvalidDataException(__METHOD__, "Invalid menu item name (cannot be empty).");
				break;
		}

		parent::set($field, $value);
	}

	/**
	 * @see AbstractModel::delete()
	 */
	public function delete($where = array())
	{
		// Start a new transaction with the database server
		$this->connexion->beginTransaction();

		// Delete the current item
		parent::delete($where);

		// Re-order the item
		$statement = $this->connexion->prepare("
		UPDATE
			".$this->table."
		SET
			`order_menu_item` = (`order_menu_item` - 1)
		WHERE
			`id_menu` = ?
		AND `order_menu_item` > ?");
		$statement->execute(array($this->id_menu, $this->order_menu_item));

		// Confirm the transaction (deletion and re-order)
		$this->connexion->commit();
	}

	/**
	 * Change order of the current element
	 *
	 * @param string $type Change type ('up' or 'down')
	 * @throws InvalidDataException Invalid type
	 * @throws PDOException Database error when changing order
	 */
	public function changeOrder($type)
	{
		// Min and max order
		$orderBorder = MenuItemModel::getMinMaxOrder($this->id_page);

		// Check type
		if (!in_array($type, array('up', 'down')))
			throw new InvalidDataException(__METHOD__, "Invalid type.");

		// If we are already in the border, we do nothing
		if (	($type == 'down' AND $this->order_menu_item == $orderBorder['max'])
			OR	($type == 'up' AND $this->order_menu_item == $orderBorder['min']))
			return;

		// Calculate the order before of after the current element, depending of type selected
		$otherOrder = ($type == 'up') ? $this->order_menu_item - 1 : $this->order_menu_item + 1;

		// Start a new transaction with the database server
		$this->connexion->beginTransaction();

		// Get the other item ID for the order exchange with the current item
		$statement = $this->connexion->prepare("
		SELECT
			id_menu_item
		FROM
			".$this->table."
		WHERE
			id_menu = ?
		AND order_menu_item = ?");
		$statement->execute(array($this->id_menu, $otherOrder));
		$id_other = $statement->fetchColumn();

		// Query for order modification (elegant way)
		$statement = $this->connexion->prepare("
		UPDATE
			".$this->table." AS item1
			JOIN ".$this->table." AS item2 ON
				   ( item1.id_menu_item = :id_base AND item2.id_menu_item = :id_other )
				OR ( item1.id_menu_item = :id_other AND item2.id_menu_item = :id_base )
		SET
			item1.order_menu_item = item2.order_menu_item,
			item2.order_menu_item = item1.order_menu_item");
		$statement->bindValue(':id_base', $this->id_menu_item, PDO::PARAM_INT);
		$statement->bindValue(':id_other', $id_other, PDO::PARAM_INT);
		$statement->execute();

		// Commit the modifications
		$this->connexion->commit();
	}

	/**
	 * Create a new menu item
	 *
	 * @param int $id_menu ID of the menu related
	 * @param int $id_page ID of the page related
	 * @param string $name_menu_item Name of the menu item
	 * @return MenuItemModel Menu item created
	 * @throws InvalidDataException Invalid name, menu or page ID
	 * @throws PDOException Database error when adding menu item
	 */
	public static function createMenuItem($id_menu, $id_page, $name_menu_item)
	{
		// Get the min and max order in this menu
		$orderBorder = MenuItemModel::getMinMaxOrder($id_menu);

		$menu = new MenuItemModel();
		$menu->set('id_menu', $id_menu);
		$menu->set('id_page', $id_page);
		$menu->set('name_menu_item', $name_menu_item);
		$menu->set('order_menu_item', $orderBorder['max'] + 1);

		try
		{
			$menu->insert();
		}
		catch (PDOException $e)
		{
			if ($e->errorInfo[1] == 1452)
				throw new InvalidDataException(__METHOD__, "Invalid menu or page (selected doesn't exist).");
			else
				throw $e;
		}

		return $menu;
	}

	/**
	 * Edit a menu item
	 *
	 * @param int $id_menu_item ID of the menu item to edit
	 * @param int $id_menu ID of the menu related
	 * @param int $id_page ID of the page related
	 * @param string $name_menu_item Name of the menu item
	 * @return MenuItemModel Menu item created
	 * @throws InvalidDataException Invalid menu item ID, menu ID, page ID or name
	 * @throws PDOException Database error when editing menu item
	 */
	public static function editMenuItem($id_menu_item, $id_menu, $id_page, $name_menu_item)
	{
		$menu = MenuItemModel::getMenuItem($id_menu_item);
		$menu->set('id_menu', $id_menu);
		$menu->set('id_page', $id_page);
		$menu->set('name_menu_item', $name_menu_item);

		try
		{
			$menu->update();
		}
		catch (PDOException $e)
		{
			if ($e->errorInfo[1] == 1452)
				throw new InvalidDataException(__METHOD__, "Invalid menu or page (selected doesn't exist).");
			else
				throw $e;
		}

		return $menu;
	}

	/**
	 * Get all menu items in an array of MenuItemModel
	 *
	 * @param int|string $id_menu ID or Key of the menu
	 * @return array Array of MenuItemModel representing all menus items
	 * @throws PDOException Database error when loading menu item list
	 */
	public static function getMenuItemList($id_menu)
	{
		// If we receive the menu ID
		if (is_int($id_menu))
		{
			$statement = PDOLib::getInstance()->prepare("
			SELECT
				`id_menu_item`,
				`id_menu`,
				`id_page`,
				`name_menu_item`,
				`order_menu_item`
			FROM
				`".DB_PREFIX."menu_item`
			WHERE
				`id_menu` = ?
			ORDER BY
				`order_menu_item` ASC");
		}
		// If we receive the menu Key
		else
		{
			$statement = PDOLib::getInstance()->prepare("
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
				`menu`.`key_menu` = ?
			ORDER BY
				`item`.`order_menu_item` ASC");
		}

		// Execute the query with the menu parameter given
		$statement->execute(array($id_menu));

		// Prepare the array of MenuItemModel object
		$listItem = array();

		// Create for each menu item its object
		while ($row = $statement->fetch(PDO::FETCH_ASSOC))
			$listItem[] = new MenuItemModel($row['id_menu_item'], $row);

		return $listItem;
	}

	/**
	 * Get the MIN and MAX order of the items in a menu
	 *
	 * @param int $id_menu Menu ID
	 * @return array Min and Max value in an associative array
	 * @throws PDOException Database error when loading min and max order
	 */
	public static function getMinMaxOrder($id_menu)
	{
		$statement = PDOLib::getInstance()->prepare("
		SELECT
			MIN(`order_menu_item`) AS `min`,
			MAX(`order_menu_item`) AS `max`
		FROM
			`".DB_PREFIX."menu_item`
		WHERE
			`id_menu` = ?");
		$statement->execute(array($id_menu));

		return $statement->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 * Get the MenuItemModel of a specific menu item
	 *
	 * @param int $id_menu_item ID of the menu item
	 * @return MenuItemModel Corresponding MenuItemModel
	 * @throws InvalidDataException Invalid menu item ID
	 * @throws PDOException Database error when loading menu item
	 */
	public static function getMenuItem($id_menu_item)
	{
		return new MenuItemModel($id_menu_item);
	}
}

?>