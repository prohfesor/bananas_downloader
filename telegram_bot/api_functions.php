<?php
/**
 * Created by PhpStorm.
 * User: pavel
 * Date: 28.10.16
 * Time: 12:57
 */
require_once "config.php";

define('SERVICE_URL', "https://api.telegram.org/bot$bot_token");

function get_updates($offset =null) {
	$url = SERVICE_URL . "/getUpdates";
	$url .= ($offset) ? "?offset=$offset" : "";
	return query($url);
}


function send_message($chat_id, $text) {
	$url = SERVICE_URL . "/sendMessage?chat_id=$chat_id&text=" . urlencode($text);
	return query($url);
}

function query($url) {
	$result = file_get_contents($url);
	if($result) {
		$result = json_decode($result);
	}
	return $result ? $result : false ;
}