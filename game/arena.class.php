<?php
require_once("bootstrap.php");

/***
*
***/
class Arena {
	function __construct($Id=null) {
		$scale = 31;

		if (!empty($Id) && is_int($Id+0)) { // add routine to verify ownership authority (or maybe not(view mode))
			$this->load_arena = $this->load($Id);
//Page::explain($load_arena, $__METHOD__);

		}
		else {
			$this->age = 1;
			$this->Id = null; // will be null until saved
			$this->tileTypes = array(1, 2, 3, 4, 5, 6, 7);  // can I move this out?
//			$this->minX = $this->minY = $scale * -1;
			$this->maxX = $this->maxY = $scale;	
			$this->log = array();

			$counter = 1;
			for ($x=1; $x <= $this->maxX; $x++) {
				for ($y=1; $y <= $this->maxY; $y++) {
					$this->tiles[$counter] = new Tile($counter, $x, $y);
					$counter++;
				}	
			}
		}
	}

	public function getTileTypes() {
		return $this->tileTypes;
	}

/**
*
**/
	public function load($Id=null) {
		if (empty($Id)) { 
			$Id = $this->Id;
		}
		$p = new Point();
		$ret = $p->getQualityValue('object', $Id); // this will change after data is broken up
		$ret = unserialize($ret);
		
		return $ret;
	}

/**
*
**/
	public function data_save() {
//		Page::explain();

		$p = new Point("Arena_Match_".uniqid());
		$this->Id = $p->Id;
		if (!empty($this->origin_Id)) { 
			$this->origin_Id = $this->Id; // better as PQual, or also PQual even?
		}

		$p->typify("Arena");
		$payload = serialize($this);
		$p->qualify(array('object' => $payload));
	}
       
	public function render() {
		$out = '<div id="arena">';
		//for ($x=1; $x <= $this->maxX; $x++) { // callback hook candidate
                  //      for ($y=1; $y <= $this->maxY; $y++) {
		foreach ($this->tiles as $tile) {
//				$tile = $this->tiles[$x][$y];
				$out .= '<div class="tile land' . $tile->tileType . '">' .
					($tile->age + 0) . // no meaning
					'</div>';
				
                    //    }
			if ($tile->coordY == $this->maxY) {
				$out .= '<div style="clear: left"></div>';
			}
                //}
		}
		$out .= '</div>';

		return $out;
	}

	public function log($mssg=null, $severity='general') {
                if (empty($mssg)) {
                        $mssg = 'Tile::log() called without $mssg argument.';
                }

                $this->log[$severity][] = array('created' => mktime(), 'mssg' => $mssg);
        }
       
// for now writing splat code for this, later do a iterate_all callback process.. never do the below
/*	private function ageTiles() {
		for ($x=1; $x <= $this->maxX; $x++) { // callback hook candidate
                        for ($y=1; $y <= $this->maxY; $y++) {
				$this->tiles[$x][$y]->age();
			}
		}
	}*/

// could this live in a decontruct?
// should this be called from a doAll callback pattern?
	public function age() {
		$this->age++;
		foreach ($this->tiles as $tile) {
			if ($tile->age > 0) { 
				$tile->age(); 
			}
		}
	}

/**
* $return	random ordered result set of tiles by type
**/
	public function locateTilesByType($tileType=0) {
		$retTiles = array(); // turn these into a method Arean::locateBlankTiles
	        foreach ($this->tiles as $tile) {
	                if ($tile->tileType == $tileType) {
        	                $retTiles[] = $tile;
                	}
		}
//Page::explain($retTiles,' !!! ',true);
	        shuffle($retTiles);
		return $retTiles;
	}
 
/**
* @desc		Locate all adjacent tile objects
* @param	(Tile) object 
* @return	Array of tiel objects
* @todo		test (none so far)
**/
	public function getTileNeighbors($tile) {
		$X = $tile->coordX;
		$Y = $tile->coordY;
		$neighborhood = array();
		
//		for ($x=$X-1; $this->minX <= $x && $x <= $this->maxX && $x <= $X+1; $x++) {
//			for ($y=$Y-1; $this->minY <= $y && $y <= $this->maxY && $y <= $Y+1; $y++) {
		foreach ($this->tiles as $neighbor) {
				$x = $neighbor->coordX;
				$y = $neighbor->coordY;

				if (!($x == $X && $y == $Y) &&
					($X-1 <= $x && $x <= $X+1) &&
					($Y-1 <= $y && $y <= $Y+1) ) {
//					$this->log("\n...considering ".$x." : ".$y."\n", "notice");
					$neighborhood[] = $neighbor;
				}
		}
//			}
//		}
		return $neighborhood;
	}
}


