<?php

	// First enable session
	// Set default timezone
	session_start();
	date_default_timezone_set("Europe/London");

	include ("mvc_settings.php");
	global $CMVC_PRJ_EXTRA_INCLUDED_CLASSES;

	$MVC_DEBUG_OUTPUT = CMVC_SETS_DEBUG_HANDLER;

	/* Require all the libraries */ {
	// Global Stuffy Stuff (1/1)
		require_once ("data/classes/Global/Password_Util.php");
		require_once ("data/mvc/GlobalDefines.php");
		require_once ("data/classes/Global/TextHelper.php");
	// Classes (1/1)
		// Storage (1/1)
			require_once ("data/classes/Storage/Database.php");
			require_once ("data/classes/Storage/Templates.php");
		// Authentication (1/1)
			require_once ("data/classes/Authentication/Settings.php");
			require_once ("data/classes/Authentication/AuthHandler.php");
		// User (1/1)
			require_once ("data/classes/User/Account.php");

	// MVC (1/1)
		require_once ("data/mvc/MVCContext.php");
		require_once ("data/mvc/MVCController.php");
		require_once ("data/mvc/MVCExecutor.php");
		require_once ("data/mvc/MVCGlobalControllers.php");
		require_once ("data/mvc/MVCHandler.php");
		require_once ("data/mvc/MVCHelper.php");
		require_once ("data/mvc/MVCResult.php");

	// Extra imports
		foreach($CMVC_PRJ_EXTRA_INCLUDED_CLASSES as $incFile)
			require_once $incFile;
	}

	$mvcExec 		= new \CommonMVC\MVC\MVCExecutor();
	$VirtualPath 	= "";

	/* Check if the current request contains a page */ {
		if (empty($_GET['mvc_path'])) {
			if(CommonMVC\Classes\Authentication\AuthHandler::isLoggedIn())
				 $VirtualPath = CMVC_PRJ_VIRTPATH_DEFAULT_AUTHED;
			else $VirtualPath = CMVC_PRJ_VIRTPATH_DEFAULT_NOAUTH;
			
		} else {
			$VirtualPath = $_GET['mvc_path'];
			
			// Check if the VirtualPath begins with a
			$ajaxRequest = (
				!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
				strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
			);
			
			if(!$ajaxRequest && CMVC_PRJ_VIRTPATH_ROOT_REDIRECT &&
				strpos($VirtualPath, "$") !== false) {
				
				/* Clean our Virtual Path */ {
					// Check if VirtualPath contains --> (RootSpec)
					$identifier = CMVC_PRJ_VIRTPATH_ROOT_SPECIFIER;
					if (strcmp($VirtualPath, $identifier) !== false) {
						// Split $VirtualPath by $identifier
						$vpArr = explode($identifier, $VirtualPath);
						$OVirtualPath = $vpArr[count($vpArr)-1];
					} else {
						$OVirtualPath = $VirtualPath;
					}
					
					if ($OVirtualPath[0]=="/") $OVirtualPath = substr($OVirtualPath,1);
					
					// Remove any spaces from the string and replace them with
					$OVirtualPath = \CommonMVC\MVC\MVCHelper::alterSpacesToDashes($OVirtualPath, false);
					
					// Clean up the Path so each word start is capitalised
					$OVirtualPath = implode('/', array_map('mb_ucfirst', explode('/', $OVirtualPath)));
				}
				
				// The redirect is to a MVC Controller
				// 1. Get the SERV->RedirectURL
				// 2. Remove the MVC Virtual Path from the URI
				// 3. That is the base url
				// 4. Append the wanted Virtual Path
				// 5. Redirect to it
				$surl 		= $_SERVER['REDIRECT_URL'];
				$baseurl 	= str_replace($VirtualPath, "", $surl);
				$url 		= $baseurl. $OVirtualPath;
				
				header("location: ".   $url);
				die("redirecting to ". $url);
			}
		}
	}

	echo "<pre>";

	$ctrl = \CommonMVC\MVC\MVCHelper::ResolveVirtualPath(
		CMVC_PRJ_DIRECTORY_CONTROLLERS,
		CMVC_PRJ_NAMESPACE_CONTROLLERS,
		$VirtualPath
	);

	$mvcExec->ExecuteControllerContext($ctrl);