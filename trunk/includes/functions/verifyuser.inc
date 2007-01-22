<?php
// Check für Zugangsberechtigung USER
$user = restoreUser();

if ($user==null) {	// wenn VIEWER
	redirectURI("/viewer/index.php");
}
else{ // wenn Session wiederhergestellt
	if ($user->checkPermissions(1,1)) {	// wenn ADMIN
		redirectURI("/admin/index.php");
	}
	if (!$user->checkPermissions(1,0)) {// wenn kein USER, dann VIEWER
		redirectURI("/viewer/index.php");
	}
}
// Sonst als USER weiter

?>