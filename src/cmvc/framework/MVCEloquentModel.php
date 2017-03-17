<?php

/**
 *  CMVC  PHP | A hackable php mvc framework written
 *  FRAMEWORK | from scratch with love
 * -------------------------------------------------------
 *   _______  ____   _______                ___  __ _____
 *  / ___/  |/  | | / / ___/               / _ \/ // / _ \
 * / /__/ /|_/ /| |/ / /__                / ___/ _  / ___/
 * \___/_/  /_/ |___/\___/               /_/  /_//_/_/
 *    _______  ___   __  ________      ______  ___  __ __
 *   / __/ _ \/ _ | /  |/  / __| | /| / / __ \/ _ \/ //_/
 *  / _// , _/ __ |/ /|_/ / _/ | |/ |/ / /_/ / , _/ ,<
 * /_/ /_/|_/_/ |_/_/  /_/___/ |__/|__/\____/_/|_/_/|_|
 *
 * -------------------------------------------------------
 * Programmed by Callum Carmicheal
 *		<https://github.com/CallumCarmicheal>
 * GitHub Repository
 *		<https://github.com/CallumCarmicheal/CommonMVC-PHP-Framework>
 *
 * Contributors:
 *
 *
 * LICENSE: MIT License
 *      <http://www.opensource.org/licenses/mit-license.html>
 *
 * You cannot remove this header from any CMVC framework files
 * which are under the following directory cmvc->framework.
 * if you are unsure what directory that is, please refer to
 * GitHub:
 * <https://github.com/CallumCarmicheal/CommonMVC-PHP-Framework/tree/master/src>
 *
 * -------------------------------------------------------
 * MIT License
 *
 * Copyright (c) 2017 Callum Carmicheal
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace CommonMVC\Framework;

use CommonMVC\Framework\Storage\Database;
use CommonMVC\Framework\Eloquent\DatabaseCollection;
use CommonMVC\Framework\Eloquent\DatabaseItem;
use CommonMVC\Framework\Eloquent\SQLRAW;

class MVCEloquentModel implements \JsonSerializable  {
	
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
		$idVal = $this->columns_values[static::$columns_id];
		
		$fmt = "UPDATE $table SET %s WHERE $idCol = ";
		$fmt .= (is_int($this->columns_values[static::$columns_id]) ? "" : "BINARY ");
		$fmt .= ":ID";
		
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
			
			if ($v instanceof SQLRAW) {
				/** @var SQLRAW $raw */
				$raw = $v;
				$clause = "`$col`=". $raw;
			} else { $arg[":$col"] = $v; }
			
			$valueClause .= $clause;
			$valCtr++;
		}
		
		$sql = sprintf($fmt, $valueClause);
	}
	
	public function delete($are_you_sure = false) {
		if (!$are_you_sure)
			return false;
		
		$id  = static::getID();
		$idc = static::$columns_id ;
		$table = static::$table;
		
		$SQL = "DELETE FROM $table WHERE $idc=:ID;";
		$PARAM = [':ID' => $id];
		
		Database::ExecuteSQL($SQL, $PARAM, static::$database);
		return true;
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
		return self::find([static::$columns_id, $id], $case_sensitive, 1);
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
	 * |- DEFAULT: TRUE
	 *
	 * $maxSize
	 * |- States the SQL limit size
	 * |
	 * |- DEFAULT: 100
	 *
	 * $order
	 * |- Orders the column results in SQL
	 * |- This can be defined as [column, bool (TRUE = ASC)/string (ASC, DESC)]
	 * |                         /--------------------------------------------\
	 * |  or [array, ...] where array = .|.
	 * |
	 * |- Example:
	 * |
	 * |  $order = ['user_id', true]  // ORDER BY USER_ID ASC
	 * |  $order = ['user_id', 'ASC'] // ---------------------
	 * |
	 * |  $order = [['user_id',  true], ['post_id', 'DESC']]
	 * |  $order = [['user_id', 'ASC'], ['post_id', 'DESC']]
	 * |           ORDER BY user_id ASC, post_id DECS
	 * |
	 * |- DEFAULT: []/Null
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
	 * SELECT * FROM table WHERE (column = blah) ORDER BY some_int ASC
	 * |- Example 1: find( ['column', 'blah'], -1, ['some_int', true]  )
	 * |- Example 2: find( ['column', 'blah'], -1, ['some_int', 'ASC'] )
	 *
	 * SELECT * FROM table WHERE (column = blah) ORDER BY some_int DESC LIMIT 2;
	 * |- Example 1: find( ['column', 'blah'], 2, ['some_int', false]  )
	 * |- Example 2: find( ['column', 'blah'], 2, ['some_int', 'DESC'] )
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
	 * @param $order bool|array Array/Array of 2 items, defining ordering. 2nd Param BOOL. TRUE = ASC/A, FALSE = DESC/D
	 * @return DatabaseItem|DatabaseCollection|bool
	 */
	public static function find($query, $case_sensitive = true, $maxSize = -1, $order = false) {
		// Get PDO Object
		$PDO   = Database::GetPDO(static::$database);
		$sql   = "";
		$binds = [];
		$table = static::$table;
		
		$orderStatement = static::compileOrderStatement($order);;
		
		// If array then format = [column, glue, value]
		// EG:                    ['name', '=', 'callum']
		if (is_array($query)) {
			
			// findFirstOrFail ([['1', '=', 1], ['name', '!=', 'test']]
			if (is_array($query[0])) {
				$format         = "SELECT %s FROM `%s` WHERE ( %s ) %s %s";
				$limitStatement = "";
				
				if ($maxSize != -1)
					$limitStatement .= " LIMIT $maxSize;";
				
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
					if ($value instanceof SQLRAW) {
						$whereClause .= "$col $glue $value";
					}
					
					// Normal Value
					else {
						//                    col{glue}vWhere
						//                    name=:Val0
						if ($case_sensitive) {
							if (!is_int($value) || !is_bool($value) || !is_string($value))
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
				
				$sql = sprintf($format, $columns, static::$table, $whereClause, $orderStatement, $limitStatement);
			} else if (count($query) == 3) {
				// [0] = column
				// [1] = glue
				// [2] = value
				
				$col = $query[0];
				$glu = $query[1];
				$val = $query[2];
				
				$columns  = self::implodeAllColumns();
				$sql      = "SELECT $columns FROM $table WHERE $col $glu ";
				
				if ($val instanceof SQLRAW) {
					/** @var SQLRAW $stmt */
					$stmt    = $val;
					$sql    .= $stmt->SQL;
				} else {
					$sql    .= ($case_sensitive ? (!is_string($val) ? "" : "BINARY ") : ""). ":V0_1";
					$binds   = [':V0_1' => $val];
				}
				
				$sql .= " ". $orderStatement. " ";

				if ($maxSize != -1) $sql .= " LIMIT $maxSize;";
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
				if ($val instanceof SQLRAW) {
					/** @var SQLRAW $stmt */
					$stmt = $val;
					$sql .= $stmt->SQL;
				}
				
				// The query is a value
				else {
					$sql .= ($case_sensitive ? (!is_string($val) ? "" : "BINARY ") : ""). ":V0_3";
					$binds    = [':V0_3' => $val];
				}
				
				$sql .= " ". $orderStatement;
				if ($maxSize != -1) $sql .= " LIMIT $maxSize;";
				else $sql .= ';';
			} else { return false; }
		}
		
		// $query = ID
		else {
			// ID = Value
			
			$col_id = static::$columns_id;
			$columns = self::implodeAllColumns();
			
			$sql   = "SELECT $columns FROM `$table` WHERE $col_id = :id $orderStatement";
			
			if ($maxSize != -1) $sql .= " LIMIT $maxSize;";
			else $sql .= ';';

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
			/*/
			echo $ex->getMessage();
			
			echo "<pre>";
			echo "\$sql: \t\t$sql\n";
			echo "\$binds: \t";
			var_dump($binds);
			echo "\n";
			exit;
			//*/
			
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
	 * Get every row in the table
	 *
	 * Set $maxSize to -1 for unlimited
	 * @param int $maxSize
	 * @param int $offset
	 * @param bool $order
	 * @return DatabaseCollection|DatabaseItem
	 */
	public static function all($maxSize = -1, $offset=0, $order = false) {
		$PDO            = Database::GetPDO(static::$database);
		$orderStatement = static::compileOrderStatement($order);
		
		$sCols          = static::implodeAllColumns(true);
		$sTable         = static::$table;
		$sOffset        = "OFFSET ". ($offset == 0 ? "0" : $offset);
		$sLimit         = ($maxSize == -1 ? "" : "LIMIT $maxSize $sOffset");
		$sQuery         = "SELECT $sCols FROM $sTable $orderStatement $sLimit";
		
		try {
			$result = $PDO->prepare($sQuery);
			$result->execute();
			$rowCount = $result->rowCount();
		} catch (\PDOException $ex) { Database::ThrowDatabaseFailedQuery($ex); }
		
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
	public static function count() {
		$PDO   = Database::GetPDO(static::$database);
		
		$sql = "SELECT count(*) FROM `%s`;";
		$sql = sprintf($sql, static::$table);
		
		try {
			$result = $PDO->prepare($sql);
			$result->execute();
			return $result->fetchColumn();
		} catch (\PDOException $ex) {
			Database::ThrowDatabaseFailedQuery($ex);
		}
		
		return -1;
	}
	
	
	/**
	 * Returns a count from a query
	 * @param $query
	 * @param $case_sensitive
	 * @return int
	 */
	public static function countWhere($query, $case_sensitive = true) {
		// Get PDO Object
		$PDO   = Database::GetPDO(static::$database);
		$sql   = "";
		$binds = [];
		$table  = static::$table;
		$col_id = static::$columns_id;
		
		// If array then format = [column, glue, value]
		// EG:                    ['name', '=', 'callum']
		if (is_array($query)) {
			
			// findFirstOrFail ([['1', '=', 1], ['name', '!=', 'test']]
			if (is_array($query[0])) {
				$format      = "SELECT COUNT(%s) FROM `%s` WHERE ( %s )";
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
					if ($value instanceof SQLRAW) {
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
				
				$sql = sprintf($format, $col_id, static::$table, $whereClause);
			} else if (count($query) == 3) {
				// [0] = column
				// [1] = glue
				// [2] = value
				
				$col = $query[0];
				$glu = $query[1];
				$val = $query[2];
				
				$sql      = "SELECT COUNT($col_id) FROM $table WHERE $col $glu ";
				
				if ($val instanceof SQLRAW) {
					/** @var SQLRAW $stmt */
					$stmt    = $val;
					$sql    .= $stmt->SQL;
				} else {
					$sql    .= ($case_sensitive ? "BINARY " : ""). ":V0_1";
					$binds   = [':V0_1' => $val];
				}
			} else if (count($query) == 2) {
				// [0] = column
				// [1] = value
				// Assumes glue is '='
				
				$col = $query[0];
				$glu = '=';
				$val = $query[1];
				
				$columns  = self::implodeAllColumns();
				$sql      = "SELECT COUNT($col_id) FROM `$table` WHERE $col $glu ";
				
				// Check if the query is a SQL Raw statement
				if ($val instanceof SQLRAW) {
					/** @var SQLRAW $stmt */
					$stmt = $val;
					$sql .= $stmt->SQL;
				}
				
				// The query is a value
				else {
					$sql .= ($case_sensitive ? "BINARY " : ""). ":V0_2";
					$binds    = [':V0_2' => $val];
				}
			} else { return false; }
		}
		
		// $query = ID
		else {
			// ID = Value
			$sql   = "SELECT COUNT($col_id) FROM `$table` WHERE $col_id = :id";
			$binds = [':id' => $query];
		}
		
		try {
			$result = $PDO->prepare($sql);
			$result->execute($binds);
			return $result->fetchColumn();
		} catch (\PDOException $ex) {
			Database::ThrowDatabaseFailedQuery($ex);
		}
		
		return -1;
	}
	
	/**
	 * Get the id for the current row
	 * @return mixed
	 */
	public function getID() {
		return $this->columns_values[static::$columns_id];
	}
	
	/**
	 * Returns the current table
	 * @return string
	 */
	public function getTable()      { return static::$table; }
	
	/**
	 * Returns the database
	 * @return string
	 */
	public function getDatabase()   { return static::$database; }
	
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
		
		if ($inCols || $inReds || $isIdCol)
			return $this->columns_values[$name];
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
	
	protected static function compileOrderStatement($order) {
		$orderStatement = "";
		
		if ($order != false && is_array($order)) {
			if ( count($order) == 2
		      && !is_array($order[0])
		      && !is_array($order[1])) {
				
				$col = $order[0];
				$dir = $order[1];
				
				if (is_string($dir)) {
					$dir = mb_strtolower($dir);
					
					if ($dir == "asc"|| $dir == "a")
						$dir = "ASC";
					else                $dir = "DESC";
				} else {
					if ($dir) $dir = "ASC";
					else      $dir = "DESC";
				}
				
				$orderStatement = "ORDER BY `". $col. "` ". $dir;
				return $orderStatement;
			}
			
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
						
						if ($dir == "asc"|| $dir == "a")
							$dir = "ASC";
						else                $dir = "DESC";
					} else {
						if ($dir) $dir = "ASC";
						else      $dir = "DESC";
					}
					
					$orderStatement = "ORDER BY `". $col. "` ". $dir;
					
					if ($cnt_cur != $cnt_max) {
						$orderStatement .= ', ';
					}
				}
				
				$cnt_cur++;
			}
		}
		
		return $orderStatement;
	}
	
	public function jsonSerialize() {
		return $this->columns_values;
	}
	
	public function __toString() {
		return json_encode(self::jsonSerialize());
	}
}