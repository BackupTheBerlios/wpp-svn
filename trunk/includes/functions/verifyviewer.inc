<?php
// Wenn Session gefunden, dann auf entsprechende Zugangs-Ebene weiterleiten. 
$user = restoreUser();

$LOG = new Log();
$LOG->write('2','verifyviewer.inc');

if ($user !=null) {	// wenn Session wiederhergestellt -> kein VIEWER
	if ($user->checkPermissions(1,1)) {	// wenn ADMIN
		redirectURI("/admin/index.php");
	}
	if ($user->checkPermissions(0,0,0,1,1)) {	// wenn ORDERER
		redirectURI("/orderer/index.php");
	}
	if ($user->checkPermissions(0,0,1)) {	// wenn USER
		redirectURI("/user/index.php");
	}
	
}
// Sonst als VIEWER weiter

?>