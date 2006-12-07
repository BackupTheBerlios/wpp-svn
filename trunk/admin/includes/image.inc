<?php

//Funktionen, um Bilder hochzuladen

function uploadImage($image) {

	$LOG =new Log();

	$img_dir = WPP_BASE.WPP_ROOT."/media/product_images";
	$LOG->write('3','Bild: '.$image['tmp_name']);
	
	$info = getimagesize($image['tmp_name']);
	
	if ($info[2]==0) {
		$LOG->write('2','Bild-Upload: Kein Bild');
		return null;
	}
	
	if (move_uploaded_file($image['tmp_name'],$img_dir."/".$image['name'])) {
		$LOG->write('2','Bild hochgeladen: '.$img_dir."/".$image['name']);
		//chmod($img_dir."/".$image['name'],755);
		return $img_dir."/".$image['name'];
	} else {
		$LOG->write('2','Fehler beim Hochladen eines Bildes'.$image['error']);
		return null;
	}
}

?>