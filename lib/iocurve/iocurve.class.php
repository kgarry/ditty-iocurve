<?php

require_once("database.class.php");

/**
*
**/
class IOCurve extends Database {
  //
	function __construct() {
		parent::__construct();
	}

/**
* @desc   output information about self
**/
	public function explainSelf() {
		$this->diary = array('state'=>"active", 'color'=>"green");

		return $this->diary;
	}

}
