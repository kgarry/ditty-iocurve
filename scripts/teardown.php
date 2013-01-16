<?php
$start = microtime(true);

$lib_path = "/var/www/vhosts/ditty.iocurve.com/htdocs/lib/iocurve";

// ---
require_once($lib_path . "/iocurve.class.php");
$x = new IOCurve();

// MySQL
$qs = array(
 "TRUNCATE PType",
 "TRUNCATE PQual",
 "TRUNCATE P",
 "TRUNCATE PLP",
 "TRUNCATE PLPType",
 "TRUNCATE PLPQual",
 "TRUNCATE PQualLPType",
);

foreach ($qs as $q) {
	echo $q . " (complete)\n";
	$x->conn->query($q);

	if ($x->conn->error) {
		echo "\t" . $x->conn->error . "\n";
	}
}

// NOSQL
//$x->nosql->drop();

// Log
echo "\n\tRuntime: " . (microtime(true) - $start) . " seconds \n";
