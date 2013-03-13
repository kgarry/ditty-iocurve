<?php
require_once("iocurve.class.php");
require_once("mgmtView.class.php");

class MgmtTypesView extends IOCurve {
	public $content = 'undefined';

	function __construct() {
		parent::__construct();

		$this->listTypes();
	}

/***
* @todo		add more, add limit, paging
***/
	private function getTypes() {
		$q = "
SELECT pt.pkPType as Id, pt.Name, pt.MACHINE, FROM_UNIXTIME(pt.dateCreated) as created,
 COUNT(DISTINCT plpt.fkP) as numPoints,
 GROUP_CONCAT(DISTINCT concat('<a target=_blank href=./mgmt_view/point/', p.pkP, '>', p.name, '</a>') SEPARATOR ' | ') as listPoints,
 COUNT(DISTINCT pq.pkPQual) as numQuals,
 GROUP_CONCAT(DISTINCT concat('<a target=_blank href=./mgmt_view/qual/', pq.pkPQual,'>', pq.name, '</a>') SEPARATOR ' | ') as listQuals
FROM PType pt
 LEFT JOIN PQualLPType pqlpt ON pqlpt.fkPType = pt.pkPType
 LEFT JOIN PQual pq ON pq.pkPQual = pqlpt.fkPQual
 LEFT JOIN PLPType plpt ON plpt.fkPType = pt.pkPType
 LEFT JOIN P p ON p.pkP = plpt.fkP
GROUP BY pt.pkPType";
	
		return $this->conn->query($q);
	}

/***
*@param		args = list of search filters??
***/
	public function listTypes() {
		$ret = '';

		$list = $this->getTypes();

		for ($i=0; $item=$list->fetch_assoc(); $i++) {
			$ret .= $this->renderType($item);
		}
	
		$this->typesList = $ret;
	}

/***
*@param		args = list of search filters??
***/
	private function renderType($o) {
		$details = '<span style="color: blue; font-weight: bold; cursor: pointer" ' .
			'onclick="$(\'#' . $this->defineTypeDetailDomId($o['Id']) . '\').toggle();">' .
			'[ ? ] </span>';

		$ret = '<div style="border-bottom: black solid thin; width: 100%">' .
			$details . $o['Name'] . $this->renderTypeDetail($o) . 
			'</div>';

		return $ret;	
	}

/***
*
***/
	private function renderTypeDetail($o) {
		$ret = '<div id="' . $this->defineTypeDetailDomId($o['Id']) . '" class="hiddenInfo">' .
			'<span class="strong">MACHINE_NAME:</span> ' . $o['MACHINE'] . ' ' . $o['created'] . ' ' .
			'<br><span class="strong">Qualities</span> (' . $o['numQuals'] . ') ' . $o['listQuals'] . ' ' .
			'<br><span class="strong">Points</span> (' . $o['numPoints'] . ') ' . $o['listPoints'] . ' ' .
			'<br>' . MgmtView::makeEditLink("Type", $o['Id']) .
                        ' ' . MgmtView::makeCloneLink("Type", $o['Id']) .
			'</div>';

		return $ret;
	}

	private function defineTypeDetailDomId($id) {
		$ret = "typeDetail_" . $id;
		
		return $ret;		
	}
}
