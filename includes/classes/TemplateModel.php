<?php
/**
 * Template Model
 * 
 * @version 1.0
 */

class TemplateModel
{
	const BackEnd = 'BACKEND';
	const FrontEnd = 'FRONTEND';

	/**
	 * List all ID and associated aliases in table
	 *
	 * @return PDOStatement Result of request
	 */
	public function listAll()
	{
		$PDO = PDOLib::getInstance();
		$statement = $PDO->prepare("SELECT * FROM cms_template");
		$statement->execute();
		return $statement;
	}

	/**
	 * Count all templates of a given side
	 *
	 * @param string $side Side enum from database
	 *
	 * @return int Number of templates
	 */
	public function getNumberBySide($side)
	{
		$PDO = PDOLib::getInstance();
		$statement = $PDO->prepare("SELECT id_template FROM cms_template WHERE type_template=?");
		$statement->execute(array($side));
		return $statement->rowCount();
	}

	/**
	 * Indicates wether an active template is set for given side or not
	 *
	 * @param string $side Side enum from database
	 *
	 * @return bool True if an active template is set for given side
	 */
	public function getActiveBySide($side)
	{
		$PDO = PDOLib::getInstance();
		$statement = $PDO->prepare("SELECT id_template FROM cms_template WHERE type_template=? AND active_template='1'");
		$statement->execute(array($side));
		return ($statement->rowCount()>0);
	}

	/**
	 * Set randomly an active template for given side
	 *
	 * @param string $side Side enum from database
	 */
	public function setActiveBySide($side)
	{
		$PDO = PDOLib::getInstance();
		$statement = $PDO->prepare("SELECT id_template FROM cms_template WHERE type_template=?");
		$statement->execute(array($side));
		$list = $statement->fetchAll();
		$id = $list[0]['id_template'];
		$statement = $PDO->prepare("UPDATE cms_template SET active_template='1' WHERE id_template=?");
		$statement->execute(array($id));
	}

	/**
	 * Insert a new record in table
	 *
	 * @param array $infos List of field=>value informations to add
	 */
	public function insertOne($infos)
	{
		$PDO = PDOLib::getInstance();
		$fields = '';
		$values = '';
		foreach ($infos as $key=>$value)
		{
			$fields .= $key . ',';
			$values .= '"' . $value . '",';
		}
		$fields = trim($fields, ',');
		$values = trim($values, ',');
		$statement = $PDO->prepare("INSERT INTO cms_template($fields) VALUES ($values)");
		$statement->execute();
	}

	/**
	 * Update informations on a template
	 *
	 * @param int $id ID of template to update
	 * @param array $infos List of field=>value informations to update
	 */
	public function updateOne($id, $infos)
	{
		$PDO = PDOLib::getInstance();
		$updates = '';
		foreach ($infos as $key => $value)
		{
			$updates .= $key . '="' . $value . '",';
		}
		$updates = trim($updates, ',');
		$statement = $PDO->prepare("UPDATE cms_template SET $updates WHERE id_template=?");
		$statement->execute(array($id));
	}

	/**
	 * Deactivate current template for side given
	 *
	 * @param string $side Side indication
	 */
	public function deactivate($side)
	{
		$PDO = PDOLib::getInstance();
		$statement = $PDO->prepare("UPDATE cms_template SET active_template='0' WHERE type_template=?");
		$statement->execute(array($side));
	}

	/**
	 * List all data for an ID-specified template
	 *
	 * @param int $id ID of template to gather data from
	 *
	 * @return PDOStatement Result of request
	 */
	public function getOne($id)
	{
		$PDO = PDOLib::getInstance();
		$statement = $PDO->prepare("SELECT * FROM cms_template WHERE id_template=?");
		$statement->execute(array($id));
		return $statement;
	}

	/**
	 * Delete a record from the table
	 *
	 * @param int $id ID of record to delete
	 */
	public function deleteOne($id)
	{
		$PDO = PDOLib::getInstance();
		$statement = $PDO->prepare("DELETE FROM cms_template WHERE id_template=?");
		$statement->execute(array($id));
	}

	/**
	 * Gather the active template path for given side
	 *
	 * @param string $side Side indication
	 *
	 * @return string Path from folder skins
	 */
	public static function getActivePath($side)
	{
		$PDO = PDOLib::getInstance();
		$statement = $PDO->prepare("SELECT path_template FROM cms_template WHERE type_template=? AND active_template='1'");
		$statement->execute(array($side));
		$list = $statement->fetchAll();
		return $list[0]['path_template'];
	}

}

?>