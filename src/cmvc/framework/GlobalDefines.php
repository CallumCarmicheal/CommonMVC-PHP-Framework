<?php
/**
 * Proj: Chaotic360 - API
 * User: Callum Carmicheal
 * Date: 25/09/2016
 * Time: 03:19
 */

define("CMVC_MVC_ERROR_IDS_INVALID_CONTROLLER", 	0);
define("CMVC_MVC_ERROR_IDS_MISSING_CONTROLLER", 	1);
define("CMVC_MVC_ERROR_IDS_MISSING_ACTION", 		2);
define("CMVC_MVC_ERROR_IDS_CONTROLLER_DISABLED", 	3);

if (!function_exists("_require_all")) {
	function _require_all($dir, $depth = 0) {
		if ($depth > 50) {
			return;
		}
		
		// require all php files
		$scan = glob("$dir/*");
		foreach ($scan as $path) {
			if (preg_match('/\.php$/', $path)) {
				//echo "Included $path <br>";
				require_once $path;
			} else if (is_dir($path)) {
				_require_all($path, $depth + 1);
			}
		}
	}
}