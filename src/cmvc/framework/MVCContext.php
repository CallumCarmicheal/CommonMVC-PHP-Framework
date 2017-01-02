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
	
	// --MVC INFORMATION---------
	private $CallMVC		= false; // This if the call is from the MVC Executor etc
	private $CallAjax		= false; // If the call is ajax

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
		
		$this->CallAjax = MVCHelper::isAjax();
	}


	// --CONTROLLER INFORMATION------------------------------------------------------------------------
	public function getNamespace() 						{ return $this->Namespace; }
	public function getController() 					{ return $this->Controller; }
	public function getControllerClass()				{ return $this->Controller . "Controller"; }
	public function getAction()							{ return $this->Action; }
	public function getClass()							{ return $this->Namespace.'\\'.
																	$this->Controller. "Controller";}
	// --FILE INFORMATION------------------------------------------------------------------------------
	public function getFileName() 						{ return $this->FileName; }
	public function getFolder() 						{ return $this->Folder; }
	public function getPath() 							{ return $this->Path; }
	public function getVirtualPath()					{ return empty($this->VirtualPath) ? '/' : $this->VirtualPath; }
	// --MVC INFORMATION-------------------------------------------------------------------------------
	public function isCallAjax() 						{ return $this->CallAjax; }
	public function isCallMVC()  						{ return $this->CallMVC; }

	// --CONTROLLER INFORMATION------------------------------------------------------------------------
	public function setNamespace($Namespace)			{ $this->Namespace 		= $Namespace; }
	public function setController($Controller) 			{ $this->Controller 	= $Controller; }
	public function setAction($Action) 					{ $this->Action 		= $Action; }
	// --FILE INFORMATION------------------------------------------------------------------------------
	public function setFileName($FileName) 				{ $this->FileName		= $FileName; }
	public function setFolder($Folder)					{ $this->Folder 		= $Folder; }
	public function setPath($Path) 						{ $this->Path 			= $Path; }
	public function setVirtualPath($VirtualPath) 		{ $this->VirtualPath 	= $VirtualPath; }


	public function __toString() {
		$tmpClass = array (
		    'Evaluated' => 'Namespace + "\\" + Controller + "Controller"',
			'Result' => $this->getClass()
		);

		$tmp = array(
			'Namespace'  	=> $this->Namespace,
			'Controller' 	=> $this->Controller,
			'Class' 	 	=> $tmpClass,
			'Action' 	 	=> $this->Action,
			'FileName' 	 	=> $this->FileName,
			'Folder' 	 	=> $this->Folder,
			'Path' 		 	=> $this->Path,
			'VirtualPath' 	=> $this->VirtualPath
		);

		// How to cheat 101
		return (string) print_r($tmp, true);
	}

}