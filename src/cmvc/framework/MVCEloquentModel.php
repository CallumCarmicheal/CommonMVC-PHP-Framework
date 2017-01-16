<?php
/**
 * User: CallumCarmicheal
 * Date: 21/12/2016
 * Time: 15:22
 * Url:  https://github.com/CallumCarmicheal/CommonMVC-PHP-Framework
 */

namespace lib\CMVC\mvc;

use CommonMVC\Classes\Storage\Database;
use lib\CMVC\mvc\Eloquent\DatabaseCollection;
use lib\CMVC\mvc\Eloquent\DatabaseItem;

class MVCEloquentModel {
	
	protected static $table                 = "";
	protected static $columns               = [];
	protected static $columns_readonly      = [];
	protected static $columns_id            = "id";
	
	protected static $database              = "";
	
	protected static $useTimeColumns        = false;
	protected static $columns_Time_Created  = "date_created";
	protected static $columns_Time_Edited   = "date_lastedited";
	
	protected $columns_values               = [];
	protected $columns_changed              = [];
	
	public static $Database_DateTime_Format = "Y-m-d H:i:s";
	
	protected $exists = false;
	
	public function __SetDatabaseResults($results) {
		$this->columns_values = $results;
		$this->exists = true;
	}
	
	/**
	 * @param $all bool Saves all columns not just changed columns
	 */
	public function save($all = false) {
		// Load PDO
		$PDO = Database::GetPDO(static::$database);
		
		if (empty($this->columns_changed))
			return;
		
		/*
		 UPDATE table_name
		 SET column1=value, column2=value2,...
		 WHERE $columns_id=:ID
		 */
		
		$sql = "";
		$arg = [];
		
		if ($this->exists)
			 $this->generateUpdate($all, $sql, $arg);
		else $this->generateInsert($sql, $arg);
		
		/*/
			echo "\n\n\nSQL: $sql \nARG: ";
			print_r ($arg);
			echo "\n";
		//*/
		
		try {
			$statement = $PDO->prepare($sql);
			$statement->execute($arg);
		} catch (\Exception $ex) { Database::ThrowDatabaseFailedQuery($ex); }
		//echo "SQL: $sql";
		//die("");
		
		// Reset changed items
		$this->columns_changed = [];
		$this->exists = true;
	}
	
	private function generateInsert(&$sql, &$arg) {
		/*  INSERT INTO TABLE_NAME (column1, column2, column3,...columnN)]
			VALUES (value1, value2, value3,...valueN); */
		$table       = static::$table;
		$fmt         = "INSERT INTO $table (%s) VALUES (%s);";
		$arg         = [];
		$columns     = $this->columns_values;
		$strCols     = "";
		$strVals     = "";
		$colCtr      = 0;
		
		foreach ($columns as $col => $val) {
			
			if ($colCtr != 0) {
				$strCols .= ", ";
				$strVals .= ", ";
			}
			
			$vKey       = ":V". $colCtr;
			$strCols   .= "'". $col. "'";
			$strVals   .= $vKey;
			$arg[$vKey] = $val;
			
			$colCtr++;
		}
		
		$sql = sprintf($fmt, $strCols, $strVals);
	}
	
	private function generateUpdate($all, &$sql, &$arg) {
		$table = static::$table;
		$idCol = static::$columns_id;
		$idVal = $this->columns_values["id"];
		$fmt = "UPDATE $table SET %s WHERE $idCol = ". (is_int($this->columns_values[self::$columns_id]) ? "" : "BINARY "). ":ID";
		$arg = [":ID" => $idVal];
		$valueClause = "";
		$valCtr      = 0;
		
		if ($all)
			 $columns = static::$columns;
		else $columns = $this->columns_changed;
		
		foreach ($columns as $col) {
			if ($valCtr != 0)
				$valueClause .= ", ";
			
			$valueClause .= "'$col'=:$col";
			$arg[":$col"] = $this->columns_values[$col];
			
			$valCtr++;
		}
		
		$sql = sprintf($fmt, $valueClause);
	}
	
	/**
	 * Checks if the item exists in the database
	 *
	 * This is used when creating a new instance to be placed
	 * inside the database, it checks if it already exists by
	 * seeing if we have a ID attached to it.
	 * @return bool
	 */
	public function Exists() {
		return $this->exists;
	}
	
	/**
	 * Check if a id exists in the database
	 *
	 * @param $id
	 * @return bool
	 */
	public static function existsID($id)  { return static::find($id)->containsItems(); }
	
	/**
	 * Find a row by the table id column
	 *
	 * @param $id mixed
	 * @param bool $case_sensitive
	 * @return bool|DatabaseCollection|DatabaseItem
	 */
	public static function findByID($id, $case_sensitive = true) {
		return self::find($id, $case_sensitive, 1);
	}
	
