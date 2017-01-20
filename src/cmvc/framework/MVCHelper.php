<?php

/**
 * User: Callum Carmicheal
 * Date: 26/09/2016
 * Time: 17:03
 */

namespace CommonMVC\MVC;


class MVCHelper {
	
	public static function alterSpacesToDashes($string, $convertToLower = false) {
		//Lower case everything
		if($convertToLower) $string = strtolower($string);
		
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
	
	public static function str_contains($string, $search) {
		return strpos($string, $search) !== false;
	}
	
	public static function isAjax() {
		return (
			!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
			strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
		);
	}
	
	public static function isNullOrEmptyString($question){
		return (!isset($question) || trim($question)==='');
	}
	
	/**
	 * Get the path of a MVC Controller using the Virtual Path
	 * @param $ControllerRootDir string The root directory of the controllers
	 * @param $ControllerNamespace string The default namespace to be used
	 * @param $VirtualPath string The Virtual Path to resolve
	 * @return MVCContext
	 */
	public static function ResolveVirtualPath($ControllerRootDir, $ControllerNamespace, $VirtualPath) {
		$VirtualPathRaw = $VirtualPath;
		
		// Debug virtual paths
		$DEBUG_VIRTUALPATH = false;
		
		if ($DEBUG_VIRTUALPATH) {
			ob_get_clean();
			echo "<pre>VirtualPath Debugging \n---------------------\n\n";
		}
		
		/* Clean the Virtual Path */ {
			// Disabled because it would not be needed,
			// (IN THE EXAMPLE CMVC_PRJ_VIRTPATH_ROOT_SPECIFIER = $)
			// the idea behind this was to have in javascript a
			// file that would be in the file View/Page/Action
			// and it could access Hello/World/Action by doing
			// $/Hello/World/Action, which would be shown as in a web request
			// View/Page/$/Hello/World/Action but evaluated as
			// Hello/World/Action. You may re-enable this if you please
			// its simple to implement just define CMVC_PRJ_VIRTPATH_ROOT_SPECIFIER with
			// the specifier you want to use.
			//
			// This would be bad practice, the better idea is to use the
			// url function which is defined in cmvc/framework/globals/urlhandler.php
			
			
			/*/ Check if VirtualPath contains --> (RootSpec)
			$identifier = CMVC_PRJ_VIRTPATH_ROOT_SPECIFIER;
			if (strcmp($VirtualPath, $identifier) !== false) {
				// Split $VirtualPath by $identifier
				$vpArr = explode($identifier, $VirtualPath);
				$VirtualPath = $vpArr[count($vpArr)-1];
			} /*/
			
			if (is_array($VirtualPath))
				if ($VirtualPath[0]=="/")
					$VirtualPath = substr($VirtualPath, 1);
			
			// Remove any spaces from the string and replace them with a dash
			$VirtualPath = self::alterSpacesToDashes($VirtualPath, false);
			
			// Clean up the Path so each word start is capitalised
			$VirtualPath = implode('/', array_map('mb_ucfirst', explode('/', $VirtualPath)));
		}
		
		// Setup the variables we are going to use
		$Path 		= $ControllerRootDir;
		$Namespace 	= $ControllerNamespace;
		$Controller = "";
		$Action		= "";
		
		$VPath      = "";
		$Arr 		= explode("/", $VirtualPath);
		$ArrLen 	= count($Arr)-1;
		
		
		// Debug Code:
		//echo "<pre>Path: $Path\nNamespace: $Namespace";
		//die("");
		
		// Just a slash (/) meaning
		// Controller = Index,
		// Action = Index
		if ($VirtualPathRaw == "/") {
			$Controller = "Index";
			$Action     = "Index";
			
			if ($DEBUG_VIRTUALPATH) echo "if VirtualPath == '/' \n";
		}
		
		
		// Just a controller or action
		// CONTROLLER  	if ENDSWITH ("/")
		// ELSE    		ACTION
		else if ($ArrLen == 0) {
			
			if (self::endsWith($VirtualPathRaw, '/')) {
				// [0]      = Controller
				// "Index"  = Action
				
				$Controller = $Arr[0];
				$Action     = "Index";
				
				if (empty($Controller))
					$Controller = "Index";
				
				if ($DEBUG_VIRTUALPATH) echo "if ArrLen == 0 ... (VPR) ENDS WITH / \n";
			}
			
			else {
				
				// "Index"  = Controller
				// [0]      = Action
				
				$Controller = "Index";
				$Action     = $Arr[0];
				
				if (empty($Action))
					$Action = "Index";
				
				if ($DEBUG_VIRTUALPATH) echo "if ArrLen == 0 ... ELSE \n";
			}
			
		}
		
		// Path, Controller and Action
		else if ($ArrLen >= 2) {
			// Check if the path is a index call
			// EG: Some/Controller/
			// the trailing slash assumes / = INDEX
			if (self::endsWith($VirtualPathRaw, '/')) {
				// [0-( LEN-2 )]    Path
				// [Len]            Controller
				// "Index"          Action
				
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
				$Path .= '/'. $tmpNamespace;
				$VPath = $tmpNamespace;
				
				// Replace all forward slashes with backslashes
				$tmpNamespace = str_replace('/', '\\', $tmpNamespace);
				
				// Append our classes namespace onto the root namespace
				$Namespace .= '\\'. $tmpNamespace;
				
				// Set the controller and action
				$Controller = $Arr[$ArrLen-1];
				$Action     = "Index";
				
				if ($DEBUG_VIRTUALPATH) echo "if ArrLen >= 2 AND endsWith / \n";
			}
			
			// Gets the usual web call including a path
			else {
				// [0-( Len-2 )] 	Path
				// [Len-1] 		    Controller
				// [Len]		    Action
				
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
				$Path .= '/'. $tmpNamespace;
				$VPath = $tmpNamespace;
				
				// Replace all forward slashes with backslashes
				$tmpNamespace = str_replace('/', '\\', $tmpNamespace);
				
				// Append our classes namespace onto the root namespace
				$Namespace .= '\\'. $tmpNamespace;
				
				// Set the controller and action
				$Controller = $Arr[$ArrLen-1];
				$Action = $Arr[$ArrLen];
				
				if ($DEBUG_VIRTUALPATH) echo "if ArrLen >= 2 \n";
			}
		}
		
		// Controller and Action
		// or Path and Controller
		else {
			// Check if the path is a index call
			// EG: Some/Controller/ {INDEX ASSUMED}
			// the trailing slash assumes / = INDEX
			//
			// or
			// Controller/ {INDEX ASSUMED}
			if (self::endsWith($VirtualPathRaw, '/')) {
				
				// /Controller/ {INDEX ASSUMED}
				if (empty($Arg[1])) {
					
					// [0]     = Controller
					// "Index" = Action
					
					$Controller = $Arr[0];
					$Action = "Index";
					
					if ($DEBUG_VIRTUALPATH) echo "ELSE... if endsWith / ... ARG[1] == EMPTY";
				}
				
				// Else
				// Some/Controller/ {INDEX ASSUMED}
				else {
					// [0]     = Path
					// [1]     = Controller
					// "Index" = Action
					
					var_dump ($Arr);
					exit;
					
					$VPath = $Arr[0];
					$Namespace .= '\\'. $Arr[0];
					$Controller = $Arr[1];
					$Action = "Index";
					
					if ($DEBUG_VIRTUALPATH) echo "ELSE... if endsWith / ... ELSE";
				}
				
			}
			
			// Gets a usual web call with just a controller
			// and index
			else {
				
				// [0] = Controller
				// [1] = Action
				
				$Controller = $Arr[0];
				$Action = $Arr[1];
				
				if ($DEBUG_VIRTUALPATH) echo "ELSE...";
			}
		}
		
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
			$VirtualPath,
			$VPath
		);
		
		// Dump our context to debug
		// Most of the classes, mainly the ones that
		// would require debugging have built in ToString()
		// methods to make debugging exceptionally easy.
		if ($DEBUG_VIRTUALPATH)
			die ("\nContext: ". $info);
		
		return $info;
	}
	
}
