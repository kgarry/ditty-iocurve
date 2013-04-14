<?php

define('__PAGE_DEBUG_MODE__', false);
define('__ARENA_SCALE__', 21);
define('__MIN_INFLUENCE_THRESHHOLD__', 80);
define('__BLANK_TILE__', 0);
define('__TILE_TYPES_ARR__', serialize(array(1, 2, 3, 4, 5, 6, 7)));

# IOCurve
require_once("../point.class.php");

# Game
require_once("arena.class.php");
require_once("tile.class.php");
require_once("hero.class.php");

# GUI
require_once("page.class.php");

# composer->rachet->web socketo.me
require_once('../../composer/vendor/autoload.php');
