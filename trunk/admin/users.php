<?php

include('../includes/includes.inc');
include('../includes/startApplication.php');

$user = restoreUser();
if ($user ==null || !$user->checkPermissions(1,1)) {
	redirectURI("/admin/login.php","camefrom=users.php");
}

$LOG = new Log();
$tpl = new TemplateEngine("template/users.html","template/frame.html",$lang["admin_users"]);

//Alle Nutzer finden
$users_query = DB_query("SELECT
				u.users_id,
				u.name,
				u.lastname,
				r.name as rolename,
				r.deletable,
				u.active
				FROM users u, roles r
				WHERE u.role_id=r.role_id");
$users_list = array();

while ($users = DB_fetchArray($users_query)) {
	$users_list[] = array(
		"id" => $users['users_id'],
		"name" => $users['name'],
		"lastname" => $users['lastname'],
		"rolename" => $users['rolename'],
		"deletable" => $users['deletable'],
		"active" => $users['active']
	);
}

$tpl->assign('users',$users_list);

$tpl->display();



?>