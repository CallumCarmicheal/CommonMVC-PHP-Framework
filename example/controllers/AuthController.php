<?php
/**
 * User: Callum Carmicheal
 * Date: 26/09/2016
 * Time: 16:45
 */

namespace ExampleProject\Controllers;

	use CommonMVC\Classes\Authentication\AuthStatus;
	use CommonMVC\Classes\Storage\Templates;
	use CommonMVC\MVC\MVCController;
	use CommonMVC\MVC\MVCResult;
	use CommonMVC\MVC\MVCResultEnums;

	class AuthController extends MVCController {

		public function __construct() {
			$this->ControllerName 	= "";
			$this->Enabled 			= true;
			$this->AuthRequired 	= false;
		}


		/**
		 * @return MVCResult
		 */
		public function Index() {
			// Redirect to dashboard
			// if the user is logged in
			if (AuthStatus::isLoggedIn())
				return MVCResult::Redirect(
					"SecureArea/Home/Index",
					MVCResultEnums::$REDIRECT_MVC);

			// Redirect to login if the user is not
			// logged in
			return $this->Login();
		}

		public function Login() {
			// Redirect to dashboard
			// if the user is logged in
			if (AuthStatus::isLoggedIn())
				return MVCResult::Redirect(
					"SecureArea/Home/Index",
					MVCResultEnums::$REDIRECT_MVC);

			// Return a crappy gui

			$replace = array( 'SOME_STRING' => 'Hello World!' );
			$html = Templates::ReadTemplate("Auth/Login", false, $replace);

			if(!$html)
				return MVCResult::HtmlContent("Cannot find the required template resource (Dashboard.html)");
			else return MVCResult::HtmlContent($html);
		}
	}