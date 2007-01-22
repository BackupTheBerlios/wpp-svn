<?php

include('../includes/includes.inc');
include('../includes/startApplication.php');

//include('../includes/functions/verifyuser.inc');

$user = restoreUser();
if ($user ==null || !$user->checkPermissions(1)) {
	redirectURI("/viewer/index.php");
}

$LOG = new Log();
$tpl = new TemplateEngine("template/viewProduct.html","template/frame.html",$lang["user_viewProduct"]);

$LOG->write('3', 'user/viewProduct.php');

$pID = $_GET['pID'];
$tpl->assign('ID',$pID);

/*
// In den Warenkorb:
if (isset($_POST['action'])){
	$action = $_POST['action'];
	if($action =="into_basket"){
		$pid = $_POST['pid'];
		$uid = $user->getID();
		$count = $_POST['count'];
		$date = formatDate();

		$res=DB_query("INSERT INTO basket (0,$pid,$uid,$date,$count)");
		if($res){
			echo "Erfolgreich in den Warenkorb eingetragen.";
		}
		else{
			echo "gescheitert!";
		}
	}
}
*/

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