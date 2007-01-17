<?php

function formatPrice($value) {
	$bereiche=explode('.',(string) $value);
	return "<span class='pre_comma'>".$bereiche[0]."</span> <span class='post_comma'>".$bereiche[1]."</span> ".CURRENCYSYMBOL;
	
}

function formatDate($value = '') {
	
	return date("YmdHis");

}

?>