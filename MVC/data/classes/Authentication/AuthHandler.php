<?php
/**
 * User: Callum Carmicheal
 * Date: 24/09/2016
 * Time: 02:50
 */

namespace CommonMVC\Classes\Authentication;


	use CommonMVC\Classes\Storage\Database;
	use CommonMVC\Classes\User\Account;
	
	class AccountCreationState {
		
		private $Username;
		private $State = false;
		private $Error = "";
		
		public function __construct($Username, $State, $Error = "") {
			$this->Username = $Username;
			$this->State = $State;
			$this->Error = $Error;
		}
		
		public function getUsername() {return $this->Username;}
		public function isCreated() {return $this->State;}
		public function getErrorText() {return $this->Error;}
		
		
	}
	
	class UserReturnState {
		private $User;
		private $Exists;
		
		public function getExists() 		{ return $this->Exists; }
		public function getUser() 			{ return $this->User; }
		
		public function setExists($Exists) 	{ $this->Exists = $Exists; }
		public function setUser($User) 		{ $this->User = $User; }
	}
	
	class AuthHandler {

		public static function isLoggedIn() {
			return !empty($_SESSION[Settings::$SESSION_NAME]);
		}

		/**
		 * Check if a user password combo is valid
		 * @param $username string The username to check against
		 * @param $password string The password to check with
		 * @return int
		 */
		public static function isValidLoginUP($username, $password) {
			$pdo = Database::GetPDO();

			$query = "
				SELECT id, pass
				FROM user_authentication
				WHERE BINARY user = :user and BINARY pass = :pass
			";

			$query_params = array(
				':user' => $username,
				':pass' => $password
			);

			try {
				$stmt = $pdo->prepare($query);
				$stmt->execute($query_params);
			} catch(\PDOException $ex) {
				return false;
			}

			$row = $stmt->fetch();
			if($row)
				return
					password_verify($password, $row['pass']);

			return false;
		}
		
		public static function UsernameExists($Username) {
			$pdo = Database::GetPDO();
			
			$query = "
				SELECT id
				FROM user_authentication
				WHERE BINARY user = :user
			";
			
			$query_params = array( ':user' => $Username );
			
			try {
				$stmt = $pdo->prepare($query);
				$stmt->execute($query_params);
			} catch(\PDOException $ex) {
				return false;
			}
			
			$row = $stmt->fetch();
			if($row)
				return true;
			return false;
		}
		
		public static function UserIDExists($ID) {
			$pdo = Database::GetPDO();
			
			$query = "
				SELECT id
				FROM user_authentication
				WHERE id = :user
			";
			
			$query_params = array( ':user' => $ID );
			
			try {
				$stmt = $pdo->prepare($query);
				$stmt->execute($query_params);
			} catch(\PDOException $ex) {
				return false;
			}
			
			$row = $stmt->fetch();
			if($row)
				return true;
			return false;
		}
		
		public static function EmailExists($Email) {
			$pdo = Database::GetPDO();
			
			$query = "
				SELECT id
				FROM user_authentication
				WHERE email = :user
			";
			
			$query_params = array( ':user' => $Email );
			
			try {
				$stmt = $pdo->prepare($query);
				$stmt->execute($query_params);
			} catch(\PDOException $ex) {
				return false;
			}
			
			$row = $stmt->fetch();
			if($row)
				return true;
			return false;
		}
		
		/**
		 * @param $Username string 
		 * @param $Password string 
		 * @param $Email string 
		 * @return AccountCreationState
		 */
		public static function RegisterNewUser($Username, $Password, $Email) {
			if (self::UsernameExists($Username)) 
				return new AccountCreationState($Username, false, 
					"Username $Username already exists.");
			
			else if (self::EmailExists($Email))
				return new AccountCreationState($Username, false,
					"Email $Email already exists.");
			
			$pdo = Database::GetPDO();
			
			$query = "
				INSERT INTO user_authentication (user, pass, email, is_enabled)
				VALUES (:user, :pass, :email, :enabled)
			";
			
			
			// hash the password
			
			$hPassword = \password_hash($Password, \PASSWORD_DEFAULT);
			
			$enabled = CMVC_AUTH_REGISTER_DEFAULT_ENABLED ? 1 : 0;
			$query_params = array(
				':user'  	=> $Username,
				':pass'  	=> $hPassword,
				':email' 	=> $Email,
				':enabled' 	=> $enabled
			);
			
			try {
				$stmt = $pdo->prepare($query);
				$stmt->execute($query_params);
				$lastId = $pdo->lastInsertId();
				
				
				$query = "
					INSERT INTO user_details (uid)
					VALUES (:id)
				";
				
				$query_params = array (
					':id' => $lastId
				);
				
				$stmt = $pdo->prepare($query);
				$stmt->execute($query_params);
			} catch(\PDOException $ex) {
				return new AccountCreationState($Username, false, 
					"There was a exception when trying to create the account. Please try again later!");
				//throw $ex;
			}
			
			return new AccountCreationState($Username, true);
		}

		
		public static function GetUserViaName($Username) {
			if (!self::UsernameExists($Username))
				return new AccountCreationState($Username, false,
					"Username $Username already exists.");
		}
	}