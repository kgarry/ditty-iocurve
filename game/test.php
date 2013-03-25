<?php
// add some heroes
// add turn cycling
// add land mutation
// add user->environment actions

$s1 = microtime(true);
require_once("bootstrap.php");

//-------
$page = new BasicPage();
$minInfluenceThreshhold = 80; // influenceThreshhold shoul dre-calc and increase as age increases

// prepare Arena
if (!empty($_REQUEST['aid'])) { // todo check if they own the arenaId :)
	$arena = new Arena($_REQUEST['aid']);
	$arena = (object) $arena->load_arena;
//	Page::explain($arena, 'Begin test with _REQ aid',1);
	// cycle turn, age arena and its tiles
	$arena->age();
	//$arena->eachTile(age); // not sure yet
//print '<pre>'.var_export($arena->tiles, true) . '</pre>'; exit;
	
	$blankTiles = $arena->locateTilesByType(0);
//Page::explain($blankTiles,' blankTiles ',true);

	foreach ($blankTiles as $blankTile) {
//Page::explain($tile, ' tile ',true);
		$neighbors = $arena->getTileNeighbors($blankTile);
//Page::explain($tile->neighbors, ' neighbors ',true);
		foreach ($neighbors as $neighbor) {
			$blankTile->considerNeighborType($neighbor);
		}
		arsort($blankTile->neighborInfluence); 
//Page::explain($blankTile->neighborInfluence,false,true);
		$neighborId = key($blankTile->neighborInfluence); // should be on first item, else add reset
		if (!empty($neighborId)) {
			$neighborInfluence = array_shift($blankTile->neighborInfluence);
//echo $blankTile->Id . ' @ ' . $neighborId . ' -> ' . $neighborInfluence . '<br>';
			if ($neighborInfluence > $minInfluenceThreshhold) {
				$blankTile->setType($arena->tiles[$neighborId]->tileType);
				$blankTile->age();
//$debug05 .= 'Set ' . $blankTile->Id . ' @ ' . $neighborId . ' -> ' . $neighborInfluence . 'to ' . $arena->tiles[$neighborId]->tileType . '<br>';
			}
		}
	}
	unset($blankTiles);

//Page::explain($debug05, ' ',true);
//Page::explain($arena, 'End test with _REQ aid',true);
	//Page::explain($arena->tiles);
}
// make a new arena instance from scratch
else {
	$arena = new Arena();
	$tileTypes = $arena->getTileTypes();

	// place some random color tile foo
	$iters = rand(3,10);
	$numTiles = count($arena->tiles);
	for ($i=0; $i < $iters; $i++) {
		$tile = $arena->tiles[rand(1, $numTiles)];
		$tile->setType($tileTypes[rand(0, count($tileTypes)-1)]);
	}
}

//       $arena->getTileNeighbors($here);

// output payload
echo $page->header; 
echo $arena->render();
Page::explain('pre-save Id: '.$arena->Id);
$arena->data_save();	// this wont live here
Page::explain('post-save Id: '.$arena->Id);
// here start the page sub-model for controls
echo '<br><a href="?aid='.$arena->Id.'">[ Next Turn ]</a> ';
echo '<a href="./test.php">[ Start New Arena ]</a>';
echo $page->footer;
//	echo var_export($arena->log, 1);


//$arena_post_save = $arena->load();

$e1 = microtime(true);


// Analysis
echo '<hr>';
Page::explain('Test1 ran in: ' . ($e1-$s1) . "\nMem peak: actual: " . (memory_get_peak_usage(true)/1048576) . " mb\n",
	'forcing debug',
	true);
