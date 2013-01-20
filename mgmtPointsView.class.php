<?php

require_once("point.class.php");

class MgmtPointsView extends Point {
	public $content = 'undefined';

	function __construct() {
		parent::__construct();

		$this->listPoints();
	}

/***
* @todo		add in return of qualities w/ values (LEFT JOIN PQual pt ON pt.pkPQual = plpq.fkPQual)
***/
	private function getPoints() {
		// check for $this->filters here
		// if (!empty($this->filters)) { }
		$q = "
SELECT p.pkP, p.Name, 
 count(DISTINCT plpt.fkPType) as numTypes, GROUP_CONCAT(DISTINCT pt.name) as typeList,
 count(DISTINCT plpq.fkPQual) as numQuals
FROM P p
 LEFT JOIN PLPType plpt ON plpt.fkP = p.pkP
 LEFT JOIN PType pt ON pt.pkPType = plpt.fkPType
 
 LEFT JOIN PLPQual plpq ON plpq.fkP = p.pkP
 
GROUP BY p.pkP 
";
		$r = $this->conn->query($q);

		return $r;
	}

/***
*@param		args = list of search filters??
***/
	public function listPoints() {
		$ret = $this->renderPointHeader(array("Name"=>"Name", "numTypes"=>"# Types", "numQuals"=>"# Qualities"));

		$plist = $this->getPoints();

		for ($i=0; $p=$plist->fetch_assoc(); $i++) {
			$ret .= $this->renderPoint($p);
		}
	
		$this->pointList = $ret;
	}

/***
*@param         args = list of search filters??
***/
        private function renderPointHeader() {
		$ret = '<style>
			.left50{float:left; width: 50%}
			.left25{float:left; width: 25%}
                        .title{font-weight: bold; color: #e5e5e5; background-color: #333}	
			.clearLeft{clear: left}
			</style>' 
	
			. '<div class="outer">
                        <div class="left50 title">Name</div>
                        <div class="left25 title"># Types</div>
                        <div class="left25 title"># Quals</div>'

                	. '</div><div class="clearLeft"></div>';

		return $ret;
	}

/***
*@param		args = list of search filters??
***/
	private function renderPoint($p, $extraClass=null) {
		if (!empty($extraClass)) { $extraClass = implode(' ', $extraClass); }

		$ret = '<style>
			.left50{float:left; width: 50%}
			.left25{float:left; width: 25%}
			.clearLeft{clear: left}
			.outer{border-bottom: black solid thin; width: 99%"}
			</style>

			<div class="outer"> 
			<div class="left50 ' . $extraClass . '">' . $p['Name'] . ' <a>[edit]</a></div>
			<div class="left25 ' . $extraClass . '">' . $p['numTypes'] . ' <a>[edit]</a> <a>[add type]</a></div>
			<div class="left25 ' . $extraClass . '">' . $p['numQuals'] . ' <a>[edit]</a> <a>[add quality]</a></div>
			</div><div class="clearLeft"></div>';
		return $ret;	
	}
}
