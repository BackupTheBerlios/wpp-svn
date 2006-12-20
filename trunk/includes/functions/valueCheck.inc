<?

function checkInput($input = '', $constraint = '') {
	
	if ($constraint == 'email') {
		$regexp = '[a-zA-Z.-_]+@[a-zA-Z]+[a-zA-Z0-9]*.[a-zA-Z][a-zA-Z]+';
	} elseif ($constraint == 'plz') {
		$regexp = '[0-9][0-9][0-9][0-9][0-9]';
	} else {
		$regexp = $constraint;
	}
	
	if (preg_match('/^'.$regexp.'$/', $input)) {
		return $input;
	} else {
		header($base.'?error=');
		exit;
	}
	
	
}

?>