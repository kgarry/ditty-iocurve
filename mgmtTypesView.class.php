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
* @todo		add more
***/
	private function getTypes() {
		$q = "
SELECT pt.pkPType, pt.Name, pt.MACHINE, FROM_UNIXTIME(pt.dateCreated) as created,
 count(DISTINCT pqlpt.fkPQual) as numQuals, GROUP_CONCAT(DISTINCT pt.name) as qualList,
 count(DISTINCT plpt.fkP) as numPoints
FROM PType pt
 LEFT JOIN PQualLPType pqlpt ON pqlpt.fkPType = pt.pkPType
 LEFT JOIN PQual pq ON pq.pkPQual = pqlpt.fkPQual

 LEFT JOIN PLPType plpt ON plpt.fkPType = pt.pkPType

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
			'onclick="$(\'#' . $this->defineTypeDetailDomId($o['pkPType']) . '\').toggle();">' .
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
		$ret = '<div id="' . $this->defineTypeDetailDomId($o['pkPType']) . '" class="hiddenInfo">' .
			'<span class="strong">MACHINE_NAME:</span> ' . $o['MACHINE'] . ' (' . $o['created'] . ')' .
			'<br><span class="strong">#Types:</span> ' . $o['numQuals'] . ' (' . $o['qualList'] . ')' .
			'<br><span class="strong">#Points:</span> ' . $o['numPoints'] . 
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
