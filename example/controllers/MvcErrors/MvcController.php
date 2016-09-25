<?php
/**
 * User: Callum Carmicheal
 * Date: 24/09/2016
 * Time: 18:51
 */

namespace ExampleProject\Controllers\MvcErrors;


	use CommonMVC\Classes\Storage\Templates;
	use CommonMVC\MVC\MVCErrorInformation;
	use CommonMVC\MVC\MVCResult;
	use CommonMVC\MVC\MVCResultEnums;

	class MvcController extends \CommonMVC\MVC\MVCController {
		function __construct() {
			$this->ControllerName 	= "Errors/Mvc";
			$this->Enabled 			= true;
			$this->AuthRequired 	= true;
		}

		/**
		 * Display a error page stating that the MVC Controller cannot be found
		 * @param $info MVCErrorInformation
		 * @return MVCResult
		 */
		function ControllerNotFound($info) {
			$replace = array(
				'VirtualPath' => $info->getContext()->getVirtualPath(),
				'Id' 		  => CMVC_MVC_ERROR_IDS_MISSING_CONTROLLER,
				'Desc' 		  => 'Cannot find the requested controller you were looking for'
			);

			$html = Templates::ReadTemplate("GenericErrorPage", false, $replace);

			if(!$html) {
				return MVCResult::SimpleHTML("ITS FALSE?");
			}

			if(!$html)
				 return MVCResult::SimpleHTML("Cannot find the requested controller for VP ('". $info->getContext()->getControllerFile(). "').");
			else return MVCResult::SimpleHTML($html);
		}

		/**
		 * Display a error page stating that the MVC Action could not found
		 * @param $info MVCErrorInformation
		 * @return MVCResult
		 */
		function ActionNotFound($info) {

			$replace = array(
				'VirtualPath' => $info->getContext()->getVirtualPath(),
				'Id' 		  => CMVC_MVC_ERROR_IDS_MISSING_ACTION,
				//'Desc'      => "The action (". $info->getContext()->getAction(). ") could not be found in the controller"
				'Desc' 		  => "Could not find the requested page you were looking for"
			);

			$html = Templates::ReadTemplate("GenericErrorPage", false, $replace);

			if(!$html)
				 return MVCResult::SimpleHTML("Cannot find the action for the controller of '". $info->getContext()->getControllerFile(). "'.");
			else return MVCResult::SimpleHTML($html);
		}
	}