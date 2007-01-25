<?php

include('../includes/includes.inc');
include('../includes/startApplication.php');

//include('../includes/functions/verifyuser.inc');

$user = restoreUser();
if ($user ==null || !$user->checkPermissions(1)) {
	redirectURI("/viewer/index.php");
}
if ($user !=null && $user->checkPermissions(1,1)) {	// falls Admin-Rechte
	$isAdmin=1;
}
else{
	$isAdmin=0;
}

$LOG = new Log();
$tpl = new TemplateEngine("template/viewProduct.html","template/frame.html",$lang["user_viewProduct"]);

$LOG->write('3', 'user/viewProduct.php');

$pID = $_GET['pID'];
$tpl->assign('ID',$pID);

// In den Warenkorb:
if (isset($_POST['action'])){
	$action = $_POST['action'];
	if($action =="into_basket"){
		$pid = $_POST['pid'];
		$uid = $user->getID();
		$count = $_POST['count'];
		$date = formatDate();
					
		// aktuellen Stock mit gewünschter Anzahl vergleichen												
		$count_query = DB_query("	
			SELECT
			stock
			FROM products
			WHERE products_id = $pid
			AND stock>=$count		
		");
		if(mysql_num_rows($count_query)==1){
			$res=DB_query("INSERT INTO basket VALUES (0,$pid,$uid,$date,$count)");
			if($res){
				$LOG->write('3', 'In den Warenkorb eingetragen.');
			}
			else{
				$LOG->write('3', 'user/viewProduct.php');
			}
		}
		else{
			$tpl->assign('error','stock<count');
		}
	}
}

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
$tpl->assign('image_small',$product['image_small']);
$tpl->assign('image_big',$product['image_big']);
$tpl->assign('stock',$product['stock']);
$tpl->assign('price',$product['price']);
//$tpl->assign('deleted',$product['deleted']);
//$tpl->assign('error',$_GET['error']);

// Warenkorb des Users erstellen				
$userid=$_SESSION['user'];													
$basket=restoreUserBasket($userid);

$tpl->assign('basket_array_bid',$basket["basket_array_bid"]);
$tpl->assign('basket_array_count',$basket["basket_array_count"]);
$tpl->assign('basket_array_pid',$basket["basket_array_pid"]);
$tpl->assign('basket_array_product',$basket["basket_array_product"]);


$tpl->assign('menu',$menu);
$tpl->assign('user_name',$user->getName());
$tpl->assign('user_lastname',$user->getLastname());
$tpl->assign('is_admin',$isAdmin);

$tpl->display();



?>