<?php

include('includes/includes.inc');
include('includes/startApplication.php');

$query = DB_query('SELECT
		*
		FROM categories
		WHERE categories_id = 1');

$test = DB_fetchArray($query);

echo $test['name'];
echo $test['categories_id'];


?>
