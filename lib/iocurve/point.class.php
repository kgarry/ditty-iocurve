<?php

require_once("iocurve.class.php");

class Point extends IOCurve {
  public $ID;
  public $children = array();
  public $parents = array();
  public $name;

  function __construct() {
    parent::__construct();
  }

/***
* @desc 	make a point (::pun_alert())
***/
  function registerPoint($name=null) {
	// handle missing name
	if ($name === null) {
		$name = uniqid("auto_" . date("Ymd_His_"), true);
	}	

	$i = "
INSERT INTO P 
SET name = '" . $name . "',
 deactivated = 0,
 dateCreated = UNIX_TIMESTAMP()
	";
	$this->conn->query($i);

	$this->ID = $this->conn->insert_id;

	return $this->ID;
  }

/***
* @desc         load a point
* @param	$ID(pkP), $verbose(true/false)
* @todo		should this be just loadPoint and be able to "target" it via multipl ways (id, PType-intersect+name
* @todo		do the mass volume version of this after convert to IOC->ETL->noSQL
***/
  function loadPointById($ID, $verbose=false) {
	$ret = (string) '';
	if ($verbose === false) {
		$q = "
SELECT P.name, P.deactivated, P.dateCreated
FROM P
WHERE pkP = " . $ID;
	}
	else {
		$q = "
SELECT P.name, P.deactivated, P.dateCreated,
 GROUP_CONCAT(DISTINCT P_Par.pkP) AS ParentIDS, GROUP_CONCAT(DISTINCT P_Par.name) AS ParentNames,
 GROUP_CONCAT(DISTINCT P_Child.pkP) AS ChildIDS, GROUP_CONCAT(DISTINCT P_Child.name) AS ChildNames,
 GROUP_CONCAT(DISTINCT PType.RESERVEDVAR) as PTypeRESERVEDVARS, GROUP_CONCAT(DISTINCT PType.name) as PTypeNames,
 GROUP_CONCAT(DISTINCT PQual.name) as PQualNames, GROUP_CONCAT(DISTINCT PQual.pkPQual) as PQualIDS,
 GROUP_CONCAT(DISTINCT PQual2.name) as PQualNamesInherited, GROUP_CONCAT(DISTINCT PQual2.pkPQual) as PQualIDSInherited

FROM P
 LEFT JOIN PLP PLP_Par ON PLP_Par.fkP2 = P.pkP
 LEFT JOIN P P_Par ON P_Par.pkP = PLP_Par.fkP

 LEFT JOIN PLP PLP_Child ON PLP_Child.fkP = P.pkP
 LEFT JOIN P P_Child ON P_Child.pkP = PLP_Child.fkP2

 LEFT JOIN PLPType ON PLPType.fkP = " . $ID . "
 LEFT JOIN PType ON PType.pkPType = PLPType.fkPType
 LEFT JOIN PQualLPType ON PQualLPType.fkPType = PType.pkPType
 LEFT JOIN PQual PQual2 ON PQual2.pkPQual = PQualLPType.fkPQual
 
 LEFT JOIN PLPQual ON PLPQual.fkP = " . $ID . "
 LEFT JOIN PQual ON PQual.pkPQual = PLPQual.fkPQual
WHERE P.pkP = " . $ID . "
GROUP BY P.pkP";
	}
//exit($q);	

	$r = $this->conn->query($q);
	while ($item = $r->fetch_assoc()) {
		$ret .= "\n\n" . var_dump($item); 
	}

	return $ret;
  }

/***
* @desc		alter point name
***/
  function renamePoint($new_name) {
    $u = "
UPDATE P
SET name = '" . $new_name . "'
WHERE pkP = " . $this->ID;
    $this->conn->query($u);
  }

/***
*
***/
  function deactivatePoint() {
    $u = "
UPDATE P 
SET deactivated = UNIX_TIMESTAMP() 
WHERE pkP = " . $this->ID;
    $this->conn->query($u);
  }

/******************* Relationship *************************/
/***
* TODO later allow number of depth(down)
***/
  function findChildrenPoint() {
    $q = "
SELECT fkP2 
FROM PLP
WHERE fkP = " . $this->ID . "
ORDER BY dateCreated ASC";
    $r = $this->conn->query($q);

    while ($row = $r->fetch_row($r)) {
      $this->children[] = $row[0];
    }
  }

/***
* TODO later allow number of depth(up)
***/
  function findParentsPoint() {
    $q = "
SELECT fkP
FROM PLP
WHERE fkP2 = " . $this->ID . "
ORDER BY dateCreated ASC";
    $r = $this->conn->query($q);

    while ($row = $r->fetch_row($r)) {
      $this->parents[] = $row[0];
    }
  }

/***
* FIXME
***/
  function adoptPoint($new_child_ID) {
    $i = "
INSERT INTO PLP
SET fkP = " . $this->ID . "
, fkP2 = " . $new_child_ID . "
, dateCreated = UNIX_TIMESTAMP()
    ";
    
    $this->findChildrenPoint();
// TODO
  }

/***
* FIXME
***/
	function reverseAdoptPoint($new_parent_ID) {
		$i = "
INSERT INTO PLP
SET fkP = " . $new_parent_ID . "
, fkP2 = " . $this->ID . "
, dateCreated = UNIX_TIMESTAMP()";
	
		$this->conn->query($i);
	}

/***
* child_ID must be attached kto object prior
* FIXME this needs to look for sub-children and make sure that they are not orpahned without a plan
***/
  function disownPoint($child_ID) {
    $u = "
UPDATE PLP 
SET desactivated = UNIX_TIMESTAMP()
WHERE fkP = " . $this->ID . "
AND fkP2 = " . $child_ID;
//    $this
// TODO
  }

/***
* @desc		add a PType to this P
***/
        function typifyPoint($fkType) {
		$i = "
INSERT INTO PLPType
SET fkP = " . $this->ID . "
, fkPType = " . $fkType . "
, dateCreated = UNIX_TIMESTAMP()";

		$this->conn->query($i);
        }

/***
* @desc         deactivate a PType for this P
***/
        function detypifyPoint($fkType) {
                $u = "
UPDATE PLPType
SET deactivated = UNIX_TIMESTAMP() 
WHERE fkP = " . $this->ID . "
, fkPType = " . $fkType;

                $this->conn->query($u);
        }

/***
* @desc         add a PType to this P
***/
        function qualifyPoint($fkQual) {
                $i = "
INSERT INTO PLPQual
SET fkP = " . $this->ID . "
, fkPQual = " . $fkQual . "
, dateCreated = UNIX_TIMESTAMP()";

                $this->conn->query($i);
        }

/***
* @desc         deactivate a PType for this P
***/
        function disqualifyPoint($fkQual) {
                $u = "
UPDATE PLPQual
SET deactivated = UNIX_TIMESTAMP()
WHERE fkP = " . $this->ID . "
, fkPQual = " . $fkQual;

                $this->conn->query($u);
        }

}

