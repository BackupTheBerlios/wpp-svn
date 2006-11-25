<?php

//Logging starten
$LOG = new Log();

//DB öffnen
DB_connect(DB_USER,DB_PASSWORD,DB_HOST,DB_PORT,DB_NAME);
$LOG->write('3','Datenbankverbindung hergestellt');

//Sprachdatei 
//noch automatisch die richtige Sprache wählen lassen
include("../lang/lang_de.php");


?>