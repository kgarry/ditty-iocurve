<?php
require_once("bootstrap.php");
/**
*
**/
class Tile {
	function __construct($Id, $X, $Y) {
		$this->Id = $Id;
		$this->coordX = $X;
		$this->coordY = $Y;
		$this->type = __BLANK_TILE__;
		$this->age = 1;
		$this->log = array();
		$this->neighborInfluence = array();
		$this->classes[] = 'tile';
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
	public function age() {
               	$this->age++;
		
//		$this->log('age('.$tile->age.'): <br>');
	}

	public function setType($type) {
		$tileTypes = Arena::getTileTypes();
		if (!in_array($type, $tileTypes)) { 
//Page::explain($tileTypes, 'setType: '.$type.' not in type options???', false);
                    return false; 
                }
		$this->type = $type;
		$this->removeClass('land', 'begin');
                $this->addClass(array('land' . $type));	
		$this->log('setType: <br>');
	}

/*** 
* Could have objects other than hero claim things.. very interesting 
* Must have Id value
***/
	public function claim($owner_object) {
//		if (!empty($this->claimed['current']) {
			if (!empty($this->claimed['current']) && is_array($this->claimed['current'])) {
				$this->claimed[] = $this->claimed['current'];
			}
//		}
//echo ' =>'. $owner_object->Id .'<br>';
		$this->claimed['current']['type'] = get_class($owner_object);
		$this->claimed['current']['Id'] = $owner_object->Id;
	}

	private function getTileTypeRank() {
/* * This should do something..? */
	}

/**
* @param	$coordX,$coordY(int) coordinate values of terraform spot
		tiletype
**/
	public function terraform($coordX, $coordY, $type) {
		// these should throw exceptions
		if ($this->type == 'Blank') { 
			return false; 
		}

		if (!in_array($type, Arena::getTileTypes())) { 
			return false; 
		}

//		$this->Arena->Tile::load[$coordX][$coordY]->setType();
	}

/**
* $param	$neighbor is a adjacent tile
* todo		see if this should consider influence from anothe rlayer out as well
**/
	public function considerNeighborType($neighbor) {
//		$originTile = $tile->getTileTypeRank($tile);
//		$neighborTiles = $tile->getNeighbors();
		if ($neighbor->type == __BLANK_TILE__) { return; }
		if ($neighbor->age == 1) { return; }

		$this->neighborInfluence[$neighbor->Id] = rand(0,100); // make rand() into a cool algorithm.. someday
	}

/**
*
**/
	public function render() {
		$claimed = '';
		if (@is_array($this->claimed['current'])) {
			$pack = $this->claimed['current']['type'].','.$this->claimed['current']['Id'];
			$onclick = ' onclick="expose(\'tile-info\', \'#\', \'' . $pack . '\');"';
		}

		$out = '<div class="' . $this->renderClass() . '" ' . $onclick . '>' .
			$this->age .
			'</div>';

		return $out;
	}
	
/**
* @todo 	de-dupe?
**/
	public function renderClass() {
		return implode(" ", $this->classes);
	}


/**
* @param	$class (string) class to remove
		$type (string) 'exact'/null -> remove exact key, 'begin' remove keys beginning with
**/
	public function removeClass($class, $type='exact') {
		if ($type == 'exact') {
			$key = array_search($class, $this->classes);
//echo 'removing '.$class.' on '.$key."\n";
			$this->classes[$key] = false;
		}
		elseif ($type == 'begins') {
			$len = strlen($class);
			foreach ($this->classes as $key => $val) {
				if (substr($val, 0, $len) == $class) {
		                        $key = array_search($class, $this->classes);
					$this->classes[$key] = false;
//echo 'removing '.$class.' on '.$key."\n";
				}
			}
                }

	}

/**
*
**/
	public function addClass($classes) {
		if (!is_array($classes)) { $classes = array($classes); }

		foreach ($classes as $class) {
			$this->classes[] = $class;
//print_r($this->classes); echo '<hr>';
		}
	}
}
