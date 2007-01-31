<?php

function formatPrice($value) {
	$value = money_format ("%.2n",$value);	// zwei Nachkommastellen erzwingen
	$bereiche=explode('.',(string) $value);
	$bereiche[0]=number_format($bereiche[0], 2, '', '.');	// Tausender-Stellen
	return "<span class='pre_comma'>".$bereiche[0]."</span> <span class='post_comma'>".$bereiche[1]."</span> ".CURRENCYSYMBOL;
}

function formatDate($value = '') {
	return date("YmdHis");
}

?>