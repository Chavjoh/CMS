<?php

/**
 * Layout Model
 *
 * @package CMS
 * @subpackage Model
 * @author Chavjoh
 * @since 1.0.0
 */
class LayoutModel extends AbstractModel
{
	/**
	 * Database column
	 *
	 * @var mixed
	 */
	protected $id_layout, $name_layout, $position_layout, $code_layout;

	/**
	 * @see AbstractModel::__construct()
	 */
	public function __construct($id = 0, $data = array())
	{
		$this->table = DB_PREFIX.'layout';
		parent::__construct($id, $data);
	}
}
