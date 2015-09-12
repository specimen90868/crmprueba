
function load(str)
{
	var xmlhttp;
	
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("myDiv").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("POST","opt.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("q="+str);
}

function objetoAjax(){
var xmlhttp=false;
try {
	   xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
} catch (e) {
	   try {
		  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	   } catch (E) {
			   xmlhttp = false;
	   }
}

if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
	   xmlhttp = new XMLHttpRequest();

}

return xmlhttp;

}

 

function MostrarConsulta(datos,fecha,organizacion)
{
	divResultado = document.getElementById('resultado');
	ajax=objetoAjax();
	ajax.open("POST", datos, true);
	ajax.onreadystatechange=function()
	{
		if (ajax.readyState==4)
		{
			   divResultado.innerHTML = ajax.responseText
		}
	}
	//ajax.send("date="+fecha)
	//alert('La fecha es ' + fecha);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("date="+fecha+"&organizacion="+organizacion)
}