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

namespace CommonMVC\Framework\Storage;


/**
 * Templates is a simple replace range implementation
 * where values of in the style of {{VAR.[]} where []
 * is the variable are replaced. Its simple and light
 * weight as can be.
 *
 * USE ONLY AS A LAST RESORT, there are a million better
 * solutions our there.
 *
 * Class Templates
 * @package CommonMVC\Classes\Storage
 */
class Templates {

	/**
	 * Set a template variable's text
	 * @param $TemplateText string Template HTML/Text
	 * @param $TemplateVariable string Template Variable
	 * @param $Text string The text to set the variable to
	 * @return string Replaced Template Output
	 */
	public static function ReplaceTemplateVariable($TemplateText, $TemplateVariable, $Text) {
		return str_replace('{{VAR.'. $TemplateVariable. '}}', $Text, $TemplateText);
	}

	/**
	 * Replace variables in output
	 * @param $TemplateText string Template HTML/Text
	 * @param $TemplateVariables object A KV Array containing the template variables
	 * @return string Replaced Template Output
	 */
	public static function ReplaceTemplateVariables($TemplateText, $TemplateVariables = null) {
		if($TemplateVariables == null) return $TemplateText;
		foreach ($TemplateVariables as $key => $val)
			$TemplateText = str_replace("{{VAR.". $key. "}}", $val, $TemplateText);
		return $TemplateText;
	}

	/**
	 * Reads in the template and executes the code within the template and
	 * 	 returns the scripts result
	 * @param $scriptLocation string Template Location
	 * @param $executePHP bool If true it will load a PHP script and execute code, If false loads html
	 * @param $replacementStrings array Array of strings to replace in the template
	 * @param $dieOnMissing bool Stop execution if the file is missing
	 * @return bool|string Template output
	 */
	public static function ReadTemplate($scriptLocation, $executePHP = false, $replacementStrings = null, $dieOnMissing = false) {
		$fileL = $executePHP ? $scriptLocation. '.php' : $scriptLocation. '.html';
		$fileL = "templates/". $fileL;

		if (!file_exists($fileL)) {
			if($dieOnMissing) ("Cannot find the file ($fileL)");
			return false;
		}

		$replace = !empty($replacementStrings);
		
		if($executePHP) {
			ob_start();
			
			/** @noinspection PhpIncludeInspection */ include ($fileL);
			if($replace) 
				return self::ReplaceTemplateVariables(ob_get_clean(), $replacementStrings);
			return ob_get_clean();
		} else {
			$txt  = file_get_contents($fileL);
			
			if($replace)
				return self::ReplaceTemplateVariables($txt, $replacementStrings);
			return $txt;
		}
	}
}