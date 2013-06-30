<?php
/**
 * Highlight Module
 *
 * Allows you to highlight elements composed of a title, an image and a text description.
 *
 * @version 1.0
 */

class HighlightModule extends AbstractModule
{
	/**
	 * @see AbstractModule::getContent()
	 */
	public function getContent()
	{
		$this->templateFile = $this->module->getPath().'/templates/highlightElement.tpl';

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
			'image' => (isset($_POST['image'])) ? $_POST['image'] : "",
			'title' => (isset($_POST['title'])) ? $_POST['title'] : "",
			'content' => (isset($_POST['content'])) ? $_POST['content'] : ""
		);

		$this->settings->set('data_module_page', serialize($data));
		$this->settings->update();
	}
}

?>