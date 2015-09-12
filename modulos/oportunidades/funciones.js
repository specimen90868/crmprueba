// JavaScript Document
function nuevoAjax(){
	var xmlhttp=false;
	try{
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	}catch(e){
		try {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}catch(E){
			xmlhttp = false;
		}
	}

	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	
	return xmlhttp;
}


function buscaropt(){
	resul = document.getElementById('resultado');
	estatus=document.frmbusquedaopt.estatusopt.value;
	agente=document.frmbusquedaopt.agente.value;
	
	ajax=nuevoAjax();
	ajax.open("POST", "bus_opt.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			resul.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("estatus="+estatus+"&agente="+agente)

}