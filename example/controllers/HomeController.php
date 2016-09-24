<?php
/**
 * User: Callum Carmicheal
 * Date: 23/09/2016
 * Time: 20:26
 */

namespace ExampleProject\Controllers;

	use CommonMVC\MVC\MVCContext;
	use CommonMVC\MVC\MVCResult;
	use CommonMVC\MVC\MVCController;
	use CommonMVC\MVC\MVCResultEnums;

	class HomeController extends MVCController {
		function __construct() {
			$this->ControllerName 	= "HomeTest";
			$this->Enabled 			= true;
			$this->AuthRequired 	= true;
		}

		/**
		 * The default page for the Home
		 * @return MVCResult Page Result
		 */
		public function Index() {
			return $this->Dashboard();
		}

		// Here you state the page name as the function
		// (case sensitive :: START WITH A CAPITAL)
		public function Dashboard() {
			// Dashboard is currently WIP
			// So lets just redirect to google
			return MVCResult::Redirect(
				"https://google.com",
				MVCResultEnums::$REDIRECT_EXTERNAL, MVCResultEnums::$HTTP_CLEAN_CONHEAD);

		}
	}