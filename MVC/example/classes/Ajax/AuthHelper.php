<?php
/**
 * User: Callum Carmicheal
 * Date: 01/10/2016
 * Time: 02:02
 */

namespace MVC\example\classes\Ajax;


	use CommonMVC\MVC\MVCHelper;
	
	class RequestResponse {
		private $State = false;
		private $Message = "";
		
		public function __construct($State, $Message="") {
			$this->State = $State;
			$this->Message = $Message;
		}
		
		public function isState() 	 { return $this->State; }
		public function getMessage() { return $this->Message; }
	}
	
	class AuthHelper {
		
		/**
		 * @return bool
		 */
		public static function ValidRequest_Login(&$Username, &$Password) {
			// Check if the strings are null/whitespaced
			if (MVCHelper::isNullOrEmptyString($_POST['authUsername']))
				return new RequestResponse(false, "Username is null/empty/whitespace'd.");
			else if (MVCHelper::isNullOrEmptyString($_POST['authPassword']))
				return new RequestResponse("Password is null/empty/whitespace'd.");
			else if (MVCHelper::isNullOrEmptyString($_POST['authEmail']))
				return new RequestResponse("Email is null/empty/whitespace'd.");
			
			$Username 	= $_POST['authUsername'];
			$Password 	= $_POST['authPassword'];
			
			return true;
		}
		
		
		/**
		 * @param $Username string 
		 * @param $Password string 
		 * @param $Email string 
		 * @return RequestResponse
		 */
		public static function ValidRequest_Register(&$Username, &$Password, &$Email) {
			$Username = "";
			$Password = "";
			$Email    = "";
			
			// Check if the strings are null/whitespaced
			if (MVCHelper::isNullOrEmptyString($_POST['authUsername']))
				return new RequestResponse(false, "Username is null/empty/whitespace'd.");
			else if (MVCHelper::isNullOrEmptyString($_POST['authPassword']))
				return new RequestResponse(false, "Password is null/empty/whitespace'd.");
			else if (MVCHelper::isNullOrEmptyString($_POST['authEmail']))
				return new RequestResponse(false, "Email is null/empty/whitespace'd.");
			
			$Username 	= $_POST['authUsername'];
			$Password 	= $_POST['authPassword'];
			$Email 		= $_POST['authEmail'];
			
			
			// Check if the username is of a valid length
			// Limit is 5 or more chars
			if(strlen($Username) < 5) return new RequestResponse(false, "Username is to short (>= 5)");  
			
			// Check if the password is of a valid length
			// Limit is 5 or more chars
			// ------------------------------------------
			// Here you could also add a regex to check
			// for any special characters to enforce
			// better password security
			if(strlen($Password) < 5) return new RequestResponse(false, "Password is to short (>= 5)");
			
			// Check if the Email is of a valid length
			// 6 is the lowest amount of characters
			// i know of to be a valid email address.
			if(strlen($Email) < 6) return new RequestResponse(false, "Password is to short (>= 6)");
			
			// Check if the email is of a valid format
			if(!filter_var($Email, FILTER_VALIDATE_EMAIL)) 
				return new RequestResponse(false, "Email is of a invalid format");
			
			// Checks are complete return true
			return new RequestResponse(true, "");
		}
		
	}