<?php
/**
 * User: pavel
 * Date: 28.10.16
 * Time: 12:32
 */

require_once "api_functions.php";
require_once "process_functions.php";

echo "Bot listener started \n\n";

for ($i=1;$i<=RETRY;$i++) {
    echo " Query $i... \n";

    //get last updates
    $offset = load_last_processed();
    $updates = get_updates($offset+1);
    var_dump($updates);

    //process updates
    process($updates);
}

echo "Timed out! \n\n";