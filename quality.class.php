<?php

require_once("iocurve.class.php");

class Quality extends IOCurve {
  private $max_machine_name_tries = 50;

  function __construct($name=null, $reuseQuals=true) {
    parent::__construct();

    if ($name) { 
      if ($reuseQuals) {
        $this->register($name);
      
      } else {
        $this->preregisterName($name);
        $this->register($name, $this->MACHINE);
      }
    }
  }


/***
* @desc		new point quality, registered to IOC
***/
  function register($name, $MACHINE=null, $smDesc='undefined') {
    if (empty($MACHINE)) {
      $this->MACHINE = parent::sanitizeMachineName($name);
      
    } else {
      $this->MACHINE = $MACHINE;
    }

    $this->Id = $this->checkMachine($this->MACHINE);
    
    // not found? insert and collect Id
    if (empty($this->Id)) {
      $this->Id = $this->insert($name, $this->MACHINE, $smDesc);  
    }

// save to nosql as well
#	$this->nosql->PQual->save( $this->loadQuality() );

	  return $this->Id;  //need to return a value?
  }


/***
***/
  private function insert($name, $MACHINE, $smDesc) {
    $i = "
INSERT INTO PQual 
SET name = '" . $name . "', 
 MACHINE = '" . $MACHINE . "',
 mode = b'1',
 smDesc = '" . $smDesc . "',
 dateCreated = UNIX_TIMESTAMP()";
    try {
    $this->conn->query($i);
  }
  catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(),
      "\nThe quality ({$name}: {$MACHINE}) could not be created.",
      "\nsql: {$i}\n";
  }

  return $this->conn->insert_id;
  }

/***
* @desc         handler to assure unique machine_name
***/
	private function preregisterName($name, $max_tries=null) {
    if (empty($max_tries)) { 
      $max_tries = $this->max_machine_name_tries; 
    }

    $tries = (int) 0;

    $MACHINE_BASE = parent::sanitizeMachineName($name);

    while ($tries < $max_tries) {
      $MACHINE = $MACHINE_BASE;

      if ($tries > 0) {
        $MACHINE .= uniqid();
      }
      $tries++;

      if ($this->checkMachine($MACHINE) === FALSE) {
        $this->MACHINE = $MACHINE; 
        return true;   
      }
    }

    return false;
  }

/***
* @return   false or pkPQual id
***/
  public function checkMachine($MACHINE) {
    $q = "
SELECT pkPQual
FROM PQual
WHERE MACHINE = '" . $MACHINE . "'";
    $r = $this->conn->query($q);
    
    while ($row = $r->fetch_object()) { 
      return $row->pkPQual;
    }
    
    return false;
  }

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
