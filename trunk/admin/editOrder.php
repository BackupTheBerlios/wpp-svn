<?php

include('../includes/includes.inc');
include('../includes/startApplication.php');

//include('../includes/functions/verifyadmin.inc');

$user = restoreUser();
if ($user ==null || !$user->checkPermissions(0,0,0,1,0)) {
	redirectURI("/admin/login.php","camefrom=editOrder.php");
}

$LOG = new Log();
$tpl = new TemplateEngine("template/editOrder.html","template/frame.html",$lang["admin_orders"]);


$order_id = $_GET['id'];

if (isset($_POST['ordershipped'])) {
	
	$shipping_date = formatDate();
	DB_query("UPDATE orders SET
			shipping_date = '".$shipping_date."'
			WHERE orders_id = ".$order_id);

}


//Alle Details zu der Bestellung finden
$order_query = DB_query("SELECT
				*
				FROM orders
				WHERE orders_id = ".$order_id);
$order = DB_fetchArray($order_query);
$tpl->assign('orderDate',$order['date']);
$tpl->assign('shippingDate', $order['shipping_date']);
$tpl->assign('orderid', $order_id);

//Alle bestellten Artikel finden
$items_query = DB_query("SELECT
				*
				FROM order_items
				WHERE orders_id = ".$order_id);
$items = array();
$price_all = 0;
while ($item = DB_fetchArray($items_query)) {
	$product_query = DB_query("SELECT
					*
					FROM products
					WHERE products_id = ".$item['products_id']);
	$product = DB_fetchArray($product_query);
	$items[] = array(
			"id" => $product['products_id'],
			"name" => $product['name'],
			"price" => formatPrice($product['price']),
			"count" => $item['count'],
			"price_total" => formatPrice($product['price']*$item['count'])
			);
	$price_all += $product['price']*$item['count'];
}
$tpl->assign('items',$items);
$tpl->assign('price_all', formatPrice($price_all));

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
