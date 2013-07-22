<?php

/**
 * Abstract version of the Model.
 *
 * Implements default functions for SQL (Add, Edit, Remove)
 *
 * @package CMS
 * @subpackage Model
 * @author Chavjoh
 * @since 1.0.0
 */
abstract class AbstractModel
{
	/**
	 * PDO Database Link
	 *
	 * @var PDO
	 */
	protected $connexion;

	/**
	 * Primary key name (column name in the database)
	 *
	 * @var string|array
	 */
	protected $primaryKey;

	/**
	 * Array of columns and type.
	 * Keys => Columns name,
	 * Values => Types
	 *
	 * @var array
	 */
	protected $bindArray = array();

	/**
	 * Table name
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * Constructor for creating new Model
	 *
	 * @param integer|array|NULL $id ID of the current element (optional, for creating)
	 * @param array $data Data of the current element (optional, for loading)
	 */
	public function __construct($id = null, $data = array())
	{
		$this->connexion = PDOLib::getInstance();
		
		if (BindArrayCache::exist($this->table))
		{
			$this->bindArray = BindArrayCache::get($this->table);
			$this->loadPrimaryKey();
		}
		else
		{
			$this->createBindArray();
			BindArrayCache::set($this->table, $this->bindArray);
			BindArrayCache::updateCacheFile();
		}

		// If the ID is null, we stop the constructor here
		if ($id == null)
			return;

		// Set ID
		$this->set($this->primaryKey, $id);

		// Load from data received by parameter if necessary
		$this->load($data);
	}

	/**
	 * Get value of the element
	 *
	 * @param string $field Name of the field
	 * @return array|mixed|NULL Value of the field for this element, or NULL if it doesn't exist
	 */
	public function get($field)
	{
		if (is_array($field))
		{
			$valueList = array();

			foreach ($field AS $key)
				$valueList[] = $this->get($key);

			return $valueList;
		}
		else
		{
			if (property_exists($this, $field))
				return $this->$field;
			else
				return null;
		}
	}

	/**
	 * Set value of a element
	 *
	 * @param string|array $field Name of the field
	 * @param string|array $value New value of the field
	 * @throws FatalErrorException Array size aren't the same
	 */
	public function set($field, $value)
	{
		if (is_array($field))
		{
			if (!is_array($value))
				throw new FatalErrorException(__METHOD__, "If field is an array, value must be an array too.");
			else if (count($field) != count($value))
				throw new FatalErrorException(__METHOD__, "Can't set with array (size aren't the same).");

			for ($i = 0; $i < count($field); $i++)
				$this->set($field[$i], $value[$i]);
		}
		else
		{
			if (property_exists($this, $field))
				$this->$field = $value;
		}
	}

	/**
	 * Get the PDO parameter type based on the type of the field
	 *
	 * @param string $type Type of the field
	 * @return int PDO Parameter type
	 */
	protected function getPdoParameterType($type)
	{
		if (strpos($type, 'int') !== false)
			return PDO::PARAM_INT;
		else
			return PDO::PARAM_STR;
	}

	/**
	 * Get the primary key(s) <strong>name</strong>
	 *
	 * @return string|array Name of the primary key(s)
	 */
	public function getPrimaryKey()
	{
		return $this->primaryKey;
	}

	/**
	 * Indicate if the table is an associative table
	 *
	 * @return bool True if it's associative, False otherwise
	 */
	public function isAssociative()
	{
		return (is_array($this->primaryKey) AND count($this->primaryKey) > 1);
	}

	/**
	 * Load primary keys from Bind Array
	 */
	protected function loadPrimaryKey()
	{
		// Reset primary key variable
		$this->primaryKey = "";

		// Load from Bind Array
		foreach ($this->bindArray AS $field => $type)
		{
			// Search for primary key type
			if (strpos($type, 'PRI') !== false)
			{
				if (empty($this->primaryKey))
					$this->primaryKey = $field;

				else if (is_array($this->primaryKey))
					$this->primaryKey[] = $field;

				// Otherwise, convert to array and add field
				else
					$this->primaryKey = array($this->primaryKey, $field);
			}
		}
	}

	/**
	 * Create a table with the corresponding fields and types in the database element
	 */
	protected function createBindArray()
	{
		// If the bind array aren't defined
		if (count($this->bindArray) == 0)
		{
			// Get MySQL table columns
			$result = $this->connexion->query("SHOW COLUMNS FROM ".$this->table);

			while ($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				// Type of the column (int, varchar, ...)
				$type = $row['Type'];

				// Add the key information if necessary (PRI, MUL, UNI, ...)
				if (!empty($row['Key']))
					$type .= " ".$row['Key'];

				// Store column type in the bind array
				$this->bindArray[$row['Field']] = $type;
			}

			// Load the primary key from bind array
			$this->loadPrimaryKey();
		}
	}

