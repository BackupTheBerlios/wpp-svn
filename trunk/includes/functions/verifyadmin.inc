<?php
// Check für Zugangsberechtigung ADMIN

$LOG = new Log();
$LOG->write('2','verifyadmin.inc');

$user = restoreUser();

if($user==null){	// als VIEWER weiter
	redirectURI("/viewer/index.php");
}
else{	// wenn Session wiederhergestellt
	if (!$user->checkPermissions(1,1)) {	// wenn nicht ADMIN
		if($user->checkPermissions(0,0,0,1,1)){ // wenn ORDERER
			redirectURI("/orderer/index.php");
		}
		elseif ($user->checkPermissions(0,0,1)) {	// wenn USER
			redirectURI("/user/index.php");
		}
		else{	// sonst VIEWER
			redirectURI("/viewer/index.php");
		}
	}
}
// Sonst als ADMIN weiter
?>