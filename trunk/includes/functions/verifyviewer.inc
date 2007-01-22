<?php
// Wenn Session gefunden, dann auf entsprechende Zugangs-Ebene weiterleiten. 
$user = restoreUser();

if ($user !=null) {	// wenn Session wiederhergestellt -> kein VIEWER
	if ($user->checkPermissions(1,1)) {	// wenn ADMIN
		redirectURI("/admin/index.php");
	}
	if ($user->checkPermissions(1,0)) {	// wenn USER
		redirectURI("/user/index.php");
	}
}
// Sonst als VIEWER weiter

?>