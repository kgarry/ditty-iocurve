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
 , deactivated = 0
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
SELECT name, description, deactivated, dateCreated
FROM ioc.PType
WHERE pkPType = " . $this->ID;
    $r = $this->conn->query($q);
    $ret = $r->fetch_object($r);

    return $ret;
  }

/***
* assign ->new_name, new_description prior to call
***/
  function mutatePointType() {
    $u = "
UPDATE ioc.PType
SET name = '" . $this->new_name . "'
, description = '" . $this->new_description . "'
WHERE pkP = " . $this->pkP;
    $this->conn->query($u);

    return true;  
  }

/***
*
***/
  function deactivatePointType() {
    $u = "
UPDATE ioc.PType
SET deactivated = UNIX_TIMESTAMP() 
WHERE pkPType = " . $this->ID;
    $this->conn->query($u);

    return true;
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

    return true;
  }

/***
* requires remove_quality_ID prior to call
***/
  function disqualifyPointType($fkQual) {
    $u = "
UPDATE ioc.PQualLPType
SET deactivated = UNIX_TIMESTAMP()
WHERE fkPType = " . $this->ID . "
 AND fkPQual = " . $fkQual;
    $this->conn->query($u);

    return true;
  }

/***
* #desc		
***/
//	function 
}

