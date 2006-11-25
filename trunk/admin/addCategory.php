<?php

include('../includes/includes.inc');
include('../includes/startApplication.php');

$LOG = new Log();
$tpl = new TemplateEngine("templates/addCategory.html","templates/frame.html",$lang["admin_addCategories"]);

if (isset($_POST['action'])) {

	$LOG->write('3', 'admin/addCategory.php: action set');
	
	if ($_POST['action']=='add') {
		$LOG->write('3', 'admin/addCategory.php: action=add');
		
		if ($_POST['active']=='on') {$active=1;}
		else {$active=0;}
		DB_query("INSERT INTO categories VALUES (0,'".$_POST['name']."',".$_POST['cat'].",".$active.",'".$_POST['description']."',".$_POST['sort_order'].")");
	}
	
	elseif ($_POST['action']=='edit') {
		$LOG->write('3', 'admin/addCategory.php: action=edit');
		
		if ($_POST['active']=='on') {$active=1;}
		else {$active=0;}
		DB_query("UPDATE categories SET 
					name='".$_POST['name']."',
					active=".$active.",
					description='".$_POST['description']."',
					sort_order=".$_POST['sort_order']."
					WHERE categories_id=".$_POST['catID']);
		
		
	}
	
} elseif ($_GET['action']=='edit') {

	$LOG->write('3', 'admin/addCategory.php: get-action=edit');

	$catID = $_GET['catID'];
	$tpl->assign('catID',$catID);
	$tpl->assign('action','edit');
	
	//Alte Daten zur Kategorie
	$category_query = DB_query("SELECT
					*
					FROM categories
					WHERE categories_id = ".$catID);
	$category = DB_fetchArray($category_query);
	
	$tpl->assign('name',$category['name']);
	$tpl->assign('description',$category['description']);
	$tpl->assign('sort_order',$category['sort_order']);
	$tpl->assign('active',$category['active']);
	
	$tpl->display();
	
} elseif ($_GET['action']=='delete') {

	$LOG->write('3', 'admin/addCategory.php: get-action=delete');

	DB_query("DELETE FROM categories WHERE categories_id=".$_GET['catID']);
		

} else {

	$LOG->write('3', 'admin/addCategory.php: get-action=none');

	$catID = $_GET['catID'];
	$tpl->assign('catID',$catID);
	$tpl->assign('action','add');
	$tpl->display();
}

?>