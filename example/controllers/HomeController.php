<?php
/**
 * User: Callum Carmicheal
 * Date: 23/09/2016
 * Time: 20:26
 */

namespace ExampleProject\Controllers;

	use CommonMVC\Classes\Storage\Templates;
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
			$replace = array( 'SOME_STRING' => 'Hello World!' );
			$html = Templates::ReadTemplate("Home/Dashboard", true, $replace);

			if(!$html)
				 return MVCResult::SimpleHTML("Cannot find the required template resource (Dashboard.html)");
			else return MVCResult::SimpleHTML($html);
		}
	}