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
	if ($user !=null && $user->checkPermissions(0,0,0,1,1)) {	// wenn ORDERER
		redirectURI("/orderer/index.php");
	}
	if ($user !=null && $user->checkPermissions(0,0,1)) {	// wenn USER
		redirectURI("/user/index.php");
	}
}


$LOG = new Log();
$tpl = new TemplateEngine("template/categories.html","template/frame.html",$lang["viewer_categories"]);

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
				ORDER BY sort_order, name");
$children = array();
while ($line = DB_fetchArray($children_query)) {
	$list = array(
			"id" => $line['categories_id'],
			"name" => $line['name'],
			"active" => $line['active']);
	$LOG->write('3',"viewer/categories.php:39: ".$list['name']);
	$children[] = $list;
}


//Produkte in der Kategorie ermitteln
$products_query = DB_query("SELECT
				*
				FROM products
				WHERE categories_id = ".$requestedCategory."
				AND deleted = 0
				ORDER BY sort_order, name");
$products = array();
while ($line = DB_fetchArray($products_query)) {
	$list = array(
			"id" => $line['products_id'],
			"name" => $line['name'],
			"description" => $line['description'],
			"active" => $line['active']);
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
	$LOG->write('3',"viewer/categories.php:69: ".$entry['name']);
	$menu[] = $entry;
}
for ($i=0;$i<sizeof($products);$i++) {
	$entry = array(
			"type" => "product",
			"name" => $products[$i]["name"],
			"id" => $products[$i]["id"],
			"active" => $products[$i]["active"]
	);
	$menu[] = $entry;
}


$tpl->assign('menu',$menu);
$tpl->assign('is_admin',$isAdmin);

$tpl->display();

$LOG->write('3','viewer/categories.php: Anzeige');

?>