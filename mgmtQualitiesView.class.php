<?php

require_once("iocurve.class.php");
require_once("mgmtView.class.php");

class MgmtQualitiesView extends IOCurve {
	public $content = 'undefined';

	function __construct() {
		parent::__construct();

		$this->listQualities();
	}

/***
* @todo		add in return of qualities w/ values (LEFT JOIN PQual pt ON pt.pkPQual = plpq.fkPQual)
***/
	private function getQualities() {
		// check for $this->filters here
		// if (!empty($this->filters)) { }
		$q = "
SELECT pq.pkPQual as Id, pq.Name, pq.MACHINE, FROM_UNIXTIME(pq.dateCreated) as created,
 COUNT(DISTINCT plpq.fkP) as numPoints,
 GROUP_CONCAT(DISTINCT concat('<a target=_blank href=./mgmt_view/point/', p.pkP, '>', p.name, '</a>') SEPARATOR ' | ') as listPoints,
 COUNT(DISTINCT pt.pkPType) as numTypes,
 GROUP_CONCAT(DISTINCT concat('<a target=_blank href=./mgmt_view/type/', pt.pkPType,'>', pq.name, '</a>') SEPARATOR ' | ') as listTypes
FROM PQual pq
 LEFT JOIN PQualLPType pqlpt ON pqlpt.fkPQual = pq.pkPQual 
 LEFT JOIN PType pt ON pt.pkPType = pqlpt.fkPType
 LEFT JOIN PLPQual plpq ON plpq.fkPQual = pq.pkPQual
 LEFT JOIN P p ON p.pkP = plpq.fkP
GROUP BY Id
";
		$r = $this->conn->query($q);

		return $r;
	}

/***
*@param		args = list of search filters??
***/
	public function listQualities() {
		$ret = '';

		$qlist = $this->getQualities();

		for ($i=0; $q=$qlist->fetch_assoc(); $i++) {
			$ret .= $this->renderQuality($q);
		}
	
		$this->qualitiesList = $ret;
	}

/***
*@param		args = list of search filters??
***/
	private function renderQuality($o) {
		$details = '<span style="color: blue; font-weight: bold; cursor: pointer" ' .
			'onclick="$(\'#' . $this->defineQualityDetailDomId($o['Id']) . '\').toggle();">' .
			'[ ? ] </span>';

		$ret = '<div style="border-bottom: black solid thin; width: 100%">' .
			$details . $o['Name'] . $this->renderQualityDetail($o) . 
			'</div>';

		return $ret;	
	}

/***
*
***/
	private function renderQualityDetail($o) {
		$ret = '<div id="' . $this->defineQualityDetailDomId($o['Id']) . '" class="hiddenInfo">' .
			'<span class="strong">MACHINE_NAME:</span> ' . $o['MACHINE'] . ' (<span class="strong">Created: </span>' . $o['created'] . ')' .
			'<br><span class="strong">Types</span> (' . $o['numTypes'] . ') ' . $o['listTypes'] . 
			'<br><span class="strong">Points</span> (' . $o['numPoints'] . ') ' . $o['listPoints'] .
			'<br>' . MgmtView::makeEditLink("Quality", $o['Id']) . 
			' ' . MgmtView::makeCloneLink("Quality", $o['Id']) .
			'</div>';

		return $ret;
	}

	private function defineQualityDetailDomId($id) {
		$ret = "qualDetail_" . $id;
		
		return $ret;		
	}
}
