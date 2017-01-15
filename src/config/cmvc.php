<?php 

namespace Config;

class CMVC {
	
	public static function SetupConstants() {
        // Disable debugging 
        define ("CMVC_SETS_DEBUG_HANDLER", false);

        /// ============= File locations
        define ("CMVC_PRJ_DIRECTORY",                    __DIR__ . "/../". "app");
        define ("CMVC_PRJ_DIRECTORY_CONTROLLERS",        CMVC_PRJ_DIRECTORY. "/Controllers");
        define ("CMVC_PRJ_DIRECTORY_CONTROLLERS_ERRORS", CMVC_PRJ_DIRECTORY_CONTROLLERS. "/Mvc");
		define ("CMVC_PRJ_DIRECTORY_EVENTS",             CMVC_PRJ_DIRECTORY. "/Events");

        /// ============= Namespaces 
        define ("CMVC_PRJ_NAMESPACE",                   "App");
        define ("CMVC_PRJ_NAMESPACE_CONTROLLERS",        CMVC_PRJ_NAMESPACE. "\\Controllers");
        define ("CMVC_PRJ_NAMESPACE_CONTROLLERS_ERRORS", CMVC_PRJ_NAMESPACE_CONTROLLERS. "\\Mvc");
		define ("CMVC_PRJ_NAMESPACE_EVENTS",             CMVC_PRJ_NAMESPACE. "\\Events");
        
		/// ============= Virtual Paths 
		define ("CMVC_ROOT_URL", 						"http://websitelocation.com")
	}
}