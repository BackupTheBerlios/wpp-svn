<?php

//include('../../includes/functions/format.inc');
include('../../includes/includes.inc');
include('../../includes/startApplication.php');

$order_id = $_GET['id'];

$shipping_date = formatDate();
DB_query("UPDATE orders SET
			shipping_date = '".$shipping_date."'
			WHERE orders_id = ".$order_id);

$date_query = DB_query("SELECT shipping_date
			FROM orders
			WHERE orders_id = ".$order_id);
$date = DB_fetchArray($date_query);

echo $date['shipping_date'];

?>