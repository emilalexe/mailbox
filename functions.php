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

function getBootstrap($folderVersion = null){
	if(isset($folderVersion)){
		getStyle("$folderVersion/css/bootstrap.min");
		getScript("$folderVersion/js/bootstrap.min");
	}
	else {
		$path = __DIR__ . '/assets/css/';
		$latest = getLatestVersionFolder($path);
		$version = $latest['version'];
		$folderVersion = $latest['folder_name'];

		if ($latest) {
			$msg = "Folderul cu cea mai recentă versiune este: $version & $folderVersion";
			console($msg);
			getStyle("$folderVersion/css/bootstrap.min");
			getScript("$folderVersion/js/bootstrap.min");
		} else {
			$msg = "Nu a fost găsit niciun folder care să respecte formatul.";			
			console($msg);
		}
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

function getLatestVersionFolder($basePath, $prefix = 'bootstrap-') {
    // 1. Scanăm directorul după toate fișierele/folderele
    $folders = scandir($basePath);
    
    $latestVersion = '0.0.0';
    $latestFolder = null;

    foreach ($folders as $folder) {
        // 2. Filtrăm doar folderele care încep cu prefixul dorit (ex: bootstrap-)
        if (strpos($folder, $prefix) === 0) {
            
            // 3. Extragem versiunea din numele folderului
            // Eliminăm prefixul și sufixul '-dist' pentru a rămâne doar cu numărul
            $version = str_replace([$prefix, '-dist'], '', $folder);
            
            // 4. Comparăm versiunea curentă cu cea mai mare găsită până acum
            if (version_compare($version, $latestVersion, '>')) {
                $latestVersion = $version;
                $latestFolder = $folder;
            }
        }
    }

    return [
        'folder_name' => $latestFolder,
        'version'     => $latestVersion
    ];
}

function console($msg){
	echo "<script>console.log('PHP Debug: " . $msg . "');</script>";
}


?>