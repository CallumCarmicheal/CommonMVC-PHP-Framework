<?php 

namespace Config;

class Database {
	// Set these values
	public static $HOST             = "localhost";
    public static $PORT             = 3307;
    public static $DATABASE         = "test";
    public static $AUTH_USER        = "root";
    public static $AUTH_PASSWORD    = "";

    public static function SetupCMVCDefines() {
    	// Define our CMVC database connection settings
		define ("CMVC_PRJ_STORAGE_DB_HOST", 	    self::$HOST. ":". self::$PORT);
		define ("CMVC_PRJ_STORAGE_DB_USER", 	    self::$AUTH_USER);
		define ("CMVC_PRJ_STORAGE_DB_PASS", 	    self::$AUTH_PASSWORD);
		define ("CMVC_PRJ_STORAGE_DB_DB",   	    self::$DATABASE);

		// Set the database to allow UTF8 values.
		define ("CMVC_PRJ_STORAGE_DB_CHARSET", 	    "utf8");
    }
}