<?php
require_once("bootstrap.php");

/***
*
***/
class Arena {
	function __construct($Id=null) {
		$scale = 31;
		define('__ARENA_DEBUG_MODE__', false);

		if (!empty($Id) && is_int($Id+0)) { // add routine to verify ownership authority (or maybe not(view mode))
			$this->load_arena = $this->load($Id);
//$this->explain($load_arena, $__METHOD__);

		}
		else {
			$this->age = 0;
			$this->Id = null; // will be null until saved
			$this->tileTypes = array(1, 2, 3, 4, 5, 6, 7);  // can I move this out?
			$this->minX = $this->minY = $scale * -1;
			$this->maxX = $this->maxY = $scale;	
			$this->log = array();

			for ($x=1; $x <= $this->maxX; $x++) {
				for ($y=1; $y <= $this->maxY; $y++) {
					$this->tiles[$x][$y] = new Tile($x, $y);
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
//		$this->explain();

		$p = new Point("Arena_Match_".uniqid());
		$this->Id = $p->Id;
		$p->typify("Arena");
		$payload = serialize($this);
		$p->qualify(array('object' => $payload));
	}
       
	public function explain($matter=false, $extra_info='') {
		if (__ARENA_DEBUG_MODE__ !== true) { return; }

		$trace = debug_backtrace();
		$caller = '';
		if (!empty($trace[1]['function'])) { 
			$caller = ((string) $trace[1]['function']);
		} 

		$info = ' * EXPLAINING * ' . __CLASS__ . '::' . $caller .'-' . $extra_info . "\n";
		echo '<textarea class="explain">' . $info;
		if (!$matter) { 
			echo var_export($this);
		} else {
			echo var_export($matter);
		}
		echo '</textarea>';
	}
 
	public function render() {
		$out = '<div id="arena">';
		for ($x=1; $x <= $this->maxX; $x++) { // callback hook candidate
                        for ($y=1; $y <= $this->maxY; $y++) {
				$tile = $this->tiles[$x][$y];
				$out .= '<div class="tile land' . $tile->tileType . '">' .
					($tile->age + 0) . // no meaning
					'</div>';
				
                        }
			$out .= '<div style="clear: left"></div>';
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


