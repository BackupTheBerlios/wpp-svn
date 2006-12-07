<?php

session_start();

include('../includes/includes.inc');
include('../includes/startApplication.php');

$LOG = new Log();
$tpl = new TemplateEngine("templates/login.html","templates/frame_login.html",$lang["admin_login"]);


if (isset($_POST['action'])) {
	
	if ($_POST['action']=='login') {
		
		$password = $_POST['password'];
		$sign = $_POST['sign'];
		$forward = $_POST['camefrom'];
		
		$user = new User();
		$login = $user->login($sign, $password);
		
		if ($login && $forward!='') {
			redirectURI('/admin/'.$forward);
		} elseif ($login && $forward=='') {
			redirectURI('/admin/index.php');
		} else {
			redirectURI('/admin/login.php','error=failed&camefrom='.$forward);
		}
	}

} 
elseif (isset($_GET['action'])) {

	if ($_GET['action']=='logout') {
		$user = restoreUser();
		$user->logout();
		redirectURI('/admin/index.php');
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


/*

$user = new User();

$login = $user->login('andre','test');

if ($login) {
$_SESSION['ufile:///mnt/Projekte/Studium/Webprogramming/Projekt/wpp/admin/login.phpser']=$user->id;
echo $_SESSION['user'];
}
*/

?>