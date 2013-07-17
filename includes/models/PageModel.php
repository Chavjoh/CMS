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
	 * @see AbstractModel::set()
	 * @throws InvalidDataException Invalid alias or title
	 */
	public function set($field, $value)
	{
		switch ($field)
		{
			case 'alias_page':
				if (empty($value))
					throw new InvalidDataException(__METHOD__, "Invalid page alias (cannot be empty).");
				else if ($value == URL_ADMIN)
					throw new InvalidDataException(__METHOD__, "Invalid page alias (cannot be admin URL key).");
				else if (is_dir($value))
					throw new InvalidDataException(__METHOD__, "Invalid page alias (cannot be a directory name).");
				else if (!preg_match('/^[a-zA-Z0-9]*$/i', $value))
					throw new InvalidDataException(__METHOD__, "Invalid page alias (only characters and number without space).");
				break;

			case 'title_page':
				if (empty($value))
					throw new InvalidDataException(__METHOD__, "Invalid page title (cannot be empty).");
				break;
		}

		parent::set($field, $value);
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
	 * @throws InvalidDataException Invalid layout, alias or title
	 * @throws PDOException Database error when inserting page
	 */
	public static function createPage($id_layout, $alias_page, $title_page, $description_page, $keywords_page, $robots_page, $author_page)
	{
		$page = new PageModel();
		$page->set('id_layout', $id_layout);
		$page->set('alias_page', $alias_page);
		$page->set('title_page', $title_page);
		$page->set('description_page', $description_page);
		$page->set('keywords_page', $keywords_page);
		$page->set('robots_page', $robots_page);
		$page->set('author_page', $author_page);

		try
		{
			$page->insert();
		}
		catch (PDOException $e)
		{
			if ($e->errorInfo[1] == 1062)
				throw new InvalidDataException(__METHOD__, "Invalid page alias (must be unique).");
			else if ($e->errorInfo[1] == 1452)
				throw new InvalidDataException(__METHOD__, "Invalid layout (layout selected doesn't exist).");
			else
				throw $e;
		}

		return $page;
	}

	/**
	 * Edit a page
	 *
	 * @param int $id_page ID of the page to edit
	 * @param int $id_layout ID of the layout
	 * @param string $alias_page Alias of the page
	 * @param string $title_page Title of the page
	 * @param string $description_page Description of the page
	 * @param string $keywords_page Keywords Meta Tags
	 * @param string $robots_page Robots Meta Tag
	 * @param string $author_page Author Meta Tag
	 * @return PageModel Page edited
	 * @throws InvalidDataException Invalid layout, alias or title
	 * @throws PDOException Database error when editing page
	 */
	public static function editPage($id_page, $id_layout, $alias_page, $title_page, $description_page, $keywords_page, $robots_page, $author_page)
	{
		$page = PageModel::getPage($id_page);
		$page->set('id_layout', $id_layout);
		$page->set('alias_page', $alias_page);
		$page->set('title_page', $title_page);
		$page->set('description_page', $description_page);
		$page->set('keywords_page', $keywords_page);
		$page->set('robots_page', $robots_page);
		$page->set('author_page', $author_page);

		try
		{
			$page->update();
		}
		catch (PDOException $e)
		{
			if ($e->errorInfo[1] == 1062)
				throw new InvalidDataException(__METHOD__, "Invalid page alias (must be unique).");
			else if ($e->errorInfo[1] == 1452)
				throw new InvalidDataException(__METHOD__, "Invalid layout (layout selected doesn't exist).");
			else
				throw $e;
		}

		return $page;
	}

	/**
	 * Get all pages in an array of PageModel
	 *
	 * @return array Array of PageModel representing all pages of the CMS
	 * @throws PDOException Database error when loading page list
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

		// Execute the query and get the PDO statement
		$listPageStatement = $connexion->query($listPageQuery);

		// Create for each page its object
		while ($row = $listPageStatement->fetch(PDO::FETCH_ASSOC))
			$listPage[] = new PageModel($row['id_page'], $row);

		return $listPage;
	}

	/**
	 * Get page by alias
	 *
	 * @param string $alias Alias of the page to load
	 * @return array PageModel corresponding
	 * @throws InvalidDataException Invalid page alias
	 * @throws PDOException Database error when loading page
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
			`alias_page` = ?";

		// Prepare and execute the query
		$pageStatement = $connexion->prepare($pageQuery);
		$pageStatement->execute(array($alias));

		// If the page exists
		if ($pageStatement->rowCount() == 1)
		{
			// Get its data row
			$row = $pageStatement->fetch(PDO::FETCH_ASSOC);

			// Return the model associated the the data
			return new PageModel($row['id_page'], $row);
		}
		else
			throw new InvalidDataException(__METHOD__, "Unknown page alias.");
	}

	/**
	 * Get the PageModel of a specific page
	 *
	 * @param int $id_page ID of the page
	 * @return PageModel Corresponding PageModel
	 * @throws InvalidDataException Invalid page ID
	 * @throws PDOException Database error when loading page
	 */
	public static function getPage($id_page)
	{
		return new PageModel($id_page);
	}
}

?>