<?php

namespace App\Controllers;

use App\Models\Test;
use CommonMVC\MVC\MVCController;
use CommonMVC\MVC\MVCResult;

class IndexController extends MVCController {
	
	public function __construct() {
		$this->ControllerName = "Index";
		$this->Enabled = true;
	}
	
	// Case insensitive
	public function index() {
		return MVCResult::HtmlContent('Hello World!');
	}
	
	public function ListGetFirstItem() {
		
	}
	
	public function AddOneItem() {
		
	}
}