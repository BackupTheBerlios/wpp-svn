<?php


	function restoreUser() {
	
		$LOG = new Log();
		session_start();
		$LOG->write('3','session.inc:8:Versuche User wiederherzustellen');

		if (isset($_SESSION['user'])) {
			$user = new User();
			$user->restore($_SESSION['user']);
			$LOG->write('2','session.inc:13:Wiederhergestellter Benutzer: '.$user->name);
			return $user;
		} else {
			$LOG->write('2','session.inc:16:Kein Benutzer angemeldet');
			return null;
		}
		
	}
	
	
	
?>