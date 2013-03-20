<?php

require_once("iocurve.class.php");

class Point extends IOCurve {
//  public $Id;
//  public $children = array();
//  public $parents = array();
//  public $name;

  function __construct($name=null) {
    parent::__construct();

    if ($name) {
      $this->registerPoint($name);
    }
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

	$this->Id = $this->conn->insert_id;

	// FIXME save to NOSQL as well, adding pkP as _id
	//$this->saveNosqlPoint();

	return $this->Id;
  }

/***
* @desc         load a point
* @param	$Id(pkP), $verbose(true/false)
* @todo		should this be just loadPoint and be able to "target" it via multipl ways (id, PType-intersect+name
* @todo		do the mass volume version of this after convert to IOC->ETL->noSQL
***/
  function loadPointById($Id) {
	// TODO will need to set max group_concat_length (pref @server)
	$q = "
SELECT P.pkP as Id, P.name, P.mode, P.dateCreated,
 GROUP_CONCAT(DISTINCT P_Par.pkP SEPARATOR '[***]') AS arr_ParentIdS, 
 GROUP_CONCAT(DISTINCT P_Par.name SEPARATOR '[***]') AS arr_ParentNames,
 GROUP_CONCAT(DISTINCT P_Child.pkP SEPARATOR '[***]') AS arr_ChildIdS, 
 GROUP_CONCAT(DISTINCT P_Child.name SEPARATOR '[***]') AS arr_ChildNames,
 GROUP_CONCAT(DISTINCT PType.MACHINE SEPARATOR '[***]') AS arr_PTypeMACHINES, 
 GROUP_CONCAT(DISTINCT PType.name SEPARATOR '[***]') AS arr_PTypeNames,
 GROUP_CONCAT(DISTINCT PQual.name SEPARATOR '[***]') AS arr_PQualNames, 
 GROUP_CONCAT(DISTINCT PQual.pkPQual SEPARATOR '[***]') AS arr_PQualIdS,
 GROUP_CONCAT(DISTINCT PQual2.name SEPARATOR '[***]') AS arr_PQualNamesViaPType, 
 GROUP_CONCAT(DISTINCT PQual2.pkPQual SEPARATOR '[***]') AS arr_PQualIdSViaPType

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
WHERE P.pkP = " . $Id . "
GROUP BY P.pkP";
	
//exit($q);	

	$r = $this->conn->query($q);

	return $r->fetch_assoc();
  }

/***
* @desc         load a point
* @param	$Id(pkP), $verbose(true/false)
* @todo		should this be just loadPoint and be able to "target" it via multipl ways (id, PType-intersect+name
* @todo		do the mass volume version of this after convert to IOC->ETL->noSQL
***/
  function lookupPointById($Id) {
	$q = "
SELECT P.pkP, P.name, P.mode, P.dateCreated
FROM P
WHERE pkP = " . $Id;
	$r = $this->conn->query($q);

        $result = $r->fetch_assoc();

	$ret = new Point();
        $ret->Id = $ret['pkP'];
        $ret->name = $ret['name'];
        $ret->dateCreated = $ret['dateCreated'];

        return $ret;
  }



/***
* 
***/
	public function loadPointIdListByType($filter) {
		$q = "
SELECT P.pkP as Id 
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
	public function loadPointIdListByQual($PQualMName) {
		$q = "
SELECT P.pkP as Id 
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
* @param	$Id originates from ioc.P.pkP_
* @fixme	this looks outside the current object (could defult to $this->Id)
***/
/*	function readNosqlPointById($Id=null) {
		if ($Id === null) { 
			$Id = $this->Id; 
		}
		$Id = (string) $Id;

		//$item = $this->nosql->P->find( array('_id' => new MongoId($this->Id)) );
		$item = $this->nosql->P->findOne( array('pkP' => $Id) );

		return $item;
	}*/

/***
* @desc         save point to NOSQL storage
* @param        $Id originates from ioc.P.pkP_
* @ TODO 	TEST THI SAGAIN
***/
        function saveNosqlPoint() {
		$item = $this->loadPointById($this->Id, true);
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
	}




/***
* @desc		alter point name
***/
	function renamePoint($new_name) {
		$u = "
UPDATE P
SET name = '" . $new_name . "'
WHERE pkP = " . $this->Id;

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
WHERE pkP = " . $this->Id;
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
WHERE fkP = " . $this->Id . "
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
WHERE fkP2 = " . $this->Id . "
ORDER BY dateCreated ASC";
    $r = $this->conn->query($q);

    while ($row = $r->fetch_row($r)) {
      $this->parents[] = $row[0];
    }
  }

/***
* FIXME
***/
  function adoptPoint($new_child_Id) {
    $i = "
INSERT INTO PLP
SET fkP = " . $this->Id . "
, fkP2 = " . $new_child_Id . "
, dateCreated = UNIX_TIMESTAMP()";
    $r = $this->conn->query($i);

    // rebuild family-down
    $this->findChildrenPoint();
// TODO
  }

/***
* FIXME
***/
	function reverseAdoptPoint($new_parent_Id) {
		$i = "
INSERT INTO PLP
SET fkP = " . $new_parent_Id . "
, fkP2 = " . $this->Id . "
, dateCreated = UNIX_TIMESTAMP()";
	
		$this->conn->query($i);
	}
        

/***
* child_Id must be attached kto object prior
* FIXME this needs to look for sub-children and make sure that they are not orpahned without a plan
***/
  function disownPoint($child_Id) {
    $u = "
UPDATE PLP 
SET desactivated = UNIX_TIMESTAMP()
WHERE fkP = " . $this->Id . "
AND fkP2 = " . $child_Id;
//    $this
// TODO
  }

/***
* @desc		add type(s)
* @param	$items:mixed int, string or array (of ints & strings)
***/
        function typify($items) {
		if (!is_array($items)) {
			$items = array($items);
		}

		foreach ($items as $item) {
			if (is_int($item)) {
				$this->typifyById($item);
			}
			else {
				$this->typifyByName($item);
			}
		}
		//$this->saveNosqlPoint();
	}

/***
* @desc		add a PType to this P
***/
        private function typifyById($item) {
		$i = "
INSERT INTO PLPType
SET fkP = {$this->Id}
, fkPType = {$item}
, dateCreated = UNIX_TIMESTAMP()";
		try {
                        $this->conn->query($i);
                }
                catch (Exception $e) {
                        echo 'Caught exception: ',  $e->getMessage(),
                                "\nThe type Id ({$item}) could not be found.",
                                "\nsql: {$i}\n";
                }
        }

/***
* @desc		add a PType to this P by looking up PType machine name
***/
        private function typifyByName($item) {
		$i = "
INSERT INTO PLPType
SET fkP = {$this->Id}
, fkPType = (SELECT pkPType FROM PType WHERE MACHINE = '" . 
		parent::sanitizeMachineName($item) . "')
, dateCreated = UNIX_TIMESTAMP()";
		try {
                        $this->conn->query($i);
                }
                catch (Exception $e) {
                        echo 'Caught exception: ',  $e->getMessage(),
                                "\nThe type ({$item}) could not be found.",
                                "\nsql: {$i}\n";
                }
        }

/***
* @desc         add a PType to this P
* @fixme	handle require class families in a bootstrapper/collection
***/
/*        function typifyByName($fkType) {
		require_once("./pointType.class.php");


                foreach ($fkType as $item) {
                        $pt = new PointType();
			$pt->findPointTypeByName($item);
			$this->typifyPoint($pt->Id);
		}
        }*/

/***
* @desc         deactivate a PType for this P
* @todo		need func deTypify
***/
        function detypifyPoint($item) {
                $u = "
UPDATE PLPType
SET mode = b'1' 
WHERE fkP = {$this->Id}
        AND fkPType = {$item};

                $this->conn->query($u);
		//$this->saveNosqlPoint();
        }

/***
* @desc		add quality(s)
* @param	$items:mixed int, string or array (of ints & strings)
***/
        function qualify($items) {
		if (!is_array($items)) {
			$items = array($items);
		}

		foreach ($items as $item => $val) {
			if (is_int($item)) {
				$this->qualifyById($item, $val);
			}
			else {
				$this->qualifyByName($item, $val);
			}
		}
		//$this->saveNosqlPoint();
	}


/***
* @desc		use known Id method
***/
        private function qualifyById($item) {
		$i = "
INSERT INTO PLPQual
SET fkP = {$this->Id}
, fkPQual = {$item}
, dateCreated = UNIX_TIMESTAMP()";

		try {
			$this->conn->query($i);
		}
		catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), 
				"\nThe quality Id (".$item.") could not be found.", 
				"\nsql: ",  $i, "\n";
		}
        }


/***
* @desc         use MACHINE NAME  method
***/
        private function qualifyByName($item, $val) {
		$i = "
INSERT INTO PLPQual
        SET fkP = {$this->Id}
 , fkPQual = (SELECT pkPQual FROM PQual WHERE MACHINE = '" . 
	parent::sanitizeMachineName($item) . "')
 , value = '" . $val . "'
 , dateCreated = UNIX_TIMESTAMP()";
                
		try {
			$this->conn->query($i);
		}
		catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), 
				"\nThe quality (".$item.") could not be found.", 
				"\nsql: ",  $i, "\n";
		}
        }

/***
* @desc         deactivate a Quality union for this P
***/
        function disqualify($fkQual) {
                $u = "
UPDATE PLPQual
SET mode = b'1'
WHERE fkP = {$this->Id}
, fkPQual = {$fkQual};

                $this->conn->query($u);
		//$this->saveNosqlPoint();
        }
}
