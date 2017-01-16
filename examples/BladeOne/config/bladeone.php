<?php 

namespace Config;

class BladeOne {
	
    public static function Load() {
    	// Define our CMVC database connection settings
		define ("VENDORS_BLADEONE_DIR_STORAGE", __DIR__. '/../storage');
	    define ("VENDORS_BLADEONE_DIR_CACHE",   VENDORS_BLADEONE_DIR_STORAGE. '/cache');
	    define ("VENDORS_BLADEONE_DIR_VIEWS",   VENDORS_BLADEONE_DIR_STORAGE. '/views');
		
	    // (optional) 1=forced (test),2=run fast (production), 0=automatic, default value.
	    define ("BLADEONE_MODE",                0);
    }
}