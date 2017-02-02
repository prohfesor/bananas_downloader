<?php

require_once "config.php";
require_once "api_functions.php";
require_once "downloads_functions.php";

function process($updates, $last_processed) {
	if (!$updates || empty($updates->result) || !is_array($updates->result)) {
		return false;
	}
	foreach ($updates->result as $update) {
		if($update->update_id <= $last_processed) {
			continue;
		}
		if(empty($update->message->text)){
            continue;
        }
		if(contains($update, "/start")) {
			process_start($update);
			process_help($update);
			continue;
		}
		if(contains($update, "/help")) {
			process_help($update);
			continue;
		}
		if(contains($update, "/id")) {
			process_id($update);
			continue;
		}
		if(!is_eligible($update)){
            continue;
        }
		if(contains_url($update)) {
            process_url($update);
			continue;
        }
        if(contains_magnet($update)) {
            process_magnet($update);
			continue;
        }
        send_not_understand($update);
	}
	return true;
}

function contains($update, $needle) {
	return (false !== stripos($update->message->text, $needle));
}

function contains_url($update) {
    $regexp = '/\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/im';
    return preg_match($regexp, $update->message->text);
}

function contains_magnet($update) {
    $regexp = '/magnet:\?[^\s]*/simx';
    return preg_match($regexp, $update->message->text);
}

function is_eligible($update) {
    $id = $update->message->from->id;
    if(in_array($id, WHITELISTED_USERS)){
        return true;
    }
    $reply = "Who are you? I don't recognize. Go away.";
    send_reply($update, $reply);
    return false;
}

function process_start($update) {
	$fullName = $update->message->from->first_name ." ". $update->message->from->last_name;
	$reply = "Hello, {$fullName}!\n".
	"Nice to meet you!";
	send_reply($update, $reply);
}

function process_help($update) {
	$reply = "This bot works to help you manage your downloads. \n".
	"Just send me the link that you want to download. Url can be torrent magnet, either regular http link. \n\n".
	"List of commands: \n".
	"/help - this text\n".
	"/id - your unique id\n";
	send_reply($update, $reply);
}

function process_id($update) {
	$id = $update->message->from->id;
	$reply = "Your id is: $id\n".
	"Use it for whitelisted users setting.\n";
	send_reply($update, $reply);
}

function process_url($update) {
    if (preg_match('/\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/im', $update->message->text, $regs)) {
        $url = $regs[0];
    } else {
        return false;
    }
    $result = download_add($url);
    $reply = $result ? "Download successfully added" : "Error adding this url to download queue";
    send_reply($update, $reply);
}

function process_magnet($update) {
    if (preg_match('/magnet:\?[^\s]*/simx', $update->message->text, $regs)) {
        $url = $regs[0];
    } else {
        return false;
    }
    $result = download_add($url);
    $reply = $result ? "Download successfully added" : "Error adding this url to queue";
    send_reply($update, $reply);
}

function send_not_understand($update) {
    send_message_reply($update->message->chat->id, $update->message->message_id, "I don't understand");
    save_last_processed($update);
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