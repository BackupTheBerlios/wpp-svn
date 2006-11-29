<?php

/* Alle Funktionen für die DB-Verbindung

*/

$mysql_socket;
$mysql_connection;


function DB_connect($username, $password, $hostname, $port, $dbname) {

	$mysql_socket = mysql_connect($hostname.":".$port, $username, $password);
	$mysql_connection = mysql_select_db($dbname, $mysql_socket);
	
}

function DB_query($query) {
	
	$LOG = new Log();
	$LOG->write('2','DB-Anfrage: '.$query);
	$answer = mysql_query($query);
	if (mysql_errno()) {
		$LOG->write('2','Fehler DB-Anfrage: '.mysql_error());
	} else {
		$LOG->write('3','DB-Anfrage akzeptiert');
	}
	return $answer;
	
}

function DB_fetchArray($selection) {
	
	return mysql_fetch_array($selection);

}


?>