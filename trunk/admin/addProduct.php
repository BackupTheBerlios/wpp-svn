<?php

include('../includes/includes.inc');
include('../includes/startApplication.php');
include('includes/image.inc');

//include('../includes/functions/verifyadmin.inc');

$user = restoreUser();
if ($user ==null || !$user->checkPermissions(1,1)) {
	redirectURI("/admin/login.php","camefrom=categories.php");
}


$LOG = new Log();
$tpl = new TemplateEngine("template/addProduct.html","template/frame.html",$lang["admin_addProduct"]);

if (isset($_POST['action'])) {

	$LOG->write('3', 'admin/addProduct.php: action set');
	
	if ($_POST['action']=='add') {
		$LOG->write('3', 'admin/addProduct.php: action=add');
		
		if (!checkInput($_POST['name'], 'string')) {redirectURI('/admin/addProduct.php','action=add&catID='.$_POST['ID'].'&error=name_error');}
		if (!checkInput($_POST['description'], 'string')) {redirectURI('/admin/addProduct.php','action=add&catID='.$_POST['ID'].'&error=desc_error');}
		if (!checkInput($_POST['stock'], 'int')) {redirectURI('/admin/addProduct.php','action=add&catID='.$_POST['ID'].'&error=stock_error');}
		if (!checkInput($_POST['price'], 'price')) {redirectURI('/admin/addProduct.php','action=add&catID='.$_POST['ID'].'&error=price_error');}
		
		$image1 = $_FILES['image_small'];
		$image_uri_1 = uploadImage($image1);
		
		$image2 = $_FILES['image_big'];
		$image_uri_2 = uploadImage($image2);

		$createtime = formatDate();
		
		if ($_POST['active']=='on') {$active=1;}
		else {$active=0;}
		if ($_POST['sort_order']=='') {$sortorder=0;}
		else {$sortorder=$_POST['sort_order'];}
		
		DB_query("INSERT INTO products VALUES (
					0,
					'".$_POST['name']."',
					".$_POST['ID'].",
					0,
					".$active.",
					'".$_POST['description']."',
					'".$image_uri_1."',
					'".$image_uri_2."',
					'".$_POST['stock']."',
					'".$_POST['price']."',
					'".$createtime."',
					".$sortorder.")
					");
		$LOG->write('2', 'Produkt '.mysql_insert_id().' hinzugefügt');
	
		$parent = $_POST['ID'];
		redirectURI('/admin/categories.php','catID='.$parent);
	}
	
	elseif ($_POST['action']=='edit') {
		$LOG->write('3', 'admin/addProduct.php: action=edit');
		
		$cat_query = DB_query("SELECT *
					FROM products
					WHERE products_id = ".$_POST['ID']);
		$cat = DB_fetchArray($cat_query);
		
		
		if (!checkInput($_POST['name'], 'string')) {redirectURI('/admin/addProduct.php','action=edit&pID='.$_POST['ID'].'&error=name_error');}
		if (!checkInput($_POST['description'], 'string')) {redirectURI('/admin/addProduct.php','action=edit&pID='.$_POST['ID'].'&error=desc_error');}
		if (!checkInput($_POST['stock'], 'int')) {redirectURI('/admin/addProduct.php','action=edit&pID='.$_POST['ID'].'&error=stock_error');}
		if (!checkInput($_POST['price'], 'price')) {redirectURI('/admin/addProduct.php','action=edit&pID='.$_POST['ID'].'&error=price_error');}
		
		$LOG->write('3',sizeof($_FILE));
		$image1 = $_FILES['image_small'];

		// Wenn Image-Auswahl leer bleibt, so wird altes Bild in DB behalten und nicht geleert.
		// Sonst keine Änderungen im Produkt ohne Neuauswahl des Bildes möglich.
		if($image1['name']!=""){
			$image_uri_1 = uploadImage($image1);
		}
		else{
			$image_uri_1 = $cat['image_small'];
		}

		$image2 = $_FILES['image_big'];
		if($image2['name']!=""){
			$image_uri_2 = uploadImage($image2);
		}	
		else{
			$image_uri_2 = $cat['image_big'];
		}
		
		$createtime = formatDate();
		
		//Auflösen der Kategorie-ID
		$cat = $cat['categories_id'];
		
		if ($_POST['active']=='on') {$active=1;}
		else {$active=0;}
		if ($_POST['sort_order']=='') {$sortorder=0;}
		else {$sortorder=$_POST['sort_order'];}

		DB_query("INSERT INTO products VALUES (
					0,
					'".$_POST['name']."',
					".$cat.",
					0,
					".$active.",
					'".$_POST['description']."',
					'".$image_uri_1."',
					'".$image_uri_2."',
					'".$_POST['stock']."',
					'".$_POST['price']."',
					'".$createtime."',
					".$sortorder.")
					");

		$neueID=mysql_insert_id();

		DB_query("UPDATE products SET
					deleted=1
					where products_id=".$_POST['ID']);
		
		// noch in Warenkörben alte IDs zu neuen IDs ändern.
		DB_query("
			UPDATE basket
			SET products_id = ".$neueID."
			WHERE products_id = ".$_POST['ID']
		);

		$LOG->write('2', 'Produkt '.$_POST['ID'].' geändert, neue ID='.$neueID);
	
		$parent = $cat;
		//redirectURI('/admin/categories.php');		
	
		redirectURI('/admin/categories.php','catID='.$parent);		
	}
	
} elseif ($_GET['action']=='edit') {

	$LOG->write('3', 'admin/addProduct.php: get-action=edit');

	$pID = $_GET['pID'];
	$tpl->assign('ID',$pID);
	$tpl->assign('action','edit');
	
	//Alte Daten zur Kategorie
	$product_query = DB_query("SELECT
					*
					FROM products
					WHERE products_id = ".$pID);
	$product = DB_fetchArray($product_query);
	
	$tpl->assign('name',$product['name']);
	$tpl->assign('description',$product['description']);
	$tpl->assign('sort_order',$product['sort_order']);
	$tpl->assign('active',$product['active']);
	$tpl->assign('image_small',$product['image_small']);
	$tpl->assign('image_big',$product['image_big']);
	$tpl->assign('stock',$product['stock']);
	$tpl->assign('price',$product['price']);
	$tpl->assign('deleted',$product['deleted']);
	$tpl->assign('error',$_GET['error']);
	$tpl->assign('user_name',$user->getName());
	$tpl->assign('user_lastname',$user->getLastname());
	
	$tpl->display();
	
} elseif ($_GET['action']=='delete') {

	$LOG->write('3', 'admin/addCategory.php: get-action=delete');

	DB_query("UPDATE products SET
					deleted=1
					where products_id=".$_GET['pID']);
		
	$LOG->write('2', 'Produkt '.$_GET['pID'].' gelöscht');
	
	$parent = $_GET['parent'];
	redirectURI('/admin/categories.php','catID='.$parent);

} elseif ($_GET['action']=='deleteImage') {		// Bild löschen

	$LOG->write('3', 'admin/addCategory.php: get-action=deleteImage');

	$bild_http=urldecode($_GET['img']);

	// http://localhost/wpp aus Bild-URI entfernen:
	$bild = str_replace(HTTP_HOSTNAME, "", $bild_http);

	// alle Vorkommen der Bild-URI ersetzen, da Bild gelöscht wird.

	DB_query("
		UPDATE products
		SET image_small='kein Bild'
		WHERE image_small='$bild_http'
	");
	DB_query("
		UPDATE products
		SET image_big='kein Bild'
		WHERE image_big='$bild_http'
	");
	unlink(WPP_BASE.$bild);
	
	// neu anzeigen:
	redirectURI('/admin/addProduct.php','action=edit&pID='.$_GET['pID']);

} else {

	$LOG->write('3', 'admin/addProduct.php: get-action=none');

	$ID = $_GET['catID'];
	$tpl->assign('ID',$ID);
	$tpl->assign('action','add');
	$tpl->assign('error',$_GET['error']);
	$tpl->assign('user_name',$user->getName());
	$tpl->assign('user_lastname',$user->getLastname());

	$tpl->display();
}

?>