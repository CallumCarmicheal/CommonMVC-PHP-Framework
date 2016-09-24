<?php
/**
 * User: Callum Carmicheal
 * Date: 23/09/2016
 * Time: 20:28
 */

namespace CommonMVC\MVC;

	class MVCResultEnums {
		public static $RESULT_SUCCESS      		= 0;
		public static $RESULT_SUCCESS_HTML 		= 1;
		public static $RESULT_INVALID 			= 1;
		public static $RESULT_ERROR   			= 2;
		public static $RESULT_REDIRECT			= 3;

		public static $HTTP_RESULT_OK			= 200;
		public static $HTTP_RESULT_ERROR 		= 505;
		public static $HTTP_RESULT_NOTFOUND    	= 404;

		public static $HTTP_CLEAN_NOTHING		= 0;
		public static $HTTP_CLEAN_CONTENT		= 1;
		public static $HTTP_CLEAN_HEADERS		= 2;
		public static $HTTP_CLEAN_CONHEAD		= 3; // Content and Headers

		public static $REDIRECT_EXTERNAL   		= 0;
		public static $REDIRECT_MVC				= 1;
	}

	class MVCResult {

		private $page_result 	 = 0;
		private $page_content 	 = "";
		private $http_result  	 = 400;
		private $http_redirect   = "";
		private $http_redirect_t = 0;
		private $http_clean		 = 0;

		public function getPageContent() 				   	{ return $this->page_content; }
		public function getPageResult() 				   	{ return $this->page_result; }
		public function getHttpResult() 				   	{ return $this->http_result; }
		public function getHttpRedirect() 				   	{ return $this->http_redirect; }
		public function getHttpRedirectT()  			   	{ return $this->http_redirect_t; }
		public function getHttpClean()						{ return $this->http_clean; }

		public function setPageContent($page_content) 	   	{ $this->page_content = $page_content; }
		public function setPageResult($page_result) 	   	{ $this->page_result = $page_result; }
		public function setHttpResult($http_result) 	   	{ $this->http_result = $http_result; }
		public function setHttpRedirect($http_redirect)    	{ $this->http_redirect = $http_redirect; }
		public function setHttpRedirectT($http_redirect_t) 	{ $this->http_redirect_t = $http_redirect_t; }
		public function setHttpClean($http_clean)			{ $this->http_clean = $http_clean; }

		public function appendPageContent($page_content)  	{ $this->page_content .= $page_content; }

		/**
		 * Redirect user to another location
		 * @param $location string Location to redirect to
		 * @param $type int Type of redirect (External = 0, MVC = 1)
		 * @param $httpclean int Clean http output before setting headers
		 * @return MVCResult Automatically generated mvc result
		 */
		public static function Redirect($location, $type, $httpclean = 3) {
			$mvc = new MVCResult();

			// Redirect, type
			// Clean content headers
			$mvc->setHttpRedirect($location);
			$mvc->setHttpRedirectT($type);
			$mvc->setHttpClean($httpclean); // DEFAULT: MVCResultEnums::$HTTP_CLEAN_CONHEAD

			$mvc->setPageResult(MVCResultEnums::$RESULT_REDIRECT);

			return $mvc;
		}
	}