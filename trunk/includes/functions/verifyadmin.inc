<?php
// Check für Zugangsberechtigung ADMIN
$user = restoreUser();

/*
$uri=$_SERVER['REQUEST_URI'];
//$getVars = echo $_SERVER[QUERY_STRING];	// Get-Variablen holen
echo $uri;
*/

if ($user !=null) {	// wenn Session wiederhergestellt
	if ($user->getRole()!=1) {	// wenn nicht ADMIN
		if ($user->getRole()==2) {	// wenn USER
			redirectURI("/user/index.php");
		}
		else{
			redirectURI("/viewer/index.php");
		}
	}
}
else{	// wenn keine Session wiederhergestellt
	redirectURI("/viewer/index.php");
}
?>