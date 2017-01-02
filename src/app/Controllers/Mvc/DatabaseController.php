<?php

namespace App\Controllers\Mvc;

use CommonMVC\MVC\MVCController;
use CommonMVC\MVC\MVCResult;

class DatabaseController extends MVCController {
	
	public function __construct() {
		$this->ControllerName = "Mvc/Errors/Database";
		$this->Enabled = false; // Disabled any url calls
	}
	
	public function FailedConnection() {
		return MVCResult::HtmlContent('Failed to connect to the database!');
	}
	
	/**
	 * @param $exception \Exception
	 * @return MVCResult
	 */
	public function FailedQuery($exception) {
		return MVCResult::HtmlContent('Failed to run query:\n Message: '. $exception->getMessage().
			"\n File: ". $exception->getFile(). ":".  $exception->getLine(). " | ". $exception->getCode()
		);
	
	}
}