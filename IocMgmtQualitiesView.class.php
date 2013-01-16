<?php

require_once("point.class.php");

class IocMgmtQualitiesView extends Point {
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
SELECT pq.pkPQual, pq.Name, 
 count(DISTINCT pqlpt.fkPType) as numTypes, GROUP_CONCAT(DISTINCT pt.name) as typeList,
 count(DISTINCT plpq.fkP) as numPoints
FROM PQual pq
 LEFT JOIN PQualLPType pqlpt ON pqlpt.fkPQual = pq.pkPQual
 LEFT JOIN PType pt ON pt.pkPType = pqlpt.fkPType
 
 LEFT JOIN PLPQual plpq ON plpq.fkPQual = pq.pkPQual
 
GROUP BY pq.pkPQual
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
	private function renderQuality($q) {
		$ret = '<div style="border-bottom: black solid thin; wdith: 100%">' . 
			$q['Name'] . " :#P: " . $q['numPoints'] . " :#T: " . $q['numTypes'] .
			'</div>';

		return $ret;	
	}
}