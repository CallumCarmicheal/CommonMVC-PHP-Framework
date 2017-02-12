<?php
/**
* User: Callum Carmicheal
* Date: 24/09/2016
* Time: 17:51
*/

namespace CommonMVC\MVC;

use CommonMVC\Classes\Authentication\AuthHandler;

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
		$url  = $mvc->getHttpRedirect();
		$type = $mvc->getHttpRedirectT();
		
		// Determine path type
		if ($type == MVCResult::$E_REDIRECT_AUTOMATIC) {
			if (MVCHelper::startsWith($url, 'https://') || MVCHelper::startsWith($url, 'http://'))
				 $type = MVCResult::$E_REDIRECT_EXTERNAL;
			else $type = MVCResult::$E_REDIRECT_MVC;
		}
		
		// Check if the redirect is a simple redirect to url
		if($type == MVCResult::$E_REDIRECT_EXTERNAL) {
			// This is just a simple url redirect
			//header("location: $url");
			die("Redirecting to $url");
		}
		
		
		/*/
		OLD IDEA, did not quite work.
		
		The redirect is to a MVC Controller
		// 1. Get the SERV->RedirectURL
		// 2. Remove the MVC Virtual Path from the URI
		// 3. That is the base url
		// 4. Append the wanted Virtual Path
		// 5. Redirect to it
		$surl 		= $_SERVER['REDIRECT_URL'];
		$baseurl 	= str_replace($ctx->getVirtualPath(), "", $surl);
		$url 		= $baseurl. $mvc->getHttpRedirect();
		
		*/
		
		$url = CMVC_ROOT_URL. ltrim($url, '/');
		
		// Set the location header
		header("location: $url");
		die("Redirecting to $url"); exit;
		
	/** @noinspection PhpUnreachableStatementInspection */
	return 0; }

	/**
	 * Handle MVCResult errors
	 * @param $controller MVCController
	 * @param $mvc MVCResult
	 * @param $ctx MVCContext
	 * @return int Error Code
	 */
	private function HandleError($controller, $mvc, $ctx) {
		// Print a pretty yet very ugly hard for a user to understand
		// error page so they go running and screaming to the hills!!

		// TODO: That crap above!
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
		/*
		echo "<pre>";
		var_dump ($mvc);
		echo "\n\nSUCCESS";
		exit; //*/
		
		// First check if we are required to clean the output and headers
		if ($mvc->getHttpClean() == MVCResult::$E_HTTP_CLEAN_CONTENT)
			ob_get_clean();

		if ($mvc->isHeaderCustomContent())
			header("Content-Type: ". $mvc->getHeaderContentType());

		if ($mvc->getHttpResult() == MVCResult::$E_HTTP_RESULT_OK) {
			// Just echo the html...
			die($mvc->getPageContent());
		} else {
			// TODO: HANDLE HTTP RESULTS WHICH ARE NOT OK/SUCCESS
			
			if ($mvc->getHttpResult() == MVCResult::$E_HTTP_RESULT_NOTFOUND) {
				header("HTTP/1.0 404 Not Found");
				die($mvc->getPageContent());
			}
		}

	return 0; }

	/**
	 * Execute a MVCResult
	 * @param $controller MVCController The MVC Controller
	 * @param $res MVCResult The Controller's result
	 * @param $ctx MVCContext The MVC Context
	 * @return int Error Code
	 */
	public function ExecuteControllerResult($controller, $res, $ctx) {
		// Check if the result is a viewable type such as text, int.
		if (is_string($res) || is_int($res)) {
			// Clear the output
			ob_get_clean();
			
			// Echo the string
			// then exit
			
			if (is_int($res) && $res == 0) $res = "0";
			die ($res);
		} else if (is_null($res)) {
			// The result is null or not returned
			// we cannot do anything, it was maybe a echo
			//
			// For the framework we have 2 choices,
			// let it slide and just exit or 2 clean and exit.
			// for now im going to let it slide and let the developers
			// choose later in a option.
			exit;
		} else if (is_array($res)) {
			// Set the $res to ApplicationContent
			$res = MVCResult::ApplicationContent($res);
		}
		
		// MVC Result Type
		$mvcType = $res->getPageResult();

		// Check if the result is a redirect
		if ($mvcType == MVCResult::$E_RESULT_REDIRECT)
			return $this->HandleRedirect($res, $ctx);

		// Check if the result is a error
		if ($mvcType == MVCResult::$E_RESULT_ERROR)
			return $this->HandleError($controller, $res, $ctx);

		// Check if the result is invalid
		if ($mvcType == MVCResult::$E_RESULT_INVALID)
			return $this->HandleInvalid($controller, $res, $ctx);

		if ($mvcType == MVCResult::$E_RESULT_SUCCESS)
			return $this->HandleSuccess($controller, $res, $ctx);

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
		$method_exists 		= method_exists($Controller, $Context->getAction());
		
		/*$method_exists_Err 	= method_exists($Controller, "MVC_ERR_ActionNotFound");
		if (!$method_exists && $method_exists_Err) {
			
			// Setup a new ctx
			// TODO: Find a way of doing this with sending the error information

		} else //*/
		
		if(!$method_exists) {
			// Check for the error controller
			$eCtx  = MVCGlobalControllers::MVC_VPathController();
			$eCtrl = self::GetControllerFromContext($eCtx);

			// Set the Action to ActionNotFound
			$eCtx->setAction("ActionNotFound");

			if (!$eCtrl)
				 return $this->errorCtrlNotFound ($Context, $eCtx,  "Action/Page");
			else return $this->runErrorController($Context, $eCtrl, "ActionNotFound");
		} else {

			/* Check if the controller's settings */ {
				// Check if the controller is disabled
				if (!$Controller->hasWebAccess()) {
					$aCtx  = MVCGlobalControllers::MVC_AccessController();
					$aCtrl = self::GetControllerFromContext($aCtx);
					return self::runErrorController($Context, $aCtrl, "WebAccessDisabled");
				}
			}

			// Set the Controller's context
			$Controller->setContext($Context);
			$action   = $Context->getAction();
			
			// Check if the method is private or
			// callable at all.
			if (!is_callable([$Controller, $action])) {
				$aCtx  = MVCGlobalControllers::MVC_AccessController();
				$aCtrl = self::GetControllerFromContext($aCtx);
				return self::runErrorController($Context, $aCtrl, "CannotCallAction");
			}
			
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
			echo "Error ". MVCResult::$E_HTTP_RESULT_ERROR. ": Cant find the controller and/or action";
			echo "<pre>Class:           ". $ctx->getClass().    "</pre>";
			echo "<pre>Expected Path:   ". $ctx->getPath().     "</pre>";
			echo "<pre>Expected Action: ". $ctx->getAction().   "</pre>";
			echo "<pre>\$errorControllerOnly: True</pre>";
		} else {
			echo "Error 404: Cannot find The requested $errorType.<br>";
			echo "<pre>Controller:      ". $ctx->getClass(). 	"</pre>";
			echo "<pre>Expected Path:   ". $ctx->getPath(). 	"</pre>";
			echo "<pre>Expected Action: ". $ctx->getAction(). 	"</pre>";
			echo "<br>";
			echo "<pre>\$errorControllerOnly: False</pre><br>";

			echo "Error ". MVCResult::$E_HTTP_RESULT_ERROR. ": Cant find the controller and/or action";
			echo "<pre>Class:           ". $eCtx->getClass().   "</pre>";
			echo "<pre>Expected Path:   ". $eCtx->getPath().    "</pre>";
			echo "<pre>Expected Action: ". $eCtx->getAction().  "</pre>";
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
			// Check for the error controller
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