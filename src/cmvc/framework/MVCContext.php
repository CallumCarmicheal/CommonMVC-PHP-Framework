<?php
/**
 * User: Callum Carmicheal
 * Date: 24/09/2016
 * Time: 19:16
 */

namespace CommonMVC\MVC;

class MVCContext {
	// --CONTROLLER INFORMATION--
	private $Namespace 		  = "";
	private $Controller		  = "";
	private $Action			  = "";

	// --FILE INFORMATION--------
	private $FileName		  = "";
	private $Folder   		  = "";
	private $Path 			  = "";
	
	// --VIRTUAL INFORMATION-----
	private $VirtualPath	  = "";
	private $VirtualDirectory = "";
	
	// --MVC INFORMATION---------
	private $CallMVC		= false; // This if the call is from the MVC Executor etc
	private $CallAjax		= false; // If the call is ajax

	public function __construct($Namespace = "", $Controller = "",
								$Action = "",
								$FileName = "",  $Folder = "",
								$Path = "", 	 $VirtualPath = "",
								$VirtualDirectory = "") {

		// --CONTROLLER INFORMATION--
		$this->Namespace 		= $Namespace;
		$this->Controller		= $Controller;
		$this->Action			= $Action;

		// --FILE INFORMATION--------
		$this->FileName			= $FileName;
		$this->Folder   		= $Folder;
		$this->Path 			= $Path;
		
		// --VIRTUAL INFORMATION-----
		$this->VirtualPath		= $VirtualPath;
		$this->VirtualDirectory = $VirtualDirectory;
		
		$this->CallAjax = MVCHelper::isAjax();
	}
	
	
	// --MVC INFORMATION---------------------------GETTERS---------------------------------------------
	public function isCallAjax() 						{ return $this->CallAjax; }
	public function isCallMVC()  						{ return $this->CallMVC; }
	// --CONTROLLER INFORMATION--------------------GETTERS---------------------------------------------
	public function getNamespace() 						{ return $this->Namespace; }
	public function getController() 					{ return $this->Controller; }
	public function getControllerClass()				{ return $this->Controller . "Controller"; }
	public function getAction()							{ return $this->Action; }
	public function getClass()							{ return $this->Namespace.'\\'.
																	$this->Controller. "Controller";}
	// --FILE INFORMATION--------------------------GETTERS---------------------------------------------
	public function getFileName() 						{ return $this->FileName; }
	public function getFolder() 						{ return $this->Folder; }
	public function getPath() 							{ return $this->Path; }
	// --VIRTUAL INFORMATION-----------------------GETTERS---------------------------------------------
	public function getVirtualPath()					{ return empty($this->VirtualPath) ? '/' : $this->VirtualPath; }
	public function getVirtualDirectory()               { return empty($this->VirtualDirectory) ? '/' : $this->VirtualDirectory; }
	// --CONTROLLER INFORMATION--------------------SETTERS---------------------------------------------
	public function setNamespace($Namespace)			{ $this->Namespace 		  = $Namespace; }
	public function setController($Controller) 			{ $this->Controller 	  = $Controller; }
	public function setAction($Action) 					{ $this->Action 		  = $Action; }
	// --FILE INFORMATION--------------------------SETTERS---------------------------------------------
	public function setFileName($FileName) 				{ $this->FileName		  = $FileName; }
	public function setFolder($Folder)					{ $this->Folder 		  = $Folder; }
	public function setPath($Path) 						{ $this->Path 			  = $Path; }
	// --VIRTUAL INFORMATION-----------------------SETTERS---------------------------------------------
	public function setVirtualPath($VirtualPath) 		{ $this->VirtualPath 	  = $VirtualPath; }
	public function setVirtualDirectory($VirtualD)      { $this->VirtualDirectory = $VirtualD; }
	
	public function __toString() {
		$tmpClass = array (
		    'Evaluated' => 'Namespace + "\\" + Controller + "Controller"',
			'Result' => $this->getClass()
		);

		$tmp = array(
			'Namespace'  	    => $this->Namespace,
			'Controller' 	    => $this->Controller,
			'Class' 	 	    => $tmpClass,
			'Action' 	 	    => $this->Action,
			'FileName' 	 	    => $this->FileName,
			'Folder' 	 	    => $this->Folder,
			'Path' 		 	    => $this->Path,
			'VirtualPath' 	    => $this->VirtualPath,
			'VirtualDirectory'  => $this->VirtualDirectory
		);

		// How to cheat 101
		return (string) print_r($tmp, true);
	}

}