<?php
require_once("bootstrap.php");
/**
*
**/
class Tile {
	function __construct($X, $Y) {
		$this->tileType = '0';
		$this->coordX = $X;
		$this->coordY = $Y;
		$this->age = 0;
		$this->log = array();
	}

	public function load($X, $Y) {
		return $this;
	}

	public function log($mssg=null, $severity='general') {
		if (empty($mssg)) { 
			$mssg = 'Tile::log() called without $mssg argument.';
		}

		$this->log[$severity][] = array('created' => mktime(), 'mssg' => $mssg);
	}	

/**
* @desc		Should be triggered to run at beginning of 'turn'
**/
	public function age($force=false) {
		if ($force || $tile->age > 0) {
                	$tile->age++;
        	}
//		$this->log('age('.$tile->age.'): <br>');
	}

	public function setType($tileType) {
		$a = new Arena; // hmm
		if (!in_array($tileType, $a->tileTypes)) { 
                    return false; 
                }
		$this->tileType = $tileType;
		$this->age(true);
		$this->log('setType: <br>');
	}

	private function getTileTypeRank() {
		$a = new Arena; // hmm
		$type = $tile->tileType;

		if (!$type || $type == 'Blank') {
                    return false;
		}		
		else {
			return array_search($type, $a->tileTypes);
		}
	}

/**
* @param	$coordX,$coordY(int) coordinate values of terraform spot
		tiletype
**/
	public function terraform($coordX, $coordY, $tileType) {
		// these should throw exceptions
		if ($this->tileType == 'Blank') { 
			return false; 
		}

		if (!in_array($tileType, Arena::getTileTypes())) { 
			return false; 
		}

//		$this->Arena->Tile::load[$coordX][$coordY]->setType();
	}

	private function considerSpread($tile) {
		$originTile = $tile->getTileTypeRank($tile);
//		$neighborTiles = $tile->getNeighbors();
		foreach ($tile->neighbors as $neighbor) {
			
		}
	}

}
