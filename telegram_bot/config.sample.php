<?php
//
// Telegram Bot configuration
//

define('BOT_TOKEN', "666666666:XXXXX-xxxxxxxxxxxxxxxxxxxxxxxxxxxx");

define('DATA_DIR', realpath( dirname( __FILE__ )."/../") );
define('FILENAME_LAST_PROCESSED', DATA_DIR.'/last_processed');
define('FILENAME_DOWNLOADS', DATA_DIR.'/downloads');

define('RETRY', 20);
define('TIMEOUT_POLING', 40);