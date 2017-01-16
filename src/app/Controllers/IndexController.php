<?php

namespace App\Controllers;

use App\Models\Test;
use CommonMVC\MVC\MVCController;
use CommonMVC\MVC\MVCResult;

class IndexController extends MVCController {
	
	/**
	 * Setups the controller name and states if it
	 * can be accessed from the internet (WebAccess)
	 *
	 * IndexController constructor.
	 */
	public function __construct() {
		$this->ControllerName = "Index";
		$this->WebAccess = true;
	}
	
	/**
	 * Case insensitive, Default action for each controller
	 * @return MVCResult|string
	 */
	public function index() {
		return MVCResult::HtmlContent('Hello World!');
	}
	
	
	/**
	 * ROOT/AddAndList
	 *
	 * Lists all the rows in the test table by using
	 * the tests model. (VAR_DUMP)
	 *
	 * @returns MVCResult|string
	 */
	public function AddAndList() {
		
		// First we want to
		
		
	}
}