<?php
/**
 * User: CallumCarmicheal
 * Date: 18/12/2016
 * Time: 03:33
 */

class Input {
	
	// Returns -1 for not found
	
	/**
	 * @param $string mixed Searched input
	 * @param $default mixed Default returned value
	 * @return mixed returns $default when not found.
	 */
	public static function get($string, $default = -1) {
		
		if (!empty($_POST[$string]))
			return $_POST[$string];
		
		if (!empty($_GET[$string]))
			return $_GET[$string];
		
		return $default;
	}
	
	public static function contains($string) {
		$s = false;
		
		if (!empty($_POST[$string])) $s = true;
		if (!empty($_GET[$string]))  $s = true;
		return $s;
	}
}