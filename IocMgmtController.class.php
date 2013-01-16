<?php

//require_once("../iocurve.class.php"); //fixme use defined pat from bootstrap

class IOCurveManageController {
	
	function __construct() {
		$this->top = "<html><head></head><body>";
		$this->header = "header<hr>";
		$this->menu = "Points | Types | Qualities";
		$this->content = "stuff";
		$this->footer = "";
		$this->bottom = "</body></html>";
	}

	public function drawIOCurveManageController() {
		return $this->top .
			$this->header .
			$this->menu .
			$this->content . 
			$this->footer .
			$this->bottom;
	}

}
