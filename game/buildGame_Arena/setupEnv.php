<?php
// register starttime as micro/float
#$start = microtime(true);
$root_path 	= "/var/www/vhosts/ditty.iocurve.com/htdocs";
$lib_path 	= "/lib/iocurve";	

// load required libs
require_once($root_path . $lib_path . "/point.class.php");
require_once($root_path . $lib_path . "/type.class.php");
require_once($root_path . $lib_path . "/quality.class.php");


// Types ----------------------------------------
echo "\t ... making some type(s)\n";
$type = new Type('Arena');
$qual = new Quality("object");
$type->qualifyType($qual->Id);

