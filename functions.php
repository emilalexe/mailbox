<?php

function getSection($filename = null, $path = ""){
    if(file_exists($path . $filename . '.php')){
        include_once($path . $filename . '.php');
    } else {
        return null;
    }
}

function getHeader(){
    getSection('header');
}

function getFooter(){
    getSection('footer');
}

function getMenu(){
    getSection('menu');
}

function getStyle($filename = "style"){
    if(file_exists('assets/css/' . $filename . '.css')){
        echo '<link rel="stylesheet" href="assets/css/' . $filename . '.css">';
    }
}

function getScript($filename = "script"){
    if(file_exists('assets/script/' . $filename . '.js')){
        echo '<script type="text/javascript" src="assets/script/' . $filename . '.js"></script>';
    }
}

function getImage($filename = "image", $type = "png", $alt = "", $path = null){
    if($path == null){
        if(file_exists('assets/img/' . $filename . '.' . $type)){
            echo '<img alt="' . $alt . '" src="assets/img/' . $filename . '.' . $type . '" />';
        } 
    } else {
        if(file_exists($path . $filename . '.' . $type)){
            echo '<img alt="' . $alt . '" src="' . $path . $filename . '.' . $type . '" />';
        }
    }
}

function getLogo($url = false){
    if($url){
        echo '<a href="./">';
        getImage('logo','svg','MailBox logo','assets/brand/');
        echo '</a>';
    } else {
        getImage('logo','png','MailBox logo','assets/brand/');
    }
}

?>