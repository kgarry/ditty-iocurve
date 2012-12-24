<?php
$start = microtime(true);

require_once("database.class.php");

$x = new Database();

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

echo "\n\tRuntime: " . (microtime(true) - $start) . " seconds \n";
