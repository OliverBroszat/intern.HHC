var xmlHttp;

function add_to_search_box(new_value){
	$('#suggests').toggleClass("show",false); 
	$('#text-box').val(new_value);
	// document.getElementById("text-box").focus();
	$('form#form-suche').submit();
}

function suggest(suchbegriff) {
	xmlHttp=httpXMLobjects();
	if (xmlHttp==null) {
		alert ("Browser does not support AJAX");
		return;
	}
	if (suchbegriff.length==0) { 
		document.getElementById("suggests").innerHTML="";
		$('#suggests').toggleClass("show",false); 
		return;
	}
	else {
		//URL vorbereiten, Zufallszahl umgeht den Browsercache	
		var aufruf="/wordpress/wp-content/themes/intern-hhc/functions/suchfunktion/suggests.php"+"?q="+suchbegriff+"&sid="+Math.random();
		xmlHttp.onreadystatechange=stateChanged;
		xmlHttp.open("GET",aufruf,true);
		xmlHttp.send(null);
		}
	} 

function stateChanged() { 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete") { 
		document.getElementById("suggests").innerHTML=xmlHttp.responseText;
		if (xmlHttp.responseText!=0) {
				$('#suggests').toggleClass("show",true);
		}else{
			 $('#suggests').toggleClass("show",false); 
		}

	}
}

//AJAX-Standards 
function httpXMLobjects() {
	var xmlHttp=null;
	try {
		// Fuer Firefox, Opera und Safari
		xmlHttp=new XMLHttpRequest();
	 }
	catch (e) {
		// IE
		try {
			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
			}
		catch (e) {
			xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
	 }
	return xmlHttp;
}


