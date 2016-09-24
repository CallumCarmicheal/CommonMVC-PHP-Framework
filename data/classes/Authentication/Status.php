<?php
	/**
	 * User: Callum Carmicheal
	 * Date: 24/09/2016
	 * Time: 02:50
	 */

	namespace CommonMVC\Classes\Authentication;


	class Status {

		public static function isLoggedIn() {
			return empty($_SESSION[Settings::$SESSION_NAME]);
		}

	}