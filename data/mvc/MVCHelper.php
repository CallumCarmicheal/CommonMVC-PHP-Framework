<?php

/**
 * User: Callum Carmicheal
 * Date: 26/09/2016
 * Time: 17:03
 */

namespace CommonMVC\MVC;

	class MVCControllerInformation {
		private $Controller = "";

		private $FileName	= "";
		private $Folder   	= "";
		private $Path 		= "";


		public function __construct(
			$Controller = "", $FileName = "",
			$Folder = "",     $Path = "") {

			$this->Controller 	= $Controller;
			$this->FileName 	= $FileName;
			$this->Folder 		= $Folder;
			$this->Path 		= $Path;
		}

		public function getController() { return $this->Controller; }
		public function getFileName() 	{ return $this->FileName; }
		public function getFolder() 	{ return $this->Folder; }
		public function getPath() 		{ return $this->Path; }

		public function setController($Controller) 	{ $this->Controller = $Controller; }
		public function setFileName($FileName) 		{ $this->FileName = $FileName; }
		public function setFolder($Folder)			{ $this->Folder = $Folder; }
		public function setPath($Path) 				{ $this->Path = $Path; }
	}

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
		 * @param $VirtualPath
		 * @return MVCControllerInformation Controller information
		 */
		public static function getCtrlFromPath($ControllerRootDir, $ControllerNamespace, $VirtualPath) {
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
			$Action 	= "";
			$Arr 		= explode("/", $VirtualPath);
			$ArrLen 	= count($Arr)-1;

			if ($ArrLen == 0) {
				// [0] = Controller

				$Controller = $Arr[0];
				$Action = "Index";
			}

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

			else {
				// [0] = Controller
				// [1] = Action

				$Controller = $Arr[0];
				$Action 	= $Arr[1];
			}

			echo "\n\n";


			// Setup the MVC Controller Information
			// $Controller = "", $File = "", $Folder = "", $Path = ""
			$info = new MVCControllerInformation(
				$Controller,
				$Controller. "Controller.php",

				$Path,
				$Path. "/". $Controller. "Controller.php"
			);

			echo '$Controller: '. 	$info->getController() . "\n";
			echo '$File:       '. 	$info->getFileName(). "\n";
			echo '$Folder:     '. 	$info->getFolder(). "\n";
			echo '$Path:       '. 	$info->getPath(). "\n";

			return $info;
		}
	}