<?php 

namespace Config;

class FileLoader {

	private static function LoadNonAppFiles() {
		// This will load folders recusively,
		// require_once will be used on every file
		// in a specified folder, 
		//
		// NOTE: DO NOT USE the folder CMVC_PRJ_DIRECTORY (default = app) or "cmvc"
		// those files will be loaded automatically,
		// when adding a cmvc file you will want to add it
		// in config/cmvc.php not here.
		//
		// Example: 
		// lets say you have this folder structure
		//
		// src
		// |- app, cmvc, config...
		// |- classes
		// |  |- file1.php
		// |  |- file2.php
		// |  |- file3.php
		// |  |- file4.php		
		// |  |- subfolder1
		// |  |  |- subfile1.php
		// |  |  |- subfolder2
		// |  |  |  |- sub_subfile1.php 
		// 
		// You would add 'classes' as the directory,
		// the files "file 1-4" will be loaded including all the 
		// subfolders up to the depth of 50, the max depth can be changed
		// in the function (scroll below).
		
		$directories = [
			
		];
		
		foreach ($directories as $file) 
			self::_require_all(__DIR__. '/../'. $file);
		
		// Load any singular files here
		
		// Load bladeone
		require_once (__DIR__. '/../vendors/bladeone/BladeOne.php');
	}
	
	public static function Load() {
		// Loads everything that is not the framework or the CMVC_PRJ_DIRECTORY folder.
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
				
				// DEBUG: Show files loading
				//echo "Included $path \n";
				
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