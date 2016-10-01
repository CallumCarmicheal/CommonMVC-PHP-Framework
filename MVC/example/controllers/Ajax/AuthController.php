<?php
/**
 * User: Callum Carmicheal
 * Date: 27/09/2016
 * Time: 19:46
 */

namespace ExampleProject\Controllers\Ajax;


	use CommonMVC\Classes\Authentication\AuthHandler;
	use CommonMVC\MVC\MVCController;
	use CommonMVC\MVC\MVCResult;
	use MVC\example\classes\Ajax\AuthHelper;
	
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
			
			return MVCResult::Redirect("/", MVCResult::$E_REDIRECT_MVC);
		}

		/**
		 * @return MVCResult
		 */
		public function Login() {
			// Check if the request is ajax and is post
			if (!($this->Context->isCallAjax() && ($_SERVER['REQUEST_METHOD'] == "POST"))) {
				// Return error 
				return json_encode(array (
					'allowAccess' => false,
					'rejectMessage' => 'Invalid Request call'
				));
			}
			
			$arr = array(
				'allowAccess'   => false,
				'rejectMessage' => "Default Login Message"
			);

			return MVCResult::ApplicationContent(jsoN_encode($arr), "application/json", true);
		}
		
		/**
		 * @return MVCResult
		 */
		public function Register() {
			
			// Check if the request is ajax and is post
			if (!($this->Context->isCallAjax() && ($_SERVER['REQUEST_METHOD'] == "POST"))) {
				// Return error 
				return MVCResult::ApplicationContent(json_encode(array (
					'allowAccess' => false,
					'rejectMessage' => 'Invalid Request call'
				)));
			}
			
			// Check if the post parameters are here and are correct
			$req = AuthHelper::ValidRequest_Register($Username, $Password, $Email);
			
			if(!$req->isState()) {
				return MVCResult::ApplicationContent(json_encode(array (
					'allowAccess' => false,
					'rejectMessage' => 'VR_R: '. $req->getMessage()
				)));
			}
			
			/* Register the new account */ {
				$req = AuthHandler::RegisterNewUser($Username, $Password, $Email);
				
				if(!$req->isCreated()) {
					return MVCResult::ApplicationContent(json_encode(array (
						'allowAccess' => false,
						'rejectMessage' => 'RNU: '. $req->getErrorText()
					)));
				}
			}
			
			$arr = array(
				'allowAccess'   => true,
				'rejectMessage' => "User $Username has been registered successfully!"
			);
			
			return MVCResult::ApplicationContent(json_encode($arr), "application/json", true);
		}
	}