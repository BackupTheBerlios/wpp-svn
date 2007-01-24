<?php

include('../functions/valueCheck.inc');
include('../../lang/lang_de.php');

if (checkInput($_GET['input'],$_GET['constraint'])) {
	echo 'true'; 
} else {
	//echo $_GET['input'];
	echo $lang['admin_errmsgs'][$_GET['id']];
}


?>