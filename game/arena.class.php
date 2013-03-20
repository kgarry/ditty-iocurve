<?php
require_once("bootstrap.php");

/***
*
***/
class Arena {
	function __construct() {
		$scale = 31;

		$this->age = 0;
		$this->Id = 37; // fixme
		$this->tileTypes = array('Blue', 'Green', 'Red', 'Yellow');
		$this->minX = $this->minY = $scale * -1;
		$this->maxX = $this->maxY = $scale;	
		$this->log = array();

		for ($x=1; $x <= $this->maxX; $x++) {
			for ($y=1; $y <= $this->maxY; $y++) {
				$this->tiles[$x][$y] = new Tile($x, $y);
			}	
		}
	}

	public function getTileTypes() {
		return $this->tileTypes;
	}
/**
*
**/
	public function save() {

	}
        
	public function render() {
//		$renderMatrix = array(); // need to elegantly map tile codes to assets, but not here
		$out = '';
		for ($x=1; $x <= $this->maxX; $x++) { // callback hook candidate
                        for ($y=1; $y <= $this->maxY; $y++) {
				$tile = $this->tiles[$x][$y];
                                switch ($tile->tileType) {
					case 'Blue':
						$applyStyle = 'blue';
						break;
					case 'Green':
						$applyStyle = 'green';
						break;
					default:
						$applyStyle = 'black';
				}
				$out .= '<div style="float: left; width: 18px; height: 18px; background-color: ' . $applyStyle . '">' .
					($tile->age + 0) . // no meaning
					'</div>';
				
                        }
			$out .= '<div style="clear: left"></div>';
                }
		return $out;
	}

	public function log($mssg=null, $severity='general') {
                if (empty($mssg)) {
                        $mssg = 'Tile::log() called without $mssg argument.';
                }

                $this->log[$severity][] = array('created' => mktime(), 'mssg' => $mssg);
        }
       
	private function ageTiles() {
		for ($x=1; $x <= $this->maxX; $x++) { // callback hook candidate
                        for ($y=1; $y <= $this->maxY; $y++) {
				$this->tiles[$x][$y]->age();
			}
		}
	}
 
	public function getTileNeighbors($tile) {
		$out = "<-->\n";
		$X = $tile->coordX;
		$Y = $tile->coordY;
		$neighborhood = array();
		
		for ($x=$X-1; $this->minX <= $x && $x <= $this->maxX && $x <= $X+1; $x++) {
			for ($y=$Y-1; $this->minY <= $y && $y <= $this->maxY && $y <= $Y+1; $y++) {
				if (!($x ==$X && $y == $Y)) {
//					$this->log("\n...considering ".$x." : ".$y."\n", "notice");
					$neighborhood[] = $this->tiles[$x][$y];
				}
			}
		}
	}
}


