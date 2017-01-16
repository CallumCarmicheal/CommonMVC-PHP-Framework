<?php 

namespace Config;

class Database {
	// Set these values
	public static $HOST             = "localhost";
    public static $PORT             = 3307;
    public static $DATABASE         = "test_eloquent";
    public static $AUTH_USER        = "root";
    public static $AUTH_PASSWORD    = "";
	
	// This will output any errors onto the
	// page.
	public static $DEBUG            = false;
	
    public static function Load() {
    	// Define our CMVC database connection settings
		define ("CMVC_PRJ_STORAGE_DB_HOST", 	    self::$HOST. ":". self::$PORT);
		define ("CMVC_PRJ_STORAGE_DB_USER", 	    self::$AUTH_USER);
		define ("CMVC_PRJ_STORAGE_DB_PASS", 	    self::$AUTH_PASSWORD);
		define ("CMVC_PRJ_STORAGE_DB_DB",   	    self::$DATABASE);
	    define ("CMVC_PRJ_STORAGE_DB_DEBUG",        self::$DEBUG);

		// Set the database to allow UTF8 values.
		define ("CMVC_PRJ_STORAGE_DB_CHARSET", 	    "utf8");
    }
}