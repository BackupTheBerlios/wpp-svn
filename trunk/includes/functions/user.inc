<?php

function editUser() {

	if ($_POST['active']=='on') {$active=1;}
	else {$active=0;}
	DB_query("UPDATE users SET 
			name='".$_POST['name']."',
			lastname='".$_POST['lastname']."',
			email='".$_POST['email']."',
			role_id='".$_POST['role_id']."',
			active=".$active.",
			bill_name='".$_POST['bill_name']."',
			bill_street='".$_POST['bill_street']."',
			bill_postcode='".$_POST['bill_postcode']."',
			bill_city='".$_POST['bill_city']."',
			bill_state='".$_POST['bil_state']."',
			ship_name='".$_POST['ship_name']."',
			ship_street='".$_POST['ship_street']."',
			ship_postcode='".$_POST['ship_postcode']."',
			ship_city='".$_POST['ship_city']."',
			ship_state='".$_POST['ship_state']."',
			bank_name='".$_POST['bank_name']."',
			bank_iban='".$_POST['bank_iban']."',
			bank_number='".$_POST['bank_number']."',
			bank_account='".$_POST['bank_account']."'
			WHERE users_id=".$_POST['uID']);
}

function deleteUser() {
	

	DB_query("DELETE FROM users WHERE users_id=".$_GET['uID']);
		
}

function loginUser($sign, $password) {
		
		$user = new User();
		$login = $user->login($sign, $password);
		
		return $login;
	}

function logoutUser() {
		$user = restoreUser();
		$user->logout();
}

?>