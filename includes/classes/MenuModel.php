<?php
/**
 * Menu Model for Menu Management
 * 
 * @version 1.0
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
	 * User primary key
	 *
	 * @var string
	 */
	protected $primaryKey = 'id_menu';

	/**
	 * @see AbstractModel::__construct()
	 */
	public function __construct($id = 0, $data = array())
	{
		$this->table = DB_PREFIX.'menu';
		parent::__construct($id, $data);
	}

	/**
	 * Create a new menu
	 *
	 * @param string $key_menu Key of the menu
	 * @param string $name_menu Name of the menu
	 * @return MenuModel Menu created
	 */
	public static function createMenu($key_menu, $name_menu)
	{
		$menu = new MenuModel();
		$menu->set('key_menu', $key_menu);
		$menu->set('name_menu', $name_menu);
		$menu->insert();

		return $menu;
	}

	/**
	 * Get all menus in an array of MenuModel
	 *
	 * @return array Array of MenuModel representing all menus of the CMS
	 */
	public static function getMenuList()
	{
		$connexion = PDOLib::getInstance();

		$listMenu = array();
		$listMenuQuery = "
		SELECT
			`id_menu`,
			`key_menu`,
			`name_menu`
		FROM
			`".DB_PREFIX."menu`
		ORDER BY
			`name_menu` ASC";

		$listMenuStatement = $connexion->query($listMenuQuery);

		while ($row = $listMenuStatement->fetch(PDO::FETCH_ASSOC))
		{
			$listMenu[] = new MenuModel($row['id_menu'], $row);
		}

		return $listMenu;
	}

	/**
	 * Get the MenuModel of a specific menu
	 *
	 * @param int|string $id_menu ID or key of the menu
	 * @return MenuModel Corresponding MenuModel
	 */
	public static function getMenu($id_menu)
	{
		if (is_int($id_menu))
			return new MenuModel($id_menu);
		else
		{
			$connexion = PDOLib::getInstance();

			$menuQuery = "
			SELECT
				`id_menu`,
				`key_menu`,
				`name_menu`
			FROM
				`".DB_PREFIX."menu`
			ORDER BY
				`name_menu` ASC";

			$menuStatement = $connexion->query($menuQuery);
			$row = $menuStatement->fetch(PDO::FETCH_ASSOC);

			if ($menuStatement->rowCount() == 1)
				return new MenuModel($row['id_menu'], $row);
			else
				return null;
		}
	}
}

?>