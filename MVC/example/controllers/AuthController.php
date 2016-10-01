<?php
/**
 * User: Callum Carmicheal
 * Date: 26/09/2016
 * Time: 16:45
 */

namespace ExampleProject\Controllers;

	use CommonMVC\Classes\Authentication\AuthHandler;
	use CommonMVC\Classes\Storage\Templates;
	use CommonMVC\MVC\MVCController;
	use CommonMVC\MVC\MVCResult;
	
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
			if (AuthHandler::isLoggedIn())
				return MVCResult::Redirect(
					"SecureArea/Home/Index",
					MVCResult::$REDIRECT_MVC);

			// Redirect to login if the user is not
			// logged in
			return $this->Login();
		}

		public function Login() {
			// Redirect to dashboard
			// if the user is logged in
			if (AuthHandler::isLoggedIn())
				return MVCResult::Redirect(
					"SecureArea/Home/Index",
					MVCResult::$E_REDIRECT_MVC);
			
			// Return html
			$html = Templates::ReadTemplate("Auth/Login", false);

			if(!$html)
			 	 return MVCResult::HtmlContent("Cannot find the required template resource (Auth/Login.html)");
			else return MVCResult::HtmlContent($html);
		}
		
		public function Register() {
			// Redirect to dashboard
			// if the user is logged in
			if (AuthHandler::isLoggedIn())
				return MVCResult::Redirect(
					"SecureArea/Home/Index",
					MVCResult::$E_REDIRECT_MVC);
			
			// Return html
			$html = Templates::ReadTemplate("Auth/Register", false);
			
			if(!$html)
				 return MVCResult::HtmlContent("Cannot find the required template resource (Auth/Register.html)");
			else return MVCResult::HtmlContent($html);
		}
	}