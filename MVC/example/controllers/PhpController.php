<?php
/**
 * User: Callum Carmicheal
 * Date: 01/10/2016
 * Time: 00:35
 */

namespace ExampleProject\Controllers;


	use CommonMVC\MVC\MVCController;
	use CommonMVC\MVC\MVCResult;

	class PhpController extends MVCController {


		public function __construct() {
			$this->ControllerName = "Php";
			$this->Enabled = true;
			$this->AuthRequired = false;
		}

		public function Index() {
			return $this->Info();
		}


		public function Info() {
			ob_start(); phpinfo();
			return MVCResult::HtmlContent(ob_get_clean(), true);
		}
	}