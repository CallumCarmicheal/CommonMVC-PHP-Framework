<?php
/**
 * Proj: Chaotic360 - API
 * User: CallumCarmicheal
 * Date: 22/12/2016
 * Time: 01:32
 */

namespace lib\CMVC\mvc\Eloquent;


class Base {
	protected $Count                   = -1;
	
	public function isCollection()  { return true; }
	public function isEmpty()       { return $this->Count == -1; }
	public function containsItems() { return $this->Count >= 0; }
	
	public function getCount() 		{ return $this->Count; }
	
	public function get()           { return null; }
	public function set($item)      { }
}