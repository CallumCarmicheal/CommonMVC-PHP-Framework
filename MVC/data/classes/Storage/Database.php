<?php
/**
 * User: Callum Carmicheal
 * Date: 24/09/2016
 * Time: 03:07
 */

namespace CommonMVC\Classes\Storage;


	class Database {

		private static $PDOStorageName = "CMVC_CLASSES_STORAGE_DATABASE_PDO_OBJECT_STORE";

		/**
		 * @return \PDO
		 * @throws \PDOException
		 */
		private static function setupPDO() {
			$ip = CMVC_PRJ_STORAGE_DB_HOST;
			$un = CMVC_PRJ_STORAGE_DB_USER;
			$pw = CMVC_PRJ_STORAGE_DB_PASS;
			$db = CMVC_PRJ_STORAGE_DB_DB;
			$cs = CMVC_PRJ_STORAGE_DB_CHARSET;

			$dsn = "mysql:host=$ip;dbname=$db;charset=$cs";

			$opt = [
				\PDO::ATTR_ERRMODE 				=> \PDO::ERRMODE_EXCEPTION,
				\PDO::ATTR_DEFAULT_FETCH_MODE 	=> \PDO::FETCH_ASSOC,
				\PDO::ATTR_EMULATE_PREPARES		=> false
			];

			try {
				$pdo = new \PDO($dsn, $un, $pw, $opt);
			} catch (\PDOException $ex) {
				ob_get_clean();
				die("Failed to connect to the database (Storage\\Database::setupPDO)");
			}

			return $pdo;
		}

		/**
		 * Create a PDO Object for the current database
		 * @return \PDO
		 * @throws \PDOException
		 */
		public static function GetPDO() {
			// Store the pdo in globals so we don't keep recreating it
			// a way of efficiently caching

			if(isset($GLOBALS[self::$PDOStorageName])
			   && get_class($GLOBALS[self::$PDOStorageName]) == 'PDO'){

				return $GLOBALS[self::$PDOStorageName];
			} else {
				$conn = self::setupPDO();
				$GLOBALS[self::$PDOStorageName] = &$conn;
				return $conn;
			}
		}

	}