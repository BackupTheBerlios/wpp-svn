<?php

function redirectURI($uri,$params='') {

	$LOG = new Log();
	$LOG->write('2','Weitergeleitet zu: '.$uri.'?'.$params);
	//http_redirect($uri);
	if ($params=='') {
		header("Location: ".HTTP_HOSTNAME.WPP_ROOT.$uri);
	} else {
		header("Location: ".HTTP_HOSTNAME.WPP_ROOT.$uri."?".$params);
	}
	exit;	
}

?>