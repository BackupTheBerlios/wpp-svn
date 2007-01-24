<?php

include('../includes/includes.inc');
include('../includes/startApplication.php');

include('../includes/functions/verifyadmin.inc');

$user = restoreUser();
if ($user ==null || !$user->checkPermissions(1,1)) {
	redirectURI("/admin/login.php","camefrom=users.php");
}

$LOG = new Log();
$tpl = new TemplateEngine("template/editUser.html","template/frame.html",$lang["admin_users"]);

if (isset($_POST['action'])) {

	$LOG->write('3', 'admin/editUser.php: action set');
	
	if ($_POST['action']=='add') {
		$LOG->write('3', 'admin/editUser.php: action=add');
		
		if ($_POST['active']=='on') {$active=1;}
		else {$active=0;}
		
		addUser();
		
		$LOG->write('2', 'Nutzer '.mysql_insert_id().' hinzugefügt');
		redirectURI('/admin/users.php');
	}
	
	elseif ($_POST['action']=='edit') {
		$LOG->write('3', 'admin/editUser.php: action=edit');
		
		editUser();
		
		$LOG->write('2', 'Nutzer '.$_GET['catID'].' bearbeitet');
		redirectURI('/admin/users.php');
		
	}	// Eigene Einstellungen editieren (mit Passwort)
	elseif ($_POST['action']=='editSelf') {
		$LOG->write('3', 'admin/editUser.php: action=editSelf');
		
		if($_POST['password']==$_POST['repeatPassword']){
			editSelfUser();	
			$LOG->write('2', 'Nutzer '.$_GET['catID'].' bearbeitet');
			redirectURI('/admin/index.php');	
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

} elseif ($_GET['action']=='edit') {
	
	$LOG->write('3', 'admin/editUser.php: get-action=edit');

	$uID = $_GET['uID'];
	$tpl->assign('uID',$uID);
	$tpl->assign('action','edit');
	
	
	//Alle Daten zum Benutzer
	$users_query = DB_query("SELECT
				u.name,
				u.lastname,
				u.email,
				r.name as rolename,
				u.role_id,
				u.active,
				u.bill_name,
				u.bill_street,
				u.bill_postcode,
				u.bill_city,
				u.bill_state,
				u.ship_name,
				u.ship_street,
				u.ship_postcode,
				u.ship_city,
				u.ship_state,
				u.bank_name,
				u.bank_iban,
				u.bank_number,
				u.bank_account
				FROM users u, roles r
				WHERE u.role_id=r.role_id
				AND u.users_id = ".$uID);
	$userdata = DB_fetchArray($users_query);
	
	$tpl->assign('name',$userdata['name']);
	$tpl->assign('lastname',$userdata['lastname']);
	$tpl->assign('email',$userdata['email']);
	$tpl->assign('rolename',$userdata['rolename']);
	$tpl->assign('role_id',$userdata['role_id']);
	$tpl->assign('active',$userdata['active']);
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

	//Alle Rollen
	$roles_query = DB_query("SELECT
					role_id,
					name
					FROM roles");
	$roles = array();
	while ($role = DB_fetchArray($roles_query)) {
		$roles[] = array(
			"id" => $role['role_id'],
			"name" => $role['name']);
	}
	$tpl->assign('roleslist',$roles);
	$tpl->assign('user_name',$user->getName());
	$tpl->assign('user_lastname',$user->getLastname());

	$tpl->display();

	//	Eigene Einstellung (inkl. Passwort) ändern
} elseif ($_GET['action']=='editSelf') {
	
	$LOG->write('3', 'admin/editUser.php: get-action=editSelf');

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

	// Löschvorgang:
} elseif ($_GET['action']=='delete') {

	$LOG->write('3', 'admin/editUser.php: get-action=delete');
	
	deleteUser();
	
	$LOG->write('2', 'Nutzer '.$_GET['uID'].' gelöscht');
	
	redirectURI('/admin/users.php');
	
} elseif ($_GET['action']=='add') {

	//Alle Rollen
	$roles_query = DB_query("SELECT
					role_id,
					name
					FROM roles");
	$roles = array();
	while ($role = DB_fetchArray($roles_query)) {
		$roles[] = array(
			"id" => $role['role_id'],
			"name" => $role['name']);
	}
	$tpl->assign('roleslist',$roles);
	
	$tpl->assign('action','add');
	$tpl->assign('user_name',$user->getName());
	$tpl->assign('user_lastname',$user->getLastname());


	$tpl->display();
	
}


?>
