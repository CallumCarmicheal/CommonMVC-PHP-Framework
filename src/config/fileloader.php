<?php 

namespace Config;

class FileLoader {

	private static function LoadNonAppFiles() {
		// These are classes/files which are not in the 
		// mvc root folder
		// Eg  
		// src
		// |- data
		//    |- TestClass.php
		// Would be: data/TestClass.php
		
		
		$files = [
			
		];
		
		foreach ($files as $file) 
			self::_require_all(__DIR__. '/'. $file);
	}
	
	public static function LoadFiles() {
		self::LoadNonAppFiles();
		
		/* Loads any and all files in the "app" or the folder that 
		   was set to the mvc root except the folder 'Controllers' */
		self::_require_all(CMVC_PRJ_DIRECTORY, 0, [CMVC_PRJ_DIRECTORY. '/Controllers']);
	}
	
	private static function _require_all($dir, $depth=0, $excl=[]) {
		// Set the max sub directory amount / depth
		if ($depth > 50)
			return;

		// require all php file in the folder
		$scan = glob("$dir/*");
		
		foreach ($scan as $path) {
			if (preg_match('/\.php$/', $path)) {
				$skip = false;
				foreach ($excl as $str)
					if (strcmp ($str, $path) == 0)
						$skip = true;
				if ($skip) continue;
				
				echo "Included $path \n";
				require_once $path;
			} else if (is_dir($path)) {
				$skip = false;
				foreach ($excl as $str)
					if (strcmp ($str, $path) == 0)
						$skip = true;
				if ($skip) continue;
				self::_require_all($path, $depth+1);
			}
		}
	}
}