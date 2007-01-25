<?php

include('../includes/includes.inc');
include('../includes/startApplication.php');

//include('../includes/functions/verifyuser.inc');

$user = restoreUser();
if ($user ==null || !$user->checkPermissions(1)) {
	redirectURI("/user/login.php","camefrom=index.php");
}

$LOG = new Log();
$tpl = new TemplateEngine("template/editUser.html","template/frame.html",$lang["user_users"]);


// Warenkorb des Users erstellen				
$userid=$_SESSION['user'];													
$basket=restoreUserBasket($userid);

$tpl->assign('basket_array_bid',$basket["basket_array_bid"]);
$tpl->assign('basket_array_count',$basket["basket_array_count"]);
$tpl->assign('basket_array_pid',$basket["basket_array_pid"]);
$tpl->assign('basket_array_product',$basket["basket_array_product"]);

if (isset($_POST['action'])) {

	$LOG->write('3', 'user/editUser.php: action set');
	
	// Eigene Einstellungen editieren (mit Passwort)
	if ($_POST['action']=='editSelf') {
		$LOG->write('3', 'user/editUser.php: action=editSelf');
		
		if($_POST['password']==$_POST['repeatPassword']){
			editSelfUser();	
			$LOG->write('2', 'Nutzer '.$_GET['catID'].' bearbeitet');
			redirectURI('/user/categories.php');	
		}
		else{	// falsche Passwortwiederholung
			$passwordError="1";
			$tpl->assign('action','editSelf');
			$tpl->assign('uID',$user->getID());
			$tpl->assign('user_name',$user->getName());
			$tpl->assign('user_lastname',$user->getLastname());
			$tpl->assign('password_error',$passwordError);
			$tpl->assign('name',$_POST['name']);
			$tpl->assign('lastname',$_POST['lastname']);
			$tpl->assign('email',$_POST['email']);
			$tpl->assign('bill_name',$_POST['bill_name']);
			$tpl->assign('bill_street',$_POST['bill_street']);
			$tpl->assign('bill_postcode',$_POST['bill_postcode']);
			$tpl->assign('bill_city',$_POST['bill_city']);
			$tpl->assign('bill_state',$_POST['bill_state']);
			$tpl->assign('ship_name',$_POST['ship_name']);
			$tpl->assign('ship_street',$_POST['ship_street']);
			$tpl->assign('ship_postcode',$_POST['ship_postcode']);
			$tpl->assign('ship_city',$_POST['ship_city']);
			$tpl->assign('ship_state',$_POST['ship_state']);
			$tpl->assign('bank_number',$_POST['bank_number']);
			$tpl->assign('bank_iban',$_POST['bank_iban']);
			$tpl->assign('bank_name',$_POST['bank_name']);
			$tpl->assign('bank_account',$_POST['bank_account']);

			$tpl->display();
		}
	}
}	//	Eigene Einstellung (inkl. Passwort) ändern
elseif ($_GET['action']=='editSelf') {
	
	$LOG->write('3', 'user/editUser.php: get-action=editSelf');

	$uID = $user->getID();
	$tpl->assign('uID',$uID);
	$tpl->assign('action','editSelf');
	
	//Alle Daten zum Benutzer
	$users_query = DB_query("SELECT
				name,
				lastname,
				password,
				email,
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
				FROM users
				WHERE users_id = ".$uID);
	$userdata = DB_fetchArray($users_query);
	
	$tpl->assign('name',$userdata['name']);
	$tpl->assign('lastname',$userdata['lastname']);
	$tpl->assign('password',$userdata['password']);
	$tpl->assign('email',$userdata['email']);
	$tpl->assign('bill_name',$userdata['bill_name']);
	$tpl->assign('bill_street',$userdata['bill_street']);
	$tpl->assign('bill_postcode',$userdata['bill_postcode']);
	$tpl->assign('bill_city',$userdata['bill_city']);
	$tpl->assign('bill_state',$userdata['bill_state']);
	$tpl->assign('ship_name',$userdata['ship_name']);
	$tpl->assign('ship_street',$userdata['ship_street']);
	$tpl->assign('ship_postcode',$userdata['ship_postcode']);
	$tpl->assign('ship_city',$userdata['ship_city']);
	$tpl->assign('ship_state',$userdata['ship_state']);
	$tpl->assign('bank_number',$userdata['bank_number']);
	$tpl->assign('bank_iban',$userdata['bank_iban']);
	$tpl->assign('bank_name',$userdata['bank_name']);
	$tpl->assign('bank_account',$userdata['bank_account']);

	$tpl->assign('user_name',$user->getName());
	$tpl->assign('user_lastname',$user->getLastname());
	$tpl->display();

}
?>
