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

if (isset($_GET['action'])){
	// aus Warenkorb löschen:
	if($_GET['action'] == "removeFromBasket"){
		$bid=$_GET['bID'];	// BasketID
		$remove_query = DB_query("	
			DELETE
			FROM basket
    	WHERE basket_id=$bid
		");
	}
}
if (isset($_POST['action'])){
	// in Kasse bestätigt. -> Bestellung 
	if($_POST['action'] == "agreeOrder"){
		$bids=$_POST['bids'];
		$ablageArray=explode(";",$bids);	// wieder Array aus Liste erzeugen.
		$bidsArray=array_slice($ablageArray,0,count($ablageArray)-1);

		$pids=$_POST['pids'];
		$ablageArray=explode(";",$pids);	// wieder Array aus Liste erzeugen.
		$pidsArray=array_slice($ablageArray,0,count($ablageArray)-1);

		$counts=$_POST['counts'];
		$ablageArray=explode(";",$counts);	// wieder Array aus Liste erzeugen.
		$countsArray=array_slice($ablageArray,0,count($ablageArray)-1);

		$date = formatDate();
		$userid=$_POST['userid'];

		if(count($bidsArray)>0){	// wenn Warenkorb nicht leer war
			$order_query = DB_query("	
				INSERT
				INTO orders
				VALUES
				(0, $date, $userid, null, 0)
			");
			$orderid=mysql_insert_id();	// letzte ID des AutoIncrement holen
			$i=-1;
			foreach ($pidsArray as $pid){
				$i++;
				$order_items_query = DB_query("	
					INSERT
					INTO order_items
					VALUES
					($orderid, $pid, ".$countsArray[$i].")
				");
				// bestellte Waren aus Warenkorb löschen
				$basket_query = DB_query("	
					DELETE
					FROM basket
					WHERE basket_id=".$bidsArray[$i]."
				");
			}
		}
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
$BIDlist="";
while ($line = DB_fetchArray($basket_query)) {
	$basketBID[] = $line['basket_id'];
	$basketCount[] = $line['count'];
	$basketPID[] = $line['products_id'];
	$basketProducts[] = $line['name'];
	$basketSinglePrices[]= $line['price'];
	$basketSumPrices[] = $line['price'] * $line['count'];
	$basketSumAll+=$line['price'] * $line['count'];
}
for($i=0;$i<count($basketBID);$i++){	// Um zu gewährleisten, dass nur das in der Kasse Angezeigte bestellt wird, und keine Änderungen während des Ansehens in der Kasse berücksichtigt werden
	$PIDlist.=$basketPID[$i].";";	// Liste der ProduktIDs zusammen stellen, die beim Bestellen übergeben wird.
	$countList.=$basketCount[$i].";";	// Liste der Produkt-Anzahlen zusammen stellen, die beim Bestellen übergeben wird.
	$BIDlist.=$basketBID[$i].";";	// Liste der BasketIDs zusammen stellen, die beim Bestellen übergeben wird.
}	
$tpl->assign('basket_array_bid',$basketBID);
$tpl->assign('basket_array_count',$basketCount);
$tpl->assign('basket_array_pid',$basketPID);
$tpl->assign('basket_array_product',$basketProducts);
$tpl->assign('basket_array_single_prices',$basketSinglePrices);
$tpl->assign('basket_array_sum_prices',$basketSumPrices);
$tpl->assign('basket_array_sum_all',$basketSumAll);
$tpl->assign('bids',$BIDlist);		//CSV
$tpl->assign('pids',$PIDlist);		//CSV
$tpl->assign('counts',$countList);	//CSV

$tpl->assign('user_name',$user->getName());
$tpl->assign('user_lastname',$user->getLastname());
$tpl->assign('user_id',$user->getID());

$tpl->display();
?>