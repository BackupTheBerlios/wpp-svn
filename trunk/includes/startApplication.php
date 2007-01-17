<?php

//Logging starten
$LOG = new Log();

//DB öffnen
DB_connect(DB_USER,DB_PASSWORD,DB_HOST,DB_PORT,DB_NAME);
$LOG->write('3','startApplication.php:8:Datenbankverbindung hergestellt');

//Sprachdatei 
//noch automatisch die richtige Sprache wählen lassen
include(WPP_BASE.WPP_ROOT."/lang/lang_de.php");
$LOG->write('3','startApplication.php:13:Sprache: de');

session_start();
$LOG->write('3','startApplication.php:16:Session gestartet');

// noch anpassen an erkannte Sprache...:
//$_SESSION['lang']="de";

?>