	/**
	 * Get the first item in a query!
	 *
	 * @param $query mixed
	 * @param bool $case_sensitive
	 * @return bool|DatabaseCollection|DatabaseItem
	 */
	public static function first($query, $case_sensitive = true) {
		return self::find($query, $case_sensitive, 1);
	}
	
	/**
	 * Query the database
	 *
	 * Parameters:
	 *
	 * $query
	 * |- 2 or 3 value array
	 * |  |- [Column, Value]
	 * |  |- [Column, Glue, Value]
	 * |  |  Glue being the calculation, eg =, >, <, >=, <=
	 * |
	 * |-  Array of Arrays
	 * |   |- [ [C,V], [C,G,V]... ]
	 * |   |  C = Column, V = Value, G = Glue
	 *
	 * $case_sensitive
	 * |- States if the parameters have
	 * |  BINARY placed before them
	 * |  making them case sensitive
	 * |
	 * |  DEFAULT: TRUE
	 *
	 * $maxSize
	 * |- States the SQL limit size
	 * |
	 * |  DEFAULT: 100
	 *
	 * Example usage:
	 *
	 * SELECT * FROM table WHERE (column = blah);
	 * |- Example 1: find( ['column', 'blah'] );
	 * |- Example 2: find( ['column', '=', 'blah'];
	 *
	 * SELECT * FROM table WHERE (number >= 99);
	 * |- Example: find( ['number', '>=', 99] );
	 *
	 * SELECT * FROM table WHERE (column = blah and column2 = blah);
	 * |- Example 1: find( [ ['column', 'blah'], ['column2', 'blah'] ] )        // Case Sensitive
	 * |- Example 2: find( [ ['column', 'blah'], ['column2', 'blah'] ], false ) // Case Insensitive
	 *
	 * SELECT * FROM table WHERE (column = blah and column2 = blah) LIMIT 2;
	 * |- Example 1: find( [ ['column', 'blah'], ['column2', 'blah'] ],   true,  2 ) // Case Sensitive
	 * |- Example 2: find( [ ['column', 'blah'], ['column2', 'blah'] ],   false, 2 ) // Case Insensitive
	 * |- Example 3: find( [ ['column', 'blah'], ['column3', '>=', 33] ], false, 2 ) // Column >= Calculation
	 *
	 * SELECT * FROM table WHERE ... INNER JOIN ....
	 * |- Sorry but this does not currently exist.
	 * |  I will need to redesign how this function works
	 * |  and at the moment it works for my needs, so i recommend doing something like this
	 * |
	 * |  $user = User::find( ['username', 'blah'] );
	 * |  if ($user->isEmpty()) // Error handle
	 * |
	 * |  $user = $user->get();
	 * |  $uid = $user->id;
	 * |
	 * |  $paidOrders = Orders::find([ ['paid', '=', true], ['user_id', '=', $uid] ]);
	 * |  ..... etc etc...
	 *
	 * @param $query mixed
	 * @param $maxSize int Limit amount
	 * @param $case_sensitive bool Determines if the variables added are queried as BINARY
	 * @return DatabaseItem|DatabaseCollection|bool
	 */
	public static function find($query, $case_sensitive = true, $maxSize = 100) {
		// Get PDO Object
		$PDO   = Database::GetPDO(static::$database);
		$sql   = "";
		$binds = [];
		$table = static::$table;
		
		// If array then format = [column, glue, value]
		// EG:                    ['name', '=', 'callum']
		if (is_array($query)) {
			
			// findFirstOrFail ([['1', '=', 1], ['name', '!=', 'test']]
			if (is_array($query[0])) {
				$format      = "SELECT %s FROM `%s` WHERE ( %s ) LIMIT $maxSize;";
				$columns     = self::implodeAllColumns();
				$whereClause = ""; // Where clause
				$binds       = []; // PDO Binded Values
				$vInt        = 0;  // Keeps track of how many vars there are.
				
				foreach ($query as $queryClause) {
					$vWhere = ":V$vInt";
					$col    = $queryClause[0];
					$glue   = $queryClause[1];
					$value  = $queryClause[2];
					
					if ($vInt != 0)
						$whereClause .= " AND ";
					
					//                    col{glue}vWhere
					//                    name=:Val0
					if ($case_sensitive)
						 $whereClause .= "$col $glue BINARY $vWhere";
					else $whereClause .= "$col $glue $vWhere";
					
					$binds[$vWhere] = $value;
					$vInt++;
				}
				
				$sql = sprintf($format, $columns, static::$table, $whereClause);
			} else if (count($query) == 3) {
				// [0] = column
				// [1] = glue
				// [2] = value
				
				$col = $query[0];
				$glu = $query[1];
				$val = $query[2];
				
				$columns  = self::implodeAllColumns();
				$sql      = "SELECT $columns FROM $table WHERE $col $glu ". ($case_sensitive ? "BINARY" : ""). " :val LIMIT $maxSize;";
				$binds    = [':val' => $val];
			} else if (count($query) == 2) {
				// [0] = column
				// [1] = value
				// Assumes glue is '='
				
				$col = $query[0];
				$glu = '=';
				$val = $query[2];
				
				$columns  = self::implodeAllColumns();
				$sql      = "SELECT $columns FROM $table WHERE $col $glu ". ($case_sensitive ? "BINARY" : ""). " :val LIMIT $maxSize;";
			} else {
				return false;
			}
		} else {
			// ID = Value
			$columns = self::implodeAllColumns();
			$sql   = "SELECT $columns FROM $table WHERE id = :id LIMIT $maxSize;";
			$binds = [':id' => $query];
		}
		
		/* Debugging */ {
			/*/
			echo "\$sql: \t\t$sql\n";
			echo "\$binds: \t";
			var_dump ($binds);
			echo "\n";
			
			exit;
			//*/
		}
		
		$rowCount = 0;
		try {
			$result = $PDO->prepare($sql);
			$result->execute($binds);
			$rowCount = $result->rowCount();
		} catch (\Exception $ex) {
			Database::ThrowDatabaseFailedQuery($ex);
		}
		
		if ($rowCount == 0) {
			return DatabaseItem::__SearchFailed();
		} else if ($rowCount == 1) {
			$itm = new DatabaseItem();
			
			$me = new static();
			$me->__SetDatabaseResults($result->fetch());
			
			$itm->set($me);
			return $itm;
		} else {
			// We have a list
			$listOfDB = $result->fetchAll();
			$tmp      = [];
			
			foreach($listOfDB as $row) {
				$me = new static();
				$me->__SetDatabaseResults($row);
				array_push($tmp, $me);
			}
			
			$itm = new DatabaseCollection();
			$itm->set($tmp);
			return $itm;
		}
	}
	
