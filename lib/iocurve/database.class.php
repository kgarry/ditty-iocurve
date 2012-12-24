<?php

// FIXME get user/pass/host out of here, use lib/.htaccess defined sys vars - kjg

/**
*
**/
class Database {
	private $host 	= "localhost";
	private $user 	= "ioc";
	private $pass 	= "thecolorpurplehaze1";
	private $db 	= "ioc";

/**
* @desc   create db connection pointer
**/
	public function __construct() {
		$this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);

		return $this->conn;
	}
}
