<?php
/**
 * User: Callum Carmicheal
 * Date: 24/09/2016
 * Time: 18:51
 */

namespace ExampleProject\Controllers\MvcErrors;


	use CommonMVC\MVC\MVCResult;
	use CommonMVC\MVC\MVCResultEnums;

	class MvcController extends \CommonMVC\MVC\MVCController {

		function __construct() {
			$this->ControllerName 	= "Errors/Mvc";
			$this->Enabled 			= true;
			$this->AuthRequired 	= true;
		}

		/**
		 * Display a error page stating that the MVC Controller cannot be found
		 * @return MVCResult
		 */
		function ControllerNotFound() {
			// Create our result
			$mvc = new MVCResult();

			// Setup our flags
			$mvc->setHttpClean(MVCResultEnums::$HTTP_CLEAN_CONTENT);
			$mvc->setPageResult(MVCResultEnums::$RESULT_SUCCESS);

			// Set the http content
			$mvc->setPageContent("Error: ");

			// Append http to the content
			$mvc->appendPageContent("Cannot find the controller for ");



			return new MVCResult();
		}

	}