<?php
/**
 * User: Callum Carmicheal
 * Date: 24/09/2016
 * Time: 18:51
 */

namespace ExampleProject\Controllers\Mvc;


	use CommonMVC\Classes\Storage\Templates;
	use CommonMVC\MVC\MVCController;
	use CommonMVC\MVC\MVCResult;

	class VPathController extends MVCController {
		function __construct() {
			$this->ControllerName 	= "Mvc/VPath";
			$this->Enabled 			= true;
			$this->AuthRequired 	= true;
		}

		/**
		 * Display a error page stating that the MVC Controller cannot be found
		 * @return MVCResult
		 */
		function ControllerNotFound() {
			$replace = array(
				'VirtualPath' => $this->Context->getVirtualPath(),
				'Id' 		  => CMVC_MVC_ERROR_IDS_MISSING_CONTROLLER. " (Missing Controller)",
				'Desc' 		  => 'Cannot find the requested controller you were looking for'
			);

			$html = Templates::ReadTemplate("GenericErrorPage", false, $replace);

			if(!$html)
				 return MVCResult::SimpleHTML("Cannot find the requested controller for VP ('". $info->getContext()->getControllerFile(). "').");
			else return MVCResult::SimpleHTML($html);
		}

		/**
		 * Display a error page stating that the MVC Action could not found
		 * @return MVCResult
		 */
		function ActionNotFound() {

			$replace = array(
				'VirtualPath' => $this->Context->getVirtualPath(),
				'Id' 		  => CMVC_MVC_ERROR_IDS_MISSING_ACTION. " (Missing Action)",
				//'Desc'      => "The action (". $info->getContext()->getAction(). ") could not be found in the controller"
				'Desc' 		  => "Could not find the requested page you were looking for"
			);

			$html = Templates::ReadTemplate("GenericErrorPage", false, $replace);

			if(!$html)
				 return MVCResult::SimpleHTML("Cannot find the action for the controller of '". $info->getContext()->getControllerFile(). "'.");
			else return MVCResult::SimpleHTML($html);
		}
	}