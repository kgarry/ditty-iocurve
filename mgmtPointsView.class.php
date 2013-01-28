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
 count(DISTINCT plpt.fkPType) as numTypes, GROUP_CONCAT(DISTINCT pt.name) as typeList,
 count(DISTINCT plpq.fkPQual) as numQuals
FROM P p
 LEFT JOIN PLPType plpt ON plpt.fkP = p.pkP
 LEFT JOIN PType pt ON pt.pkPType = plpt.fkPType

 LEFT JOIN PLPQual plpq ON plpq.fkP = p.pkP

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
		$ret = '<div id="' . $this->definePointDetailDomId($o['Id']) . '" class="hiddenInfo">' .
			'<span class="strong">Created: </span>' . $o['created'] . 
			'<br><span class="strong">#Types:</span> ' . $o['numTypes'] . ' (' . $o['typeList'] . ')' .
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
