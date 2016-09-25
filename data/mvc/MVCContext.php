<?php
/**
 * User: Callum Carmicheal
 * Date: 24/09/2016
 * Time: 19:16
 */

namespace CommonMVC\MVC;

	class MVCContext {

		private $Path 			= "";
		private $Namespace 		= "";
		private $Controller		= "";
		private $Action			= "";
		private $FilePath 		= "";
		private $ControllerFile = "";
		private $VirtualPath	= "";

		public function __construct($Path = "", $Namespace = "", $Controller = "", $FilePath = "",
									$ControllerFile = "", $VirtualPathRaw = "", $Action = "") {
			$this->Path 			= $Path;
			$this->Namespace		= $Namespace;
			$this->Controller 		= $Controller;
			$this->FilePath 		= $Controller;
			$this->ControllerFile 	= $ControllerFile;
			$this->VirtualPath 		= $VirtualPathRaw;
			$this->Action			= $Action;
		}

		public function getController() 					{ return $this->Controller; }
		public function getAction()							{ return $this->Action; }
		public function getFilePath() 						{ return $this->FilePath; }
		public function getNamespace() 						{ return $this->Namespace; }
		public function getPath() 							{ return $this->Path; }
		public function getControllerFile()					{ return $this->ControllerFile; }
		public function getVirtualPath()					{ return $this->VirtualPath; }

		public function setController($Controller) 			{ $this->Controller 	= $Controller; }
		public function setAction($Action) 					{ $this->Action 		= $Action; }
		public function setFilePath($FilePath) 				{ $this->FilePath 		= $FilePath; }
		public function setNamespace($Namespace)			{ $this->Namespace 		= $Namespace; }
		public function setPath($Path) 						{ $this->Path 			= $Path; }
		public function setControllerFile($ControllerFile) 	{ $this->ControllerFile = $ControllerFile; }
		public function setVirtualPath($VirtualPath) 		{ $this->VirtualPath = $VirtualPath; }
	}