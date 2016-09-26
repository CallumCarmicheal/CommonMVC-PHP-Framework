<?php
/**
 * User: Callum Carmicheal
 * Date: 24/09/2016
 * Time: 17:51
 */

namespace CommonMVC\MVC;

	use CommonMVC\MVC\MVCResult;
	use ExampleProject\Controllers\MvcErrors\VPathController;

	class MVCExecutor {



		public static $ENUM_EXECUTE_RESULT_SUCCESS 	   = 0;
		public static $ENUM_EXECUTE_RESULT_ERR_NO_CTRL = 1;
		public static $ENUM_EXECUTE_RESULT_ERR_NO_ACT  = 1;


		/**
		 * Handles redirects
		 * @param $mvc MVCResult
		 * @param $ctx MVCContext
		 * @return int Error Code
		 */
		private function HandleRedirect($mvc, $ctx) {
			// Clear the output buffer
			ob_get_clean();
			$url = $mvc->getHttpRedirect();

			// Check if the redirect is a simple redirect to url
			if($mvc->getHttpRedirectT() == MVCResultEnums::$REDIRECT_EXTERNAL) {
				// This is just a simple url redirect
				header("location: $url");
				die("Redirecting to $url");
			}

			// The redirect is to a MVC Controller
			// 1. Get the SERV->RedirectURL
			// 2. Remove the MVC Virtual Path from the URI
			// 3. That is the base url
			// 4. Append the wanted Virtual Path
			// 5. Redirect to it
			$surl = $_SERVER['REDIRECT_URL'];
			$baseurl = str_replace($ctx->getVirtualPath(), "", $surl);
			$url = $baseurl. $mvc->getHttpRedirect();

			// Set the location header
			header("location: $url");
			die("Redirecting to $url"); exit;

		// Even though this wont work ill do it to
		// shut up phpstorm
		return 0; }

		/**
		 * Handle MVCResult errors
		 * @param $controller VPathController
		 * @param $mvc MVCResult
		 * @param $ctx MVCContext
		 * @return int Error Code
		 */
		private function HandleError($controller, $mvc, $ctx) {
			// Print a pretty yet very ugly hard for a user to understand
			// error page so they go running and screaming to the hills!!

			// Todo That crap above!

		return 0; }

		/**
		 * Handle MVCResult invalid requests
		 * @param $controller VPathController
		 * @param $mvc MVCResult
		 * @param $ctx MVCContext
		 * @return int Error Code
		 */
		private function HandleInvalid($controller, $mvc, $ctx) {
			// No idea what this will be used for so yeah...
		return 0; }

		/**
		 * Handle MVCResult success
		 * @param $controller VPathController
		 * @param $mvc MVCResult
		 * @param $ctx MVCContext
		 * @return int Error Code
		 */
		private function HandleSuccess($controller, $mvc, $ctx) {
			// First check if we are required to clean the output and headers
			if ($mvc->getHttpClean() == MVCResultEnums::$HTTP_CLEAN_CONTENT)
				ob_get_clean();

			if ($mvc->getHttpResult() == MVCResultEnums::$HTTP_RESULT_OK) {
				// Just echo the html...
				die($mvc->getPageContent());
			} else {
				// TODO: HANDLE HTTP RESULTS WHICH ARE NOT OK/SUCCESS
			}

		return 0; }

		/**
		 * Execute a MVCResult
		 * @param $controller MVCController The MVC Controller
		 * @param $mvc MVCResult The Controller's result
		 * @param $ctx MVCContext The MVC Context
		 * @return int Error Code
		 */
		public function ExecuteControllerResult($controller, $mvc, $ctx) {
			// MVC Result Type
			$mvcType = $mvc->getPageResult();

			// Check if the result is a redirect
			if ($mvcType == MVCResultEnums::$RESULT_REDIRECT)
				return $this->HandleRedirect($mvc, $ctx);

			// Check if the result is a error
			if ($mvcType == MVCResultEnums::$RESULT_ERROR)
				return $this->HandleError($controller, $mvc, $ctx);

			// Check if the result is invalid
			if ($mvcType == MVCResultEnums::$RESULT_INVALID)
				return $this->HandleInvalid($controller, $mvc, $ctx);

			if ($mvcType == MVCResultEnums::$RESULT_SUCCESS)
				return $this->HandleSuccess($controller, $mvc, $ctx);

			// Okay so we have handled all valid mvcTypes
			// lets bitch about it if we reach here!
			ob_get_clean();
			echo "MVCExecution Error: MVCType Fallthrough failure!";
			return "I AM BITCHING ABOUT A ERROR!";
		}

		/**
		 * Execute a controller
		 * @param $Controller MVCController
		 * @param $Context MVCContext
		 * @return int Error Code
		 */
		public function ExecuteController($Controller, $Context) {
			if ($Controller == null)
				return self::$ENUM_EXECUTE_RESULT_ERR_NO_CTRL;

			if ($Context->getAction() == "")
				$Context->setAction("Index");

			// Check if the method exists
			if(!method_exists($Controller, $Context->getAction())) {
				// Check for the error controler
				$eCtx  = MVCGlobalControllers::MVC_VPathController();
				$eCtrl = self::GetControllerFromContext($eCtx);

				// Set the Action to ControllerNotFound
				$eCtx->setAction("ControllerNotFound");

				if (!$eCtrl)
					 return $this->errorCtrlNotFound($Context, $eCtx, "Action/Page");
				else return $this->runErrorController($Context, $eCtrl, "ActionNotFound");
			} else {
				// Set the Controller's context
				$Controller->setContext($Context);

				$action = $Context->getAction();

				/**
				 * @var MVCResult
				 */
				$result = $Controller->$action();

				return self::ExecuteControllerResult($Controller, $result, $Context);
			}
		}


		/**
		 * Display a generic error
		 * @param $ctx MVCContext
		 * @param $eCtx MVCContext
		 * @param $errorType string
		 * @param $errorControllerOnly bool
		 * @return int Error Code
		 */
		private function errorCtrlNotFound($ctx, $eCtx, $errorType, $errorControllerOnly = false) {
			ob_get_clean();

			if ($errorControllerOnly) {
				echo "Error ". MVCResultEnums::$HTTP_RESULT_ERROR. ": Cant find the controller and/or action";
				echo "<pre>Controller:      ". $ctx->getControllerClass(). "</pre>";
				echo "<pre>Expected Path:   ". $eCtx->getPath(). "</pre>";
				echo "<pre>Expected Action: ". $eCtx->getAction(). "</pre>";
			} else {
				echo "Error 404: Cannot find The requested $errorType.<br>";
				echo "<pre>Controller:      ". $ctx->getControllerClass(). 	"</pre>";
				echo "<pre>Expected Path:   ". $ctx->getPath(). 			"</pre>";
				echo "<pre>Expected Action: ". $ctx->getAction(). 			"</pre>";
				echo "<br>";

				echo "Error ". MVCResultEnums::$HTTP_RESULT_ERROR. ": Cant find the controller and/or action";
				echo "<pre>Controller:      ". $ctx->getControllerClass(). "</pre>";
				echo "<pre>Expected Path:   ". $eCtx->getPath(). "</pre>";
				echo "<pre>Expected Action: ". $eCtx->getAction(). "</pre>";
			}

			die("");
		}

		/**
		 * Run a error page
		 * @param $ctx MVCContext
		 * @param $eCtrl MVCController
		 * @param $action string
		 * @return int Error Code
		 */
		private function runErrorController($ctx, $eCtrl, $action) {
			// Check if the controller has the action
			if(!method_exists($eCtrl, $action))
				return $this->errorCtrlNotFound($ctx, $eCtrl->getContext(), "", true);

			$eCtx = $eCtrl->getContext();
			$eCtrl->setContext($ctx);

			/**
			 * @var MVCResult
			 */
			$result = $eCtrl->$action();

			return $this->ExecuteControllerResult($eCtrl, $result, $eCtx);
		}

		/**
		 * Execute a controller's context
		 * @param $ctx MVCContext
		 * @return int Error Code
		 */
		public function ExecuteControllerContext($ctx) {
			$ctrl = $this->GetControllerFromContext($ctx);

			if (!$ctrl) {
				// Check for the error controler
				$eCtx  = MVCGlobalControllers::MVC_VPathController();
				$eCtrl = self::GetControllerFromContext($eCtx);

				if (!$eCtrl)
					 return $this->errorCtrlNotFound($ctx, $eCtx, "Controller");
				else {
					$eCtrl->setContext($eCtx);
					return $this->runErrorController($ctx, $eCtrl, "ControllerNotFound");
				}
			}

			// Execute the controller
			return self::ExecuteController($ctrl, $ctx);
		}

		/**
		 * Get a controller from a Context
		 * @param $ctx MVCContext
		 * @return MVCController controller
		 */
		public function GetControllerFromContext($ctx) {
			if(!file_exists($ctx->getPath()))
				return false;

			require_once $ctx->getPath();
			$class = $ctx->getClass();

			if(class_exists($class))
				 return new $class();
			else return false;
		}
	}