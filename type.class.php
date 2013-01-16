<?php
require_once("iocurve.class.php");


class PointType extends IOCurve {
/***
* @desc		new point quality, registered to IOC
* @todo		decide how to handle the isCore characteristic - should we look up project type in a global config table?
***/
  function registerPointType($name, $lgdesc='undefined', $reservedvar=null) {
	// handle missing RESERVEDVAR value
	if (empty($reservedvar)) {
		$reservedvar = str_replace(" ", "_", strtoupper($name));
        }
	
	// 50 tries to make a clean machine_name for them
	$tries = (int) 0;
	while ($this->preregisterPointTypeName($reservedvar) === false) {
		$reservedvar .= rand(0,9);
		$tries++;
		
		if ($tries > 50) { 
			return false;
		}
	}		

	$i = "
INSERT INTO ioc.PType 
SET name = '" . $name . "'
 , lgDesc = '" . $lgdesc . "'
 , RESERVEDVAR = '" . $reservedvar . "'
 , dateCreated = UNIX_TIMESTAMP()";
	$this->conn->query($i);

	$this->ID = $this->conn->insert_id;
  }

/***
* @desc		handler to assure unique machine_name
***/
	function preregisterPointTypeName($reservedvar) {
		$q = "
SELECT pkPType
FROM PType
WHERE RESERVEDVAR = '" . $reservedvar . "'";
		$r = $this->conn->query($q);
		
		if ($r->num_rows > 0) {
			return false;
		}
	}

/***
* requires ID in object currently
* 
***/
  function loadPointType() {
    $q = "
SELECT name, description, mode, dateCreated
FROM ioc.PType
WHERE pkPType = " . $this->ID;
    $r = $this->conn->query($q);
    $ret = $r->fetch_object($r);

    return $ret;
  }

/***
* @desc		rename PType	
***/
  function namePointType($new_name) {
    $u = "
UPDATE PType
SET name = '" . $new_name . "'
WHERE pkP = " . $this->ID;

    $this->conn->query($u);
  }

/***
* @desc		update description of PType
***/
  function describePointType($new_desc) {
    $u = "
UPDATE PType
SET lgDesc = '" . $new_desc . "'
WHERE pkP = " . $this->ID;

    $this->conn->query($u);
  }

/***
*
***/
  function deactivatePointType() {
    $u = "
UPDATE PType
SET mode = b'1'
WHERE pkPType = " . $this->ID;

    $this->conn->query($u);
  }

/***
* requires pkPQual as new_type_quality prior to call
* could change to allow multi
* could change to allow by name
***/
  function qualifyPointType($fkQual) {
    $i = "
INSERT INTO ioc.PQualLPType
SET fkPType = " . $this->ID . "
, fkPQual = " . $fkQual . "
, dateCreated = UNIX_TIMESTAMP()";

    $this->conn->query($i);
  }

/***
* requires remove_quality_ID prior to call
***/
  function disqualifyPointType($fkQual) {
    $u = "
UPDATE ioc.PQualLPType
SET mode = b'1'
WHERE fkPType = " . $this->ID . "
 AND fkPQual = " . $fkQual;

    $this->conn->query($u);
  }

/***
* #desc		
***/
	public function findPointTypeByName($name) {
		$q = "
SELECT pkPType as ID
FROM PType
WHERE RESERVEDVAR = '" . $name . "'";
		$this->conn->query($q);
		$ret = $r->fetch_object($r);

		return $ret->ID;
	}
}

