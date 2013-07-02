<?php
/**
 * Page Model
 * 
 * @version 1.0
 */

class PageModel extends AbstractModel
{
	/**
	 * Database column
	 *
	 * @var mixed
	 */
	protected $id_page, $id_layout, $alias_page, $title_page, $description_page, $keywords_page, $robots_page, $author_page;

	/**
	 * @see AbstractModel::__construct()
	 */
	public function __construct($id = 0, $data = array())
	{
		$this->table = DB_PREFIX.'page';
		parent::__construct($id, $data);
	}

	/**
	 * Create a new page
	 *
	 * @param int $id_layout ID of the layout selected
	 * @param string $alias_page Alias of the page
	 * @param string $title_page Title of the page
	 * @param string $description_page Description of the page
	 * @param string $keywords_page Keywords Meta Tags
	 * @param string $robots_page Robots Meta Tag
	 * @param string $author_page Author Meta Tag
	 * @return PageModel Page created
	 */
	public static function createPage($id_layout, $alias_page, $title_page, $description_page, $keywords_page, $robots_page, $author_page)
	{
		$menu = new PageModel();
		$menu->set('id_layout', $id_layout);
		$menu->set('alias_page', $alias_page);
		$menu->set('title_page', $title_page);
		$menu->set('description_page', $description_page);
		$menu->set('keywords_page', $keywords_page);
		$menu->set('robots_page', $robots_page);
		$menu->set('author_page', $author_page);
		$menu->insert();

		return $menu;
	}

	/**
	 * Get all pages in an array of PageModel
	 *
	 * @return array Array of PageModel representing all pages of the CMS
	 */
	public static function getPageList()
	{
		$connexion = PDOLib::getInstance();

		$listPage = array();
		$listPageQuery = "
		SELECT
			`id_page`,
			`id_layout`,
			`alias_page`,
			`title_page`,
			`description_page`,
			`keywords_page`,
			`robots_page`,
			`author_page`
		FROM
			`".DB_PREFIX."page`
		ORDER BY
			`title_page` ASC";

		$listPageStatement = $connexion->query($listPageQuery);

		while ($row = $listPageStatement->fetch(PDO::FETCH_ASSOC))
			$listPage[] = new PageModel($row['id_page'], $row);

		return $listPage;
	}

	/**
	 * Get page by alias
	 *
	 * @param string $alias Alias of the page to load
	 * @return array PageModel corresponding
	 */
	public static function getPageByAlias($alias)
	{
		$connexion = PDOLib::getInstance();

		$pageQuery = "
		SELECT
			`id_page`,
			`id_layout`,
			`alias_page`,
			`title_page`,
			`description_page`,
			`keywords_page`,
			`robots_page`,
			`author_page`
		FROM
			`".DB_PREFIX."page`
		WHERE
			`alias_page` = '".Security::in($alias)."'";

		$pageStatement = $connexion->query($pageQuery);
		$row = $pageStatement->fetch(PDO::FETCH_ASSOC);

		if ($pageStatement->rowCount() == 1)
			return new PageModel($row['id_page'], $row);
		else
			return null;
	}

	/**
	 * Get the PageModel of a specific page
	 *
	 * @param int $id_page ID of the page
	 * @return PageModel Corresponding PageModel
	 */
	public static function getPage($id_page)
	{
		return new PageModel($id_page);
	}
}

?>