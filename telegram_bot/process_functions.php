<?php

require_once "config.php";
require_once "api_functions.php";

function process($updates) {
	if (!$updates || !$updates->result || !is_array($updates->result)) {
		return false;
	}
	$last_processed = load_last_processed();
	foreach ($updates->result as $update) {
		if($update->update_id <= $last_processed) {
			continue;
		}
		if(contains($update, "/start")) {
			process_start($update);
			process_help($update);
		}
		if(contains($update, "/help")) {
			process_help($update);
		}
	}
	return true;
}

function contains($update, $needle) {
	return (false !== stripos($needle, $update->message->text));
}

function process_start($update) {
	$fullName = $update->message->from->first_name ." ". $update->message->from->last_name;
	$reply = "Hello, {$fullName}!\n".
	"Nice to meet you!";
	send_reply($update, $reply);
}

function process_help($update) {
	$reply = "This bot works to help you manage your downloads. \n".
	"Just send me the link that you want to download. Url can be torrent, either regular http link. \n\n".
	"List of commands: \n".
	"/help - this text\n";
	send_reply($update, $reply);
}

function send_reply($update, $text) {
	send_message($update->message->chat->id, $text);
	save_last_processed($update);
}

function load_last_processed() {
	$id = file_get_contents(FILENAME_LAST_PROCESSED);
	return $id ? $id : 0;
}

function save_last_processed($update) {
	$id = $update->update_id;
	file_put_contents(FILENAME_LAST_PROCESSED, $id);
}