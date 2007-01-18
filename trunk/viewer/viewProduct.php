<?php

include('../includes/includes.inc');
include('../includes/startApplication.php');

$LOG = new Log();
$tpl = new TemplateEngine("template/viewProduct.html","template/frame.html",$lang["viewer_viewProduct"]);

$LOG->write('3', 'viewer/viewProduct.php');

$pID = $_GET['pID'];
$tpl->assign('ID',$pID);

//Produktdaten
$product_query = DB_query("SELECT
				*
				FROM products
				WHERE products_id = ".$pID);
$product = DB_fetchArray($product_query);

$tpl->assign('name',$product['name']);
$tpl->assign('description',$product['description']);
//$tpl->assign('sort_order',$product['sort_order']);
$tpl->assign('active',$product['active']); // zur Unterscheidung, ob anzeigbar, weiterhin mitliefern
$tpl->assign('image_small',$product['image_small']);
$tpl->assign('image_big',$product['image_big']);
$tpl->assign('stock',$product['stock']);
$tpl->assign('price',$product['price']);
//$tpl->assign('deleted',$product['deleted']);
//$tpl->assign('error',$_GET['error']);

$tpl->display();



?>