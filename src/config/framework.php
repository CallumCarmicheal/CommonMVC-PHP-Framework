<?php 

namespace Config;

class Framework {

	/**
	 * Setup the defines
	 */
    public static function Setup() {
	    // Defines
        require_once ("cmvc.php");
        CMVC::SetupConstants();
        
        // Database 
        require_once ("database.php");
        Database::Load();
    }
	
	/**
	 * Load all the files in the CMVC Framework
	 */
    public static function Load() {
	
    // Global methods
		require_once (__DIR__. "/../cmvc/framework/globals/Password_Util.php");
		require_once (__DIR__. "/../cmvc/framework/globals/TextHelper.php");
	    require_once (__DIR__. "/../cmvc/framework/globals/Input.php");
	    require_once (__DIR__. "/../cmvc/framework/globals/urlhandler.php");
	    require_once (__DIR__ . "/../cmvc/framework/globals/Request.php");
	    
	// Storage
		require_once (__DIR__. "/../cmvc/framework/Storage/Database.php");
		require_once (__DIR__. "/../cmvc/framework/Storage/Templates.php");
	    
	// MVC
	    require_once (__DIR__. "/../cmvc/framework/MVCEncryption.php");
		require_once (__DIR__. "/../cmvc/framework/MVCContext.php");
		require_once (__DIR__. "/../cmvc/framework/MVCController.php");
		require_once (__DIR__. "/../cmvc/framework/MVCExecutor.php");
		require_once (__DIR__. "/../cmvc/framework/MVCGlobalControllers.php");
		require_once (__DIR__. "/../cmvc/framework/MVCHelper.php");
		require_once (__DIR__. "/../cmvc/framework/MVCResult.php");
	    require_once (__DIR__. "/../cmvc/framework/MVCEloquentModel.php");

    // Eloquent Model
	    require_once (__DIR__. "/../cmvc/framework/Eloquent/Base.php");
	    require_once (__DIR__. "/../cmvc/framework/Eloquent/DatabaseItem.php");
	    require_once (__DIR__. "/../cmvc/framework/Eloquent/DatabaseCollection.php");
    }
}