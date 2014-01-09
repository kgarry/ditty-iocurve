<?php
require_once("iocurve.class.php");

class Type extends IOCurve {
  private $max_machine_name_tries = 50;

  function __construct($name=null) {
    parent::__construct();

    if ($name) {
      $this->registerType($name);
    }
  }

/***
* @desc		new point quality, registered to IOC
* @todo		decide how to handle the isCore characteristic - should we look up project type in a global config table?
***/
  protected function registerType($name, $MACHINE=null, $lgdesc='undefined') {
	// 50 tries to make a clean machine_name for them
	if (!is_string($MACHINE)) { // rethink?prot vs int machine names here? 
		if (!$this->preregisterTypeName($name)) {
			$this->error_log[] = "registerType operation aborted. Machine name could not be established automatically.";
			return;
		}
	}
	else {
		$this->MACHINE = $MACHINE;
	}
	
	$i = "
INSERT INTO ioc.PType 
SET name = '" . $name . "',
  lgDesc = '" . $lgdesc . "',
  mode = b'1',
  MACHINE = '" . $this->MACHINE . "',
  dateCreated = UNIX_TIMESTAMP()";

	try {
		$this->conn->query($i);
	}
	catch (Exception $e) {
		echo 'Caught exception: ',  $e->getMessage(),
			"\nThe type Id ({$name}: {$this->MACHINE}) could not be inserted.",
			"\nsql: {$i}\n";
	}


	$this->Id = $this->conn->insert_id;
	// save to nosql as well
//	$this->nosql->PType->save( $this->loadType() );

	return $this->Id;
  }

/***
* @desc		handler to assure unique machine_name
***/
	private function preregisterTypeName($name, $max_tries=null) {
		if (empty($max_tries)) { $max_tries = $this->max_machine_name_tries; }

		$tries = (int) 0;
		
		$MACHINE_BASE = parent::sanitizeMachineName($name);

		while ($tries < $max_tries) {
//$this->preregisterTypeName($name, $this->max_machine_name_tries) === false) {
			$MACHINE = $MACHINE_BASE;
			if ($tries > 0) { 
				$MACHINE .= uniqid();
			} 
			$tries++;
		
			$q = "
SELECT pkPType
FROM PType
WHERE MACHINE = '" . $MACHINE . "'";
			$r = $this->conn->query($q);
			
			if ($r->num_rows < 1) {
				$this->MACHINE = $MACHINE;
				return true;
			}
		}
		
		return false; // failed 50? times :)
	}

/***
* requires Id in object currently
* 
***/
  function load($Id) { // this needs to go through a wash first todo
    $q = "
SELECT name, lgDesc, mode, dateCreated
FROM PType
WHERE pkPType = " . $Id;
    $r = $this->conn->query($q);
    $ret = $r->fetch_object();

    return $ret;
  }

/***
* @desc		rename PType	
***/
  function nameType($new_name) {
    $u = "
UPDATE PType
SET name = '" . $new_name . "'
WHERE pkP = " . $this->Id;

    $this->conn->query($u);
  }

/***
* @desc		update description of PType
***/
  function describeType($new_desc) {
    $u = "
UPDATE PType
SET lgDesc = '" . $new_desc . "'
WHERE pkP = " . $this->Id;

    $this->conn->query($u);
  }

/***
*
***/
  function deactivateType() {
    $u = "
UPDATE PType
SET mode = b'0'
WHERE pkPType = " . $this->Id;

    $this->conn->query($u);
  }

/***
* @param	fkType pkPType reference
		ordermust be poistive
***/
  function orderType($fkType, $order) {
    if ($order < 0) { return false; }

    $u = "
UPDATE PType
SET order = {$order} 
WHERE pkPType = " . $this->Id;

    $this->conn->query($u);  
  }

/***
* requires pkPQual as new_type_quality prior to call
* could change to allow multi
* could change to allow by name
***/
  function qualify($fkQual) {
    $i = "
INSERT INTO ioc.PQualLPType
SET fkPType = {$this->Id}
, fkPQual = {$fkQual}
, dateCreated = UNIX_TIMESTAMP()";

    try {
      $this->conn->query($i);
    }
    catch (Exception $e) {
      echo 'Caught exception: ',  $e->getMessage(),
        "\nThe type Id ({$this->Id}) could not be qualified to quality Id ({$fkQual}).",
        "\nsql: {$i}\n";
    }
  }

/***
* requires target quality_Id for removal prior to call
***/
  function disqualify($fkQual) {
    $u = "
UPDATE ioc.PQualLPType
SET mode = b'0'
WHERE fkPType = {$this->Id}
 AND fkPQual = {$fkQual}";

    $this->conn->query($u);
  }

/***
* #desc		
***/
	public function findTypeByName($name) {
		$q = "
SELECT pkPType as Id
FROM PType
WHERE MACHINE = '" . parent::sanitizeMachineName($name) . "'";
		$this->conn->query($q);
		$ret = $r->fetch_object($r);

		return $ret->Id;
	}
}

