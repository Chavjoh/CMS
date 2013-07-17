<?php
/**
 * BackEnd Modules Controller
 * Manage modules integrated to the CMS
 *
 * @version 1.0
 */

class AdminModulesController extends BackEndController
{
	/**
	 * Show module list
	 *
	 * @throws PDOException Database error when listening module
	 */
	public function index()
	{
		$this->templateFile = 'moduleList.tpl';

		// Get all module list
		$moduleList = ModuleModel::getModuleList();
		$moduleObject = array();

		foreach ($moduleList AS $module)
		{
			$moduleKey = $module->get('key_module');

			try
			{
				// Try to load the module class
				$module->loadModule();

				// Check if the module class was loaded
				if (!class_exists($moduleKey, FALSE))
					Logger::logMessage(new LoggerMessage("Class ($moduleKey) not found.", LoggerSeverity::ERROR));
				else
					$moduleObject[$moduleKey] = new $moduleKey($module);
			}
			catch (FileNotFoundException $e)
			{
				Logger::logMessage(new LoggerMessage($e->getMessage(), LoggerSeverity::ERROR));
			}
		}

		// Assign to the template the module list and the module object list
		$this->smarty->assign('moduleObject', $moduleObject);
		$this->smarty->assign('moduleList', $moduleList);
	}

	/**
	 * @see AbstractController::getPageName()
	 */
	public function getPageName()
	{
		return 'Modules - '.parent::getPageName();
	}
}

?>