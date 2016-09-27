<?php

	// First enable session
	// Set default timezone
	session_start();
	date_default_timezone_set("Europe/London");

	include ("mvc_settings.php");
	global $CMVC_PRJ_EXTRA_INCLUDED_CLASSES;

	$MVC_DEBUG_OUTPUT = CMVC_SETS_DEBUG_HANDLER;

	/* Require all the libraries */ {
	// Global Defines (1/1)
		require_once ("data/mvc/GlobalDefines.php");
		require_once ("data/classes/Global/TextHelper.php");
	// Classes (1/1)
		// Storage (1/1)
			require_once ("data/classes/Storage/Database.php");
			require_once ("data/classes/Storage/Templates.php");
		// Authentication (1/1)
			require_once ("data/classes/Authentication/Settings.php");
			require_once ("data/classes/Authentication/AuthStatus.php");
			require_once ("data/classes/Authentication/AReqHandler.php");
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
			if(CommonMVC\Classes\Authentication\AuthStatus::isLoggedIn())
				 $VirtualPath = CMVC_PRJ_VIRTPATH_DEFAULT_AUTHED;
			else $VirtualPath = CMVC_PRJ_VIRTPATH_DEFAULT_NOAUTH;
		} else {
			$VirtualPath = $_GET['mvc_path'];
		}
	}

	echo "<pre>";

	$ctrl = \CommonMVC\MVC\MVCHelper::ResolveVirtualPath(
		CMVC_PRJ_DIRECTORY_CONTROLLERS,
		CMVC_PRJ_NAMESPACE_CONTROLLERS,
		$VirtualPath
	);

	$mvcExec->ExecuteControllerContext($ctrl);