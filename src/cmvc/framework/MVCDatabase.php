<?php
/**
 * User: Callum Carmicheal
 * Date: 24/09/2016
 * Time: 03:07
 * Url:  https://github.com/CallumCarmicheal/CommonMVC-PHP-Framework
 */

namespace CommonMVC\Classes\Storage;

	use API\Controllers\Mvc\ErrorController;
	use CommonMVC\MVC\MVCExecutor;
	use CommonMVC\MVC\MVCHelper;
	
	class Database {
		private static $PDOStorageName = "CMVC_CLASSES_STORAGE_DATABASE_PDO_OBJECT_STORE";

		/**
		 * @return \PDO
		 * @throws \PDOException
		 */
		private static function setupPDO($db) {
			$ip = CMVC_PRJ_STORAGE_DB_HOST;
			$un = CMVC_PRJ_STORAGE_DB_USER;
			$pw = CMVC_PRJ_STORAGE_DB_PASS;
			$cs = CMVC_PRJ_STORAGE_DB_CHARSET;
			
			if (empty($database))
				 $db = $database;
			else $db = CMVC_PRJ_STORAGE_DB_DB;

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
		
		public static function ThrowDatabaseOfflineException($ex) {
			ob_get_clean();
			
			echo "Failed to connect to database";
			if (CMVC_PRJ_STORAGE_DB_DEBUG) 
				echo ": Exception - ". $ex->getMessage();
			
			exit;
		}
		
		public static function ThrowDatabaseFailedQuery($ex) {
			ob_get_clean();
			
			echo "Failed to run database query";
			if (CMVC_PRJ_STORAGE_DB_DEBUG) 
				echo ": Exception - ". $ex->getMessage();
			
			exit;
		}

		/**
		 * Create a PDO Object for the current database
		 * @return \PDO
		 * @throws \PDOException
		 */
		public static function GetPDO($db = "") {
			// Store the pdo in globals so we don't keep recreating it
			// a way of efficiently caching
			
			if (!empty($db))
				$db = "_".$db;
			
			if(isset($GLOBALS[self::$PDOStorageName. $db])
			   && get_class($GLOBALS[self::$PDOStorageName. $db]) == 'PDO'){

				return $GLOBALS[self::$PDOStorageName. $db];
			} else {
				$conn = self::setupPDO($db);
				$GLOBALS[self::$PDOStorageName. $db] = &$conn;
				return $conn;
			}
		}

	}