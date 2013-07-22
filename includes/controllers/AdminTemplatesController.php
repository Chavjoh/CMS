<?php

/**
 * BackEnd templates Controller
 *
 * Display templates of this CMS Engine.
 *
 * @package CMS
 * @subpackage Controller
 * @author Chavjoh
 * @since 1.0.0
 */
class AdminTemplatesController extends BackEndController
{
	/**
	 * Construct controller variable
	 *
	 * @see BackEndController::__construct()
	 */
	public function __construct(array $arguments)
	{
		parent::__construct($arguments);

		$this->urlController .= 'Templates/';
	}

	/**
	 * Template list
	 */
	public function index()
	{
		$this->templateFile = 'templateList.tpl';
		$this->smarty->assign('templateList', TemplateModel::getTemplateList());
	}

	/**
	 * Register a template
	 *
	 * @throws PDOException Database error when adding a template
	 */
	public function create()
	{
		// When a template is created
		if (count($_POST) > 0)
		{
			try
			{
				TemplateModel::createTemplate(
					(isset($_POST['name_template'])) ? $_POST['name_template'] : '',
					(isset($_POST['path_template'])) ? $_POST['path_template'] : '',
					(TemplateSide::exist($_POST['side_template'])) ? $_POST['side_template'] : TemplateSide::getDefault(),
					(isset($_POST['active_template'])) ? 1 : 0
				);
			}
			catch (InvalidDataException $e)
			{
				Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
			}

			// Redirect to the template list page
			$this->header[] = 'Location:'.$this->urlController;
		}

		// When we have to show the template to create a template
		else
		{
			$this->templateFile = 'templateForm.tpl';
			$this->smarty->assign('action', $this->urlController.'create/');
		}
	}

	/**
	 * Edit  template
	 *
	 * @throws PDOException Database error when editing template
	 * @throws ArgumentMissingException Missing template ID
	 */
	public function edit()
	{
		if (count($this->arguments) < 1)
			throw new ArgumentMissingException(__METHOD__, Language::get(__CLASS__.'.ArgumentMissingException.TemplateID'));

		// Get the template ID to edit
		$id_template = intval($this->arguments[0]);

		// When a template is edited
		if (count($_POST) > 0)
		{
			try
			{
				TemplateModel::editTemplate(
					$id_template,
					(isset($_POST['name_template'])) ? $_POST['name_template'] : '',
					(isset($_POST['path_template'])) ? $_POST['path_template'] : '',
					(isset($_POST['active_template'])) ? 1 : 0
				);
			}
			catch (InvalidDataException $e)
			{
				Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
			}

			// Redirect to the template list page
			$this->header[] = 'Location:'.$this->urlController;
		}

		// When we have to show the template to edit the template
		else
		{
			$this->templateFile = 'templateForm.tpl';
			$this->smarty->assign('id_template', $id_template);
			$this->smarty->assign('action', $this->urlController.'edit/'.$id_template);

			try
			{
				// Try to load template indicated
				$this->smarty->assign('template', TemplateModel::getTemplate($id_template));
			}
			catch (InvalidDataException $e)
			{
				Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
			}
		}
	}

	/**
	 * Delete a template
	 *
	 * @throws PDOException Database error when deleting template
	 * @throws ArgumentMissingException Missing template ID
	 */
	public function delete()
	{
		if (count($this->arguments) < 1)
			throw new ArgumentMissingException(__METHOD__, Language::get(__CLASS__.'.ArgumentMissingException.TemplateID'));

		// Get the template ID to delete
		$id_template = intval($this->arguments[0]);

		try
		{
			// Get template instance and delete it
			$template = TemplateModel::getTemplate($id_template);
			$template->delete();

			// Show a message to the final user
			Logger::logMessage(new LoggerMessage(Language::get(__CLASS__.'.DeleteSuccess'), LoggerSeverity::SUCCESS));
		}
		catch (InvalidDataException $e)
		{
			Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::WARNING));
		}

		// Redirect to the template list page
		$this->header[] = 'Location:'.$this->urlController;
	}

	/**
	 * @see AbstractController::getPageName()
	 */
	public function getPageName()
	{
		return Language::get(__CLASS__.'.PageTitle').' - '.parent::getPageName();
	}

	/**
	 * @see AbstractController::getMethodAvailable()
	 */
	public static function getMethodAvailable()
	{
		return array_merge(parent::getMethodAvailable(), array('edit', 'create', 'delete'));
	}
}
