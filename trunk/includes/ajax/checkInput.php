<?php

include('../functions/valueCheck.inc');

if (checkInput($_GET['input'],$_GET['constraint'])) {
	echo 'true'; 
} else {
	echo 'false';
}


?>