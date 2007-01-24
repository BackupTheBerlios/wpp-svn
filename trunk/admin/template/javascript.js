function checkInputs() {
	err_val = 0;
	len = document.getElementById("errorbox").childNodes.length;
	for (var j=0;j<len;j++) {
		document.getElementById("errorbox").removeChild(document.getElementById("errorbox").childNodes[0]);
	}
	for (var i=0;i<fields.length;i++) {
		//alert(fields[i][0]+" "+fields[i][1]+" "+document.getElementById(fields[i][0]).value);
		httpRequest = new XMLHttpRequest();
		httpRequest.open("GET", "../includes/ajax/checkInput.php?input="+document.getElementById(fields[i][0]).value+"&constraint="+fields[i][1]+"&id="+fields[i][0], false);
		httpRequest.send(null);
		
		if (httpRequest.responseText != 'true') {
				err_val += 1;
				//alert(httpRequest.responseText+err_val);
				document.getElementById("errorbox").appendChild(document.createTextNode(httpRequest.responseText));
				document.getElementById("errorbox").appendChild(document.createElement("br"));
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
