<?php
// KASSE

include('../includes/includes.inc');
include('../includes/startApplication.php');

//include('../includes/functions/verifyuser.inc');

$user = restoreUser();
if ($user ==null || !$user->checkPermissions(1)) {
	redirectURI("/viewer/index.php");
}

$LOG = new Log();
$tpl = new TemplateEngine("template/till.html","template/frame.html",$lang["user_till"]);

$LOG->write('3', 'user/till.php');

//$tpl->assign('ID',$pID);


// TODO: prod.stock checken, und auch runtersetzen.


// Aus Warenkorb löschen:
if (isset($_GET['action'])){
	if($_GET['action'] == "removeFromBasket"){
		$bid=$_GET['bID'];	// BasketID
		$remove_query = DB_query("	
			DELETE
			FROM basket
    	WHERE basket_id=$bid
		");
	}
}

// Warenkorb des Users erstellen				
$userid=$user->getID();													
$basket_query = DB_query("	
	SELECT
	b.products_id, b.count, p.name, b.basket_id, p.price
	FROM basket b, products p
	WHERE b.users_id = $userid
	AND p.products_id = b.products_id
	ORDER BY b.create_time
");
$basketBID = array();
$basketCount = array();
$basketPID = array();
$basketProducts = array();
$basketSinglePrices = array();
$basketSumPrices = array();
$basketSumAll=0;
while ($line = DB_fetchArray($basket_query)) {
	$basketBID[] = $line['basket_id'];
	$basketCount[] = $line['count'];
	$basketPID[] = $line['products_id'];
	$basketProducts[] = $line['name'];
	$basketSinglePrices[]= $line['price'];
	$basketSumPrices[] = $line['price'] * $line['count'];
	$basketSumAll+=$line['price'] * $line['count'];
}
$tpl->assign('basket_array_bid',$basketBID);
$tpl->assign('basket_array_count',$basketCount);
$tpl->assign('basket_array_pid',$basketPID);
$tpl->assign('basket_array_product',$basketProducts);
$tpl->assign('basket_array_single_prices',$basketSinglePrices);
$tpl->assign('basket_array_sum_prices',$basketSumPrices);
$tpl->assign('basket_array_sum_all',$basketSumAll);

$tpl->assign('user_name',$user->getName());
$tpl->assign('user_lastname',$user->getLastname());
$tpl->assign('user_id',$user->getID());

$tpl->display();
?>