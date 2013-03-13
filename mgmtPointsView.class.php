<?php

require_once("iocurve.class.php");
require_once("mgmtView.class.php");

class MgmtPointsView extends IOCurve {
	public $content = 'undefined';

	function __construct() {
		parent::__construct();

		$this->listPoints();
	}

/***
* @todo		add in return 
***/
	private function getPoints() {
		// check for $this->filters here
		// if (!empty($this->filters)) { }
		$q = "
SELECT p.pkP as Id, p.Name, FROM_UNIXTIME(p.dateCreated) as created,
 count(DISTINCT plpt.fkPType) as numTypes, 
 GROUP_CONCAT(DISTINCT concat('<a target=_blank href=./mgmt_view/type/', pt.pkPType,'>', pt.name, '</a>') SEPARATOR ' | ') as typeList,
 count(DISTINCT plpq.fkPQual) as numQuals,  
 GROUP_CONCAT(DISTINCT concat('<a target=_blank href=./mgmt_view/qual/', pq.pkPQual,'>', pq.name, '</a>') SEPARATOR ' | ') as qualList
FROM P p
 LEFT JOIN PLPType plpt ON plpt.fkP = p.pkP
 LEFT JOIN PType pt ON pt.pkPType = plpt.fkPType

 LEFT JOIN PLPQual plpq ON plpq.fkP = p.pkP
 LEFT JOIN PQual pq ON pq.pkPQual = plpq.fkPQual

GROUP BY Id";
		$r = $this->conn->query($q);

		return $r;
	}

/***
*@param		args = list of search filters??
***/
	public function listPoints() {
		$ret = '';

		$qlist = $this->getPoints();

		for ($i=0; $q=$qlist->fetch_assoc(); $i++) {
			$ret .= $this->renderPoint($q);
		}
	
		$this->pointsList = $ret;
	}

/***
			'<br><span class="strong">#Types:</span> ' . $o['numTypes'] . ' (' . $o['typeList'] . ')' .
*@param		args = list of search filters??
***/
	private function renderPoint($o) {
		$details = '<span style="color: blue; font-weight: bold; cursor: pointer" ' .
			'onclick="$(\'#' . $this->definePointDetailDomId($o['Id']) . '\').toggle();">' .
			'[ ? ] </span>';

		$ret = '<div style="border-bottom: black solid thin; width: 100%">' .
			$details . $o['Name'] . $this->renderPointDetail($o) . 
			'</div>';

		return $ret;	
	}

/***
*
***/
	private function renderPointDetail($o) {
		$types = $quals = '';
		
		if ($o['numTypes'] > 0) { 
			$types = '<br><span class="strong">Types</span> (' . $o['numTypes'] . ') ' . $o['typeList'];
		}
		if ($o['numQuals'] > 0) { 
			$quals = '<br><span class="strong">Qualities</span> (' . $o['numQuals'] . ') ' . $o['qualList'];
		}
		$ret = '<div id="' . $this->definePointDetailDomId($o['Id']) . '" class="hiddenInfo">' .
			'<span class="strong">Created: </span>' . $o['created'] . 
			$types .
			$quals . 
			'<br>' . MgmtView::makeEditLink("Point", $o['Id']) . 
			' ' . MgmtView::makeCloneLink("Point", $o['Id']) .
			'</div>';

		return $ret;
	}

	private function definePointDetailDomId($id) {
		$ret = "pointDetail_" . $id;
		
		return $ret;		
	}
}
