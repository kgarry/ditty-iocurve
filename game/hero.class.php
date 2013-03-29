<?php
require_once("bootstrap.php");
/**
*
**/
class Hero {
	function __construct($Id=null) {
		if (!empty($Id)) { 
			$this->Id = $Id; // fixme
		}
		$this->movement = 2;
		$this->favoriteTileType = rand(1,6);
	}

	public function load() {
		
		return $this;
	}

	public function setType($type) {
	}

	public function setLoc ($x, $y) {
		$this->coordX = $x;
		$this->coordY = $y;
	}

	private function getStat($stat) {
	}

/**
**/
	public function move() {
	}

/***
*
***/
	private function determineOptions($type) {
	}

	private function determineMoveOptions($tile) {
	}

	private function determineAttackOptions($tile) {
	}

	private function determineTerraformOptions($tile) {
	}
	
}
