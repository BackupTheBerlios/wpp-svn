<?php

function formatPrice($value) {
	$value = money_format ("%.2n",$value);	// zwei Nachkommastellen erzwingen
	$bereiche=explode('.',(string) $value);
	return "<span class='pre_comma'>".$bereiche[0]."</span> <span class='post_comma'>".$bereiche[1]."</span> ".CURRENCYSYMBOL;
}

function formatDate($value = '') {
	return date("YmdHis");
}

function viewDate($date){
	return date_format($date,"d.m.Y, h:i:s");
}	
?>