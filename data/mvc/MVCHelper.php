<?php

/**
 * User: Callum Carmicheal
 * Date: 26/09/2016
 * Time: 17:03
 */

namespace CommonMVC\MVC;


	class MVCHelper {

		public static function alterSpacesToDashes($string) {
			//Lower case everything
			$string = strtolower($string);
			//Convert whitespaces and underscore to dash
			$string = preg_replace("/[\s_]/", "-", $string);
			return $string;
		}

		public static function startsWith($haystack, $needle) {
			// search backwards starting from haystack length characters from the end
			return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
		}

		public static function endsWith($haystack, $needle) {
			// search forward starting from end minus needle length characters
			return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
		}

		public static function str_lreplace($search, $replace, $subject) {
			$pos = strrpos($subject, $search);
			if($pos !== false)
				$subject = substr_replace($subject, $replace, $pos, strlen($search));
			return $subject;
		}

		/**
		 * Get the path of a MVC Controller using the Virtual Path
		 * @param $ControllerRootDir string The root directory of the controllers
		 * @param $ControllerNamespace string The default namespace to be used
		 * @param $VirtualPath string The Virtual Path to resolve
		 * @return MVCContext
		 */
		public static function ResolveVirtualPath($ControllerRootDir, $ControllerNamespace, $VirtualPath) {
			$Controller = "";

			/* Clean Virtual Path */ {
				// Remove any spaces from the string and replace them with
				$VirtualPath = self::alterSpacesToDashes($VirtualPath);

				// Clean up the Path so each word start is capitalised
				$VirtualPath = implode('/', array_map('ucfirst', explode('/', $VirtualPath)));
			}

			$Path 		= $ControllerRootDir;
			$Namespace 	= $ControllerNamespace;
			$Controller = "";
			$Action		= "";
			$Arr 		= explode("/", $VirtualPath);
			$ArrLen 	= count($Arr)-1;

			// Just a controller
			if ($ArrLen == 0) {
				// [0] = Controller

				$Controller = $Arr[0];
				$Action = "Index";
			}

			// Path, Controller and Action
			else if ($ArrLen >= 2) {
				// [0-(Len-2)] 	Path
				// [Len-1] 		Controller
				// [Len]		Action

				// Set the amount of times to
				// append the vp to the namespace
				$PathCount = $ArrLen-2;
				$tmpNamespace = "";

				// Append the VirtualPath to the
				// namespace
				for($x = 0; $x <= $PathCount; $x++)
					$tmpNamespace .= $Arr[$x]. "/";

				// Remove any trailing forward slashes
				$tmpNamespace = rtrim($tmpNamespace,"/");

				// Append our current namespace with forward slashes
				// 	to the folder location
				$Path .= $tmpNamespace;

				// Replace all forward slashes with backslashes
				$tmpNamespace = str_replace('/', '\\', $tmpNamespace);

				// Append our classes namespace onto the root namespace
				$Namespace .= $tmpNamespace;

				// Set the controller and action
				$Controller = $Arr[$ArrLen-1];
				$Action = $Arr[$ArrLen];
			}

			// Controller and Action
			else {
				// [0] = Controller
				// [1] = Action

				$Controller = $Arr[0];
				$Action 	= $Arr[1];
			}

			echo "\n\n";


			// Setup the MVC Controller Information
			// $Namespace   = "", $Controller = "", $Action = "",
			// $FileName    = "", $Folder     = "", $Path = ""
			// $VirtualPath = ""

			$info = new MVCContext(
				$Namespace,
				$Controller,
				$Action,

				$Controller. "Controller.php",				// $FileName
				$Path, 										// $Folder
				$Path. "/". $Controller. "Controller.php",	// $Path
				$VirtualPath
			);

			return $info;
		}

	}
