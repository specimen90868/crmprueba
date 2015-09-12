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

function buscarorg(){
	resul = document.getElementById('resultadoorg');
	
	bus=document.frmbusqueda.dato.value;
	tipo=document.frmbusqueda.tipo_registro.value;
	agente=document.frmbusqueda.agente.value;
	
	ajax=nuevoAjax();
	ajax.open("POST", "bus_org.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			resul.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("busqueda="+bus+"&tiporegistro="+tipo+"&promotor="+agente)

}

function buscarlista(){
	resul = document.getElementById('resultadoorg');
	
	texto=document.frmbusqueda.dato_lista.value;
	tipolista=document.frmbusqueda.tipo_lista.value;
	
	ajax=nuevoAjax();
	ajax.open("POST", "bus_lst.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			resul.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("campo="+texto+"&tipocampo="+tipolista)

}

function buscaract(){
	resul = document.getElementById('resultado');
	
	estatus=document.frmbusquedaact.estatus.value;
	tipo=document.frmbusquedaact.tipo_actividad.value;
	organizacion = document.getElementById("organizacion").value;
	
	ajax=nuevoAjax();
	ajax.open("POST", "bus_act.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			resul.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("estatus="+estatus+"&tiporegistro="+tipo+"&claveorganizacion="+organizacion)

}

function buscaropt(){
	resul = document.getElementById('resultado');
	
	estatus=document.frmbusquedaopt.estatusopt.value;
	organizacion = document.getElementById("organizacion").value;
	
	ajax=nuevoAjax();
	ajax.open("POST", "bus_opt.php",true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			resul.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("estatus="+estatus+"&claveorganizacion="+organizacion)

}