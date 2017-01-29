<?php
/**
 * Created by PhpStorm.
 * User: pavel
 * Date: 29.01.17
 * Time: 22:11
 */

function download_add($url) {
    $list = downloads_list();
    foreach ($list as $k=>$line) {
        if(trim($url) == trim($line)) {
            return false;
        }
        $list[$k] = trim($line);
    }
    $list[] = trim($url);
    file_put_contents(FILENAME_DOWNLOADS, implode("\n",$list));
    return true;
}

function downloads_list() {
    $list = file(FILENAME_DOWNLOADS);
    return $list;
}