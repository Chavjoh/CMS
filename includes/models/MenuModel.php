<?php

/**
 * Menu Model for Menu Management
 *
 * @package CMS
 * @subpackage Model
 * @author Chavjoh
 * @since 1.0.0
 */
class MenuModel extends AbstractModel
{
	/**
	 * Database column
	 *
	 * @var mixed
	 */
	protected $id_menu, $key_menu, $name_menu;

	/**
	 * @see AbstractModel::__construct()
	 */
	public function __construct($id = 0, $data = array())
	{
		$this->table = DB_PREFIX.'menu';
		parent::__construct($id, $data);
	}

	/**
	 * @see AbstractModel::set()
	 * @throws InvalidDataException Invalid key or name
	 */
	public function set($field, $value)
	{
		switch ($field)
		{
			case 'key_menu':
				if (empty($value))
					throw new InvalidDataException(__METHOD__, "Invalid key menu (cannot be empty).");
				else if (!preg_match('/^[a-zA-Z0-9]*$/i', $value))
					throw new InvalidDataException(__METHOD__, "Invalid key menu (only characters and number without space).");
				break;

			case 'name_menu':
				if (empty($value))
					throw new InvalidDataException(__METHOD__, "Invalid menu name (cannot be empty).");
				break;
		}

		parent::set($field, $value);
	}

	/**
	 * Create a new menu
	 *
	 * @param string $key_menu Key of the menu
	 * @param string $name_menu Name of the menu
	 * @return MenuModel Menu created
	 * @throws InvalidDataException Invalid name or key
	 * @throws PDOException Database error when inserting menu
	 */
	public static function createMenu($key_menu, $name_menu)
	{
		$menu = new MenuModel();
		$menu->set('key_menu', $key_menu);
		$menu->set('name_menu', $name_menu);

		try
		{
			$menu->insert();
		}
		catch (PDOException $e)
		{
			if ($e->errorInfo[1] == 1062)
				throw new InvalidDataException(__METHOD__, "Invalid menu key (must be unique).");
			else
				throw $e;
		}

		return $menu;
	}

	/**
	 * Edit a menu
	 *
	 * @param int $id_menu ID of the menu to edit
	 * @param string $key_menu Key of the menu
	 * @param string $name_menu Name of the menu
	 * @return MenuModel Menu edited
	 * @throws InvalidDataException Invalid menu ID, name or key
	 * @throws PDOException Database error when updating menu
	 */
	public static function editMenu($id_menu, $key_menu, $name_menu)
	{
		$menu = MenuModel::getMenu($id_menu);
		$menu->set('key_menu', $key_menu);
		$menu->set('name_menu', $name_menu);

		try
		{
			$menu->update();
		}
		catch (PDOException $e)
		{
			if ($e->errorInfo[1] == 1062)
				throw new InvalidDataException(__METHOD__, "Invalid menu key (must be unique).");
			else
				throw $e;
		}

		return $menu;
	}

	/**
	 * Get all menus in an array of MenuModel
	 *
	 * @return array Array of MenuModel representing all menus
	 * @throws PDOException Database error when loading menu list
	 */
	public static function getMenuList()
	{
		$statement = PDOLib::getInstance()->prepare("
		SELECT
			`id_menu`,
			`key_menu`,
			`name_menu`
		FROM
			`".DB_PREFIX."menu`
		ORDER BY
			`name_menu` ASC");
		$statement->execute();

		// Prepare the array of MenuModel object
		$listMenu = array();

		// Create for each menu its object
		while ($row = $statement->fetch(PDO::FETCH_ASSOC))
			$listMenu[] = new MenuModel($row['id_menu'], $row);

		return $listMenu;
	}

	/**
	 * Get the MenuModel of a specific menu
	 *
	 * @param int|string $id_menu ID or key of the menu
	 * @return MenuModel Corresponding MenuModel
	 * @throws InvalidDataException Invalid menu ID or key
	 * @throws PDOException Database error when loading menu
	 */
	public static function getMenu($id_menu)
	{
		// If we receive the menu ID
		if (is_int($id_menu))
			return new MenuModel($id_menu);

		// If we receive the menu Key
		else
		{
			$statement = PDOLib::getInstance()->prepare("
			SELECT
				`id_menu`,
				`key_menu`,
				`name_menu`
			FROM
				`".DB_PREFIX."menu`
			WHERE
				`key_menu` = ?");

			// Execute the query and get the data row corresponding to the menu key
			$statement->execute(array($id_menu));
			$row = $statement->fetch(PDO::FETCH_ASSOC);

			// Check menu existence
			if ($statement->rowCount() == 1)
				return new MenuModel($row['id_menu'], $row);
			else
				throw new InvalidDataException(__METHOD__, "Invalid menu key (doesn't exist).");
		}
	}
}
