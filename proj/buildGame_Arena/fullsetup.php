<?php
$start = microtime(true);

$path = "/var/www/vhosts/ditty.iocurve.com/htdocs/lib/iocurve/proj/buildGame_Arena";

echo "\n... cleaning db\n";
include($path . "/teardown.php");
echo "... db cleaned\n\n";

echo "... setting up base environment\n";
include($path . "/setupEnv.php");
echo "... base environment written\n\n";

echo "... setting up point(s)\n";
include($path . "/setupExamples.php");

$end = microtime(true);

echo "Done in " . ($end-$start) . "s\n";
