<?php
/**
 * User: Callum Carmicheal
 * Date: 27/09/2016
 * Time: 19:46
 */

namespace ExampleProject\Controllers\Ajax;


	use CommonMVC\MVC\MVCController;
	use CommonMVC\MVC\MVCResult;
	use CommonMVC\MVC\MVCResultEnums;

	class AuthController extends MVCController {

		public function __construct() {
			$this->ControllerName = "Ajax/Auth";
			$this->Enabled = true;
			$this->IndexEnabled = false;
			$this->AuthRequired = false;
		}


		/**
		 * @return MVCResult
		 */
		public function Index() {
			// Redirect to the default controller
			return MVCResult::Redirect("/", MVCResultEnums::$REDIRECT_MVC);
		}

		/**
		 * @return MVCResult
		 */
		public function Login() {
			$arr = array(
				'allowAccess' => false,
				'rejectMessage' => "Default Message"
			);

			return MVCResult::ApplicationContent(jsoN_encode($arr), "application/json", true);
		}
	}