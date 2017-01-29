<?php
/**
 * User: pavel
 * Date: 28.10.16
 * Time: 12:32
 */

require_once "config.php";
require_once "api_functions.php";
require_once "process_functions.php";

//get last updates
$updates = get_updates();

var_dump($updates);

//process updates
process($updates);