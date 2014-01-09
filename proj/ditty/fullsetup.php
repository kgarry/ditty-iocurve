<?php

$path = "/var/www/vhosts/ditty.iocurve.com/htdocs/lib/iocurve/scripts";

echo "\n... cleaning db\n";
include($path . "/teardown.php");
echo "... db cleaned\n\n";

echo "... setting up base environment\n";
include($path . "/setupEnv.php");
echo "... base environment written\n\n";

echo "... setting up example song(s)\n";
include($path . "/setupExamples.php");
echo "... example songs written\n\n";

echo "Done";
