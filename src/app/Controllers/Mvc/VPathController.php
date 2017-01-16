<?php

namespace App\Controllers\Mvc;

use CommonMVC\MVC\MVCController;
use CommonMVC\MVC\MVCResult;

class VPathController extends MVCController {
	function __construct() {
		$this->ControllerName 	= "Mvc/VPath";
		$this->WebAccess 			= false;
	}

	/**
	 * Display a error page stating that the MVC Controller cannot be found
	 * @return MVCResult
	 */
	function ControllerNotFound() {
		return MVCResult::HtmlContent("Cannot find the requested controller for VP ('". $this->getContext()->getVirtualPath(). "').");
	}

	/**
	 * Display a error page stating that the MVC Action could not found
	 * @return MVCResult
	 */
	function ActionNotFound() {
		return MVCResult::HtmlContent("Cannot find the action for the controller of '". $this->getContext()->getVirtualPath(). "'.");
	}
}