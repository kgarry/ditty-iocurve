<?php

require_once("iocurve.class.php");

class Quality extends IOCurve {

/***
* @desc		new point quality, registered to IOC
***/
  function registerQuality($name=null, $MACHINE=null, $smdesc='undefined') {
	if ($name === null) {
		$name = "auto_" . uniqid(date("Ymd_His_"), true);
	}

	        // handle missing MACHINE value
        if (empty($MACHINE)) {
                $MACHINE = str_replace(" ", "_", strtoupper($name));
        }

        // 50 tries to make a clean machine_name for them
        $tries = (int) 0;
        while ($this->preregisterQualName($MACHINE) === false) {
                $MACHINE .= rand(0,9);
                $tries++;

                if ($tries > 50) {
                        return false;
                }
        }

	
	$i = "
INSERT INTO PQual 
SET name = '" . $name . "', 
 MACHINE = '" . $MACHINE . "',
 mode = b'1',
 smDesc = '" . $smdesc . "',
 dateCreated = UNIX_TIMESTAMP()";
	$this->conn->query($i);

	$this->Id = $this->conn->insert_id;

	// save to nosql as well
//	$this->nosql->PQual->save( $this->loadQuality() );
  }


/***
* @desc         handler to assure unique machine_name
***/
        function preregisterQualName($MACHINE) {
                $q = "
SELECT pkPQual
FROM PQual
WHERE MACHINE = '" . $MACHINE . "'";
                $r = $this->conn->query($q);

                if ($r->num_rows > 0) {
                        return false;
                }
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
SET mode = b'1'
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
