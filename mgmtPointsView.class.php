<?php

require_once("iocurve.class.php");
require_once("mgmtView.class.php");

class MgmtPointsView extends IOCurve {
	public $content = 'undefined';
  public $filters = array();

	function __construct($Id = null) {
		parent::__construct();

    if (!empty($Id)) {
      $this->filters['Id'] = $Id;
    }
		
    $this->listPoints();
	}

/***
* @todo		add in return 
***/
	private function getPoints() {
		// check for $this->filters here
		// if (!empty($this->filters)) { }
// GROUP_CONCAT(DISTINCT concat('<a target=_blank onclick=\"paintWires(\'./mgmt_view/type/', pt.pkPType, '\', \'extraInfoBox\'); setupJukeModal()\">', pt.name, '</a>') SEPARATOR ' | ') as typeList, 
    $filter = false;
    if (!empty($this->filters['Id'])) {
      $filter = " AND pkP = " . $this->filters['Id'] . " ";
    }

		$q = "
SELECT p.pkP as Id, p.Name, FROM_UNIXTIME(p.dateCreated) as created,
 count(DISTINCT plpt.fkPType) as numTypes, 
 GROUP_CONCAT(DISTINCT concat(pt.name, '((:))', pt.pkPType, '[[:]]') SEPARATOR ' | ') as typeList,
 count(DISTINCT plpq.fkPQual) as numQuals,  
 GROUP_CONCAT(DISTINCT concat('<a target=_blank href=./mgmt_view/qual/', pq.pkPQual,'>', pq.name, '</a>') SEPARATOR ' | ') as qualList
FROM P p
 LEFT JOIN PLPType plpt ON plpt.fkP = p.pkP
 LEFT JOIN PType pt ON pt.pkPType = plpt.fkPType

 LEFT JOIN PLPQual plpq ON plpq.fkP = p.pkP
 LEFT JOIN PQual pq ON pq.pkPQual = plpq.fkPQual

WHERE 1 = 1 " . $filter . " 

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
			$types = '<br><span class="strong">Types</span> (' . $o['numTypes'] . ') ' . $this->translateTokenString($o['typeList']);
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

// '<a target=_blank onclick=\"paintWires(\'./mgmt_view/type/', pt.pkPType, '\', \'extraInfoBox\'); setupJukeModal()\">', pt.name, '</a>'
	private function translateTokenString($tokensStr) {
		$out = '';
		
		$arr = explode('[[:]]', $tokensStr);
		foreach ($arr as $token) {
			$parts = explode('((:))', $token);
			if (!empty($parts[0])) {
				$out .= '<a target=_blank onclick="paintWires(\'./mgmt_view/type/' . 
					$parts[1] . 
					'\', \'extraInfoBox\'); jQuery(\'.jukeModal\').css(\'display\', \'inline\');">' . 
					$parts[0] . '</a>';
			}
		}
		
		return $out;
	}

	private function definePointDetailDomId($id) {
		$ret = "pointDetail_" . $id;
		
		return $ret;		
	}
}
