<?php

/**
 * Template Model
 *
 * @package CMS
 * @subpackage Model
 * @author Chavjoh
 * @since 1.0.0
 */
class TemplateModel extends AbstractModel
{
	/**
	 * Cache for active template object (one for each side)
	 *
	 * @var array
	 */
	protected static $activeTemplate = array();

	/**
	 * Database column
	 *
	 * @var mixed
	 */
	protected $id_template, $name_template, $path_template, $type_template, $active_template;

	/**
	 * @see AbstractModel::__construct()
	 */
	public function __construct($id = 0, $data = array())
	{
		$this->table = DB_PREFIX.'template';
		parent::__construct($id, $data);
	}

	/**
	 * @see AbstractModel::set()
	 * @throws InvalidDataException Invalid name, path, type or active attribute
	 */
	public function set($field, $value)
	{
		switch ($field)
		{
			case 'name_template':
				if (empty($value))
					throw new InvalidDataException(__METHOD__, "Invalid template name (cannot be empty).");
				break;

			case 'path_template':
				if (empty($value))
					throw new InvalidDataException(__METHOD__, "Invalid template path (cannot be empty).");
				else if (!is_dir(PATH_SKIN.$value))
					throw new InvalidDataException(__METHOD__, "Invalid template path (folder does not exist).");
				break;

			case 'type_template':
				if (!in_array($value, TemplateSide::getConstList()))
					throw new InvalidDataException(__METHOD__, "Invalid template type (FRONTEND or BACKEND)");
				break;

			case 'active_template':
				if (!in_array($value, array('0', '1')))
					throw new InvalidDataException(__METHOD__, "Invalid value for active template (only 0 or 1).");
				else if ($this->active_template == 1 AND $value == 0)
					throw new InvalidDataException(__METHOD__, "Cannot deactivate this template, an active template is necessary for each side.");
				break;
		}

		parent::set($field, $value);
	}

	/**
	 * @see AbstractModel::delete()
	 * @throws InvalidDataException If active template
	 */
	public function delete($where = array())
	{
		if ($this->isActiveTemplate())
			throw new InvalidDataException(__METHOD__, "Active template cannot be deleted.");
		else
			parent::delete($where);
	}

	/**
	 * Set the current template as active for the side indicated
	 *
	 * @param string $side Template side (FRONTEND or BACKEND)
	 * @throws InvalidDataException Invalid side
	 */
	public function setActiveTemplate($side)
	{
		static::disableAllTemplate($side);

		// Set the current template as active for the side indicated
		$this->set('active_template', '1');

		// Save active template cache
		static::$activeTemplate[$side] = $this;
	}

	/**
	 * Indicate if the template is active
	 *
	 * @return bool True if it's an active template, False otherwise
	 */
	public function isActiveTemplate()
	{
		return ($this->get('active_template') == '1');
	}

	/**
	 * Register a new template
	 *
	 * @param string $name_template Template name
	 * @param string $path_template Template path
	 * @param string $type_template Type (side) of the template, provided by TemplateSide
	 * @param int $active_template Indicate if the template is immediately active
	 * @return TemplateModel Template created
	 * @throws InvalidDataException Invalid name, path, type or active attribute
	 * @throws PDOException Database error when inserting template
	 */
	public static function createTemplate($name_template, $path_template, $type_template, $active_template)
	{
		$template = new TemplateModel();
		$template->set('name_template', $name_template);
		$template->set('path_template', $path_template);
		$template->set('type_template', $type_template);
		$template->set('active_template', $active_template);

		// Start a new transaction, necessary for template activating and inserting
		PDOLib::getInstance()->beginTransaction();

		// Activate the template immediately if indicated
		if ($active_template)
			$template->setActiveTemplate($type_template);

		// Finally, insert the template in database
		$template->insert();

		// Commit transaction
		PDOLib::getInstance()->commit();

		return $template;
	}

	/**
	 * Edit a template
	 *
	 * @param int $id_template Template ID
	 * @param string $name_template Template name
	 * @param string $path_template Template path
	 * @param int $active_template Indicate if the template is immediately active
	 * @return TemplateModel Template edited
	 * @throws InvalidDataException Invalid template ID, name, path, type or active attribute
	 * @throws PDOException Database error when updating template
	 */
	public static function editTemplate($id_template, $name_template, $path_template, $active_template)
	{
		$template = new TemplateModel($id_template);
		$template->set('name_template', $name_template);
		$template->set('path_template', $path_template);
		$template->set('active_template', $active_template);

		// Start a new transaction, necessary for template activating and updating
		PDOLib::getInstance()->beginTransaction();

		// Activate the template if indicated
		if ($active_template)
			$template->setActiveTemplate($template->get('type_template'));

		// Finally update the template in database
		$template->update();

		// Commit transaction
		PDOLib::getInstance()->commit();

		return $template;
	}

