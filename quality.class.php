<?php

require_once("iocurve.class.php");

class Quality extends IOCurve {

/***
* @desc		new point quality, registered to IOC
***/
  function registerQuality($name=null, $smdesc='undefined') {
	if ($name === null) {
		$name = "auto_" . uniqid(date("Ymd_His_"), true);
	}
	
	$i = "
INSERT INTO PQual 
SET name = '" . $name . "', 
 mode = b'1',
 smDesc = '" . $smdesc . "',
 dateCreated = UNIX_TIMESTAMP()";
	$this->conn->query($i);

	$this->ID = $this->conn->insert_id;

	// save to nosql as well
//	$this->nosql->PQual->save( $this->loadQuality() );
  }

/***
* @desc		requires ID in object currently
* 
***/
	function loadQuality() {
		$q = "
SELECT name, smDesc, mode, dateCreated
FROM PQual
WHERE pkPQual = " . $this->ID;
		$r = $this->conn->query($q);
		$ret = $r->fetch_assoc();

		return $ret;
	}


/***
* @desc		assign ->new_name, new_description prior to call
***/
	function mutateQuality() {
		$u = "
UPDATE PQual
SET name = '" . $this->new_name . "',
smDesc = '" . $this->new_description . "'
WHERE pkP = " . $this->pkP;
		$this->conn->query($u);

		return true;  
	}

/***
* 
***/
	function deactivateQuality() {
		$u = "
UPDATE PQual
SET mode = b'1'
WHERE pkPQual = " . $this->ID;
		$this->conn->query($u);

		return true;
	}

/***
*
***/
	function cloneQuality() {
				

		return $c;
	}

}

?>
