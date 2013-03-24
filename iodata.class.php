<?php
// FIXME get user/pass/host out of here, use lib/.htaccess defined sys vars - kjg

/**
* @todo		extend database/mysql and override funcs to callback nosql mgmt..?
**/
class IOData extends mysqli {

/**
* @desc   create db connection pointers (MySQL & NOSQL)
**/
	public function __construct($host, $user, $pass, $db) {
		// init MySQL
		parent::__construct($host, $user, $pass, $db);

		// init MongoDB
#		$mongo = new Mongo("mongodb://127.0.0.1:27017");
#		$this->nosql = $mongo->ioc;
	}

	public function explainStatus() {
                $this->diary = array('state'=>"active", 'color'=>"green");

                return $this->diary;
        }

	public function query($query, $resultmode=MYSQLI_STORE_RESULT) {
		// get lead-in sql statement type
#		$type = strtoupper(substr(trim($query), 0, strpos($query, ' ')));
		
#		switch ($type) {
#			case "SELECT":
				// do mongo lookup (which fails over to mysqli) [$this->nosql->P->save($newItem);]
#				$r = $this->nosql->some_table->save($some_data);
#				break;
#			default:
//				$q = mysqli_real_escape_string($this, $query);
				$r = $this->queryRun($query, $resultmode);
				// $this->nosql->P->save($newItem);
				#$this->nosql->some_table->save($some_data);
#				break;
#		}

		return $r;
	}	

	private function queryRun($query, $resultmode) {
		$r = parent::query($query, $resultmode);
		if ($this->error) { 
			throw new Exception($this->error);
		}
		
		return $r;
	}
}
