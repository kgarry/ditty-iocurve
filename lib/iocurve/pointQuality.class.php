<?php

require_once("iocurve.class.php");

class PointQuality extends IOCurve {

/***
* @desc		new point quality, registered to IOC
***/
  function registerPointQuality($name=null, $smdesc='undefined') {
	if ($name === null) {
		$name = "auto_" . uniqid(date("Ymd_His_"), true);
	}
	
	$i = "
INSERT INTO ioc.PQual 
SET name = '" . $name . "', 
 deactivated = 0,
 smDesc = '" . $smdesc . "',
 dateCreated = UNIX_TIMESTAMP()";
	$this->conn->query($i);

	$this->ID = $this->conn->insert_id;
  }

/***
* @desc		requires ID in object currently
* 
***/
  function loadPointQuality() {
    $q = "
SELECT name, smDesc, deactivated, dateCreated
FROM ioc.PQual
WHERE pkPQual = " . $this->ID;
    $r = $this->conn->query($q);
    $ret = $r->fetch_object($r);

    return $ret;
  }

/***
* @desc		assign ->new_name, new_description prior to call
***/
  function mutatePointQuality() {
    $u = "
UPDATE ioc.PQual
SET name = '" . $this->new_name . "',
 smDesc = '" . $this->new_description . "'
WHERE pkP = " . $this->pkP;
    $this->conn->query($u);

    return true;  
  }

/***
* 
***/
  function deactivatePointQuality() {
    $u = "
UPDATE ioc.PQual
SET deactivated = UNIX_TIMESTAMP() 
WHERE pkPQual = " . $this->ID;
    $this->conn->query($u);

    return true;
  }

}

?>
