<?php

include('../includes/includes.inc');
include('../includes/startApplication.php');

$user = restoreUser();
if ($user ==null || !$user->checkPermissions(0,0,0,1,0)) {
	redirectURI("/admin/login.php","camefrom=users.php");
}

$LOG = new Log();
$tpl = new TemplateEngine("templates/editOrder.html","templates/frame.html",$lang["admin_orders"]);


$order_id = $_GET['id'];

//Alle Details zu der Bestellung finden
$order_query = DB_query("SELECT
				*
				FROM orders
				WHERE orders_id = ".$order_id);
$order = DB_fetchArray($order_query);
$tpl->assign('order',$order);

//Alle bestellten Artikel finden
$items_query = DB_query("SELECT
				*
				FROM order_items
				WHERE orders_id = ".$order_id);
$items = array();
while ($item = DB_fetchArray($items_query)) {
	$product_query = DB_query("SELECT
					*
					FROM products
					WHERE products_id = ".$item['products_id']);
	$product = DB_fetchArray($product_query);
	$items[] = array(
			"name" => $product['name'],
			"price" => $product['price'],
			"count" => $item['count']
			);
}
$tpl->assign('items',$items);

//Daten zum Nuter
$user_query = DB_query("SELECT
				*
				FROM users
				WHERE users_id = ".$order['users_id']);
$user_data = DB_fetchArray($user_query);
$tpl->assign('user',$user_data);


if ($user->checkPermissions(0,0,0,1,1)) {
	$tpl->assign('can_change',1);
}

$tpl->display();

?>
