<?php
$s1 = microtime(true);
require_once("bootstrap.php");

test1();

//-------
function test1() {
        $a = new Arena();
        $here1 = $a->tiles[3][5];
        $here1->setType('Green');
        $here2 = $a->tiles[6][8];
        $here2->setType('Blue');
//var_dump($here);

        $a->getTileNeighbors($here1);
	
	echo $a->render() . "<br>";
	echo var_export($a->log, 1);	
}

$e1 = microtime(true);

echo 'Test1 ran in: ' . ($e1-$s1);

