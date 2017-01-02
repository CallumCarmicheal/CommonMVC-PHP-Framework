<?php
/**
 * Proj: GH TMP
 * User: CallumCarmicheal
 * Date: 02/01/2017
 * Time: 03:38
 */

namespace App\Models;


use lib\CMVC\mvc\MVCEloquentModel;

class Test extends MVCEloquentModel {
	// Model settings
	protected static $table             = "users";
	protected static $useTimeColumns    = false;
	
	// Database columns
	protected static $columns_id        = "id";
	protected static $columns           = ['value'];
	protected static $columns_readonly  = [''];
	
	public static function findByValue($value) {
		return self::find(['value', '=', $value]);
	}
}