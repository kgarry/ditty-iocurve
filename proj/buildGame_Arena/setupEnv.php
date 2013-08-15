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
// System
$type = new Type('Callback');
$qual = new Quality("ServiceType");
$type->qualifyType($qual->Id);
$qual = new Quality("ServicePath");
$type->qualifyType($qual->Id);

// Map
$type = new Type('Arena');
$qual = new Quality("object");
$type->qualifyType($qual->Id);

$type = new Type('Coord');
$qual = new Quality("X");
$type->qualifyType($qual->Id);
$qual = new Quality("Y");
$type->qualifyType($qual->Id);
$qual = new Quality("Z");
$type->qualifyType($qual->Id);

$type = new Type('TileType');
$qual = new Quality("AssetId");
$type->qualifyType($qual->Id);
$qual = new Quality("Visible");
$type->qualifyType($qual->Id);

$type = new Type('Team');
$qual = new Quality("Banner");
$type->qualifyType($qual->Id);

$type = new Type('Leader');
$qual = new Quality("Type");
$type->qualifyType($qual->Id);

$type = new Type('Event');
$qual = new Quality("Range");
$type->qualifyType($qual->Id);
$qual = new Quality("AffectsLeaders");
$type->qualifyType($qual->Id);

$type = new Type('Resource');
$qual = new Quality("Minable");
$type->qualifyType($qual->Id);

// Player
$type = new Type('Player');
$qual = new Quality("Genre");
$type->qualifyType($qual->Id);
$qual = new Quality("OAuthType");
$type->qualifyType($qual->Id);

$type = new Type('Genre');
$qual = new Quality("Namespace");
$type->qualifyType($qual->Id);

/*
p.Arena 
	p.Coord
		p.TileType
		p.Hero
		p.Event
		p.Prize
p.Callback
*/
