<?php
/**
 * User: Callum Carmicheal
 * Date: 27/09/2016
 * Time: 19:26
 */

namespace ExampleProject\Controllers\Mvc;


	use CommonMVC\Classes\Storage\Templates;
	use CommonMVC\MVC\MVCController;
	use CommonMVC\MVC\MVCResult;

	class AccessController extends MVCController {


		public function __construct() {
			$this->ControllerName 	= "Mvc/Access";
			$this->AuthRequired 	= false;
			$this->Enabled 			= false;
		}


		/**
		 * @return MVCResult
		 */
		public function ControllerDisabled() {
			$replace = array(
				'VirtualPath' => $this->Context->getVirtualPath(),
				'Id' 		  => CMVC_MVC_ERROR_IDS_CONTROLLER_DISABLED. " (Disabled Controller)",
				'Desc' 		  => 'The requested controller was disabled'
			);


			$html = Templates::ReadTemplate("GenericErrorPage", false, $replace);

			if(!$html)
				 return MVCResult::HtmlContent("The requested controller has been disabled ('". $this->getContext()->getVirtualPath(). "').");
			else return MVCResult::HtmlContent($html);
		}
	}