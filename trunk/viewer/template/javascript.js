function checkInputs() {
	err_val = 0;
	for (var i=0;i<fields.length;i++) {
		httpRequest = new XMLHttpRequest();
		httpRequest.open("GET", "../includes/ajax/checkInput.php?input="+document.getElementById(fields[i][0]).value+"&constraint="+fields[i][1]+"&id="+fields[i][0], false);
		httpRequest.send(null);

		if (httpRequest.responseText == 'true') {	// Für alle korrekten Eingaben: alte Fehlermeldungen löschen
			if(document.getElementById(fields[i][0]).parentElement.lastChild.nodeValue==null){	// wenn Fehlermeldung existiert (nur dann ist letzter Knoten NULL und nicht nur leer (""))
				// Fehlermeldung leeren
				document.getElementById(fields[i][0]).parentElement.removeChild(document.getElementById(fields[i][0]).parentElement.lastChild); // <div> samt Fehlermeldung entfernen
				if(document.getElementsByTagName("form")[0].id=="into_basket"){	// Sonderbehandlung, weil's schöner aussieht.
					document.getElementById(fields[i][0]).parentElement.parentElement.style.backgroundColor='#e1efff';	// Farbe (annähernd) zurück setzen
				}
				else{
					document.getElementById(fields[i][0]).parentElement.style.backgroundColor='#e1efff'; // Farbe (annähernd) zurück setzen
				}
			}
		}
		else {	// Wenn Fehler bei Eingabe gefunden:
			err_val += 1;
			if(document.getElementById(fields[i][0]).parentElement.lastChild.nodeValue==null){	// wenn Fehlermeldung existiert (nur dann ist letzter Knoten NULL und nicht nur leer (""))
				// Fehlermeldung leeren
				document.getElementById(fields[i][0]).parentElement.removeChild(document.getElementById(fields[i][0]).parentElement.lastChild); // <div> samt Fehlermeldung entfernen
			}
			var node = document.createElement("div");	 // Zeilenumbruch, <br/> geht nicht.
			node.appendChild(document.createTextNode(httpRequest.responseText));
			document.getElementById(fields[i][0]).parentElement.appendChild(node);
			if(document.getElementsByTagName("form")[0].id=="into_basket"){	// Sonderbehandlung, weil's schöner aussieht.
				document.getElementById(fields[i][0]).parentElement.parentElement.style.backgroundColor='#ffeeaa';
			}
			else{
				document.getElementById(fields[i][0]).parentElement.style.backgroundColor='#ffeeaa';
			}
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