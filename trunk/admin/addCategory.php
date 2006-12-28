<?php

include('../includes/includes.inc');
include('../includes/startApplication.php');

$user = restoreUser();
if ($user ==null || !$user->checkPermissions(1,1)) {
	redirectURI("/admin/login.php","camefrom=categories.php");
}

$LOG = new Log();
$tpl = new TemplateEngine("template/addCategory.html","template/frame.html",$lang["admin_addCategories"]);

if (isset($_POST['action'])) {

	$LOG->write('3', 'admin/addCategory.php: action set');
	
	if ($_POST['action']=='add') {
		$LOG->write('3', 'admin/addCategory.php: action=add');
		
		if ($_POST['active']=='on') {$active=1;}
		else {$active=0;}
		
		if ($_POST['sort_order'] == '') {$sort_order = 0;}
		else {$sort_order=$_POST['sort_order'];}
		
		if (!checkInput($_POST['name'], 'string')) { redirectURI('/admin/addCategory.php','action=add&catID='.$_POST['catID'].'&error=name_error'); }
		if (!checkInput($_POST['description'], 'string')) { redirectURI('/admin/addCategories.php','action=add&catID='.$_POST['catID'].'&error=desc_error'); }
		if (!checkInput($sort_order, 'int')) { redirectURI('/admin/addCategory.php','action=edit&catID='.$_POST['catID'].'&error=sort_error'); }
		
		DB_query("INSERT INTO categories 
				VALUES (0,
					'".$_POST['name']."',
					".$_POST['catID'].",
					".$active.",
					'".$_POST['description']."',
					".$sort_order.")");
		
		$LOG->write('2', 'Kategorie '.mysql_insert_id().' hinzugefügt');
		redirectURI('/admin/categories.php','catID='.$_POST['catID']);
	}
	
	elseif ($_POST['action']=='edit') {
		$LOG->write('3', 'admin/addCategory.php: action=edit');
		
		if ($_POST['active']=='on') {$active=1;}
		else {$active=0;}
		
		if ($_POST['sort_order'] == '') {$sort_order = 0;}
		else {$sort_order=$_POST['sort_order'];}
		
		if (!checkInput($_POST['name'], 'string')) { redirectURI('/admin/addCategory.php','action=edit&catID='.$_POST['catID'].'&error=name_error'); }
		if (!checkInput($_POST['description'], 'string')) { redirectURI('/admin/addCategory.php','action=edit&catID='.$_POST['catID'].'&error=desc_error'); }
		if (!checkInput($sort_order, 'int')) { redirectURI('/admin/addCategory.php','action=edit&catID='.$_POST['catID'].'&error=sort_error'); }
		
		DB_query("UPDATE categories SET 
					name='".$_POST['name']."',
					active=".$active.",
					description='".$_POST['description']."',
					sort_order=".$sort_order."
					WHERE categories_id=".$_POST['catID']);
		
		$LOG->write('2', 'Kategorie '.$_GET['catID'].' bearbeitet');
		redirectURI('/admin/categories.php','catID='.$_POST['parent']);
		
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
	$tpl->assign('parent',$category['parent']);
	$tpl->assign('error',$_GET['error']);
	
	$tpl->display();
	
} elseif ($_GET['action']=='delete') {

	$LOG->write('3', 'admin/addCategory.php: get-action=delete');

	DB_query("DELETE FROM categories WHERE categories_id=".$_GET['catID']);
		
	$LOG->write('2', 'Kategorie '.$_GET['catID'].' gelöscht');
	
	$parent = $_GET['parent'];
	redirectURI('/admin/categories.php','catID='.$parent);

} else {

	$LOG->write('3', 'admin/addCategory.php: get-action=none');

	$catID = $_GET['catID'];
	$tpl->assign('catID',$catID);
	$tpl->assign('action','add');
	$tpl->assign('error',$_GET['error']);
	
	$tpl->display();
}


?>