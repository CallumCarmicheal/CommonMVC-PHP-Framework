<?php
/**
 * User: Callum Carmicheal
 * Date: 24/09/2016
 * Time: 03:07
 */

namespace CommonMVC\Classes\Storage;

use API\Controllers\Mvc\ErrorController;
use CommonMVC\MVC\MVCExecutor;
use CommonMVC\MVC\MVCHelper;

class Database {

	private static $PDOStorageName = "CMVC_CLASSES_STORAGE_DATABASE_PDO_OBJECT_STORE_";

	/**
	 * @return \PDO
	 * @throws \PDOException
	 */
	private static function setupPDO($db = "") {
		$ip  = CMVC_PRJ_STORAGE_DB_HOST;
		$un  = CMVC_PRJ_STORAGE_DB_USER;
		$pw  = CMVC_PRJ_STORAGE_DB_PASS;
		$cs  = CMVC_PRJ_STORAGE_DB_CHARSET;
		
		// If no custom database was selected
		// we load the one defined in the config
		//
		// (NOTE) This is used for custom databases
		//        from within the Eloquent models.
		if (empty($db))
			$db = CMVC_PRJ_STORAGE_DB_DB;
		
		$dsn = "mysql:host=$ip;dbname=$db;charset=$cs";

		$opt = [
			\PDO::ATTR_ERRMODE 				=> \PDO::ERRMODE_EXCEPTION,
			\PDO::ATTR_DEFAULT_FETCH_MODE 	=> \PDO::FETCH_ASSOC,
			\PDO::ATTR_EMULATE_PREPARES		=> false
		];

		try {
			$pdo = new \PDO($dsn, $un, $pw, $opt);
		} catch (\PDOException $ex) {
			self::ThrowDatabaseOfflineException($ex);
			return null;
		}

		return $pdo;
	}
	
	/**
	 * Run the Database MVC Exception controller, FailedConnection
	 *
	 * Call this when you have a failed connection
	 * @param $exception \PDOException
	 */
	public static function ThrowDatabaseOfflineException($exception) {
		/** @var \App\Controllers\Mvc\DatabaseController $ctrl */
		
		// Setup a MVCExecutor
		$exec = new MVCExecutor();
		
		// Get our context for the class
		// using the virtual path
		// NAMESPACE/Class/Action,
		//
		// You do not need a action
		// you may just leave a trailing slash to
		// indicate that "Database" is a controller
		// for example: Mvc/Database/
		$ctx = MVCHelper::ResolveVirtualPath(
			CMVC_PRJ_DIRECTORY_CONTROLLERS,
			CMVC_PRJ_NAMESPACE_CONTROLLERS,
			"Mvc/Database/FailedConnection" );
		
		// Get the controller and run the function
		// FailedConnection, then execute the result.
		// You have to manually execute the controller
		// because WebAccess is false and cannot be
		// executed through ExecuteController will
		// call Mvc/Access/WebAccessDisabled
		$ctrl = $exec->GetControllerFromContext($ctx);
		$res = $ctrl->FailedConnection($ex);
		$exec->ExecuteControllerResult($ctrl, $res, $ctx);
	}
	
	/**
	 * Run the Database MVC Exception controller, FailedQuery
	 *
	 * Call this when you have a failed query
	 * @param $exception \PDOException
	 */
	public static function ThrowDatabaseFailedQuery($exception) {
		/** @var \App\Controllers\Mvc\DatabaseController $ctrl */
		
		// Setup a MVCExecutor
		$exec = new MVCExecutor();
		
		// Get our context for the class
		// using the virtual path
		// NAMESPACE/Class/Action,
		//
		// You do not need a action
		// you may just leave a trailing slash to
		// indicate that "Database" is a controller
		// for example: Mvc/Database/
		$ctx = MVCHelper::ResolveVirtualPath(
			CMVC_PRJ_DIRECTORY_CONTROLLERS,
			CMVC_PRJ_NAMESPACE_CONTROLLERS,
			"Mvc/Database/FailedQuery" );
		
		// Get the controller and run the function
		// FailedConnection, then execute the result.
		// You have to manually execute the controller
		// because WebAccess is false and cannot be
		// executed through ExecuteController will
		// call Mvc/Access/WebAccessDisabled
		$ctrl = $exec->GetControllerFromContext($ctx);
		$res = $ctrl->FailedQuery($exception);
		$exec->ExecuteControllerResult($ctrl, $res, $ctx);
	}

	/**
	 * Create a PDO Object for the current database
	 * @param $db string
	 * @return \PDO
	 * @throws \PDOException
	 */
	public static function GetPDO($db = "") {
		// Store the pdo in globals so we don't keep recreating it
		// a way of efficiently caching

		if(isset($GLOBALS[self::$PDOStorageName. $db])
		   && get_class($GLOBALS[self::$PDOStorageName. $db]) == 'PDO'){

			return $GLOBALS[self::$PDOStorageName. $db];
		} else {
			$conn = self::setupPDO($db);
			$GLOBALS[self::$PDOStorageName. $db] = &$conn;
			return $conn;
		}
	}
	
	/**
	 * PDO Fetch
	 * @param $sql
	 * @param array $params
	 * @param string $db
	 * @return mixed
	 */
	public static function Fetch($sql, $params = array(), $db = "") {
		$db = self::GetPDO($db);
		try {
			$result = $db->prepare($sql);
			$result->execute($params);
			$rowCol = $result->fetch();
			return $rowCol;
		} catch(\PDOException $ex) {
			self::ThrowDatabaseFailedQuery($ex);
		}
	}
	
	/**
	 * PDO FetchAll
	 * @param $sql
	 * @param $rowCount
	 * @param array $params
	 * @param string $db
	 * @return array
	 */
	public static function FetchAll($sql, &$rowCount, $params = array(), $db = "") {
		$db = self::GetPDO($db);
		try {
			$result = $db->prepare($sql);
			$result->execute($params);
			$rowCount = $result->rowCount();
			$rowCol = $result->fetchAll();
			return $rowCol;
		} catch(\PDOException $ex) {
			self::ThrowDatabaseFailedQuery($ex);
		}
	}
	
	/**
	 * PDO Fetch Column
	 * @param $sql
	 * @param $col
	 * @param array $params
	 * @param string $db
	 * @return string
	 */
	public static function FetchColumn($sql, $col, $params = array(), $db = "") {
		$db = self::GetPDO($db);
		
		try {
			$result = $db->prepare($sql);
			$result->execute($params);
			$rowCol = $result->fetchColumn($col);
			return $rowCol;
		} catch(\PDOException $ex) {
			self::ThrowDatabaseFailedQuery($ex);
		}
	}
	
	/**
	 * PDO Execute
	 * @param $sql
	 * @param $error
	 * @param array $params
	 * @param string $db
	 * @return string
	 */
	public static function ExecuteSQL($sql, &$error, $params = array(), $db = "") {
		$db = self::GetPDO($db);
		
		try {
			$result = $db->prepare($sql);
			$result->execute($params);
			$error = false;
			return "";
		} catch(\PDOException $ex) {
			self::ThrowDatabaseFailedQuery($ex);
		}
	}

}