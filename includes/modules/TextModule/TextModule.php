<?php
/**
 * Default Text Module
 *
 * @version 1.0
 */

class TextModule extends AbstractModule
{
	/**
	 * @see AbstractModule::getContent()
	 */
	public function getContent()
	{
		$settings = $this->settings->getData();

		if (isset($settings['content']))
			return $settings['content'];
		else
			return parent::getContent();
	}

	/**
	 * @see AbstractModule::getEditForm()
	 */
	public function getEditForm()
	{
		$this->templateFile = $this->module->getPath().'/templates/editForm.tpl';
		return parent::getEditForm();
	}

	/**
	 * @see AbstractModule::saveForm()
	 */
	public function saveForm()
	{
		$data = array(
			'content' => (isset($_POST['content'])) ? $_POST['content'] : ""
		);

		$this->settings->set('data_module_page', serialize($data));
		$this->settings->update();
	}
}

?>