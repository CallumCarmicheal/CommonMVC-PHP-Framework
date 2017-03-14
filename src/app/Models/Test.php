<?php
/**
 * User: CallumCarmicheal
 * Date: 02/01/2017
 * Time: 03:38
 */

namespace App\Models;

use CommonMVC\Framework\MVCEloquentModel;

class Test extends MVCEloquentModel {
	// Model settings
	protected static $table             = "test";
	protected static $useTimeColumns    = true;
	
	// Database columns
	protected static $columns_id        = "id";
	protected static $columns           = ['value'];
	
	// This is columns that cannot be modified but can be
	// viewed when called.
	protected static $columns_readonly  = [''];
	
	public static function findByValue($value) {
		return self::find(['value', '=', $value]);
	}
}