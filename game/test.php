<?php
// add some heroes
// add turn cycling
// add land mutation
// add user->environment actions

$perf['s1'] = microtime(true);
require_once("bootstrap.php");

$page = new BasicPage();

/*********************************************
* load an EXISTING ARENA from data source
*********************************************/
if (!empty($_REQUEST['aid'])) { // todo check if they own the arenaId :)
	$arena = new Arena($_REQUEST['aid']);
	$arena = $arena->load_arena;
Page::explain($arena, 'Begin test with _REQ aid', false);
	$arena->age();
	
	$blankTiles = $arena->locateTilesByType(__BLANK_TILE__);
Page::explain($blankTiles, ' blankTiles ', false);

	foreach ($blankTiles as $blankTile) {
		$neighbors = $arena->getTileNeighbors($blankTile);
		foreach ($neighbors as $neighbor) {
			$blankTile->considerNeighborType($neighbor);
		}

		if (count($blankTile->neighborInfluence) === 0) { continue; }
Page::explain($blankTile->neighborInfluence, 'blankTile', false);

		arsort($blankTile->neighborInfluence); 
Page::explain($blankTile->neighborInfluence, '..', false);
		$neighborId = key($blankTile->neighborInfluence); // should be on first item, else add reseti

Page::explain($neighborId, 'key', false);
		if (!empty($neighborId)) {
			$neighborInfluence = array_shift($blankTile->neighborInfluence);
Page::explain($neighborInfluence, 'neighbor infl', false);
			if ($neighborInfluence > __MIN_INFLUENCE_THRESHHOLD__) {
				$neighbor = $arena->tiles[$neighborId];
				$blankTile->setType($neighbor->type);
				$blankTile->claim($neighbor); // mutator claiming kill
				$blankTile->age();
				$blankTile->neighborInfluence = null;
			}
		}
	}
	unset($blankTiles);

Page::explain($debug05, ' ', false);
Page::explain($arena, 'End test with _REQ aid', false);
Page::explain($arena->tiles);
}

/***********************************************
* make a NEW ARENA instance from scratch
***********************************************/
else {
	$arena = new Arena();

        // place a couple mutating tiles
//	placeTiles($arena, 2);

	// place 2 heroes
	placeHeroes($arena, 1, 1);
	placeHeroes($arena, $arena->scale, $arena->scale);
}

/***********************************************
* PRINT RESULTS and ANALYSIS
***********************************************/
renderTest($page, $arena);
renderTestAnalysis($perf);


/******************************************************************
******************************************************************/
function renderTest($page, &$arena) {
	echo $page->header;
	echo $page->controls; 
	echo $arena->render();
Page::explain('pre-save Id: '.$arena->Id);
	$arena->data_save();	// this wont live here
Page::explain('post-save Id: '.$arena->Id);
// here start the page sub-model for controls todo
	echo '<br><a href="?aid='.$arena->Id.'">[ Next Turn ]</a> '; //todo
	echo '<a href="./test.php">[ Start New Arena ]</a>'; //todo
	echo $page->footer;
}

function renderTestAnalysis($perf) {
	// Analysis
	$e1 = microtime(true);
	echo '<hr>';
Page::explain('Test1 ran in: ' . ($e1-$perf['s1']) . "\nMem peak: actual: " . 
		(memory_get_peak_usage(true)/1048576) . " mb\n",
		'forcing debug',
		true);
}

function placeTiles(&$arena, $iters, $hero=null, $types=null) {
        $blankTiles = $arena->locateTilesByType();
        $numTiles = count($blankTiles);

	if (!is_array($types)) { 
		$tileTypes = $arena->getTileTypes();
	}
        for ($i=0; $i < $iters; $i++) {
                $tile = $blankTiles[rand(1, $numTiles)];
//Page::explain($tile, 'tile picked', true);
		$newType = $tileTypes[rand(1, count($tileTypes)-1)];
                $tile->setType($newType);
		$tile->claim($hero);
Page::explain($tile, 'tile type set to '.$newType, false);
Page::explain($tile, 'tile picked', false);
        }
}

function placeHeroes(&$arena, $x, $y) {
	$numTiles = count($arena->tiles);

	$place = $arena->findTile($x, $y);
//	$place->addClass('hero');
	$hero = new Hero($x.$y); // todo slacker
//Page::explain($hero, 'hero:'.$x.':'.$y, true);
	foreach ($arena->getTileNeighbors($place, $hero->movement) as $inRangeTile) {
		$inRangeTile->addClass('heroRange');
	}
	$hero->setLoc($place->coordX, $place->coordY);
	$arena->addHero($hero);
Page::explain($arena, 'hero:'.$x.':'.$y, false);
	
	placeTiles($arena, 1, $hero);
	// set tat tile to hero favorite?? Only if has tile start skill?
}
