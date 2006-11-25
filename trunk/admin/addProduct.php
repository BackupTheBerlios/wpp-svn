<?php

include('../includes/includes.inc');
include('../includes/startApplication.php');

$LOG = new Log();
$tpl = new TemplateEngine("templates/addProduct.html","templates/frame.html",$lang["admin_addProduct"]);

if (isset($_POST['action'])) {

	$LOG->write('3', 'admin/addCategory.php: action set');
	
	if ($_POST['action']=='add') {
		$LOG->write('3', 'admin/addProduct.php: action=add');
		
		$image_uri = 'test';
		$createtime = date("YmdHis");
		
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
					'".$image_uri."',
					'".$_POST['stock']."',
					'".$_POST['price']."',
					'".$createtime."',
					".$sortorder.")
					");
	}
	
	elseif ($_POST['action']=='edit') {
		$LOG->write('3', 'admin/addProduct.php: action=edit');
		
		$image_uri = 'test';
		$createtime = date("YmdHis");
		//Auflösen der Kategorie-ID
		$cat_query = DB_query("SELECT categories_id
					FROM products
					WHERE products_id=".$_POST['ID']);
		$cat = DB_fetchArray($cat_query);
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
					'".$image_uri."',
					'".$_POST['stock']."',
					'".$_POST['price']."',
					'".$createtime."',
					".$sortorder.")
					");
		DB_query("UPDATE products SET
					deleted=1
					where products_id=".$_POST['ID']);
		
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
	$tpl->assign('stock',$product['stock']);
	$tpl->assign('price',$product['price']);
	
	$tpl->display();
	
} elseif ($_GET['action']=='delete') {

	$LOG->write('3', 'admin/addCategory.php: get-action=delete');

	DB_query("UPDATE products SET
					deleted=1
					where products_id=".$_GET['pID']);
		
		

} else {

	$LOG->write('3', 'admin/addProduct.php: get-action=none');

	$ID = $_GET['pID'];
	$tpl->assign('ID',$ID);
	$tpl->assign('action','add');
	$tpl->display();
}

?>