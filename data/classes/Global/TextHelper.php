<?php
/**
 * User: Callum Carmicheal
 * Date: 27/09/2016
 * Time: 19:06
 */

if (!function_exists('mb_ucfirst')) {
	function mb_ucfirst($str, $encoding = "UTF-8", $lower_str_end = false) {
		$first_letter = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
		$str_end = "";

		if ($lower_str_end)
			 $str_end = mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding), $encoding);
		else $str_end = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);

		$str = $first_letter . $str_end;
		return $str;
	}
}