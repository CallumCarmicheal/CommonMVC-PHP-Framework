<?php
/**
 * User: Callum Carmicheal
 * Date: 23/09/2016
 * Time: 20:28
 */

namespace CommonMVC\MVC;

	use lib\CMVC\mvc\MVCEncryption;
	
	class MVCResult {
		///			      MVCResult Enums
		///------------------------------------------------
		///
		public static $E_RESULT_SUCCESS      		=   0;
		public static $E_RESULT_INVALID 			=   1;
		public static $E_RESULT_ERROR   			=   2;
		public static $E_RESULT_REDIRECT			=   3;
		// -----------------------------------------------
		public static $E_HTTP_RESULT_OK				= 200;
		public static $E_HTTP_RESULT_NOACCESS		= 403;
		public static $E_HTTP_RESULT_NOTFOUND    	= 404;
		public static $E_HTTP_RESULT_NOALLOWED		= 405;
		public static $E_HTTP_RESULT_TIMEDOUT		= 408;
		public static $E_HTTP_RESULT_SERVICENAVIL 	= 503;
		public static $E_HTTP_RESULT_UPSTREAMFAIL 	= 504;
		public static $E_HTTP_RESULT_ERROR 			= 505;
		// -----------------------------------------------
		public static $E_HTTP_CLEAN_NOTHING			=   0;
		public static $E_HTTP_CLEAN_CONTENT			=   1;
		// -----------------------------------------------
		public static $E_REDIRECT_EXTERNAL   		=   0;
		public static $E_REDIRECT_MVC				=   1;
		public static $E_REDIRECT_AUTOMATIC         =   2;
		// -----------------------------------------------
		public static function E_Result_ToString($result) {
			if ($result == self::$E_RESULT_SUCCESS)
				return "Success";
			else if ($result == self::$E_RESULT_INVALID)
				return "Invalid";
			else if ($result == self::$E_RESULT_ERROR)
				return "Error";
			else if ($result == self::$E_RESULT_REDIRECT)
				return "Redirect";
			return "";
		}
		public static function E_Redirect_ToString($redirect) {
			if ($redirect == self::$E_REDIRECT_EXTERNAL)
				 return "External";
			else return "Mvc";
		}



		///			    	MVCResult Defaults
		///  ------------------------------------------------
		///
		
		/**
		 * Redirect user to another location
		 * @param $location string Location to redirect to
		 * @param $type int Type of redirect (External = 0, MVC = 1)
		 * @param $httpclean int Clean http output before setting headers
		 * @return MVCResult Automatically generated mvc result
		 */
		public static function Redirect($location, $type = 2, $httpclean = 1) {
			$mvc = new MVCResult();
			
			// Redirect, type
			// Clean content headers
			$mvc->setHttpRedirect($location);
			$mvc->setHttpRedirectT($type);
			$mvc->setHttpClean($httpclean); // DEFAULT: MVCResultEnums::$HTTP_CLEAN_CONTENT

			$mvc->setPageResult(self::$E_RESULT_REDIRECT);

			return $mvc;
		}

		/**
		 * Returns a pre-made result for errors
		 * @param $developer string The error that the developer sees
		 * @param $user string The error that the client/user sees
		 * @return MVCResult Automatically generated mvc result
		 */
		public static function Error($developer, $user) {
			$mvc = new MVCResult();

			$mvc->setErrorDeveloper($developer);
			$mvc->setErrorUser($user);

			$mvc->setPageResult(self::$E_RESULT_ERROR);

			return $mvc;
		}

		/**
		 * Output html to the browser with no catches!
		 * @param $html string HTML to output to the browser
		 * @param $clearContent bool Clear content when outputting the html content
		 * @return MVCResult Automatically generate a mvc success result with HTML
		 */
		public static function HtmlContent($html, $clearContent = true) {
			$mvc = new MVCResult();

			$mvc->setPageContent($html);
			$mvc->setHttpResult(self::$E_HTTP_RESULT_OK);
			$mvc->setPageResult(self::$E_RESULT_SUCCESS);

			if($clearContent)
				$mvc->setHttpClean(self::$E_HTTP_CLEAN_CONTENT);

			return $mvc;
		}
		
		/**
		 * @param $encryption MVCEncryption
		 * @param $html string
		 * @param bool $clearContent
		 * @return MVCResult
		 */
		public static function EncryptedHtmlContent($encryption, $html, $clearContent = true) {
			$mvc = new MVCResult();
			
			$mvc->setPageContent($encryption->EncryptContent($html));
			$mvc->setHttpResult(self::$E_HTTP_RESULT_OK);
			$mvc->setPageResult(self::$E_RESULT_SUCCESS);
			
			if($clearContent)
				$mvc->setHttpClean(self::$E_HTTP_CLEAN_CONTENT);
			
			return $mvc;
		}

		/**
		 * Output data to the browser with a custom Content-Type
		 * @param $data string
		 * @param $application string
		 * @param bool $clearContent
		 * @return MVCResult
		 */
		public static function ApplicationContent($data, $application = "application/json", $clearContent = true) {
			$mvc = new MVCResult();
			
			$content = self::CreateApplicationData($data, $application);
			
			$mvc->setPageContent($content);
			$mvc->setHttpResult(self::$E_HTTP_RESULT_OK);
			$mvc->setPageResult(self::$E_RESULT_SUCCESS);
			
			$mvc->setHeaderCustomContent(true);
			$mvc->setHttpHeaderContentType($application);
			
			if($clearContent)
				$mvc->setHttpClean(self::$E_HTTP_CLEAN_CONTENT);
			
			return $mvc;
		}
		
		/**
		 * Output encrypted data to the browser with a custom Content-Type
		 * @param $encryption MVCEncryption
		 * @param $data string
		 * @param $application string
		 * @param bool $clearContent
		 * @return MVCResult
		 */
		public static function EncryptedApplicationContent($encryption, $data, $application = "application/json", $clearContent = true) {
			$mvc = new MVCResult();
			
			$content = self::CreateApplicationData($data, $application);
			$content = $encryption->EncryptContent($content);
			
			$mvc->setPageContent($content);
			$mvc->setHttpResult(self::$E_HTTP_RESULT_OK);
			$mvc->setPageResult(self::$E_RESULT_SUCCESS);
			
			$mvc->setHeaderCustomContent(true);
			$mvc->setHttpHeaderContentType($application);
			
			if($clearContent)
				$mvc->setHttpClean(self::$E_HTTP_CLEAN_CONTENT);
			
			return $mvc;
		}
			
			
		/**
		 * Create data output from specified application
		 * @param $data mixed
		 * @param string $application
		 * @return string compiled content
		 */
		public static function CreateApplicationData($data, $application = "application/json") {
			// Todo: XML
			if ($application == "application/json")
				 return json_encode($data);
			else return $data;
		}
		
		
		
		///			    Custom MVC Result presets
		///  ------------------------------------------------
		///   This is where you would add your own presets
		///   used to save time when making your on custom
		///   responses such as implementing  BladeOne for
		///   example  which  is  Laravel's view framework
		///
		///   You are not required to only generate responses
		///   in this file, although its easier to place them
		///      all within this file to see every possible
		///             response through linting
		
		
		
		
		
		
		
		
		///			    	MVCResult Object
		///  ------------------------------------------------
		///

		private $page_result 	  	 		= 0;
		private $page_content 	  	 		= "";
		private $http_result  	  	 		= 200;
		private $http_redirect    	 		= "";
		private $http_redirect_t  	 		= 0;
		private $http_clean		  	 		= 0;
		private $header_custom_content 		= false;
		private $http_header_content_type   = "text/html";
		private $error_developer     		= ""; // Information for the user
		private $error_user 	  	 		= ""; // Information for the developer

		public function getPageContent() 				   				{ return $this->page_content; }
		public function getPageResult() 				   				{ return $this->page_result; }
		public function getHttpResult() 				   				{ return $this->http_result; }
		public function getHttpRedirect() 				   				{ return $this->http_redirect; }
		public function getHttpRedirectT()  			   				{ return $this->http_redirect_t; }
		public function getHttpClean()									{ return $this->http_clean; }
		public function getHeaderContentType()							{ return $this->http_header_content_type; }
		public function getErrorDeveloper()								{ return $this->error_developer; }
		public function getErrorUser() 									{ return $this->error_user; }

		public function isHeaderCustomContent() 						{ return $this->header_custom_content; }

		public function setPageContent($page_content) 	   				{ $this->page_content = $page_content; }
		public function setPageResult($page_result) 	   				{ $this->page_result = $page_result; }
		public function setHttpResult($http_result) 	   				{ $this->http_result = $http_result; }
		public function setHttpRedirect($http_redirect)    				{ $this->http_redirect = $http_redirect; }
		public function setHttpRedirectT($http_redirect_t) 				{ $this->http_redirect_t = $http_redirect_t; }
		public function setHttpClean($http_clean)						{ $this->http_clean = $http_clean; }
		public function setHeaderCustomContent($header_custom_content) 	{ $this->header_custom_content = $header_custom_content; }
		public function setHttpHeaderContentType($http_header_type) 	{ $this->http_header_content_type = $http_header_type; }
		public function setErrorDeveloper($error_developer) 			{ $this->error_developer = $error_developer; }
		public function setErrorUser($error_user)						{ $this->error_user = $error_user; }

		public function appendPageContent($page_content)  				{ $this->page_content .= $page_content; }

		/**
		 * Resets all local variables
		 */
		public function resetVariables() {
			$this->page_result 	 	= 0;
			$this->page_content 	= "";
			$this->http_result  	= 400;
			$this->http_redirect   	= "";
			$this->http_redirect_t 	= 0;
			$this->http_clean		= 0;
			$this->error_developer 	= ""; // Information for the user
			$this->error_user 	 	= ""; // Information for the developer
		}
	}
