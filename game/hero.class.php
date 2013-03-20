<?php
require_once("bootstrap.php");
/**
*
**/
class Hero {
	function __construct($playerId=null) {
		if (!empty($playerId)) { 
			$this->playerId = 73; // fixme
		}
	}

	public function load() {
		return $this;
	}

	public function setType($type) {
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
