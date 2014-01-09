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
	$soundfile->register("Sound File");

$position = new Quality();
	$position->register("Position");

$timeSignature = new Quality();
	$timeSignature->register("Time Signature");

$tempo = new Quality();
	$tempo->register("Tempo");

$volume = new Quality(); 
	$volume->register("Volume");

$location = new Quality(); 
	$location->register("Location Start");

$author = new Quality(); 
	$author->register("Author");

$instrSeq = new Quality(); 
	$instrSeq->register("Instrument Sequence");

// $duration = new Quality(); 
// $durationSeconds->register("Duration Seconds");


// Types
echo "\t ... making types\n";
$song = new Type();
	$song->registerType("Song", "SONG");
	$song->qualify($timeSignature->Id);  
	$song->qualify($tempo->Id);
	$song->qualify($author->Id);

$songComplement = new Type();
	$song->registerType("Song Complement", "SONG_COMPLEMENT");
	$song->qualify($instrSeq->Id);

$user = new Type();
	$user->registerType("User", "USER");

$instrument = new Type();
	$instrument->registerType("Instrument", "INSTRUMENT");

$posInstrument = new Type();
	$posInstrument->registerType("Position Instrument", "POSITION_INSTRUMENT");
	$posInstrument->qualify($position->Id);

$tone = new Type();
	$tone->registerType("Tone", "TONE");
	$tone->qualify($soundfile->Id);

$note = new Type();
	$note->registerType("Note", "NOTE");
	$note->qualify($volume->Id);
	$note->qualify($location->Id);


// tones
echo "\t ... making tones (points)\n";
$n_arr = array("Rest", "A","Asharp","B","C","Csharp","D","Dsharp","E","F","Fsharp","G","Gsharp");
foreach ($n_arr as $item) {
	$n = new Point();
		$n->registerPoint($item);
		$n->typifyPointByTypeName("TONE");
}


// Instruments
echo "\t ... making cello (point)\n";
$n = new Point();
	$n->registerPoint("Cello");
	$n->typifyPointByTypeName("INSTRUMENT");
	$n->qualifyPointByName("SOUND_FILE", "sounds/cello");
	
	$PList = $n->loadPointIdListByType("TONE");
	for ($i=0; $item = $PList->fetch_assoc(); $i++) {
		print("\tadopting ".$item['Id']."\r");
		$n->adoptPoint($item['Id']);
	}
	

// TODO -- here we can test a clone of a point and then change the name and sound files
echo "\t ... making piano (point)\n";
$n = new Point();
	$n->registerPoint("Piano");
	$n->typifyPointByTypeName("INSTRUMENT");
	$n->qualifyPointByName("SOUND_FILE", "sounds/piano");

echo "\t ... making snare drum (point)\n";
$n = new Point();
	$n->registerPoint("Snare Drum");
	$n->typifyPointByTypeName("INSTRUMENT");
	$n->qualifyPointByName("SOUND_FILE", "sounds/snare_drum");

