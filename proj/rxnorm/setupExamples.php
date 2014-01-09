<?php
// register starttime as micro/float
#$start = microtime(true);

$root_path 	= "/var/www/vhosts/ditty.iocurve.com/htdocs";
$lib_path 	= "/lib/iocurve";	

// load required libs
require_once($root_path . $lib_path . "/point.class.php");
require_once($root_path . $lib_path . "/type.class.php");
require_once($root_path . $lib_path . "/quality.class.php");


// Songs
echo "\t...making some points\n";

$conf_files = array(
//	'races.php',
//	'departments.php',
//	'resources.php',
);

foreach ($conf_files as $conf) {
	include_once('./conf/' . $conf);

	foreach ($items as $key => $item) {
		$p = new Point($key);

		$p->typify($item['types']); // send array

		$p->qualify($item['quals']);
	}
}