	/**
	 * Load this element with the data in parameter or by database
	 *
	 * @param array $data Data array (field => value) of the current element, NULL to load it with the database
	 * @param array $where Where condition array
	 * @throws InvalidDataException Field key not found, element not found in database
	 * @throws PDOException Database error when loading element
	 */
	protected function load($data = array(), $where = array())
	{
		// Loading element with $data
		if (count($data) > 0)
		{
			// For each one, set the value with the $data array
			foreach ($data AS $key => $value)
			{
				if (isset($value))
					$this->set($key, $value);
				//else
				//	throw new Exception("[".__METHOD__."] Data key ($key) not found during loading by parameter.");
			}
		}
		// Loading element with database
		else
		{
			// Get the key list from the bind array
			$keyList = array_keys($this->bindArray);

			// Add condition for primary key
			if (count($where) == 0)
				$this->buildPrimaryClause($where);

			// Create the where clause and get $whereParams
			$whereClause = $this->buildWhereClause($where, $whereParams);

			// Create the SQL select statement
			$statement = $this->connexion->prepare($this->buildSelectQuery($keyList, $whereClause));

			// If the statement is valid
			if ($statement)
			{
				// Set the value of the statement (only where parameters for select statement)
				foreach ($whereParams as $key => $value)
					$statement->bindValue(':'.$key, $value, $this->getPdoParameterType($this->bindArray[$key]));

				// Get the value of the element in the database with the statement query
				$statement->execute();

				if ($statement->rowCount() == 1)
				{
					// Get all values in an assoc array
					$result = $statement->fetch(PDO::FETCH_ASSOC);

					// Update the current element
					foreach ($keyList as $key)
						$this->set($key, $result[$key]);
				}
				else
					throw new InvalidDataException(__METHOD__, "Data associated with this key not found.");
			}
		}
	}

	/**
	 * Insert the current element in the database
	 *
	 * @return int Number of rows affected by the query
	 * @throws PDOException Database error when inserting element
	 */
	public function insert()
	{
		// Get the key list from the bind array
		$keyList = array_keys($this->bindArray);

		// Create the SQL insert statement
		$statement = $this->connexion->prepare($this->buildInsertQuery($keyList));

		// If the statement is valid
		if ($statement)
		{
			// Set the value of the statement (only where parameters for select statement)
			foreach ($keyList as $key)
				$statement->bindValue(':'.$key, $this->get($key), $this->getPdoParameterType($this->bindArray[$key]));

			// Insert the current element in the database
			if ($statement->execute())
			{
				if (!$this->isAssociative())
				{
					// Set the primary key after inserting to keep this instance up to date
					$this->set($this->primaryKey, $this->connexion->lastInsertId());
				}
			}

			return $statement->rowCount();
		}

		return 0;
	}

	/**
	 * Update the database with the current element values
	 *
	 * @param array $updateFields Array of fields to be updated (not value)
	 * @param array $where Array of where parameter (where parameter: Array(field, value, operator, conjunction)
	 * @return int Number of rows affected by the query
	 * @throws FatalErrorException If too many fields are indicated in parameter
	 * @throws PDOException Database error when updating element
	 */
	public function update($updateFields = array(), $where = array())
	{
		if (count($updateFields) > count($this->bindArray))
			throw new FatalErrorException(__METHOD__, "Too many fields indicated.");

		// Add condition for primary key
		if (count($where) == 0)
			$this->buildPrimaryClause($where);

		// Choose which key are used for the query
		if (count($updateFields) > 0)
			$keyList = $updateFields;
		else
			$keyList = array_keys($this->bindArray);

		// Create the SQL update statement
		$statement = $this->connexion->prepare($this->buildUpdateQuery($keyList, $this->buildWhereClause($where, $whereParams)));

		// If the statement is valid
		if ($statement)
		{
			// Set the value of the statement (updated fields)
			foreach ($keyList AS $field)
				$statement->bindValue(":new".$field, $this->get($field), $this->getPdoParameterType($this->bindArray[$field]));

			// Set the value of the statement (where fields)
			foreach ($whereParams AS $field => $value)
				$statement->bindValue(':'.$field, $value, $this->getPdoParameterType($this->bindArray[$field]));

			$statement->execute();
			return $statement->rowCount();
		}
		else
			return 0;
	}

	/**
	 * Delete the current element in the database
	 *
	 * @param array $where Array of where parameter (where parameter: Array(field, value, operator, conjunction)
	 * @return int Number of rows affected by the query
	 * @throws PDOException Database error when deleting element
	 */
	public function delete($where = array())
	{
		// Add condition for primary key
		if (count($where) == 0)
			$this->buildPrimaryClause($where);

		// Create the SQL delete statement
		$statement = $this->connexion->prepare($this->buildDeleteQuery($this->buildWhereClause($where, $whereParams)));

		// If the statement is valid
		if ($statement)
		{
			// Set the value of the statement (only where parameters for delete statement)
			foreach ($whereParams as $key => $value)
				$statement->bindValue(':'.$key, $value, $this->getPdoParameterType($this->bindArray[$key]));

			$statement->execute();
			return $statement->rowCount();
		}
		else
			return 0;
	}

