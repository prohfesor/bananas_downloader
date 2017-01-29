<?php

require_once "config.php";
require_once "api_functions.php";

function process($updates) {
	if (!$updates || !$updates->result || !is_array($updates->result)) {
		return false;
	}
	foreach ($updates->result as $update) {
		if(contains($update, "/start")) {
			process_start($update);
			process_help($update);
		}
		if(contains($update, "/help")) {
			process_help($update);
		}
	}
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
	$reply = "This bot is created to help you manage your downloads. \n".
	"You just send me the url that you want to be downloaded. Url can be torrent either regular http link. \n\n".
	"List of commands: \n".
	"/help - this text\n";
	send_reply($update, $reply);
}

function send_reply($update, $text) {
	send_message($update->message->chat->id, $text);
}