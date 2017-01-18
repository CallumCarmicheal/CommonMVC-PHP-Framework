<?php
/**
 * User: CallumCarmicheal
 * Date: 18/12/2016
 * Time: 03:33
 */

class Input {
	
	// Returns -1 for not found
	
	/**
	 * Get a string from the request
	 * @param $string mixed Searched input
	 * @param $type string Type to return, post|p, get|g or any.
	 * @param $default mixed Default returned value
	 * @return mixed returns $default when not found.
	 */
	public static function get($string, $type="any", $default = "") {
		//ob_clean(); echo "GET: ". $type. ", ". $string. " = ". $_GET[$string]; exit;
		
		$tl = mb_strtolower($type);
		
		if ($tl == "post" || $tl == "p") {
			if (!empty($_POST[$string])) return $_POST[$string];
			else                         return $default;
		} else if ($tl == "get" || $tl == "g") {
			if (!empty($_GET[$string]))  return $_GET[$string];
			else                         return $default;
		}
		
		// Always favor the post input
		// first, this will stop any
		// users trying to bypass post
		// through html params
		if (!empty($_POST[$string]))
			return $_POST[$string];
		
		if (!empty($_GET[$string]))
			return $_GET[$string];
		
		return $default;
	}
	
	/**
	 * Checks if the request contains a argument
	 * @param $string mixed Searched input
	 * @param $type string Type to return, post|p, get|g or any.
	 * @return bool If the request contains the argument
	 */
	public static function contains($string, $type = "any") {
		$tl = mb_strtolower($type);
		
		if ($tl == "post" || $tl == "p")
			return (!empty($_POST[$string]));
		
		else if ($tl == "get" || $tl == "g")
			return (!empty($_GET[$string]));
		
		if (!empty($_POST[$string])) return true;
		if (!empty($_GET[$string]))  return true;
		
		return false;
	}
}