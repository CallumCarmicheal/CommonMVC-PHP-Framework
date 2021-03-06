<?php

namespace App\Controllers;

use App\Models\Test;
use CommonMVC\Framework\MVCController;
use CommonMVC\Framework\MVCResult;

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
}