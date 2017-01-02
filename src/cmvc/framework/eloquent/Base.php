<?php
/**
 * Proj: Chaotic360 - API
 * User: CallumCarmicheal
 * Date: 22/12/2016
 * Time: 01:32
 */

namespace lib\CMVC\mvc\Eloquent;


class Base {
	public $Valid                   = false;
	public $Count                   = -1;
	
	public function isCollection()  { return true; }
	public function isEmpty()       { return true; }
	
	public function get()           { return null; }
	public function set($item)      { }
}