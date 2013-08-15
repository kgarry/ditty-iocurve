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
echo "\t ... making some types\n";
/*
$song = new Type();
	$song->registerType("Song", "SONG");
	$song->qualifyType($timeSignature->Id);  
	$song->qualifyType($tempo->Id);
	$song->qualifyType($author->Id);
*/
$type = new Type('Race');
$qual = new Quality("Alignment");
$type->qualifyType($qual->Id);

$type = new Type('Department');
$qual = new Quality("Organization Name");
$type->qualifyType($qual->Id);
$qual = new Quality("Leader Title");
$type->qualifyType($qual->Id);
  
$type = new Type('Resource');
$qual = new Quality("Scarcity");
$type->qualifyType($qual->Id);
$qual = new Quality("Seasonal");
$type->qualifyType($qual->Id);
$qual = new Quality("Renewable");
$type->qualifyType($qual->Id);
$qual = new Quality("Stash Size");
$type->qualifyType($qual->Id);

$type = new Type('Wood'); // ?? should there be PType.fkPtype for 'extends' path?
$type = new Type('Stone');

$type = new Type('Land');
$qual = new Quality("Scarcity");  // Exception thrown here
$type->qualifyType($qual->Id);
$qual = new Quality("Habitable");
$type->qualifyType($qual->Id);
$qual = new Quality("Danger Level");
$type->qualifyType($qual->Id);
$qual = new Quality("Border Restriction");
$type->qualifyType($qual->Id);



// Quals ----------------------------------------
echo "\t ... making 'floater' qualities\n";
/*
$moodInvoked = new Quality('Mood Invoked');
*/

