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
use lib\CMVC\mvc\Eloquent\SQLRAW;

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
			echo "<pre>SQL: $sql \nARG: ";
			print_r ($arg);
			echo "\n";
			exit;
		//*/
		
		try {
			$statement = $PDO->prepare($sql);
			$statement->execute($arg);
		} catch (\Exception $ex) { Database::ThrowDatabaseFailedQuery($ex); }
		//echo "SQL: $sql";
		//die("");
		
		// Reset changed items
		$this->columns_changed = [];
		$this->columns_values[static::$columns_id] = $PDO->lastInsertId();
		$this->exists = true;
		
		// TODO: Update values from db
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
			$strCols   .= "`". $col. "`";
			
			if (is_a($val, SQLRAW::class)) {
				$strVals .= $val;
			} else {
				$strVals   .= $vKey;
				$arg[$vKey] = $val;
			}
			
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
			
			$clause = "`$col`=:$col";
			
			$v = $this->columns_values[$col];
			
			if (is_a($v, SQLRAW::class)) {
				/** @var SQLRAW $raw */
				$raw = $v;
				$clause = "`$col`=". $raw;
			} else { $arg[":$col"] = $v; }
			
			$valueClause .= $clause;
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
	public static function findByID($id, $case_sensitive = false) {
		return self::find(['id', $id], $case_sensitive, 1);
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
	 * @param $order bool|array Array/Array of 2 items, defining ordering. 2nd Param BOOL. TRUE = ASC, FALSE = DESC
	 * @return DatabaseItem|DatabaseCollection|bool
	 */
	public static function find($query, $case_sensitive = true, $maxSize = 100, $order = false) {
		// Get PDO Object
		$PDO   = Database::GetPDO(static::$database);
		$sql   = "";
		$binds = [];
		$table = static::$table;
		
		$orderStatement = "";
		
		if ($order != false && is_array($order)) {
			$tmpArray = $order;
			if (!is_array($order)) {
				$tmpArray = [];
				$tmpArray[] = $order;
			}
			
			
			$cnt_cur = 1;
			$cnt_max = count($tmpArray);
			foreach($tmpArray as $order) {
				$cnt = count($order);
				
				if ($cnt == 2) {
					$col = $order[0];
					$dir = $order[1];
					
					if (is_string($dir)) {
						$dir = mb_strtolower($dir);
						
						if ($dir == "asc")  $dir = "ASC";
						else                $dir = "DESC";
					} else {
						if ($dir) $dir = "ASC";
						else      $dir = "DESC";
					}
					
					$orderStatement = "SORT BY `". $col. "` ". $dir;
					
					if ($cnt_cur != $cnt_max) {
						$orderStatement .= ', ';
					}
				}
				
				$cnt_cur++;
			}
		}
		
		// If array then format = [column, glue, value]
		// EG:                    ['name', '=', 'callum']
		if (is_array($query)) {
			
			// findFirstOrFail ([['1', '=', 1], ['name', '!=', 'test']]
			if (is_array($query[0])) {
				$format      = "SELECT %s FROM `%s` WHERE ( %s ) %s LIMIT $maxSize;";
				$columns     = self::implodeAllColumns();
				$whereClause = ""; // Where clause
				$binds       = []; // PDO Binded Values
				$vInt        = 0;  // Keeps track of how many vars there are.
				
				foreach ($query as $queryClause) {
					$cnt = count($queryClause);
					
					$vWhere = ":V$vInt";
					$col    = $queryClause[0];
					$glue   = $cnt == 3 ? $queryClause[1] : '=';
					$value  = $cnt == 3 ? $queryClause[2] : $queryClause[1];
					
					if ($vInt != 0)
						$whereClause .= " AND ";
					
					// Raw SQL statement
					if (is_a($value, SQLRAW::class)) {
						$whereClause .= "$col $glue $value";
					}
					
					// Normal Value
					else {
						//                    col{glue}vWhere
						//                    name=:Val0
						if ($case_sensitive) {
							if (!is_int($value))
								$whereClause .= "$col $glue BINARY $vWhere";
							// BINARY DOES NOT WORK ON INT
							else $whereClause .= "$col $glue $vWhere";
						} else {
							$whereClause .= "$col $glue $vWhere";
						}
						
						$binds[$vWhere] = $value;
					}
					
					$vInt++;
				}
				
				$sql = sprintf($format, $columns, static::$table, $whereClause, $orderStatement);
			} else if (count($query) == 3) {
				// [0] = column
				// [1] = glue
				// [2] = value
				
				$col = $query[0];
				$glu = $query[1];
				$val = $query[2];
				
				$columns  = self::implodeAllColumns();
				$sql      = "SELECT $columns FROM $table WHERE $col $glu ";
				
				if (is_a($val, SQLRAW::class)) {
					/** @var SQLRAW $stmt */
					$stmt    = $val;
					$sql    .= $stmt->SQL;
				} else {
					$sql    .= ($case_sensitive ? "BINARY " : ""). ":V0_1";
					$binds   = [':V0_1' => $val];
				}
				
				$sql .= " ". $orderStatement. " ";
				$sql .= " LIMIT $maxSize;";
			} else if (count($query) == 2) {
				// [0] = column
				// [1] = value
				// Assumes glue is '='
				
				$col = $query[0];
				$glu = '=';
				$val = $query[1];
				
				$columns  = self::implodeAllColumns();
				$sql      = "SELECT $columns FROM `$table` WHERE $col $glu ";
				
				// Check if the query is a SQL Raw statement
				if (is_a($val, SQLRAW::class)) {
					/** @var SQLRAW $stmt */
					$stmt = $val;
					$sql .= $stmt->SQL;
				}
				
				// The query is a value
				else {
					$sql .= ($case_sensitive ? "BINARY " : ""). ":V0_2";
					$binds    = [':V0_2' => $val];
				}
				
				$sql .= " ". $orderStatement. " ";
				$sql .= " LIMIT $maxSize;";
			} else { return false; }
		}
		
		// $query = ID
		else {
			// ID = Value
			
			$col_id = static::$columns_id;
			$columns = self::implodeAllColumns();
			$sql   = "SELECT $columns FROM `$table` WHERE $col_id = :id $orderStatement LIMIT $maxSize;";
			$binds = [':id' => $query];
		}
		
		/* Debugging */ {
			
			/*/
			if (static::$table == "users_timed") {
				echo "<pre>";
				echo "\$sql: \t\t$sql\n";
				echo "\$binds: \t";
				var_dump($binds);
				echo "\n";
				exit;
			}
			//*/
			
		}
		
		try {
			/*
			if (is_array($binds)) {
				echo "ARRAY\n";
				var_dump($binds);
				exit;
			} */
			
			$result = $PDO->prepare($sql);
			$result->execute($binds);
			$rowCount = $result->rowCount();
		} catch (\PDOException $ex) {
			
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