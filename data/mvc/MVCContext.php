<?php
/**
 * User: Callum Carmicheal
 * Date: 24/09/2016
 * Time: 19:16
 */

namespace CommonMVC\MVC;


	class MVCContext {

		private $Path 		= "";
		private $Namespace 	= "";
		private $Controller = "";

		private $FilePath 	= "";

		public function __construct($Path = "", $Namespace = "", $Controller = "", $FilePath = "") {
			$this->Path 		= $Path;
			$this->Namespace	= $Namespace;
			$this->Controller 	= $Controller;
			$this->FilePath 	= $Controller;
		}


		public function getController() 			{ return $this->Controller; }
		public function getFilePath() 				{ return $this->FilePath; }
		public function getNamespace() 				{ return $this->Namespace; }
		public function getPath() 					{ return $this->Path; }

		public function setController($Controller) 	{ $this->Controller = $Controller; }
		public function setFilePath($FilePath) 		{ $this->FilePath = $FilePath; }
		public function setNamespace($Namespace)	{ $this->Namespace = $Namespace; }
		public function setPath($Path) 				{ $this->Path = $Path; }
	}