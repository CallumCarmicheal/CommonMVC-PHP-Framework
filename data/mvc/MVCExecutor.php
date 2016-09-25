<?php
/**
 * User: Callum Carmicheal
 * Date: 24/09/2016
 * Time: 17:51
 */

namespace CommonMVC\MVC;

	use CommonMVC\MVC\MVCResult;

	class MVCExecutor {
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
		 * @param $controller MVCController
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
		 * @param $controller MVCController
		 * @param $mvc MVCResult
		 * @param $ctx MVCContext
		 * @return int Error Code
		 */
		private function HandleInvalid($controller, $mvc, $ctx) {
			// No idea what this will be used for so yeah...
		return 0; }

		/**
		 * Handle MVCResult success
		 * @param $controller MVCController
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
		public function ExecuteMVC($controller, $mvc, $ctx) {
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

	}