<?php
// register starttime as micro/float
$start = microtime(true);


// load required libs
require_once("point.class.php");
require_once("pointType.class.php");
require_once("pointQuality.class.php");


// make qualities
$q1 = new PointQuality();
$q1->registerPointQuality("Time Signature");

$q2 = new PointQuality();
$q2->registerPointQuality("Instrument");

$q3 = new PointQuality();
$q3->registerPointQuality("Tempo");

$q4 = new PointQuality();
$q4->registerPointQuality("Privacy Mode"); // PUBLIC::

$q5 = new PointQuality();
$q5->registerPointQuality("Popularity");

$q6 = new PointQuality();
$q6->registerPointQuality("Processed WAV uri");


// make 2 types, each with 1 type currently specific quality an 1 shared quality
$t1 = new PointType();
$t1->registerPointType("Song", "SONG");
$t1->qualifyPointType($q1->ID);
$t1->qualifyPointType($q4->ID);

$t2 = new PointType();
$t2->registerPointType("Orchestral", "ORCHESTRAL_SONG");
$t2->qualifyPointType($q2->ID);
$t2->qualifyPointType($q3->ID);
$t2->qualifyPointType($q4->ID); // TEST::duplicated PUBLIC PQual

$t3 = new PointType();
$t3->registerPointType("Forked");
$t3->qualifyPointType($q5->ID);

$t4 = new PointType();
$t4->registerPointType("Processed WAV file", "PROCESSED_WAV");
$t4->qualifyPointType($q6->ID);


// make point - no type, both qualities
$p1 = new Point();
$p1->registerPoint("First Song");
$p1->typifyPoint($t1->ID);

$p2 = new Point();
$p2->registerPoint("Second Song");
$p2->typifyPoint($t1->ID);
$p2->typifyPoint($t2->ID);
$p2->typifyPoint($t3->ID);
$p2->typifyPoint($t4->ID);

$p3 = new Point();
$p3->registerPoint("Third Song");
$p3->typifyPoint($t1->ID);
$p3->typifyPoint($t4->ID);


// display test bench
echo $p1->loadPointByID($p1->ID, true);
echo $p2->loadPointByID($p2->ID, true);
echo $p3->loadPointByID($p3->ID, true);


// show speed
echo "\n\tRuntime: " . (microtime(true) - $start) . "seconds \n";
