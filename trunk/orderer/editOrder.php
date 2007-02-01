<?php

include('../includes/includes.inc');
include('../includes/startApplication.php');

//include('../includes/functions/verifyadmin.inc');

$user = restoreUser();
if ($user ==null || !$user->checkPermissions(0,0,0,1,1)) {
	redirectURI("/admin/login.php","camefrom=editOrder.php");
}

$LOG = new Log();
$tpl = new TemplateEngine("template/editOrder.html","template/frame.html",$lang["orderer_orders"]);


$order_id = $_GET['id'];

if (isset($_POST['ordershipped'])) {
	
	$shipping_date = actualDate();
	DB_query("UPDATE orders SET
			shipping_date = '".$shipping_date."'
			WHERE orders_id = ".$order_id);

}


//Alle Details zu der Bestellung finden
$order_query = DB_query("SELECT
				*, UNIX_TIMESTAMP(date) AS formated_date,
				UNIX_TIMESTAMP(shipping_date) AS formated_shipping_date
				FROM orders
				WHERE orders_id = ".$order_id);
$order = DB_fetchArray($order_query);
$tpl->assign('orderDate',$order['formated_date']);
$tpl->assign('shippingDate', $order['formated_shipping_date']);
$tpl->assign('orderid', $order_id);
$tpl->assign('bill_name',$order['bill_name']);
$tpl->assign('bill_street',$order['bill_street']);
$tpl->assign('bill_postcode',$order['bill_postcode']);
$tpl->assign('bill_city',$order['bill_city']);
$tpl->assign('bill_state',$order['bill_state']);
$tpl->assign('ship_name',$order['ship_name']);
$tpl->assign('ship_street',$order['ship_street']);
$tpl->assign('ship_postcode',$order['ship_postcode']);
$tpl->assign('ship_city',$order['ship_city']);
$tpl->assign('ship_state',$order['ship_state']);
$tpl->assign('bank_name',$order['bank_name']);
$tpl->assign('bank_iban',$order['bank_iban']);
$tpl->assign('bank_number',$order['bank_number']);
$tpl->assign('bank_account',$order['bank_account']);

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
			"price" => $product['price'],
			"count" => $item['count'],
			"price_total" => $product['price']*$item['count']
			);
	$price_all += $product['price']*$item['count'];
}
$tpl->assign('items',$items);
$tpl->assign('price_all', $price_all);

//Daten zum Nutzer
$user_query = DB_query("SELECT
				name, lastname
				FROM users
				WHERE users_id = ".$order['users_id']);
$user_data = DB_fetchArray($user_query);
$tpl->assign('user',$user_data);


if ($user->checkPermissions(0,0,0,1,1)) {
	$tpl->assign('can_change',1);
}
$tpl->assign('user_id',$user->getID());
$tpl->assign('user_name',$user->getName());
$tpl->assign('user_lastname',$user->getLastname());

$tpl->display();

?>
