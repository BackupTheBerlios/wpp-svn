<?php

include('../includes/includes.inc');
include('../includes/startApplication.php');

//include('../includes/functions/verifyviewer.inc');

$user = restoreUser();
if ($user !=null && $user->checkPermissions(1,1)) {	// falls Admin-Rechte
	$isAdmin=1;
}
else{
	$isAdmin=0;
}
if ($user !=null && $user->checkPermissions(0,0,0,1)) {	// falls Orderer-Rechte
	$isOrderer=1;
}
else{
	$isOrderer=0;
}

$LOG = new Log();
$tpl = new TemplateEngine("template/viewProduct.html","template/frame.html",$lang["orderer_viewProduct"]);

$LOG->write('3', 'orderer/viewProduct.php');

$pID = $_GET['pID'];
$tpl->assign('ID',$pID);

//Produktdaten
$product_query = DB_query("SELECT
				*
				FROM products
				WHERE products_id = ".$pID."
				ORDER BY sort_order, name
				");
$product = DB_fetchArray($product_query);

$tpl->assign('name',$product['name']);
$tpl->assign('description',$product['description']);
//$tpl->assign('sort_order',$product['sort_order']);
$tpl->assign('active',$product['active']); // zur Unterscheidung, ob anzeigbar, weiterhin mitliefern
$tpl->assign('deleted',$product['deleted']);
$tpl->assign('image_small',$product['image_small']);
$tpl->assign('image_big',$product['image_big']);
$tpl->assign('stock',$product['stock']);
$tpl->assign('price',$product['price']);

$tpl->assign('user_name',$user->getName());
$tpl->assign('user_lastname',$user->getLastname());
$tpl->assign('is_admin',$isAdmin);
$tpl->assign('is_orderer',$isOrderer);

$tpl->display();



?>