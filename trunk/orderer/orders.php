<?php

include('../includes/includes.inc');
include('../includes/startApplication.php');

//include('../includes/functions/verifyadmin.inc');


$user = restoreUser();
if ($user == null || !$user->checkPermissions(0,0,0,1,1)) {
	redirectURI("/user/login.php","camefrom=orders.php");
}

if ($user !=null && $user->checkPermissions(1,1)) {	// falls Admin-Rechte
	$isAdmin=1;
}
else{
	$isAdmin=0;
}

$LOG = new Log();
$tpl = new TemplateEngine("template/orders.html","template/frame.html",$lang["orderer_orders"]);

//Alle Bestellungen finden
$orders_query = DB_query("SELECT
				*
				FROM orders
				ORDER BY date
			");
$orders_list = array();

while ($orders = DB_fetchArray($orders_query)) {
	$user_query = DB_query("SELECT 
					name,
					lastname
					FROM users
					WHERE users_id = ".$orders['users_id']);
	$users = DB_fetchArray($user_query);
	$orders_list[] = array(
		"id" => $orders['orders_id'],
		"date" => $orders['date'],
		"items_id" => $orders['order_items_id'],
		"users_id" => $orders['users_id'],
		"username" => $users['name']." ".$users['lastname'],
		"shipping_date" => $orders['shipping_date'],
		"shipped" => $orders['shipped']
	);
	
}

$tpl->assign('orders',$orders_list);
$tpl->assign('user_name',$user->getName());
$tpl->assign('user_lastname',$user->getLastname());
$tpl->assign('is_admin',$isAdmin);

$tpl->display();



?>