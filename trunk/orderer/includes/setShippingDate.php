<?php

//include('../../includes/functions/format.inc');
include('../../includes/includes.inc');
include('../../includes/startApplication.php');

$order_id = $_GET['id'];

$shipping_date = actualDate();
DB_query("UPDATE orders SET
			shipping_date = '".$shipping_date."',
			shipped = 1
			WHERE orders_id = ".$order_id);

$date_query = DB_query("SELECT 
			DATE_FORMAT(shipping_date,'%d.%m.%Y, %H:%i:%s Uhr') AS formated_shipping_date
			FROM orders
			WHERE orders_id = ".$order_id);
$date = DB_fetchArray($date_query);

echo $date['formated_shipping_date'];

?>