function checkInputs() {
	err_val = 0;
	var parentNode;
	for (var i=0;i<fields.length;i++) {
		httpRequest = new XMLHttpRequest();
		httpRequest.open("GET", "../includes/ajax/checkInput.php?input="+document.getElementById(fields[i][0]).value+"&constraint="+fields[i][1]+"&id="+fields[i][0], false);
		httpRequest.send(null);

		//TopLevelTabellenZelle
		parentNode=document.getElementById(fields[i][0]).parentNode;
		while (parentNode.nodeName!="TD") {
			parentNode=parentNode.parentNode;
		}
		if (httpRequest.responseText == 'true') {	// Für alle korrekten Eingaben: alte Fehlermeldungen löschen
			if(parentNode.lastChild.nodeValue==null){	// wenn Fehlermeldung existiert (nur dann ist letzter Knoten NULL und nicht nur leer (""))
				// Fehlermeldung leeren
				parentNode.removeChild(parentNode.lastChild); // <div> samt Fehlermeldung entfernen
				parentNode.style.backgroundColor=null;//'#e1efff'; // Farbe (annähernd) zurück setzen
			}
		}
		else {	// Wenn Fehler bei Eingabe gefunden:
			err_val += 1;
			if(parentNode.lastChild.nodeValue==null){	// wenn Fehlermeldung existiert (nur dann ist letzter Knoten NULL und nicht nur leer (""))
				// Fehlermeldung leeren
				parentNode.removeChild(parentNode.lastChild); // <div> samt Fehlermeldung entfernen
			}
			var node = document.createElement("div");	 // Zeilenumbruch, <br/> geht nicht.
			node.appendChild(document.createTextNode(httpRequest.responseText));
			parentNode.appendChild(node);
			parentNode.style.backgroundColor='#ffeeaa';
		}
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

function showPicture(bild){
	fenster = window.open(bild, "", "width=400,height=300,left=100,top=100,toolbar=no,status=no,menubar=no,location=no");
}