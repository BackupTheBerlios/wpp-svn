<?php

include('../includes/includes.inc');
include('../includes/startApplication.php');

$user = restoreUser();
if ($user ==null || !$user->checkPermissions(1,1)) {
	redirectURI("/admin/login.php","camefrom=categories.php");
}

$LOG = new Log();
$tpl = new TemplateEngine("template/categories.html","template/frame.html",$lang["admin_categories"]);

if (isset($_GET['catID'])) {
	$requestedCategory = $_GET['catID'];
} else {
	$requestedCategory = 0;
}

$tpl->assign('catID',$requestedCategory);

//Kategorie finden
if ($requestedCategory != 0) {
	$query = DB_query("SELECT
				*
				FROM categories
				WHERE categories_id = ".$requestedCategory);
	$category = DB_fetchArray($query);
	$tpl->assign('parent', $category['parent']);
} else {
	$category = null;
	$tpl->assign('parent',null);
}

//Kinder finden
$children_query = DB_query("SELECT
				*
				FROM categories
				WHERE parent = ".$requestedCategory."
				ORDER BY sort_order");
$children = array();
while ($line = DB_fetchArray($children_query)) {
	$list = array(
			"id" => $line['categories_id'],
			"name" => $line['name']);
	$LOG->write('3',"admin/categories.php:39: ".$list['name']);
	$children[] = $list;
}


//Produkte in der Kategorie ermitteln
$products_query = DB_query("SELECT
				*
				FROM products
				WHERE categories_id = ".$requestedCategory."
				AND deleted = 0
				ORDER BY sort_order");
$products = array();
while ($line = DB_fetchArray($products_query)) {
	$list = array(
			"id" => $line['products_id'],
			"name" => $line['name'],
			"description" => $line['description']);
	$products[] = $list;
}

//Menu erstellen
$menu = array();
for ($i=0;$i<sizeof($children);$i++) {
	$entry = array(
			"type" => "category",
			"name" => $children[$i]["name"],
			"id" => $children[$i]["id"],
			"active" => $children[$i]["active"]
	);
	$LOG->write('3',"admin/categories.php:69: ".$entry['name']);
	$menu[] = $entry;
}
for ($i=0;$i<sizeof($products);$i++) {
	$entry = array(
			"type" => "product",
			"name" => $products[$i]["name"],
			"id" => $products[$i]["id"],
			"active" => $children[$i]["active"]
	);
	$menu[] = $entry;
}

$tpl->assign('menu',$menu);

$tpl->display();

$LOG->write('3','admin/categories.php: Anzeige');

?>