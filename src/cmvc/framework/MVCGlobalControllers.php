<?php

/**
 *  CMVC  PHP | A hackable php mvc framework written
 *  FRAMEWORK | from scratch with love
 * -------------------------------------------------------
 *   _______  ____   _______                ___  __ _____
 *  / ___/  |/  | | / / ___/               / _ \/ // / _ \
 * / /__/ /|_/ /| |/ / /__                / ___/ _  / ___/
 * \___/_/  /_/ |___/\___/               /_/  /_//_/_/
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

class MVCGlobalControllers {

	/**
	 * Get a global MVC Controller
	 * @param $controllerName string
	 * @return MVCContext|bool
	 */
	public static function MVC_GetController($controllerName) {
		$cDir = CMVC_PRJ_DIRECTORY_CONTROLLERS_ERRORS;
		$cNsp = CMVC_PRJ_NAMESPACE_CONTROLLERS_ERRORS;

		$CFile = $controllerName. "Controller.php";
		$Path  = $cDir . "/". $CFile;

		// Check if the controller exists
		if(!file_exists($Path))
			return false;

		// Create context
		// $Namespace 	$Controller	 $Action 	  $FileName
		// $Folder  	$Path 		 $VirtualPath
		
		// Manually create the context
		$ctx = new MVCContext(
			$cNsp,              // Namespace
			$controllerName,    // Controller name
			"",                 // default = index
				
			$CFile,             // File's Name
			$cDir,              // Files Directory
			$Path,              // Full Path
			""                  // Virtual Path
		);

		return $ctx;
	}

	/**
	 * Get the mvc vpath controller
	 * @return MVCContext
	 */
	public static function MVC_VPathController() {
		return self::MVC_GetController("VPath");
	}
	
	/**
	 * Get the mvc database controller
	 * @return MVCContext
	 */
	public static function MVC_DatabaseController() {
		return self::MVC_GetController("Database");
	}

	/**
	 * Get the mvc access controller
	 * @return MVCContext
	 */
	public static function MVC_AccessController() {
		return self::MVC_GetController("Access");
	}
}