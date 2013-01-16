<?php

$path = "/var/www/vhosts/ditty.iocurve.com/htdocs/lib/iocurve/scripts";

echo "... wiping slate clean\n";
include($path . "/teardown.php");

echo "... setting up base environment\n";
include($path . "/setupEnv.php");

echo "... setting up example song(s)\n";
include($path . "/setupExamples.php");

echo "Done";
