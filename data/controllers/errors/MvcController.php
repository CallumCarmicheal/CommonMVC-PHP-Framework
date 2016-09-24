<?php
/**
 * User: Callum Carmicheal
 * Date: 24/09/2016
 * Time: 17:29
 */

namespace CommonMVC\Controllers\Errors;

	use CommonMVC\MVC\MVCResult;

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
			return new MVCResult();
		}
	}