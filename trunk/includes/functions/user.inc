<?php

function editUser() {

	checkInput($_POST['name'],'string');
	checkInput($_POST['lastname'],'string');
	checkInput($_POST['email'],'email');
	checkInput($_POST['role_id'],'int');
	checkInput($_POST['bill_name'],'string');
	checkInput($_POST['bill_street'],'street');
	checkInput($_POST['bill_postcode'],'plz');
	checkInput($_POST['bill_city'],'string');
	checkInput($_POST['bill_state'],'string');
	checkInput($_POST['ship_name'],'string');
	checkInput($_POST['ship_street'],'street');
	checkInput($_POST['ship_postcode'],'plz');
	checkInput($_POST['ship_city'],'string');
	checkInput($_POST['ship_state'],'string');
	checkInput($_POST['bank_name'],'string');
	checkInput($_POST['bank_iban'],'blz');
	checkInput($_POST['bank_number'],'banknum');
	checkInput($_POST['bank_account'],'string');
	
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
			bill_state='".$_POST['bill_state']."',
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

function editSelfUser() {

	checkInput($_POST['lastname'],'string');
	checkInput($_POST['email'],'email');
	// Passwort in aufrufendem Skript gecheckt.
	checkInput($_POST['bill_name'],'string');
	checkInput($_POST['bill_street'],'street');
	checkInput($_POST['bill_postcode'],'plz');
	checkInput($_POST['bill_city'],'string');
	checkInput($_POST['bill_state'],'string');
	checkInput($_POST['ship_name'],'string');
	checkInput($_POST['ship_street'],'street');
	checkInput($_POST['ship_postcode'],'plz');
	checkInput($_POST['ship_city'],'string');
	checkInput($_POST['ship_state'],'string');
	checkInput($_POST['bank_name'],'string');
	checkInput($_POST['bank_iban'],'blz');
	checkInput($_POST['bank_number'],'banknum');
	checkInput($_POST['bank_account'],'string');
	
	DB_query("UPDATE users SET 
			lastname='".$_POST['lastname']."',
			email='".$_POST['email']."',
			password='".$_POST['password']."',
			bill_name='".$_POST['bill_name']."',
			bill_street='".$_POST['bill_street']."',
			bill_postcode='".$_POST['bill_postcode']."',
			bill_city='".$_POST['bill_city']."',
			bill_state='".$_POST['bill_state']."',
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

		if($login){
			//will lieber gleich das ganze User-Objekt zurückgeben
			return $user;
		}
		else{
			return null;
		}
	}

function logoutUser() {
		$user = restoreUser();
		$user->logout();
}

function addUser() {
	
	checkInput($_POST['name'],'string');
	checkInput($_POST['lastname'],'string');
	checkInput($_POST['email'],'email');
	checkInput($_POST['role_id'],'int');
	checkInput($_POST['bill_name'],'string');
	checkInput($_POST['bill_street'],'street');
	checkInput($_POST['bill_postcode'],'plz');
	checkInput($_POST['bill_city'],'string');
	checkInput($_POST['bill_state'],'string');
	checkInput($_POST['ship_name'],'string');
	checkInput($_POST['ship_street'],'street');
	checkInput($_POST['ship_postcode'],'plz');
	checkInput($_POST['ship_city'],'string');
	checkInput($_POST['ship_state'],'string');
	checkInput($_POST['bank_name'],'string');
	checkInput($_POST['bank_iban'],'blz');
	checkInput($_POST['bank_number'],'banknum');
	checkInput($_POST['bank_account'],'string');
	
	if ($_POST['active']=='on') {$active=1;}
	else {$active=0;}
	
	DB_query("INSERT INTO users VALUES (
			0,
			'".$_POST['name']."',
			'".$_POST['lastname']."',
			'".$_POST['password']."',
			'".$_POST['email']."',
			'".$_POST['role_id']."',
			".$active.",
			'".$_POST['bill_name']."',
			'".$_POST['bill_street']."',
			'".$_POST['bill_postcode']."',
			'".$_POST['bill_city']."',
			'".$_POST['bill_state']."',
			'".$_POST['ship_name']."',
			'".$_POST['ship_street']."',
			'".$_POST['ship_postcode']."',
			'".$_POST['ship_city']."',
			'".$_POST['ship_state']."',
			'".$_POST['bank_number']."',
			'".$_POST['bank_iban']."',
			'".$_POST['bank_name']."',
			'".$_POST['bank_account']."')
			");

}

function getUserOrderData($uid) {
	$query = DB_query("select 
				bill_name,
				bill_street,
				bill_postcode,
				bill_city,
				bill_state,
				ship_name,
				ship_street,
				ship_postcode,
				ship_city,
				ship_state,
				bank_name,
				bank_iban,
				bank_number,
				bank_account
				from users where users_id = ".$uid);
	$arr = DB_fetchArray($query);
	return $arr;
	
}

function restoreUserBasket($userid) {
// Warenkorb des Users erstellen				
	//$userid=$_SESSION['user'];													
	$basket_query = DB_query("	
		SELECT
		b.products_id, b.count, p.name, b.basket_id
		FROM basket b, products p
		WHERE b.users_id = $userid
		AND p.products_id = b.products_id
		ORDER BY b.create_time
	");
	$basketBID = array();
	$basketCount = array();
	$basketPID = array();
	$basketProducts = array();
	//$basketBID = array();
	while ($line = DB_fetchArray($basket_query)) {
		$basketBID[] = $line['basket_id'];
		$basketCount[] = $line['count'];
		$basketPID[] = $line['products_id'];
		$basketProducts[] = $line['name'];
	
	}
	
	$ret = array("basket_array_bid" =>$basketBID,
			"basket_array_count"=>$basketCount,
			"basket_array_pid"=>$basketPID,
			"basket_array_product"=>$basketProducts);
			
	return $ret;
}

?>