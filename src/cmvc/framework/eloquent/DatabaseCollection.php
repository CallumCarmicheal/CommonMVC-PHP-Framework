<?php
/**
 * Proj: Chaotic360 - API
 * User: CallumCarmicheal
 * Date: 22/12/2016
 * Time: 01:05
 */

namespace lib\CMVC\mvc\Eloquent;


class DatabaseCollection extends Base {
// Variables:
	/**
	 * @var $items DatabaseItem[]
	 */
	private $items = [];

// Methods:
	public function isCollection() { return true; }
	public function isEmpty() { return $this->Count == -1; }
	
	/** @return DatabaseItem[] */
	public function get() {
		return $this->items;
	}
	
	/**
	 * @param $item DatabaseItem[]
	 */
	public function set($item) {
		$this->Count = count($item)-1;
		$this->items = $item;
		
		$this->Valid = $this->Count >= 0;
	}

}