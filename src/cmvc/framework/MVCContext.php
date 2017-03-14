<?php

/**
 *  CMVC  PHP | A  hackable php mvc framework written
 *  FRAMEWORK | from scratch with love
 * -------------------------------------------------------
 *   _______  ____   _______   ___  __ _____
 *  / ___/  |/  | | / / ___/  / _ \/ // / _ \
 * / /__/ /|_/ /| |/ / /__   / ___/ _  / ___/
 * \___/_/  /_/ |___/\___/  /_/  /_//_/_/
 *    _______  ___   __  ________      ______  ___  __ __
 *   / __/ _ \/ _ | /  |/  / __| | /| / / __ \/ _ \/ //_/
 *  / _// , _/ __ |/ /|_/ / _/ | |/ |/ / /_/ / , _/ ,<
 * /_/ /_/|_/_/ |_/_/  /_/___/ |__/|__/\____/_/|_/_/|_|
 *
 * -------------------------------------------------------
 * Programmed by Callum Carmicheal
 *		<https://github.com/CallumCarmicheal>
 * GitHub Repository
 *		<https://github.com/CallumCarmicheal/CommonMVC-PHP-Framework>
 *
 * Contributors:
 *
 *
 * LICENSE: MIT License
 *      <http://www.opensource.org/licenses/mit-license.html>
 *
 * You cannot remove this header from any CMVC framework files
 * which are under the following directory cmvc->framework.
 * if you are unsure what directory that is, please refer to
 * GitHub:
 * <https://github.com/CallumCarmicheal/CommonMVC-PHP-Framework/tree/master/src>
 *
 * -------------------------------------------------------
 * MIT License
 *
 * Copyright (c) 2017 Callum Carmicheal
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace CommonMVC\Framework;

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