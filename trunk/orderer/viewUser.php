<?php

include('../includes/includes.inc');
include('../includes/startApplication.php');

//include('../includes/functions/verifyuser.inc');

$user = restoreUser();
if ($user ==null || !$user->checkPermissions(0,0,0,1,1)) {
	redirectURI("/user/login.php","camefrom=index.php");
}

$user = restoreUser();
if ($user !=null && $user->checkPermissions(1,1)) {	// falls Admin-Rechte
	$isAdmin=1;
}
else{
	$isAdmin=0;
}

$LOG = new Log();
$tpl = new TemplateEngine("template/viewUser.html","template/frame.html",$lang["orderer_users"]);

	//	Nutzerdaten einsehen
if (isset($_GET['uID'])) {
	
	$LOG->write('3', 'orderer/viewUser.php');

	$uID = $_GET['uID'];
	$tpl->assign('uID',$uID);
	
	//Alle Daten zum Benutzer
	$users_query = DB_query("SELECT
				name,
				lastname,
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
	$tpl->assign('is_admin',$isAdmin);
	$tpl->display();

}
?>
