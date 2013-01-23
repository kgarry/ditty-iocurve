<?php

require_once("iocurve.class.php");

class Point extends IOCurve {
//  public $ID;
//  public $children = array();
//  public $parents = array();
//  public $name;

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
 dateCreated = UNIX_TIMESTAMP()
	";
	$this->conn->query($i);

	$this->ID = $this->conn->insert_id;

	// FIXME save to NOSQL as well, adding pkP as _id
	//$this->saveNosqlPoint();

	return $this->ID;
  }

/***
* @desc         load a point
* @param	$ID(pkP), $verbose(true/false)
* @todo		should this be just loadPoint and be able to "target" it via multipl ways (id, PType-intersect+name
* @todo		do the mass volume version of this after convert to IOC->ETL->noSQL
***/
  function loadPointById($ID, $verbose=false) {
	if ($verbose === false) {
		$q = "
SELECT P.pkP, P.name, P.mode, P.dateCreated
FROM P
WHERE pkP = " . $ID;
	}
	else {
		// TODO will need to set max group_concat_length (pref @server)
		$q = "
SELECT P.pkP as ID, P.name, P.mode, P.dateCreated,
 GROUP_CONCAT(DISTINCT P_Par.pkP SEPARATOR '[***]') AS arr_ParentIDS, 
 GROUP_CONCAT(DISTINCT P_Par.name SEPARATOR '[***]') AS arr_ParentNames,
 GROUP_CONCAT(DISTINCT P_Child.pkP SEPARATOR '[***]') AS arr_ChildIDS, 
 GROUP_CONCAT(DISTINCT P_Child.name SEPARATOR '[***]') AS arr_ChildNames,
 GROUP_CONCAT(DISTINCT PType.MACHINE SEPARATOR '[***]') AS arr_PTypeMACHINES, 
 GROUP_CONCAT(DISTINCT PType.name SEPARATOR '[***]') AS arr_PTypeNames,
 GROUP_CONCAT(DISTINCT PQual.name SEPARATOR '[***]') AS arr_PQualNames, 
 GROUP_CONCAT(DISTINCT PQual.pkPQual SEPARATOR '[***]') AS arr_PQualIDS,
 GROUP_CONCAT(DISTINCT PQual2.name SEPARATOR '[***]') AS arr_PQualNamesViaPType, 
 GROUP_CONCAT(DISTINCT PQual2.pkPQual SEPARATOR '[***]') AS arr_PQualIDSViaPType

FROM P
 LEFT JOIN PLP PLP_Par ON PLP_Par.fkP2 = P.pkP
 LEFT JOIN P P_Par ON P_Par.pkP = PLP_Par.fkP

 LEFT JOIN PLP PLP_Child ON PLP_Child.fkP = P.pkP
 LEFT JOIN P P_Child ON P_Child.pkP = PLP_Child.fkP2

 LEFT JOIN PLPType ON PLPType.fkP = P.pkP
 LEFT JOIN PType ON PType.pkPType = PLPType.fkPType
 LEFT JOIN PQualLPType ON PQualLPType.fkPType = PType.pkPType
 LEFT JOIN PQual PQual2 ON PQual2.pkPQual = PQualLPType.fkPQual
 
 LEFT JOIN PLPQual ON PLPQual.fkP = P.pkP
 LEFT JOIN PQual ON PQual.pkPQual = PLPQual.fkPQual
WHERE P.pkP = " . $ID . "
GROUP BY P.pkP";
	}
//exit($q);	

	$r = $this->conn->query($q);

	$ret = $r->fetch_object();
	$this->ID = $ret->ID;

	return $ret;	
  }

/***
* @desc         load a point
* @param	$ID(pkP), $verbose(true/false)
* @todo		should this be just loadPoint and be able to "target" it via multipl ways (id, PType-intersect+name
* @todo		do the mass volume version of this after convert to IOC->ETL->noSQL
***/
  function lookPointById($ID) {
	$q = "
SELECT P.pkP, P.name, P.mode, P.dateCreated
FROM P
WHERE pkP = " . $ID;
	$r = $this->conn->query($q);

        $result = $r->fetch_assoc();

	$ret = new Point();
        $ret->ID = $ret['pkP'];
        $ret->name = $ret['name'];
        $ret->dateCreated = $ret['dateCreated'];

        return $ret;
  }



/***
* 
***/
	public function loadPointIDListByType($filter) {
		$q = "
SELECT P.pkP as ID 
FROM P
 JOIN PLPType PLPT ON PLPT.fkP = P.pkP
 JOIN PType ON PType.pkPType = PLPT.fkPType
  AND PType.MACHINE = '" . $filter . "'
GROUP BY P.pkP";
//echo $q . "\n";
		$r = $this->conn->query($q);

        	return $r;
	}

