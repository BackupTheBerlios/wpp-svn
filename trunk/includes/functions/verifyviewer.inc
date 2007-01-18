<?php
// Wenn Session gefunden, dann auf entsprechende Zugangs-Ebene weiterleiten. 
$user = restoreUser();

if ($user !=null) {	// wenn Session wiederhergestellt
	if ($user->getRole()==2) {	// wenn USER
		redirectURI("/user/index.php");
	}
	if ($user->getRole()==1) {	// wenn ADMIN
		redirectURI("/admin/index.php");
	}
}

?>