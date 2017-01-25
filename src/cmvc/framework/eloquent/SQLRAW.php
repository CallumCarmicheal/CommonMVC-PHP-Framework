<?php
/**
 * Proj: Chaotic 360 - Panel
 * User: CallumCarmicheal
 * Date: 21/01/2017
 * Time: 04:30
 */

namespace lib\CMVC\mvc\Eloquent;


class SQLRAW {
	public $SQL = "";
	
	public function __construct($sql) {
		$this->SQL = $sql;
	}
	
	public function __toString() {
		return $this->SQL;
	}
}