	/**
	 * Build the primary clause used to build the where condition
	 *
	 * @param array $clause Clause for the primary key (unique or list)
	 * @param array|string|null $primaryKey Primary key (unique or list)
	 */
	protected function buildPrimaryClause(&$clause, $primaryKey = null)
	{
		if ($primaryKey == null)
			$primaryKey = $this->primaryKey;

		if (is_array($primaryKey))
		{
			foreach($primaryKey AS $key)
				$this->buildPrimaryClause($clause, $key);
		}
		else
		{
			$clause[] = array(
				'field' => $primaryKey,
				'operator' => '=',
				'value' => $this->get($primaryKey),
				'conjunction' => 'AND'
			);
		}

	}

	/**
	 * Build the where clause condition
	 *
	 * @param array $where Array of where parameter (where parameter: Array(field, value, operator, conjunction)
	 * @param string $bindParams Return the parameter field and value placed in the where clause
	 * @return string Where clause
	 * @throws FatalErrorException If the field or conjunction aren't specified
	 */
	protected function buildWhereClause($where, &$bindParams)
	{
		$bindParams = array();

		if (count($where) > 0)
		{
			// Start of the where clause
			$whereClause = ' WHERE ';

			// For each where parameter in the clause
			foreach ($where AS $index => $arrayParam)
			{
				// Check availability of field name, value and the operator of the present condition
				if (!isset($arrayParam['field']) OR !isset($arrayParam['value']) OR !isset($arrayParam['operator']))
					throw new FatalErrorException(__METHOD__, "Field, value or operator is missing in [where] parameter.");

				// Add conjunction if necessary
				if ($index > 0)
				{
					// Check availability of condition conjunction
					if (!isset($arrayParam['conjunction']))
						throw new FatalErrorException(__METHOD__, "Conjunction is missing in [where] parameter.");

					// Add the conjunction
					$whereClause .= ' '.$arrayParam['conjunction'].' ';
				}

				// Add the field name, value and the operator
				$whereClause .= $arrayParam['field'].' '.$arrayParam['operator'].' :'.$arrayParam['field'];

				// Add the field name and value to the bind parameter array
				$bindParams[$arrayParam['field']] = $arrayParam['value'];
			}

			return $whereClause;
		}

		return '';
	}

	/**
	 * Build the select query with the fields and where clause indicated
	 *
	 * @param array $fields Array of fields to select
	 * @param string $whereClause Where clause in a string
	 * @return string Select query
	 */
	protected function buildSelectQuery($fields, $whereClause)
	{
		return 'SELECT '.implode(",", $fields).' FROM '.$this->table.$whereClause;
	}

	/**
	 * Build the insert query with the fields indicated
	 *
	 * @param array $fields Array of fields to insert
	 * @return string Insert query
	 * @throws FatalErrorException Exception if the number of fields isn't upper than 0
	 */
	protected function buildInsertQuery($fields)
	{
		if (count($fields) <= 0)
			throw new FatalErrorException(__METHOD__, "There must be at least one field passed in the array.");

		// Clone array
		$parameters = $fields;

		// Add prefix for PDO
		for ($i = 0; $i < count($fields); $i++)
			$parameters[$i] = ' :'.$parameters[$i];

		return 'INSERT INTO '.$this->table.' ('.implode(",", $fields).') VALUES ('.implode(",", $parameters).')';
	}

	/**
	 * Build the update query with the fields and where clause indicated
	 *
	 * @param array $fields Array of fields to update
	 * @param string $whereClause Where clause in a string
	 * @return string Update query
	 * @throws FatalErrorException Exception if a field doesn't exists
	 */
	protected function buildUpdateQuery($fields, $whereClause = '')
	{
		// Start of the query
		$query = 'UPDATE '.$this->table.' SET ';

		// Calculate the basic size to know when add a separator
		$basicSize = strlen($query);

		foreach ($fields AS $key)
		{
			// Check the property existence
			if (!property_exists($this, $key))
				throw new FatalErrorException(__METHOD__, "Unknown field");

			// Add a separator if necessary
			if (strlen($query) > $basicSize)
				$query .= ', ';

			// Add element with the "new" prefix
			$query .= $key.' = :new'.$key;
		}

		// Return the query with the clause condition
		return $query.$whereClause;
	}

	/**
	 * Build the delete query with the where clause indicated
	 *
	 * @param string $whereClause Where clause in a string
	 * @return string Delete query
	 */
	protected function buildDeleteQuery($whereClause)
	{
		return 'DELETE FROM '.$this->table.$whereClause;
	}

}
