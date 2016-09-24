<?php
	/**
	 * User: Callum Carmicheal
	 * Date: 23/09/2016
	 * Time: 04:48
	 */

	namespace CommonMVC\MVC;

	class MVCPage {
		// Variables
		/**
		 * @var string The MVC Identifier
		 */
		private $VirtualPath;

		/**
		 * @var string The location of the MVC Controller
		 */
		private $Controller;

		/**
		 * @var bool If the MVC is disabled
		 */
		private $Disabled;

		/**
		 * @var bool If the MVC also contains a Ajax component
		 */
		private $Ajax;

		// Constructor
		function __construct($VirtualPath, $Controller, $Disabled, $Ajax) {
			$this->VirtualPath 	= $VirtualPath;
			$this->Controller 	= $Controller;
			$this->Disabled 	= $Disabled;
			$this->Ajax			= $Ajax;
		}

		// Getters and Setters
		public function getController() 				{ return $this->Controller; }
		public function getVirtualPath() 				{ return $this->VirtualPath; }

		public function isDisabled() 					{ return $this->Disabled; }
		public function isAjax() 						{ return $this->Ajax; }

		public function setController($Controller) 		{ $this->Controller  = $Controller; }
		public function setVirtualPath($VirtualPath)	{ $this->VirtualPath = $VirtualPath; }
		public function setDisabled($Disabled) 		 	{ $this->Disabled 	 = $Disabled; }
		public function setAjax($Ajax) 					{ $this->Ajax = $Ajax; }
	}

	class MVCHandler {

		/**
		 * @var array List of registered MVC Pages
		 */
		private static $mvcData = null;

		/**
		 * @var MVCPage Default MVC Page
		 */
		private static $mvcDefault = "NULL";

		/**
		 * Make sure the mvc variables are valid
		 */
		private static function __EnsureWorkingMVC() {
			$check_1 = is_null(self::$mvcData);
			$check_2 = !is_array(self::$mvcData);
			$check_3 = $check_1 || $check_2;

			if ($check_3) {
				self::$mvcData = array();
				self::$mvcDefault = "NULL";
			}
		}

		/**
		 * Register a MVC Page to the events
		 * @param $MVC MVCPage The mvc data to register
		 */
		public static function registerMVCPage($MVC) {
			self::__EnsureWorkingMVC();
			self::$mvcDefault[$MVC->getVirtualPath()] = $MVC;
		}

		/**
		 * Registers a MVC Page array to the events
		 * @param $MVC array Array of MVCPage
		 */
		public static function registerMVCArray($MVC) {
			self::__EnsureWorkingMVC();
			if(!is_array($MVC)) return;

			$tmp = array_merge(self::$mvcData, $MVC);
			self::$mvcData = $tmp;
		}

		/**
		 * Register a MVC Address bar
		 * @param $Page string The Page Location used in the address bar
		 * @return bool Page exists
		 */
		public static function pageExists($Page) {
			if(empty(self::$mvcData)) 		 return false;
			if(empty(self::$mvcData[$Page])) return false;

			return true;
		}

		/**
		 * Gets the physical location of the page
		 * @param $Page string The page location in the address bar
		 * @return bool The page is invalid
		 * @return string The page location
		 */
		public static function getPagePhysical($Page) {
			if (!self::PageExists($Page))
				return false;
			return self::$mvcData[$Page];
		}

		/**
		 * Get the default page location
		 * @return bool Default page not set
		 * @return string Default page location
		 */
		public static function getDefaultPage() {
			// TODO: Check if the default page is null
			// 		 then return data accordingly!
			if(self::$mvcDefault == "NULL")
				 return false;
			else return self::$mvcDefault;
		}

		/**
		 * Set default mvc page
		 * @param $page MVCPage Controller
		 */
		public static function setDefaultPage($page) {
			self::$mvcDefault = $page;
		}
	}