	/**
	 * Returns table's row count
	 * @return int
	 */
	public function count() {
		$PDO   = Database::GetPDO(static::$database);
		
		$sql = "SELECT count(*) FROM `%s`;";
		$sql = sprintf($sql, static::$table);
		
		$result = $PDO->prepare($sql);
		$result->execute();
		return $result->fetchColumn();
	}
	
	/**
	 * Get the id for the current row
	 * @return mixed
	 */
	public function getID() {
		return $this->columns_values[static::$columns_id];
	}
	
	// MAGIC FUNCTIONS
	public function __set($name, $value) {
		if (in_array($name, static::$columns) &&
		   !in_array($name, static::$columns_readonly) &&
		   ($name != static::$columns_id)) {
			
			if (!in_array($name, $this->columns_changed))
				array_push($this->columns_changed, $name);
			
			$this->columns_values[$name] = $value;
		}
	}
	public function __get($name) {
		$inCols  = in_array ($name, static::$columns);
		$inReds  = in_array ($name, static::$columns_readonly);
		$isIdCol = $name == static::$columns_id;
		
		if ($inCols || $inReds || $isIdCol) {
			return $this->columns_values[$name];
		}
	}
	
	/**
	 * Copy current instance without pending changes
	 *
	 * Creates a copy of the current instance without copying the pending changes
	 * allowing for further modification without the worry that ->save() will upload
	 * changes that are stagnant or no longer needed
	 * @return static
	 */
	public function Copy() {
		$new = static::find($this->getID());
		return $new->get();
	}
	
	// Setup column querying
	protected static function implodeAllColumns($readonly = true) {
		$c  = self::implodeColumns(static::$columns);
		$rc = $readonly ? self::implodeColumns(static::$columns_readonly) : '';
		$dt = "";
		
		if (static::$useTimeColumns)
			$dt = " ". static::$columns_Time_Created.
				  ", ". static::$columns_Time_Edited;
		
		
		
		if (!empty($c))                  $res = $c;
		if (!empty($rc) && !empty($c))   $res .= ", $rc";
		else if (!empty($rc))            $res = $rc;
		if (!empty($res) && !empty($dt)) $res .= ", $dt";
		else if (!empty($dt))            $res = $dt;
		
		if (empty($res))
			 $res = static::$columns_id;
		else $res = static::$columns_id. ", ". $res;
		
		return $res;
	}
	protected static function implodeColumns($columns) {
		return implode (", ", $columns);
	}
}