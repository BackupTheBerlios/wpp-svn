<?

function checkInput($input = '', $constraint = '') {
	
	if ($constraint == 'email') { //letter@domain.com
		$regexp = '[a-zA-Z0-9]+[[\.|\-|\_]{0,1}[\w]+]*\@[a-zA-Z0-9]+[[\.|\-|\_]{0,1}[a-zA-Z0-9]+\.[a-zA-Z]{2,4}';
	} elseif ($constraint == 'plz') { //12345
		$regexp = '[\d]{5}';
	} elseif ($constraint == 'string') { //any text
		$regexp = '[\w\W]+';
	} elseif ($constraint == 'int') { //any number
		$regexp = '[\d]+';
	} elseif ($constraint == 'price') { //12.3456
		$regexp = '[\d]+\.[\d]{2,4}';
	} elseif ($constraint == 'street') { //address 12
		$regexp = '[\w]*\s[\d]+';
	} elseif ($constraint == 'blz') { //12345678
		$regexp = '[\d]{8}';
	} elseif ($constraint == 'banknum') {
		$constraint = '[\d]{3,10}]';
	} else {
		$regexp = $constraint;
	}
			
			
			
	if (preg_match('/^'.$regexp.'$/', $input)) {
		return true;
	} else {
		return false;
	}
	
	
}

?>