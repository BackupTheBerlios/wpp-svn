<?php

include('../includes/includes.inc');
include('../includes/startApplication.php');

$LOG = new Log();
$tpl = new TemplateEngine("templates/categories.html","templates/frame.html",$lang["categories"]);

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
} else {
	$category = null;
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
			"name" => $line['name'],
			"parent" => $line['parent']);
	$LOG->write('3',"Kategorie gefunden: ".$list['name']);
	$children[] = $list;
}


//Produkte in der Kategorie ermitteln
$products_query = DB_query("SELECT
				*
				FROM products
				WHERE categories_id = ".$requestedCategory."
				ORDER BY sort_order");
$products = array();
while ($line = DB_fetchArray($products_query)) {
	$list = array(
			"id" => $line['products_id'],
			"name" => $line['name'],
			"parent" => $line['description']);
	$products[] = $list;
}


$menu = array();
for ($i=0;$i<sizeof($children);$i++) {
	$entry = array(
			"type" => "cat",
			"name" => $children[$i]["name"],
			"link" => HTTP_HOSTNAME.WPP_ROOT."/admin/categories.php?catID=".$children[$i]["id"]
	);
	$LOG->write('3',"Kategorie geschrieben: ".$entry['name']);
	$menu[] = $entry;
}

for ($i=0;$i<sizeof($products);$i++) {
	$entry = array(
			"type" => "cat",
			"name" => $products[$i]["name"],
			"link" => HTTP_HOSTNAME.WPP_ROOT."/admin/categories.php?catID=".$products[$i]["id"]
	);
	$menu[] = $entry;
}

$tpl->assign('menu',$menu);

$tpl->display();

?>