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
echo "\t...writing a blues song\n";
$p = new Point();
	$p->registerPoint("A Blues Song");
	$p->typifyPointByTypeName("SONG");
	$p->qualifyPoint("TIME_SIGNATURE", "4/4");
	$p->qualifyPoint("INSTRUMENT_SEQUENCE", "garble-dee-gook..vexflow");
	$p->qualifyPoint("LOCATION", 1);

echo "\t...writing a rock song\n";
$p = new Point(); 
	$p->registerPoint("A Rock Song");
	$p->typifyPointByTypeName("SONG");
	$p->qualifyPoint("TIME_SIGNATURE", "4/4");
	$p->qualifyPoint("INSTRUMENT_SEQUENCE", "garble-dee-gook..vexflow");
	$p->qualifyPoint("LOCATION", 1);

