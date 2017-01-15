<?php
/**
 * Proj: Chaotic360 - API
 * User: CallumCarmicheal
 * Date: 22/12/2016
 * Time: 01:04
 */

namespace lib\CMVC\mvc\Eloquent;


use lib\CMVC\mvc\MVCEloquentModel;

class DatabaseItem extends Base {
	public $Valid = false;
	private $item = null;
	
	public function isCollection()  { return false; }
	
	/**
	 * @param $item MVCEloquentModel
	 */
	public function set($item)      { $this->item = $item; $this->Valid = true; $this->Count = 0;}
	
	/**
	 * @return MVCEloquentModel
	 */
	public function get()           { return $this->item; }
	
	/**
	 * @return DatabaseItem
	 */
	public static function __SearchFailed() {
		$i = new DatabaseItem();
		$i->Valid = false;
		$i->Count = -1;
		
		return $i;
	}
}