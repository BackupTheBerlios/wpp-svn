<?php
// KASSE

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
$tpl = new TemplateEngine("template/till.html","template/frame.html",$lang["user_till"]);

$LOG->write('3', 'user/till.php');

$fehler_kapazitaetKurzVorSpeicherung = 0; // Flag, dass nach Betätigung des Absende-Buttons schon wieder Kapazität überschritten wird (zwischenzeitliche Änderung)


/*___________________________________________________
	
				>>>>>>			und auch runtersetzen: UNTEN    <<<<<<<<
	___________________________________________________
*/


// Basket-, ProduktIDs und Anzahlen aus $_POST als Arrays darstellen
if(isset($_POST['bids']) && isset($_POST['pids']) && isset($_POST['counts'])){
	$bids=$_POST['bids'];
	$ablageArray=explode(";",$bids);	// wieder Array aus Liste erzeugen.
	$bidsArray=array_slice($ablageArray,0,count($ablageArray)-1);

	$pids=$_POST['pids'];
	$ablageArray=explode(";",$pids);	// wieder Array aus Liste erzeugen.
	$pidsArray=array_slice($ablageArray,0,count($ablageArray)-1);

	$counts=$_POST['counts'];
	$ablageArray=explode(";",$counts);	// wieder Array aus Liste erzeugen.
	$countsArray=array_slice($ablageArray,0,count($ablageArray)-1);
}

// aus Warenkorb löschen:
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

// in Kasse bestätigt. -> Bestellung 
if (isset($_POST['action']) && $_POST['action'] == "agreeOrder"){
	$date = formatDate();
	$userid=$_POST['userid'];
	$userdata=getUserOrderData($userid);

	// nochmals checken, ob Produktanzahl noch verfügbar ist
	$fehlerArray = array();	// für Fehlermeldung, wenn Produktkapazität überschritten															
	$countsPerProductArray = array();			
	$productStock = array();
	$productName = array();
	for ($i = 0; $i<count($pidsArray);$i++){					
		// Anzahl pro Produkt aufaddieren:													
		$countsPerProductArray[$i] += $countsArray[$i];
		// Product.stock ermitteln:
		$productCount_query = DB_query("	
			SELECT
			stock, name
			FROM products
			WHERE products_id = ".$pidsArray[$i]."
		");
		$zeile = DB_fetchArray($productCount_query);
		$productStock[$i]=$zeile['stock'];	
		$productName[$i]=$zeile['name'];	
	
		// Kapazität mit Warenkorb-Inhalt vergleichen und Infos für Fehlermeldungen bereitstellen:
		if($countsPerProductArray[$i] > $productStock[$i]){
			$fehlerArray[$i]['product_name'] = $productName[$i];
			$fehlerArray[$i]['product_count'] = $countsPerProductArray[$i];
			$fehlerArray[$i]['product_stock'] = $productStock[$i];
			$fehler_kapazitaetKurzVorSpeicherung = 1;
		}
	}
	// fertig gecheckt.

	if($fehler_kapazitaetKurzVorSpeicherung==0){
		if(count($bidsArray)>0){	// wenn Warenkorb nicht leer war
			$order_query = DB_query("	
				INSERT
				INTO orders
				VALUES
				(0, $date, $userid, null, 0,
				'".$userdata['bill_name']."',
				'".$userdata['bill_street']."',
				'".$userdata['bill_postcode']."',
				'".$userdata['bill_city']."',
				'".$userdata['bill_state']."',
				'".$userdata['ship_name']."',
				'".$userdata['ship_street']."',
				'".$userdata['ship_postcode']."',
				'".$userdata['ship_city']."',
				'".$userdata['ship_state']."',
				'".$userdata['bank_number']."',
				'".$userdata['bank_iban']."',
				'".$userdata['bank_name']."',
				'".$userdata['bank_account']."'
				)
			");
			$orderid=mysql_insert_id();	// letzte ID des AutoIncrement holen
			$i=-1;
			foreach ($pidsArray as $pid){
				$i++;
				// Versuch, Eintrag neu zu schreiben...
				$order_items_query = DB_query("	
					INSERT
					INTO order_items
					VALUES
					($orderid, $pid, ".$countsArray[$i].")
				");
				// Wenn erfolglos: mehrere gleiche Produkte in der Liste -> Count Aufaddieren 
				if($order_items_query!=1){
					$order_items_query_2 = DB_query("	
						SELECT count
						FROM order_items
						WHERE orders_id=$orderid 
						AND products_id=$pid
					");
					$zeile=DB_fetchArray($order_items_query_2);
					$summe=$zeile['count']+$countsArray[$i];
					$order_items_query_3 = DB_query("	
						UPDATE order_items
						SET count = $summe					
						WHERE orders_id=$orderid 
						AND products_id=$pid
					");
				}
				// bestellte Waren aus Warenkorb löschen
				$basket_query = DB_query("	
					DELETE
					FROM basket
					WHERE basket_id=".$bidsArray[$i]."
				");
			}

			// Product.stock runtersetzen:
			// 		zunächst festgeschriebene neue Orders auslesen: 
			$order_query = DB_query("		
				SELECT products_id, count
				FROM order_items
				WHERE orders_id=$orderid
			");
			while ($zeile=DB_fetchArray($order_query)){
				$pid=$zeile['products_id'];
				$count=$zeile['count'];
				// stock auslesen
				$order_query_2 = DB_query("			
					SELECT stock
					FROM products
					WHERE products_id=$pid
				");
				$zeile=DB_fetchArray($order_query_2);
				$stock=$zeile['stock'];
				// anpassen
				DB_query("		
					UPDATE products
					SET stock=".($stock-$count)."
					WHERE products_id=$pid 
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

if($fehler_kapazitaetKurzVorSpeicherung == 0){	// Wenn nicht bereits obige Fehlerbehandlung greift.
	// Product.stock checken (über alle potentiellen Bestellungen)
	$fehlerArray = array();	// für Fehlermeldung, wenn Produktkapazität überschritten															
	$countsPerProductArray = array();			
	$productStock = array();
	$productName = array();
	$i=-1;	
	foreach ($basketPID AS $pid){					
		$i++;			
		// Anzahl pro Produkt aufaddieren:													
		$countsPerProductArray[$pid] += $basketCount[$i];
		// Product.stock ermitteln:
		$productCount_query = DB_query("	
			SELECT
			stock, name
			FROM products
			WHERE products_id = $pid
		");
		$zeile = DB_fetchArray($productCount_query);
		$productStock[$pid]=$zeile['stock'];	
		$productName[$pid]=$zeile['name'];	
		// Kapazität mit Warenkorb-Inhalt vergleichen und Infos für Fehlermeldungen bereitstellen:
		if($countsPerProductArray[$pid] > $productStock[$pid]){
			$fehlerArray[$pid]['product_name'] = $productName[$pid];
			$fehlerArray[$pid]['product_count'] = $countsPerProductArray[$pid];
			$fehlerArray[$pid]['product_stock'] = $productStock[$pid];
		}
	}
}



$tpl->assign('user_name',$user->getName());
$tpl->assign('user_lastname',$user->getLastname());
$tpl->assign('user_id',$user->getID());
$tpl->assign('is_admin',$isAdmin);
$tpl->assign('error',$fehlerArray);
$tpl->assign('error_capacity',$fehler_kapazitaetKurzVorSpeicherung);

$tpl->display();
?>