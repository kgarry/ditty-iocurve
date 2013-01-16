<?php
// FIXME get user/pass/host out of here, use lib/.htaccess defined sys vars - kjg

/**
* @todo		extend database/mysql and override funcs to callback nosql mgmt..?
**/
class IOCurve extends IOData {
	private $host 	= "localhost";
	private $user 	= "ioc";
	private $pass 	= "thecolorpurplehaze1";
	private $db 	= "ioc";

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

}