	/**
	 * Disable all active template in a side
	 *
	 * @param string $side Side where we disable all active template
	 * @throws InvalidDataException Invalid side
	 * @throws PDOException Database error when disabling all templates
	 */
	protected static function disableAllTemplate($side)
	{
		static::checkSide($side);

		$connexion = PDOLib::getInstance();

		// Query to disable all template in the side indicated
		$query = "
		UPDATE
			`".DB_PREFIX."template`
		SET
			`active_template` = '0'
		WHERE
			`type_template` = ?";

		// Execute query
		$statement = $connexion->prepare($query);
		$statement->execute(array($side));

		// Clear active template cache variable
		if (static::existsActiveTemplate($side))
			unset(static::$activeTemplate[$side]);
	}

	/**
	 * Get the active template in a side
	 *
	 * @param string $side Side where we get the active template
	 * @return null|TemplateModel Corresponding TemplateModel or Null if it doesn't exist
	 * @throws InvalidDataException Invalid side
	 * @throws PDOException Database error when loading templates
	 */
	public static function getActiveTemplate($side)
	{
		static::checkSide($side);

		// Return the side active template or null if it doesn't exist
		if (static::existsActiveTemplate($side))
			return static::$activeTemplate[$side];
		else
			return null;
	}

	/**
	 * Indicate if an active template exists in the side indicated
	 *
	 * @param string $side Side concerned
	 * @return bool True if an active template exists, False otherwise
	 * @throws InvalidDataException Invalid side
	 * @throws PDOException Database error when loading templates
	 */
	public static function existsActiveTemplate($side)
	{
		static::checkSide($side);

		// Load active template for FrontEnd and BackEnd
		static::loadActiveTemplate();

		return (isset(static::$activeTemplate[$side]));
	}

	/**
	 * Get the path of active template in the side indicated
	 *
	 * @param string $side Side concerned
	 * @return string Path of the active template of the side
	 * @throws InvalidDataException Invalid side
	 * @throws PDOException Database error when loading templates
	 */
	public static function getActivePath($side)
	{
		static::checkSide($side);

		// Load active template for FrontEnd and BackEnd
		static::loadActiveTemplate();

		// Get template path from the side indicated
		return static::$activeTemplate[$side]->get('path_template');
	}

	/**
	 * Load all active templates in cache
	 *
	 * @throws PDOException Database error when loading templates
	 */
	public static function loadActiveTemplate()
	{
		if (!isset(static::$activeTemplate[TemplateSide::FRONTEND])
			OR !isset(static::$activeTemplate[TemplateSide::BACKEND]))
		{
			$connexion = PDOLib::getInstance();

			// Query to load active templates
			$query = "
			SELECT
				`id_template`,
				`name_template`,
				`path_template`,
				`type_template`,
				`active_template`
			FROM
				`".DB_PREFIX."template`
			WHERE
				`active_template` = '1'";
			$statement = $connexion->query($query);

			// Save each active template in the cache
			while ($row = $statement->fetch(PDO::FETCH_ASSOC))
				static::$activeTemplate[$row['type_template']] = new TemplateModel($row['id_template'], $row);
		}
	}

	/**
	 * Check side validity
	 *
	 * @param string $side Side to check
	 * @throws InvalidDataException Invalid side
	 */
	public static function checkSide($side)
	{
		if (!in_array($side, TemplateSide::getConstList()))
			throw new InvalidDataException(__METHOD__, "Side indicated is invalid.");
	}

	/**
	 * Get all templates in an array of TemplateModel
	 *
	 * @return array Array of TemplateModel representing all templates of the CMS
	 * @throws PDOException Database error when loading templates list
	 */
	public static function getTemplateList()
	{
		$connexion = PDOLib::getInstance();

		// Query to load all templates
		$query = "
			SELECT
				`id_template`,
				`name_template`,
				`path_template`,
				`type_template`,
				`active_template`
			FROM
				`".DB_PREFIX."template`";
		$statement = $connexion->query($query);

		// Template list as array
		$templateList = array();

		// Add each template to the list
		while ($row = $statement->fetch(PDO::FETCH_ASSOC))
		{
			// Create the TemplateModel corresponding
			$template = new TemplateModel($row['id_template'], $row);

			// Save it to the template list
			$templateList[] = $template;

			// Save the template loaded in cache if necessary
			if (($row['active_template'] == 1) AND !isset(static::$activeTemplate[$row['type_template']]))
				static::$activeTemplate[$row['type_template']] = $template;
		}

		return $templateList;
	}

	/**
	 * Get the TemplateModel of a specific template
	 *
	 * @param int $id_template ID of the template
	 * @return TemplateModel Corresponding TemplateModel
	 * @throws InvalidDataException Invalid template ID
	 * @throws PDOException Database error when loading template
	 */
	public static function getTemplate($id_template)
	{
		return new TemplateModel($id_template);
	}
}
