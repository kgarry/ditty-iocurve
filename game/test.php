<?php
// add some heroes
// add turn cycling
// add land mutation
// add user->environment actions

$s1 = microtime(true);
require_once("bootstrap.php");

//-------
$page = new BasicPage();

// prepare Arena
if (!empty($_REQUEST['aid'])) { // todo check if they own the arenaId :)
	$arena = new Arena($_REQUEST['aid']);
	$arena = (object) $arena->load_arena;
	$arena->explain(false, 'Begin test with _REQ aid');
}
else {
	$arena = new Arena();
	$tileTypes = $arena->getTileTypes();

	// place some random color tile foo
	$iters = rand(3,45);
	for ($i=0; $i < $iters; $i++) {
		$x = rand(1, $arena->maxX);
		$y = rand(1, $arena->maxY);
		$tile = $arena->tiles[$x][$y];
		$tile->setType($tileTypes[rand(0, count($tileTypes)-1)]);
	}
}

//       $arena->getTileNeighbors($here);

// output payload
echo $page->header . 
	$arena->render() . 
	$page->footer;
//	echo var_export($arena->log, 1);

$arena->explain('pre-save Id: '.$arena->Id);
$arena->data_save();	// this wont live here
$arena->explain('post-save Id: '.$arena->Id);

//$arena_post_save = $arena->load();

$e1 = microtime(true);


$arena->explain('<textarea id="log">' .
	'Test1 ran in: ' . ($e1-$s1) . "\n" .
	'Mem peak: actual: ' . (memory_get_peak_usage(true)/1048576) . " mb\n");
