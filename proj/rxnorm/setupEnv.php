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
	$song->qualify($timeSignature->Id);  
	$song->qualify($tempo->Id);
	$song->qualify($author->Id);
*/
$type = new Type('conso');
$quals[] = new Quality("lat");
$quals[] = new Quality("ts");
$quals[] = new Quality("lui");
$quals[] = new Quality("stt");
$quals[] = new Quality("sui");
$quals[] = new Quality("ispref");
$quals[] = new Quality("rxaui");
$quals[] = new Quality("saui");
$quals[] = new Quality("scui");
$quals[] = new Quality("sdui");
$quals[] = new Quality("sab");
$quals[] = new Quality("tty");
$quals[] = new Quality("code");
$quals[] = new Quality("str");
$quals[] = new Quality("srl");
$quals[] = new Quality("suppress");
$quals[] = new Quality("cvf");

foreach ($quals as $qual) {
//  echo var_dump($qual,1) . "\n";
  $type->qualify($qual->Id);
}
unset($type, $quals, $qual);

$type = new Type('rel');
$quals[] = new Quality("rxcui1");
$quals[] = new Quality("rxaui1");
$quals[] = new Quality("stype1");
$quals[] = new Quality("rel");
$quals[] = new Quality("rxcui2");
$quals[] = new Quality("rxaui2");
$quals[] = new Quality("stype2");
$quals[] = new Quality("rela");
$quals[] = new Quality("rui");
$quals[] = new Quality("srui");
$quals[] = new Quality("sab");
$quals[] = new Quality("sl");
$quals[] = new Quality("dir");
$quals[] = new Quality("rg");
$quals[] = new Quality("suppress");
$quals[] = new Quality("cvf");

foreach ($quals as $qual) {
  $type->qualify($qual->Id);
}
unset($type, $quals, $qual);

$type = new Type('sat');
$quals[] = new Quality("rxcui");
$quals[] = new Quality("lui");
$quals[] = new Quality("sui");
$quals[] = new Quality("rxaui");
$quals[] = new Quality("stype");
$quals[] = new Quality("code");
$quals[] = new Quality("atui");
$quals[] = new Quality("satui");
$quals[] = new Quality("atn");
$quals[] = new Quality("sab");
$quals[] = new Quality("atv");
$quals[] = new Quality("suppress");
$quals[] = new Quality("cvf");

foreach ($quals as $qual) {
  $type->qualify($qual->Id);
}
unset($type, $quals, $qual);

// Quals ----------------------------------------
echo "\t ... making 'floater' qualities\n";
/*
$moodInvoked = new Quality('Mood Invoked');
*/

