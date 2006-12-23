<?php

include('../includes/includes.inc');
include('../includes/startApplication.php');

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
		DB_query("INSERT INTO categories VALUES (0,'".$_POST['name']."',".$_POST['catID'].",".$active.",'".$_POST['description']."',".$_POST['sort_order'].")");
		
		$LOG->write('2', 'Kategorie '.mysql_insert_id().' hinzugefügt');
		redirectURI('/admin/categories.php','catID='.$_POST['catID']);
	}
	
	elseif ($_POST['action']=='edit') {
		$LOG->write('3', 'admin/editUser.php: action=edit');
		
		editUser();
		
		$LOG->write('2', 'Kategorie '.$_GET['catID'].' bearbeitet');
		redirectURI('/admin/users.php');
		
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

	$tpl->display();


} elseif ($_GET['action']=='delete') {

	$LOG->write('3', 'admin/editUser.php: get-action=delete');
	
	deleteUser();
	
	$LOG->write('2', 'Nutzer '.$_GET['uID'].' gelöscht');
	
	redirectURI('/admin/users.php');

}

?>
