<?php
// FIXME get user/pass/host out of here, use lib/.htaccess defined sys vars - kjg

require_once("iodata.class.php");

/**
* @todo		extend database/mysql and override funcs to callback nosql mgmt..?
**/
class IOCurve {
        private $host   = "localhost";
        private $user   = "ioc";
        private $pass   = "thecolorpurplehaze1";
        private $db     = "ioc";


/**
* @desc   create db connection pointers (MySQL & NOSQL)
**/
	public function __construct() {
		// init MySQL
		$this->conn = new IOData($this->host, $this->user, $this->pass, $this->db);

		// init MongoDB
//		$mongo = new Mongo("mongodb://127.0.0.1:27017");
//		$this->nosql = $mongo->ioc;
	}

	public function explainStatus() {
                $this->diary = array('state'=>"active", 'color'=>"green");

                return $this->diary;
        }

	public function query($query, $resultmode=MYSQLI_STORE_RESULT) {
		//print substr($query, 0, 55) . "\n\n";
		$rs = parent::query($query, $resultmode);
#throw new Exception($query);
		if ($rs->error) { 
			throw new Exception($rs->error);
		}

		return $rs;
	}
	
	public function sanitizeMachineName($name) {
		$MACHINE = trim(str_replace(" ", "_", strtoupper($name)));

		return $MACHINE;
	}	
}
