<?php

function checkUser($user,$read=0,$write=0,$purchase=0) {

	if ($user->read>=$read && $user->write>=$write && $user->purchase>=$purchase) {
		return true;
	} else {
		return false;
	}
	
}

?>