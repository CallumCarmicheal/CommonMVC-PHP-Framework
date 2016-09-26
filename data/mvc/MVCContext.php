<?php
/**
 * User: Callum Carmicheal
 * Date: 24/09/2016
 * Time: 19:16
 */

namespace CommonMVC\MVC;

	class MVCContext {
		// --CONTROLLER INFORMATION--
		private $Namespace 		= "";
		private $Controller		= "";
		private $Action			= "";

		// --FILE INFORMATION--------
		private $FileName		= "";
		private $Folder   		= "";
		private $Path 			= "";
		private $VirtualPath	= "";


		public function __construct($Namespace = "", $Controller = "",
									$Action = "",
									$FileName = "",  $Folder = "",
									$Path = "", 	 $VirtualPath = "") {

			// --CONTROLLER INFORMATION--
			$this->Namespace 		= $Namespace;
			$this->Controller		= $Controller;
			$this->Action			= $Action;

			// --FILE INFORMATION--------
			$this->FileName			= $FileName;
			$this->Folder   		= $Folder;
			$this->Path 			= $Path;
			$this->VirtualPath		= $VirtualPath;
		}


		// --CONTROLLER INFORMATION-----------------------------------------------------------------
		public function getNamespace() 						{ return $this->Namespace; }
		public function getController() 					{ return $this->Controller; }
		public function getControllerClass()				{ return $this->Controller . "Controller"; }
		public function getAction()							{ return $this->Action; }
		public function getClass()							{ return $this->Namespace. '\\'.
																		$this->Controller.
																			"Controller";}
		// --FILE INFORMATION-----------------------------------------------------------------------
		public function getFileName() 						{ return $this->FileName; }
		public function getFolder() 						{ return $this->Folder; }
		public function getPath() 							{ return $this->Path; }
		public function getVirtualPath()					{ return $this->VirtualPath; }

		// --CONTROLLER INFORMATION-----------------------------------------------------------------
		public function setNamespace($Namespace)			{ $this->Namespace 		= $Namespace; }
		public function setController($Controller) 			{ $this->Controller 	= $Controller; }
		public function setAction($Action) 					{ $this->Action 		= $Action; }
		// --FILE INFORMATION-----------------------------------------------------------------------
		public function setFileName($FileName) 				{ $this->FileName = $FileName; }
		public function setFolder($Folder)					{ $this->Folder = $Folder; }
		public function setPath($Path) 						{ $this->Path 			= $Path; }
		public function setVirtualPath($VirtualPath) 		{ $this->VirtualPath = $VirtualPath; }


		public function __toString() {
			// TODO: Implement __toString() method.

			$tmp = array(
				'Namespace'  	=> $this->Namespace,
				'Controller' 	=> $this->Controller,
				'Class' 	 	=> $this->getClass(),
				'Action' 	 	=> $this->Action,
				'FileName' 	 	=> $this->FileName,
				'Folder' 	 	=> $this->Folder,
				'Path' 		 	=> $this->Path,
				'VirtualPath' 	=> $this->VirtualPath
			);

			// How to cheat 101
			return print_r($tmp, true);
		}

	}