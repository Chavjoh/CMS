<?php
/**
 * BackEnd Modules Controller
 * Manage modules integrated to the CMS
 *
 * @version 1.0
 */

class AdminModulesController extends AbstractController
{
	/**
	 * Default method called by Dispatcher
	 *
	 * @param array $arguments Arguments passed by URL to the present Controller
	 */
	public function index(array $arguments)
	{
		parent::index($arguments);
		$this->skinPath = PATH_SKIN.TEMPLATE_BACKEND.DS;
		$this->templateFile = 'moduleList.tpl';

		$moduleList = ModuleModel::getModuleList();
		$moduleObject = array();

		foreach ($moduleList AS $module)
		{
			$moduleKey = $module->get('key_module');
			include($module->getPath().$moduleKey.'.php');
			$moduleObject[$moduleKey] = new $moduleKey($module);
		}

		$this->smarty->assign('moduleObject', $moduleObject);
		$this->smarty->assign('moduleList', $moduleList);
	}

	/**
	 * @see AbstractController::getPageName()
	 */
	public function getPageName()
	{
		return 'Modules - Administration - '.parent::getPageName();
	}
}

?>