<?php
/**
 * Proj: cmvc
 * User: CallumCarmicheal
 * Date: 15/01/2017
 * Time: 22:12
 */

namespace App\Controllers\Mvc;

use CommonMVC\MVC\MVCContext;
use CommonMVC\MVC\MVCController;
use CommonMVC\MVC\MVCResult;

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
	 * @param $context MVCContext
	 * @return MVCResult|string
	 */
	public function WebAccessDisabled($context) {
		return MVCResult::HtmlContent("Controller ('". $this->getContext()->getVirtualPath(). "') has disabled web calling.");
	}
}