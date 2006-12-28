<?php

function formatPrice($value) {
	
	return $value . ' '.CURRENCY_SYMBOL;
	
}

function formatDate($value = '') {
	
	return date("YmdHis");

}

?>