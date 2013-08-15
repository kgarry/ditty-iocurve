<?php
require_once("bootstrap.php");

/***
*
***/
class Arena {
	function __construct($Id=null) {
		$this->scale = __ARENA_SCALE__;
		if (!empty($Id) && is_int($Id+0)) { // add routine to verify ownership authority (or maybe not(view mode))
			$this->load_arena = (object) $this->load($Id);
//Page::explain($load_arena, $__METHOD__);

		}
		else {
			$this->age = 1;
			$this->Id = null; // will be null until saved
			$this->heroes = array();
			$this->log = array();

			$counter = 1;
			for ($x=1; $x <= $this->scale; $x++) {
				for ($y=1; $y <= $this->scale; $y++) {
					$this->tiles[$counter] = new Tile($counter, $x, $y);
					$this->tiles[$counter]->addClass('land' . __BLANK_TILE__);
					$counter++;
				}	
			}
		}
	}

	public function getTileTypes() {
		return unserialize(__TILE_TYPES_ARR__);
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
		foreach ($this->tiles as $tile) {
			$out .= $tile->render();
			
			if ($tile->coordY == $this->scale) {
				$out .= '<div style="clear: left"></div>';
			}
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
       
// could this live in a decontruct?
// should this be called from a doAll callback pattern?
	public function age() {
		$this->age++;
		foreach ($this->tiles as $tile) {
			if ($tile->type != __BLANK_TILE__) { 
				$tile->age(); 
			}
		}
	}

/**
* $return	random ordered result set of tiles by type
* rename to getTilesByType todo
**/
	public function locateTilesByType($type=__BLANK_TILE__) {
		$retTiles = array(); // turn these into a method Arena::locateBlankTiles
	        foreach ($this->tiles as $tile) {
	                if ($tile->type == $type) {
        	                $retTiles[] = $tile;
                	}
		}
	        shuffle($retTiles);

		return $retTiles;
	}
 
/**
* @desc		Locate all adjacent tile objects
* @param	(Tile) object, $range (int) default 1 
* @return	Array of tiel objects
* @todo		test (none so far)
**/
	public function getTileNeighbors($tile, $range=1) {
		$X = $tile->coordX;
		$Y = $tile->coordY;
		$neighborhood = array();
		
		foreach ($this->tiles as $neighbor) {
				$x = $neighbor->coordX;
				$y = $neighbor->coordY;

				if (!($x == $X && $y == $Y) &&
					($X-$range <= $x && $x <= $X+$range) &&
					($Y-$range <= $y && $y <= $Y+$range) ) {
//					$this->log("\n...considering ".$x." : ".$y."\n", "notice");
					$neighborhood[] = $neighbor;
				}
		}
		return $neighborhood;

	}

/**
* @param	$x, $y (int) X,Y coordinates
* @return	(Tile) object 
**/
	public function findTile($x, $y) {
		foreach ($this->tiles as $tile) {
			if ($tile->coordX == $x && $tile->coordY == $y) {
				return $tile;
			}
		}
	}

/***
*
***/
	public function addHero($hero) {
		$this->heroes[] = $hero;
		if ($hero->coordX > 0 && $hero->coordY > 0) {
			$tile = $this->findTile($hero->coordX, $hero->coordY);
			$tile->addClass("hero");
		}		
	}
}