/***
* @todo		add MACHINE field to table
***/
	public function loadPointIDListByQual($PQualMName) {
		$q = "
SELECT P.pkP as ID 
FROM P
 JOIN PLPQual PLPQ ON PLPQ.fkP = P.pkP
 JOIN PQual ON PQual.pkPQual = PLPQ.fkPQual
  AND PQual.MACHINE = '" . $filter . "'
GROUP BY P.pkP
		";
		
		$r = $this->conn->query($q);

        	return $r->fetch_assoc();
	}


#--- NOSQL POINT ---

/***
* @desc         read point from NOSQL storage
* @param	$ID originates from ioc.P.pkP_
* @fixme	this looks outside the current object (could defult to $this->ID)
***/
/*	function readNosqlPointByID($ID=null) {
		if ($ID === null) { 
			$ID = $this->ID; 
		}
		$ID = (string) $ID;

		//$item = $this->nosql->P->find( array('_id' => new MongoId($this->ID)) );
		$item = $this->nosql->P->findOne( array('pkP' => $ID) );

		return $item;
	}*/

/***
* @desc         save point to NOSQL storage
* @param        $ID originates from ioc.P.pkP_
***/
/*        function saveNosqlPoint() {
		$item = $this->loadPointByID($this->ID, true);
		foreach ($item as $key => $val) {
			if (substr($key, 0, 4) == 'arr_') {
				$val = explode('[***]', $val);
				$key = substr($key, 4, 99);
			}

			$newItem[$key] = $val;
			
			$newItem['_id'] = new MongoId();
		}
	
		// handle MongoId for new records
		if (empty($new_item['_id'])) {
			$newItem['_id'] = new MongoId();
		//TODO -- this will need a new field in P table
			//$this->setMongoId($newItem['_id']);
		}

		
		$this->nosql->P->save($newItem);
		// todo this will set back the mongoId to the P record
	}*/




/***
* @desc		alter point name
***/
	function renamePoint($new_name) {
		$u = "
UPDATE P
SET name = '" . $new_name . "'
WHERE pkP = " . $this->ID;

		$this->conn->query($u);
		$this->saveNosqlPoint();
	}

/***
*
***/
	function deactivatePoint() {
		$u = "
UPDATE P 
SET mode = b'1' 
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
#print("findChildrenPoint: ".$q."\r");
		$r = $this->conn->query($q);

		while ($row = @$r->fetch_row($r)) {
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
, dateCreated = UNIX_TIMESTAMP()";
    $r = $this->conn->query($i);

    // rebuild family-down
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
		if (!is_array($fkType)) {
			$fkType = array($fkType);
		}

		foreach ($fkType as $item) {
			$i = "
INSERT INTO PLPType
SET fkP = " . $this->ID . "
, fkPType = " . $item . "
, dateCreated = UNIX_TIMESTAMP()";

			$this->conn->query($i);
		}
		//$this->saveNosqlPoint();
        }

/***
* @desc		add a PType to this P by looking up PType machine name
***/
        function typifyPointByTypeName($typeName) {
		if (!is_array($typeName)) {
			$typeName = array($typeName);
		}

		foreach ($typeName as $item) {
			$i = "
INSERT INTO PLPType
SET fkP = " . $this->ID . "
, fkPType = (SELECT pkPType FROM PType WHERE MACHINE = '" . $item . "')
, dateCreated = UNIX_TIMESTAMP()";
			$this->conn->query($i);
		}
		//$this->saveNosqlPoint();
        }

/***
* @desc         add a PType to this P
* @fixme	handle require class families in a bootstrapper/collection
***/
        function typifyPointByName($fkType) {
		require_once("./pointType.class.php");

                if (!is_array($fkType)) {
                        $fkType = array($fkType);
                }

                foreach ($fkType as $item) {
                        $pt = new PointType();
			$pt->findPointTypeByName($item);
			$this->typifyPoint($pt->ID);
		}
        }

/***
* @desc         deactivate a PType for this P
***/
        function detypifyPoint($fkType) {
                $u = "
UPDATE PLPType
SET mode = b'1' 
WHERE fkP = " . $this->ID . "
, fkPType = " . $fkType;

                $this->conn->query($u);
		//$this->saveNosqlPoint();
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
		//$this->saveNosqlPoint();
	}

/***
* @desc         add a PType to this P
***/
        function qualifyPointByName($item, $val) {
		$i = "
INSERT INTO PLPQual
SET fkP = " . $this->ID . "
 , fkPQual = (SELECT pkPQual FROM PQual WHERE MACHINE = '" . $item . "')
 , value = '" . $val . "'
 , dateCreated = UNIX_TIMESTAMP()";

		// FIXME -- add this trap to the mysqli overload
                if (!$this->conn->query($i)) {
			throw new Exception($i . "\n\n" . $this->conn->error);
		}
		//$this->saveNosqlPoint();
        }

/***
* @desc         deactivate a Quality union for this P
***/
        function disqualifyPoint($fkQual) {
                $u = "
UPDATE PLPQual
SET mode = b'1'
WHERE fkP = " . $this->ID . "
, fkPQual = " . $fkQual;

                $this->conn->query($u);
		//$this->saveNosqlPoint();
        }
}
