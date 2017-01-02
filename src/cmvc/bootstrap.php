<?php

use CommonMVC\Classes\Storage\Database;
use CommonMVC\MVC\MVCContext;
use CommonMVC\MVC\MVCExecutor;
use CommonMVC\MVC\MVCHelper;
use CommonMVC\MVC\MVCResult;

class Bootstrap {
	
	/**
	 * @var $mvc MVCExecutor
	 */
	private $mvc;

	public function run($VirtualPath) {
		echo "<pre>";
		
		// Load the files required to run the mvc
		$this->LoadFiles();

		// Test database connection
		$this->TestDatabase();
		
		// Setup our executor 
		$this->mvc = new MVCExecutor();
		
		// Get our controller context
		$ctx = $this->getController($VirtualPath);
		
		// Pre-process the request
		$this->PreProcessRequest($ctx);
		
		// Run the controller
		$this->executeContext($ctx);
	}
	
	/**
	 * @param $context MVCContext
	 */
	private function PreProcessRequest($context) {
		$action = "ProcessRequest";
		$file   = CMVC_PRJ_DIRECTORY_EVENTS. "/PreProcess.php";
		$class  = CMVC_PRJ_NAMESPACE_EVENTS. "\\PreProcess";
	
		if (!file_exists ($file))
			return;
		
		$class::$action($context);
	}
	
	private function LoadFiles() {
		// First require our configuration files
		require_once (__DIR__. '/../config/'. 'cmvc.php');
		require_once (__DIR__. '/../config/'. 'database.php');
		require_once (__DIR__. '/../config/'. 'fileloader.php');
		require_once (__DIR__. '/../config/'. 'framework.php');
		
		// Load the config and framework
		\Config\Framework::Setup();
		\Config\Framework::Load();
		
		// Load the application files
		\Config\FileLoader::LoadFiles();
	}
	
	/**
	 * @param $VirtualPath string Virtual Path
	 * @return \CommonMVC\MVC\MVCContext
	 */
	private function getController($VirtualPath) {
		return MVCHelper::ResolveVirtualPath(
			CMVC_PRJ_DIRECTORY_CONTROLLERS,
			CMVC_PRJ_NAMESPACE_CONTROLLERS,
			$VirtualPath
		);
	}

	/**
	 * @param $context MVCContext
	 * @return int
	 */
	private function executeContext($context) {
		return $this->mvc->ExecuteControllerContext($context);
	}
	
	private function TestDatabase() {
		// Get the pdo
		// if it cannot connect
		// it throws a error to the
		// controller:
		//     Controllers\Mvc\DatabaseController->FailedConnection
		$pdo = Database::GetPDO();
	}
}