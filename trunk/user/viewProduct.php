<?php

include('../includes/includes.inc');
include('../includes/startApplication.php');

//include('../includes/functions/verifyuser.inc');

$user = restoreUser();
if ($user !=null && $user->checkPermissions(1,1)) {	// falls Admin-Rechte
	$isAdmin=1;
}
else{
	$isAdmin=0;
	if ($user !=null && $user->checkPermissions(0,0,0,1,1)) {	// wenn ORDERER
		redirectURI("/orderer/index.php");
	}
	if ($user ==null || !$user->checkPermissions(1)) {
		redirectURI("/viewer/index.php");
	}
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
		$date = formatDate();

		// Product.stock zu der PID der aktuellen Aktion checken 
		$fehlerArray = array();	// für Fehlermeldung, wenn Produktkapazität überschritten															

		$countTry = $_POST['count'];	// angeforderte Menge, die in den Warenkorb hinzugefügt werden soll
		$count = 0;
		$stock = 0;
		$name = null;
		// verfügbare Anzahl für dieses Produkt:
		$productStock_query = DB_query("	
			SELECT
			stock, name
			FROM products
			WHERE products_id = $pid 
		");
		$zeile = DB_fetchArray($productStock_query);
		$stock=$zeile['stock'];
		$name=$zeile['name'];

		// Anzahl aller Produkte im Warenkorb mit dieser PID ermitteln und aufsummieren 
		$productCount_query = DB_query("	
			SELECT
			count
			FROM basket
			WHERE products_id = $pid
		");
		while($zeile = DB_fetchArray($productCount_query)){
			$count+=$zeile['count'];
		}

		if($count+$countTry>$stock){	// Wenn bisheriger Warenkorb-Inhalt mit dieser PID plus angeforderte Menge größer als verfügbare Kapazität ist:
			$fehlerArray['name']=$name;
			$fehlerArray['count']=$count;
			$fehlerArray['countTry']=$countTry;
			$fehlerArray['stock']=$stock;	
		}
		$tpl->assign('error',$fehlerArray);

		if($fehlerArray == null){
			// in den Warenkorb eintragen
//			for ($i=0;$i<$countTry;$i++) {
				$res=DB_query("INSERT INTO basket VALUES (0,$pid,$uid,$date,$countTry)");
				if($res){
					$LOG->write('3', 'In den Warenkorb eingetragen.');
				}
//			}
		}	
	}
}

//Produktdaten
$product_query = DB_query("SELECT
				*
				FROM products
				WHERE products_id = ".$pID."
				AND deleted = 0
				ORDER BY sort_order, name
				");
$product = DB_fetchArray($product_query);

$tpl->assign('name',$product['name']);
$tpl->assign('description',$product['description']);
$tpl->assign('active',$product['active']); // zur Unterscheidung, ob anzeigbar, weiterhin mitliefern
$tpl->assign('image_small',$product['image_small']);
$tpl->assign('image_big',$product['image_big']);
$tpl->assign('stock',$product['stock']);
$tpl->assign('price',$product['price']);

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