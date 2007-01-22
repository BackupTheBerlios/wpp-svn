<?php
// Check für Zugangsberechtigung ADMIN
$user = restoreUser();

if($user==null){	// als VIEWER weiter
	redirectURI("/viewer/index.php");
}
else{	// wenn Session wiederhergestellt
	if (!$user->checkPermissions(1,1)) {	// wenn nicht ADMIN
		if ($user->checkPermissions(1,0)) {	// wenn USER
			redirectURI("/user/index.php");
		}
		else{	// sonst VIEWER
			redirectURI("/viewer/index.php");
		}
	}
}
// Sonst als ADMIN weiter

?>