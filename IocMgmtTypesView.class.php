<?php

require_once("point.class.php");

class IocMgmtTypesView extends Point {
	public $content = 'undefined';

	function __construct() {
		parent::__construct();

		$this->listTypes();
	}

/***
* @todo		add in return of qualities w/ values (LEFT JOIN PQual pt ON pt.pkPQual = plpq.fkPQual)
***/
	private function getTypes() {
		// check for $this->filters here
		// if (!empty($this->filters)) { }
		$q = "
SELECT pt.pkPType, pt.Name, 
 count(DISTINCT pqlpt.fkPQual) as numQuals, GROUP_CONCAT(DISTINCT pq.name) as qualList,
 count(DISTINCT plpt.fkP) as numPoints
FROM PType pt
 LEFT JOIN PQualLPType pqlpt ON pqlpt.fkPType = pt.pkPType
 LEFT JOIN PQual pq ON pq.pkPQual = pqlpt.fkPQual
 
 LEFT JOIN PLPType plpt ON plpt.fkPType = pt.pkPType
 
GROUP BY pt.pkPType
";
		$r = $this->conn->query($q);

		return $r;
	}

/***
*@param		args = list of search filters??
***/
	public function listTypes() {
		$ret = '';

		$tlist = $this->getTypes();

		for ($i=0; $t=$tlist->fetch_assoc(); $i++) {
			$ret .= $this->renderType($t);
		}
	
		$this->typeList = $ret;
	}

/***
*@param		args = list of search filters??
***/
	private function renderType($t) {
		$ret = '<div style="border-bottom: black solid thin; wdith: 100%">' . 
			$t['Name'] . " :#P: " . $t['numPoints'] . " :#Q: " . $t['numQuals'] .
			'</div>';

		return $ret;	
	}
}
