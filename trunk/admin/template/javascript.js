function checkInput(element, constraint) {

	httpRequest = new XMLHttpRequest();
	httpRequest.open("GET", "../includes/ajax/checkInput.php?input="+element.value+"&constraint="+constraint, 'true');
	httpRequest.send(null);
	
	httpRequest.onreadystatechange = function() {
		if (httpRequest.readyState == 4) {	
			//alert(httpRequest.responseText);
			err_msg = document.createElement("span");
			err_msg.appendChild(document.createTextNode(httpRequest.responseText));
			element.parentNode.appendChild(err_msg);
			//alert(element.parentNode);
		}
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