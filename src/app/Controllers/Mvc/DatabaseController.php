<?php

namespace App\Controllers\Mvc;

use CommonMVC\Framework\MVCController;
use CommonMVC\Framework\MVCResult;

class DatabaseController extends MVCController {
	
	public function __construct() {
		$this->ControllerName = "Mvc/Database";
		$this->WebAccess = false; // Disabled any url calls
	}
	
	/**
	 * Failed to connect to the database
	 *
	 * NOTE: You will have to call this through
	 *       Database::ThrowDatabaseFailedQuery($ex)
	 *       to manually throw this exception
	 *
	 * @param \PDOException|null $exception
	 * @return MVCResult
	 */
	public function FailedConnection($exception = null) {
		return MVCResult::HtmlContent('Failed to connect to the database!');
	}
	
	/**
	 * Failed to query the database
	 *
	 * NOTE: You will have to call this through
	 *       Database::ThrowDatabaseOfflineException($ex)
	 *       to manually throw this exception
	 * @param \PDOException|null $exception
	 * @return MVCResult
	 */
	public function FailedQuery($exception) {
		return MVCResult::HtmlContent('Failed to run query:\n Message: '. $exception->getMessage().
			"\n File: ". $exception->getFile(). ":".  $exception->getLine(). " | ". $exception->getCode()
		);
		
	}
}