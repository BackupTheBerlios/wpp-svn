function checkInputs() {
	//FIXME: Für das letzte zu prüfende Feld wird keine Fehlermeldung empfangen, wenn nur dieses Feld einen Fehler enthält.
	// Stattdessen kommt ein leerer httpRequest.responseText zurück und das Skript liefert true.
	// Die Antwort von checkInput.php wird nicht empfangen.
	// Vielleicht sollte man lieber eine synchrone Übertragung verwenden.
	err_val = 0;
	for (var i=0;i<fields.length;i++) {
		//alert(fields[i][0]+" "+fields[i][1]+" "+document.getElementById(fields[i][0]).value);
		httpRequest = new XMLHttpRequest();
		httpRequest.open("GET", "../includes/ajax/checkInput.php?input="+document.getElementById(fields[i][0]).value+"&constraint="+fields[i][1]+"&id="+fields[i][0], false);
		httpRequest.send(null);
		
		//httpRequest.onreadystatechange = function() {
		//	if (httpRequest.readyState == 4 && httpRequest.responseText!='true') {	
		if (httpRequest.responseText != 'true') {
				err_val += 1;
				//alert(httpRequest.responseText+err_val);
				document.getElementById("errorbox").appendChild(document.createTextNode(httpRequest.responseText));
				document.getElementById("errorbox").appendChild(document.createElement("br"));
			}
		///}
	}
	if (err_val>0) {
		return false;
	} else {
		return true;
	}
	
}

function setShippingDate(id) {
	
	httpRequest = new XMLHttpRequest(id);
	httpRequest.open("GET", "includes/setShippingDate.php?id="+id, 'true');
	httpRequest.send(null);
	
	httpRequest.onreadystatechange = function() {
		if (httpRequest.readyState == 4) {
			date = document.createTextNode(httpRequest.responseText);
			document.getElementById("sd").replaceChild(date, document.getElementById("sdb"));
		}
	}
}