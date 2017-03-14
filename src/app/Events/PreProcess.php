<?php

namespace App\Events;

use CommonMVC\Framework\MVCContext;

class PreProcess {

	// Preprocess a request from a user
	// This can be used for auto redirecting
	// or password/level authed areas.

	// This is the main file that will be called,
	// you will be require to write your own rules
	// if you will be using this !
	
	/**
	 * @param $context MVCContext
	 * @return bool
	 */
	public static function ProcessRequest($context) {
		// You will have to write your own rules, 
		// look at examples on the git hub repo!
		
		
		
		// Return true to allow the request to continue
		// If false is returned the application will stop
		// and clear the output buffer, you are required
		// to call a method before this return statement and
		// exit the application!
		return true;
	}
}