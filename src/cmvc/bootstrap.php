<?php

use CommonMVC\Framework\Storage\Database;
use CommonMVC\Framework\MVCContext;
use CommonMVC\Framework\MVCExecutor;
use CommonMVC\Framework\MVCHelper;
use CommonMVC\Framework\MVCResult;

class Bootstrap {
	
	/**
	 * @var $mvc MVCExecutor
	 */
	private $mvc;
	
	/**
	 * Starts the framework
	 * @param $VirtualPath string
	 */
	public function run($VirtualPath) {
		// Start the session
		session_start();
		
		// Load the files required to run the mvc
		// |- Loads Configs
		// |- Loads rest of application from "config/fileloader.php"
		$this->LoadFiles();

		// Tests database connection
		// |- Checks if the database is connected
		// |- If not runs Controllers\Mvc\DatabaseController->FailedConnection
		$this->TestDatabase();
		
		// Setup our executor 
		// |- Executes a Controller
		$this->mvc = new MVCExecutor();
		
		// Get our controller context
		// |- Gets the controller's context from 
		// |  a virtual path.
		$ctx = $this->getController($VirtualPath);
		
		// Pre-process the request
		// |- Runs Events\PreProcessL::ProcessRequest($context)
		// |  this allows to check if the current user has access
		// |  to a path. Essentially runs before every page
		// |  and can act as a shield to verify and requests.
		$this->PreProcessRequest($ctx);
		
		// Run the controller
		// |- Calls $controller->$action() and executes the response
		$this->executeContext($ctx);
	}
	
	/**
	 * Pre-processes each request
	 * @param $context MVCContext
	 */
	private function PreProcessRequest($context) {
		// Call the preprocessor
		// |- If the preprocessor does not want the
		// |  current request to work it will return
		// |  its own content and return false or exit
		// |  to stop the request
		
		$action = "ProcessRequest";
		$file   = CMVC_PRJ_DIRECTORY_EVENTS. "/PreProcess.php";
		$class  = CMVC_PRJ_NAMESPACE_EVENTS. "\\PreProcess";
	
		// Checks if the file exists
		// |- if not then just returns without
		// |  preprocessing the request
		if (!file_exists ($file)) return;
		
		// Execute require_once on the PreProcess
		// file, this is done because by default
		// it may be in the app folder, although 
		// the programmer may want to move around the 
		// directory structure, and to support this
		// we will run require_once on the file, 
		// if it was already included in the app folder
		// or the CMVC_PRJ_DIRECTORY then it will be skipped
		require_once $file;
		
		// Call the function
		// |- PreProcess::ProcessRequest($context);
		$res = $class::$action($context);
		
		// If a MVCResult then execute it!
		if (is_a($res, MVCResult::class)) {
			$this->mvc->ExecuteControllerResult(null, $res, $context);
		}
		
		// If result is false then exit the application
		if (!$res) exit;
	}
	
	/**
	 * Loads all our included/referenced library files
	 */
	private function LoadFiles() {
		// Recursively require_once all the configuration
		// files.
		self::_require_all(__DIR__. '/../config/');
		
		// Load the config and framework
		// |- Sets up the defines/config for 
		// |  CMVC to work.
		// |- Includes all the files inside the 
		// |  CMVC framework.
		\Config\Framework::Setup();
		\Config\Framework::Load();
		
		// Load the application files
		// |- Loads the CMVC_PRJ_DIRECTORY folder, recursively
		// |  including all files in that folder (essentially 
		// |  every library/class that will be used, everything in
		// |  the folder is loaded except the Controllers directory
		\Config\FileLoader::Load();
	}
	
	/**
	 * Get a controller from the virtual path.
	 * @param $VirtualPath string Virtual Path
	 * @return \CommonMVC\Framework\MVCContext
	 */
	private function getController($VirtualPath) {
		// Resolve a path by using the MVCHelper
		// class.
		// |- Pass the controllers root directory,
		// |  the controllers default namespace,
		// |  and the VirtualPath and it will 
		// |  determine where the file is loaded and
		// |  how to call it.
		return MVCHelper::ResolveVirtualPath(
			CMVC_PRJ_DIRECTORY_CONTROLLERS,
			CMVC_PRJ_NAMESPACE_CONTROLLERS,
			$VirtualPath
		);
	}

	/**
	 * Execute a controller
	 * @param $context MVCContext
	 * @return int
	 */
	private function executeContext($context) {
		// Executes a controller through a Context.
		return $this->mvc->ExecuteControllerContext($context);
	}
	
	/** 
	 * Test if the database is connected.
	 */
	private function TestDatabase() {
		// Get the pdo
		// if it cannot connect
		// it throws a error to the
		// controller:
		//     Controllers\Mvc\DatabaseController->FailedConnection
		$pdo = Database::GetPDO();
	}
	
	/**
	 * Recursively require's files in a directory
	 * @param $dir string
	 * @param int $depth int
	 */
	private function _require_all($dir, $depth=0) {
		// Check if the folder depth is more than 50
		// if so then just return.
		//
		// CHANGE THIS FOR A BIGGER DEPTH
		if ($depth > 50)
			return;
		
		// require all php files
		$scan = glob("$dir/*");
		foreach ($scan as $path) {
			if (preg_match('/\.php$/', $path)) {
				//echo "Included $path <br>";
				require_once $path;
			} else if (is_dir($path)) {
				self::_require_all($path, $depth+1);
			}
		}
	}
}