<?php

session_start();

include('../includes/includes.inc');
include('../includes/startApplication.php');


$LOG = new Log();
$tpl = new TemplateEngine("template/login.html","template/frame_login.html",$lang["user_login"]);


if (isset($_POST['action'])) {
	
	if ($_POST['action']=='login') {
		
		$password = $_POST['password'];
		$sign = $_POST['sign'];
		$forward = $_POST['camefrom'];
		
		$user = loginUser($sign, $password);	// User-Objekt holen

		if($user != null && $user->getRole() == 2){	// Falls Login "User" ist
			if ($user!= null && $forward!='') {
				redirectURI('/user/'.$forward);
			} elseif ($user!= null && $forward=='') {
				redirectURI('/user/categories.php');
			}
		} 
		if($user != null && $user->getRole() == 1){	// Falls Login "Admin" ist
			if ($user!= null && $forward!='') {
				redirectURI('/admin/'.$forward);
			} elseif ($user!= null && $forward=='') {
				redirectURI('/admin/categories.php');
			}
		}
		if ($user == null && $forward!='') {	// allgemeine Fehlerbehandlung
			redirectURI('/user/login.php','error=failed&camefrom='.$forward);
		} 
		else {
			redirectURI('/user/login.php','error=failed');
		}
	}
} 
elseif (isset($_GET['action'])) {

	if ($_GET['action']=='logout') {
		logoutUser();
		redirectURI('/viewer/index.php');
	}

}
else {
	if (isset($_GET['camefrom'])) {
		$tpl->assign('cf',$_GET['camefrom']);
	} else {
		$tpl->assign('cf','');
	}
	
	if (isset($_GET['error'])) {
		$tpl->assign('error',$_GET['error']);
	} else {
		$tpl->assign('error','');
	}

	$tpl->display();
}

?>