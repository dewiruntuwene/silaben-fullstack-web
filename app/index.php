<?php
    // http or https request
	if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
		$currentUrl = 'https://';
	} else {
		$currentUrl = 'http://';
	}

    // get current url 
    $currentUrl .= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    header('Location: '.$currentUrl.'public/');
	exit; 

?>

Something is wrong with the web server installation :-(