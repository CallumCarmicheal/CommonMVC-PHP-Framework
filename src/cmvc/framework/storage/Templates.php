<?php
/**
 * User: Callum Carmicheal
 * Date: 24/09/2016
 * Time: 03:10
 */

namespace CommonMVC\Classes\Storage;


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