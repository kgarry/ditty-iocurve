<?php

require_once("iocurve.class.php");

class Quality extends IOCurve {
  private $max_machine_name_tries = 50;

  function __construct($name=null) {
    parent::__construct();

    if ($name) { 
      $this->registerQuality($name);
    }
  }

/***
* @desc		new point quality, registered to IOC
***/
  function registerQuality($name=null, $MACHINE=null, $smdesc='undefined') {
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
INSERT INTO PQual 
SET name = '" . $name . "', 
 MACHINE = '" . $this->MACHINE . "',
 mode = b'1',
 smDesc = '" . $smdesc . "',
 dateCreated = UNIX_TIMESTAMP()";
	try {
		$this->conn->query($i);
	}
	catch (Exception $e) {
		echo 'Caught exception: ',  $e->getMessage(),
			"\nThe quality ({$name}: {$this->MACHINE}) could not be created.",
			"\nsql: {$i}\n";
	}

	$this->Id = $this->conn->insert_id;

	// save to nosql as well
//	$this->nosql->PQual->save( $this->loadQuality() );
	return $this->Id;
  }


/***
* @desc         handler to assure unique machine_name
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
/*        function preregisterQualName($MACHINE) {
                $q = "
SELECT pkPQual
FROM PQual
WHERE MACHINE = '" . $MACHINE . "'";
                $r = $this->conn->query($q);

                if ($r->num_rows > 0) {
                        return false;
                }
        }*/


/***
* @desc		requires Id in object currently
* 
***/
	function loadQuality() {
		$q = "
SELECT name, smDesc, mode, dateCreated
FROM PQual
WHERE pkPQual = " . $this->Id;
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
SET mode = b'0'
WHERE pkPQual = " . $this->Id;
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
