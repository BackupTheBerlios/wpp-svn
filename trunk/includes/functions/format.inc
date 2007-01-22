<?php

function formatPrice($value) {
	$value = money_format ("%.2n",$value);	// zwei Nachkommastellen erzwingen

$LOG = new Log();
$LOG->write("3",$value);



	$bereiche=explode('.',(string) $value);
	return "<span class='pre_comma'>".$bereiche[0]."</span> <span class='post_comma'>".$bereiche[1]."</span> ".CURRENCYSYMBOL;
	
}

function formatDate($value = '') {
	
	return date("YmdHis");

}

?>