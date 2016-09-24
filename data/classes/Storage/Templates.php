<?php
/**
 * User: Callum Carmicheal
 * Date: 24/09/2016
 * Time: 03:10
 */

namespace CommonMVC\Classes\Storage;


	class Templates {

		/**
		 * Reads in the template and executes the code within the template and
		 * returns the scripts result
		 * @param $scriptLocation Template Location
		 * @return string Template output
		 */
		public static function ReadTemplate($scriptLocation) {
			ob_start();
			$script = "templates/". $scriptLocation;
			/** @noinspection PhpIncludeInspection */ include ($script);
			return ob_get_clean();
		}
	}