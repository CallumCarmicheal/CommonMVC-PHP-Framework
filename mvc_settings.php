<?php
/**
 * User: Callum Carmicheal
 * Date: 24/09/2016
 * Time: 18:35
 */

	//
	//
	// MVC -> Settings
	//
		/**
		 * Enables Debugging on the MVC Handler (MVC Loader)
		 */
		define ("CMVC_SETS_DEBUG_HANDLER", true);

	//
	//
	// Project -> Setup
	//
		/**
		 * The directory to use as project root
		 */
		define ("CMVC_PRJ_DIRECTORY", "example");

		/**
		 * The project namespace (Default namespace used for the project)
		 */
		define ("CMVC_PRJ_NAMESPACE", "ExampleProject");

		/**
		 * The directory to use as the controller root
		 *
		 * Default: CMVC_PRJ_NAMESPACE. "/Controllers"
		 */
		define ("CMVC_PRJ_DIRECTORY_CONTROLLERS", CMVC_PRJ_DIRECTORY. "/controllers");

		/**
		 * Controller namespace.
		 * 		This is the namespace that will be used when
		 * 		calling a controller
		 *
		 * Default: CMVC_PRJ_NAMESPACE. "/Controllers"
		 *
		 * Note:
		 * 		This can be completely different from the project namespace
		 * 		this can really be anything you want!
		 *
		 * 		Use 2 backslashes for a single backslash to escape the string!
		 */
		define ("CMVC_PRJ_NAMESPACE_CONTROLLERS", CMVC_PRJ_NAMESPACE. "\\Controllers");

	//
	//
	// Project -> Include
	//
		// Here store the location of every file that will be loaded
		// Into the framework before calling a MVC Controller
		//		This allows you to write your own classes or
		//		required functions without having to modify or tamper
		// 		with the mvc_handler and potentially breaking something.
		// Example:
		//		$CMVC_PRJ_EXTRA_INCLUDED_CLASSES[] = "example/classes/PastebinAPI.php";
		$CMVC_PRJ_EXTRA_INCLUDED_CLASSES = array();


	//
	//
	// Controllers -> Errors
	//
		/**
		 * The directory to use as the controller root
		 *
		 * Default: CMVC_PRJ_DIRECTORY_CONTROLLERS. "/MvcErrors"
		 */
		define ("CMVC_PRJ_DIRECTORY_CONTROLLERS_ERRORS", CMVC_PRJ_DIRECTORY_CONTROLLERS. "/MvcErrors");

		/**
		 * Controller errors namespace.
		 * 		This is the namespace that will be used when
		 * 		a error occurs in the framework
		 *
		 * Default: CMVC_PRJ_NAMESPACE_CONTROLLERS. "/MvcErrors"
		 *
		 * Note:
		 * 		Please get a copy of the example error controllers at
		 * 			https://github.com/
		 * 		and modify them.
		 *		* TO ENSURE THERE IS NO ERRORS
		 *
		 * 		Use 2 backslashes for a single backslash to escape the string!
		 */
		define ("CMVC_PRJ_NAMESPACE_CONTROLLERS_ERRORS", CMVC_PRJ_NAMESPACE_CONTROLLERS. "\\MvcErrors");


	//
	//
	// VirtualPaths -> Defaults
	//
		/**
		 * This is the default page when a used loads the website
		 * 		This is for when the user is logged in
		 *
		 * Note:
		 * 		This is displayed as a VIRTUAL PATH NOT CONTROLLER
		 */
		define ("CMVC_PRJ_VIRTPATH_DEFAULT_AUTHED", "Home/Index");

		/**
		 * This is the default page when a used loads the website
		 * 		This is for when the user is not logged in
		 *
		 * Note:
		 * 		This is displayed as a VIRTUAL PATH NOT CONTROLLER
		 */
		define ("CMVC_PRJ_VIRTPATH_DEFAULT_NOAUTH", "Auth/Login");

		/**
		 * If the user trys to use a controller that requires auth
		 *		The user will be redirected to this page
		 *
		 * Note:
		 * 		This is displayed as a VIRTUAL PATH NOT CONTROLLER
		 */
		define ("CMVC_PRJ_VIRTPATH_REDIRECT_NOAUTH", "Auth/Login");