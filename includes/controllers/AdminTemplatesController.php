<?php
/**
 * BackEnd templates Controller
 * Display templates of this CMS Engine.
 *
 * @version 1.0
 */

class AdminTemplatesController extends AbstractController
{
	/**
	 * Default method called by Dispatcher
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 */
	public function index(array $arguments)
	{
		// General template calls
		parent::index($arguments);
		$this->skinPath = PATH_SKIN.TEMPLATE_BACKEND.DS;
		$this->templateFile = 'templateList.tpl';

		// Load pages list
		$templateModel = new TemplateModel();
		$this->smarty->assign('list', $templateModel->listAll()->fetchAll());
	}

	/**
	 * Action for editing template settings
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 */
	public function edit(array $arguments)
	{
		// General template calls
		parent::index($arguments);
		$this->skinPath = PATH_SKIN.TEMPLATE_BACKEND.DS;
		$this->smarty->assign('action', Server::getBaseUrl() . 'admin/Templates/edit');
		$this->templateFile = 'templateForm.tpl';

		// Page model instanciation
		$templateModel = new TemplateModel();

		// Database write sequence if POST data have been sent
		if (isset($_POST['name']))
		{
			$activeImmediately = isset($_POST['active']);

			// Deactivate current template for specified side
			if ($activeImmediately)
			{
				$templateModel->deactivate(Security::in($_POST['side']));
			}

			$updates = array('name_template' => Security::in($_POST['name']),
							 'path_template' => Security::in($_POST['path']),
							 'type_template' => Security::in($_POST['side']),
							 'active_template' => $activeImmediately);
			$id = Security::in($_POST['id']);
			$templateModel->updateOne($id, $updates);

			if (!$templateModel->getActiveBySide(Security::in($_POST['side'])))
			{
				$templateModel->setActiveBySide(Security::in($_POST['side']));
			}

			$this->header[] = "Location:" . Server::getBaseUrl() . 'admin/Templates/index';
		}

		// Load informations on template if POST data have not been sent
		else
		{
			// Load informations on desired page
			$id = $arguments[0];
			$result = $templateModel->getOne($id)->fetchAll();
			$this->smarty->assign('formId', $result[0]['id_template']);
			$this->smarty->assign('formName', $result[0]['name_template']);
			$this->smarty->assign('formPath', $result[0]['path_template']);
			$this->smarty->assign('formSide', $result[0]['type_template']);
			$this->smarty->assign('formActive', $result[0]['active_template']);
		}
	}

	/**
	 * Action for creating new template
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 */
	public function create(array $arguments)
	{
		// General template calls
		parent::index($arguments);
		$this->skinPath = PATH_SKIN.TEMPLATE_BACKEND.DS;
		$this->smarty->assign('action', Server::getBaseUrl() . 'admin/Templates/create');
		$this->templateFile = 'templateForm.tpl';

		// Page model instanciation
		$templateModel = new TemplateModel();

		// Database write sequence if POST data have been sent
		if (isset($_POST['name']))
		{
			$activeImmediately = isset($_POST['active']);

			// Deactivate current template for specified side
			if ($activeImmediately)
			{
				$templateModel->deactivate(Security::in($_POST['side']));
			}

			// Add new template
			$values = array('name_template' => Security::in($_POST['name']),
							'path_template' => Security::in($_POST['path']),
							'type_template' => Security::in($_POST['side']),
							'active_template' => $activeImmediately);
			$templateModel->insertOne($values);

			$this->header[] = "Location:" . Server::getBaseUrl() . 'admin/Templates/index';
		}
	}

	/**
	 * Action for deleting a page
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 */
	public function delete(array $arguments)
	{
		// General template calls
		parent::index($arguments);
		$this->skinPath = PATH_SKIN.TEMPLATE_BACKEND.DS;
		$this->templateFile = 'templateList.tpl';

		// Database write sequence
		$templateModel = new TemplateModel();
		$id = $arguments[0];

		// Check that the deleted template is not the last of its side
		$current = $templateModel->getOne($id)->fetchAll();
		if ($templateModel->getNumberBySide($current[0]['type_template']) > 1)
		{
			$templateModel->deleteOne($id);
			if (!$templateModel->getActiveBySide($current[0]['type_template']))
			{
				$templateModel->setActiveBySide($current[0]['type_template']);
			}
		}

		$this->header[] = "Location:" . Server::getBaseUrl() . 'admin/Templates/index';
	}

	/**
	 * Internal call for template activation verification (at least a template must be active on each side)
	 */
	private function checkActiveTemplates()
	{
		// Page model instanciation
		$templateModel = new TemplateModel();


	}

	/**
	 * @see AbstractController::getPageName()
	 */
	public function getPageName()
	{
		return 'Templates - Administration - '.parent::getPageName();
	}

	/**
	 * @see AbstractController::getMethodAvailable()
	 */
	public static function getMethodAvailable()
	{
		return array_merge(parent::getMethodAvailable(), array('edit','create','delete'));
	}
}

?>