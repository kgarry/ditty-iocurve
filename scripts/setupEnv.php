<?php
// register starttime as micro/float
#$start = microtime(true);

$root_path 	= "/var/www/vhosts/ditty.iocurve.com/htdocs";
$lib_path 	= "/lib/iocurve";	

// load required libs
require_once($root_path . $lib_path . "/point.class.php");
require_once($root_path . $lib_path . "/type.class.php");
require_once($root_path . $lib_path . "/quality.class.php");


// Quals
// Time Signature, Tempo, Privacy Mode, Popularity
echo "\t ... making qualities\n";
$soundfile = new Quality();
$soundfile->registerQuality("Sound File");

$position = new Quality();
$position->registerQuality("Position");

$timeSignature = new Quality();
$timeSignature->registerQuality("Time Signature");

$tempo = new Quality();
$tempo->registerQuality("Tempo");

$volume = new Quality(); 
$volume->registerQuality("Volume");

// $duration = new Quality(); 
// $durationSeconds->registerQuality("Duration Seconds");

$location = new Quality(); 
$location->registerQuality("Location Start");


// Types
echo "\t ... making types\n";
$song = new Type();
	$song->registerType("Song", "SONG");
	$song->qualifyType($timeSignature->ID);
	$song->qualifyType($tempo->ID);

$user = new Type();
	$user->registerType("User", "USER");

$instrument = new Type();
	$instrument->registerType("Instrument", "INSTRUMENT");

$positionInstrument = new Type();
	$positionInstrument->registerType("Position Instrument", "POSITION_INSTRUMENT");
	$positionInstrument->qualifyType($position->ID);

$tone = new Type();
	$tone->registerType("Tone", "TONE");
	$tone->qualifyType($soundfile->ID);

$note = new Type();
	$note->registerType("Note", "NOTE");
	$note->qualifyType($volume->ID);
	$note->qualifyType($location->ID);


// tones
echo "\t ... making tones\n";
$n_arr = array("Rest", "A","Asharp","B","C","Csharp","D","Dsharp","E","F","Fsharp","G","Gsharp");
foreach ($n_arr as $item) {
	$n = new Point();
		$n->registerPoint($item);
		$n->typifyPointByTypeName("TONE");
}


// Instruments
echo "\t ... making cello\n";
$n = new Point();
	$n->registerPoint("Cello");
	$n->typifyPointByTypeName("INSTRUMENT");
	$n->qualifyPoint("SOUNDFILE", "sounds/cello");
	
	$PList = $n->loadPointIDListByType("TONE");
	foreach ($PList as $item) {
		print("\tadopting ".$item['ID']."\r");
		$n->adoptPoint($item['ID']);
	}
	
	#$n->adoptPoint($p->ID);
	
echo "\t ... making piano\n";
$n = new Point();
	$n->registerPoint("Piano");
	$n->typifyPointByTypeName("INSTRUMENT");
	$n->qualifyPoint("SOUNDFILE", "sounds/piano");
