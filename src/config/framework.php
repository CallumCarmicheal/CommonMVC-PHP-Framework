<?php 

namespace Config;

class Framework {

	/**
	 * Setup the defines
	 */
    public static function Setup() {
	    // Defines
        require_once ("cmvc.php");
        \Config\CMVC::Load();
        
        // Database 
        require_once ("database.php");
        \Config\Database::Load();
    }
	
	/**
	 * Load all the files in the CMVC Framework
	 */
    public static function Load() {
	
    // Global methods (1/1)
		require_once (__DIR__. "/../cmvc/framework/globals/Password_Util.php");
		require_once (__DIR__. "/../cmvc/framework/globals/TextHelper.php");
	    require_once (__DIR__. "/../cmvc/framework/globals/Input.php");
	    require_once (__DIR__. "/../cmvc/framework/globals/urlhandler.php");
	    require_once (__DIR__ . "/../cmvc/framework/globals/Request.php");
	    
	// Storage (1/1)
		require_once (__DIR__. "/../cmvc/framework/Storage/Database.php");
		require_once (__DIR__. "/../cmvc/framework/Storage/Templates.php");
	    
	// MVC (1/1)
	    require_once (__DIR__. "/../cmvc/framework/MVCEncryption.php");
		require_once (__DIR__. "/../cmvc/framework/MVCContext.php");
		require_once (__DIR__. "/../cmvc/framework/MVCController.php");
		require_once (__DIR__. "/../cmvc/framework/MVCExecutor.php");
		require_once (__DIR__. "/../cmvc/framework/MVCGlobalControllers.php");
		require_once (__DIR__. "/../cmvc/framework/MVCHelper.php");
		require_once (__DIR__. "/../cmvc/framework/MVCResult.php");
	    require_once (__DIR__. "/../cmvc/framework/MVCEloquentModel.php");

    // MVC->ELOQUENT (1/1)
	    require_once (__DIR__. "/../cmvc/framework/Eloquent/Base.php");
	    require_once (__DIR__. "/../cmvc/framework/Eloquent/DatabaseItem.php");
	    require_once (__DIR__. "/../cmvc/framework/Eloquent/DatabaseCollection.php");
    }
}