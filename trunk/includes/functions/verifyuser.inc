<?php
// Check für Zugangsberechtigung USER
$user = restoreUser();

$LOG = new Log();
$LOG->write('2','verifyuser.inc');

if ($user==null) {	// wenn VIEWER
	redirectURI("/viewer/index.php");
}
else{ // wenn Session wiederhergestellt
	if ($user->checkPermissions(1,1)) {	// wenn ADMIN
		redirectURI("/admin/index.php");
	}
	if ($user->checkPermissions(0,0,0,1,1)) {	// wenn ORDERER
		redirectURI("/orderer/index.php");
	}
	if (!$user->checkPermissions(0,0,1)) {// wenn kein USER, dann VIEWER
		redirectURI("/viewer/index.php");
	}
}
// Sonst als USER weiter

?>