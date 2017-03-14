<?php
/**
 * Proj: cmvc
 * User: CallumCarmicheal
 * Date: 15/01/2017
 * Time: 22:12
 */

namespace App\Controllers\Mvc;

use CommonMVC\Framework\MVCContext;
use CommonMVC\Framework\MVCController;
use CommonMVC\Framework\MVCResult;

class AccessController extends MVCController {
	
	public function __construct() {
		$this->ControllerName = "Mvc/Access";
		$this->WebAccess      = false;
	}
	
	/**
	 * Executed when user tries to call a controller that has
	 * the variable WebAccess set to false, this indicates that the
	 * controller is only meant to be called from within code itself.
	 *
	 * This can be used to display a 500 error page or a 403 etc...
	 * @return MVCResult|string
	 */
	public function WebAccessDisabled() {
		return MVCResult::HtmlContent("Controller ('". $this->getContext()->getVirtualPath(). "') has disabled web calling.");
	}
	
	/**
	 * Executed when a user accesses a action/function in a
	 * controller which is inaccessible this meaning that
	 * the function cannot be called, such as private function,
	 * protected etc.
	 */
	public function CannotCallAction() {
		return MVCResult::HtmlContent("The action for the virtual path ('". $this->getContext()->getVirtualPath(). "') is private or uncallable.");
	}
}