<?php

include('../includes/includes.inc');
include('../includes/startApplication.php');

//include('../includes/functions/verifyadmin.inc');


$user = restoreUser();
if ($user == null || !$user->checkPermissions(0,0,0,1,0)) {
	redirectURI("/admin/login.php","camefrom=orders.php");
}

$LOG = new Log();
$tpl = new TemplateEngine("template/orders.html","template/frame.html",$lang["admin_orders"]);

//Alle Bestellungen finden
$orders_query = DB_query("SELECT
				*
				FROM orders");
$orders_list = array();

while ($orders = DB_fetchArray($orders_query)) {
	$user_query = DB_query("SELECT 
					name,
					lastname
					FROM users
					WHERE users_id = ".$orders['users_id']);
	$user = DB_fetchArray($user_query);
	$orders_list[] = array(
		"id" => $orders['orders_id'],
		"date" => $orders['date'],
		"items_id" => $orders['order_items_id'],
		"users_id" => $orders['users_id'],
		"username" => $user['name']." ".$user['lastname'],
		"shipping_date" => $orders['shipping_date'],
		"shipped" => $orders['shipped']
	);
	
}

$tpl->assign('orders',$orders_list);

$tpl->display();



?>