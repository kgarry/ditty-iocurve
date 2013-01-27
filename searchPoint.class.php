<?php

require_once("iocurve.class.php");

class SearchPoint extends IOCurve {
	public $filters = array();

  function __construct($name=null) {
#    parent::__construct();
		if (empty($name)) { $name = uniqid(); }	
		$this->name = $name;
  }

/***
* @param	sub mixed, casts into array
* @param	op str (OR, AND)
***/
	function addSearchPointFilter($subA, $op="OR") {
		if (!is_array($subA)) { $subA = array($subA); }

		$this->op = strtoupper($op);

		foreach ($subA as $sub) {
			$this->filters[] = $sub->join;
		}
	}

/***
* @desc 	turn the parts into sql parts	
* @note		protect vs recursion
***/
/*	private function makeSQL($arr=null) {
		// if no array passed in, assume outer recursion
		if (empty($arr)) { $arr = $this->subFilter; }

		foreach ($arr as $key => $val) {
			// if value is array, look deeper
			if (is_array($val)) {
				$this->makeSQL($arr[$key]); // hrmmm
			}

			$this->subSQL[$key] = " ( interpret SQL for _".$val."_ ) ";
		}
		return "makeSQL()";
  }*/


/***
*
***/
	private function makeJoint() {
		switch ($this->op) {
			case "OR": 
				$this->joint = " LEFT ";
				break;
			case "AND":
				$this->joint = " ";
				break;
			default:
				throw new Exception("Invalid option passed to SearchPoint::makeJoint(str (OR,AND))");
		}
	}

/***
* @desc 	
* @params	op str (OR, AND, NOT)
* @todo		use the op arg
***/
	public function combine() {
		$this->makeJoint();

		$q = "SELECT pkP as Id FROM P "; 

		// iterate through the desired filters
		foreach ($this->filters as $filter) {
			// iterate each part of this filter
			foreach ($filter as $join) {
				$q .= "
 " . $this->joint . $join . " ";
			} 
		}

		$q .= " GROUP BY pkP ";
		$this->query = $q;
	}

/***
* @desc 	run query and return	
***/
	private function run() {
		return "run()";
	}

}


class SearchPointFilter {
/***
* @desc 	
* @param	str variety (Type, Qual, QualVal)
* @param	str needleVariety (Id, Name)
* @param	str needle
* @param	str op (AND, OR, NOT)
* @param	str name [optional: name your subFilter]
***/
  function __construct($name=null) {
		if (empty($name)) { $name = uniqid(); }
		$this->name = $name;
	}

/***
* @param	str variety (Type, Qual, QualVal)
* @param	str needleVariety (Id, Name)
* @param	str needle
* @param	str op (AND, OR, NOT)
***/
	public function register($searchType, $op, $needle) {
		$this->needle = $needle;
		$this->op = strtoupper($op);
		// build parts to determine func
		$user_func = 'add' . $searchType . "Filter"; 
 
		//$this->subFilter[$name]-> new SearchPoint();
		$this->$user_func(); //hrmm
	}

/***
* @desc 	
* @params	op str (OR, AND, NOT)
* @todo		use the op arg
***/
/*  public function combine($op='OR') {
		$q = "SELECT pkP as Id FROM P ";
		foreach ($this->join as $join) {
			$q .= " " . $join . " 
				";
		} 
		$q .= " GROUP BY pkP ";
		$this->query = $q;
  }*/

/***
*
***/
	private function makeJoint() {
		switch ($this->op) {
			case "OR": 
				$this->joint = " ";
				$this->jointHaving = " ";				
				break;
			case "AND":
				$this->joint = " ";
				$this->jointHaving = " HAVING qty = " . count($this->needle);
				break;
			case "NOT":
				$this->joint = " JOIN ";
#				$this->jointHaving = " HAVING qty = 0 ";
				break;
			default:
				throw new Exception("Invalid option passed to SearchPointFilter::makeJointHaving(str (OR,AND,NOT))");
		}
	}

/***
* @desc 	
* @params	SFName is the ID associated to the subFilter in question
* @todo		assumes OR for now
***/
  private function addTypeFilter() {
	$this->makeJoint();	
	
	$this->join[] = " 
JOIN (	SELECT fkP, count(DISTINCT fkP) as qty 
	FROM PLPType 
	JOIN PType ON PType.pkPType = PLPType.fkPType 
	 WHERE PType.MACHINE IN ('" . implode($this->needle, "','") . "')
	GROUP BY fkP " . $this->jointHaving . " 
) as sub_pt_" . $this->op . " ON sub_pt_" . $this->op . ".fkP = P.pkP ";
  }

/***
* @desc 	
***/
  private function addTypeIdFilter() {

  }

/***
* @desc 	
***/
  private function addQualFilter() {
	$this->makeJoint();	

	$this->join[] = " 
JOIN (	SELECT fkP, count(DISTINCT fkP) as qty 
	JOIN PQual ON PQual.pkPQual = PLPQual.fkPQual 
	 WHERE PQual.MACHINE IN ('" . implode($this->needle, "','") . "') 
	GROUP BY fkP " . $this->jointHaving . " 
) as sub_pq_" . $this->op . " ON sub_pq_" . $this->op . ".fkP = P.pkP ";
  }

/***
* @desc 	
***/
  private function addQualIdFilter() {

  }

/***
* @desc 	
***/
/*
  private function addQualValFilter($op, $needle) {
		$hash = uniqid();
		$qual = $needle["Qual"];
		$val = $needle["Val"];
		if (!is_array($val)) { 
			$val = array($val); 
		}
*/	
		/*switch ($op) {
			case 'AND': 
// TODO this can convert to an OR, check implode delimiter on single
				$cookedOp = " = '{$val}' ";
				break;
			case 'NOT':
				$cookedOp = " != '{$val}' ";
				break;
			case 'OR':
				$cookedOp = " IN ('" . implode("','", $val) . "') ";
				break;
		}*/
/*
		$cookedOp = ($op=='NOT'?' NOT':'') . 
			" IN ('" . implode("','", $val) . 
			"') ";

		$select = "plpq{$hash}.plPLPQual";
		$join = "
JOIN PLPQual plpq{$hash} ON plpq{$hash}.fkP = p.pkP
 AND plpq{$hash}.value {$cookedOp} 
JOIN PQual pq{$hash} ON pq{$hash}.pkPQual = plpq{$hash}.fkPQual
 AND pq{$hash}.MACHINE = '{$qual}' 
";
		$having = " ";
		
		$this->select[] = $select;
		$this->join[] 	= $join;
		$this->having[] = $having;
	}
*/
}

//-----------------------
# Its Instrument or Style with quality foo val of bar
/*
$f1b = new SearchPointFilter();
$f1b->register("QualVal", 
	array("Qual"=>"foo", "Val"=>"bar", 
	"AND"); 
*/

/*$nf1a = new SearchPointFilter();
$nf1a->register("Type", array("INSTRUMENT"), "NOT");
*/

$f1a = new SearchPointFilter();
$f1a->register("Type", "OR", array("INSTRUMENT", "SONG"));
#$f1b->combine(); 

$f1b = new SearchPointFilter();
$f1b->register("Qual", "NOT", array("AUTHOR"));
#$f1b->combine(); 

$f1 = new SearchPoint();
	$f1->addSearchPointFilter( array($f1a, $f1b), "AND" );
	$f1->combine();
print_r($f1->query); print "\n\n";
#$s1->search(); // db/nosql


