<?php

/**
 * User: Callum Carmicheal
 * Date: 26/09/2016
 * Time: 18:31
 */

namespace CommonMVC\MVC;

	class MVCGlobalControllers {

		/**
		 * Get a global MVC Controller
		 * @param $controllerName string
		 * @return MVCContext|bool
		 */
		public static function MVC_GetController($controllerName) {
			$cDir = CMVC_PRJ_DIRECTORY_CONTROLLERS_ERRORS;
			$cNsp = CMVC_PRJ_NAMESPACE_CONTROLLERS_ERRORS;

			$CFile = $controllerName. "Controller.php";
			$Path  = $cDir . "/". $CFile;

			// Check if the controller exists
			if(!file_exists($Path))
				return false;

			// Create context
			// $Namespace 	$Controller	$Action 	  $FileName
			// $Folder  	$Path 		$VirtualPath

			$ctx = new MVCContext(
				$cNsp,
				$controllerName,
				"",

				$CFile,
				$cDir,
				$Path,
				""
			);

			return $ctx;
		}

		/**
		 * Get the mvc vpath controller
		 * @return MVCContext
		 */
		public static function MVC_VPathController() {
			return self::MVC_GetController("VPath");
		}

		/**
		 * Get the mvc access controller
		 * @return MVCContext
		 */
		public static function MVC_AccessController() {
			return self::MVC_GetController("Access");
		}
	}