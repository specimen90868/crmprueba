<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
include ("../../includes/FusionCharts.php");

$claveagente=$_SESSION[Claveagente];
$responsable=$_SESSION[Rol];
$claveorganizacion=$_GET[organizacion];

$sqlagt="SELECT * FROM usuarios LEFT JOIN (responsables) ON (usuarios.id_responsable=responsables.id_responsable) WHERE usuarios.claveagente= '".$claveagente."'";
$rsagt= mysql_query ($sqlagt,$db);
$rwagt=mysql_fetch_array($rsagt);


$Fecha=getdate();
$date=date("Y-m-d");
$Anio=$Fecha["year"]; 
$Mes=$Fecha["mon"];
$month=date("m"); 
$Dia=$Fecha["mday"]; 
$Hora=$Fecha["hours"].":".$Fecha["minutes"].":".$Fecha["seconds"];
$Anio_anterior=$Anio-1;

$ultimodia= $Anio."-".$month."-".ultimo_dia($Mes,$Anio)." 00:00:00";
$primerdia= $Anio."-".$month."-01 00:00:00";

$meses_espanol = array(
    '1' => 'Enero',
    '2' => 'Febrero',
    '3' => 'Marzo',
    '4' => 'Abril',
    '5' => 'Mayo',
    '6' => 'Junio',
    '7' => 'Julio',
    '8' => 'Agosto',
    '9' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre',
    ); 

//Oportunidades atrasadas cierre anterior a la fecha actual y abiertas
$sqloverdueopt="SELECT * FROM `oportunidades` WHERE `fecha_cierre_esperado` < '".$date."' AND (`id_etapa`!=6 AND `id_etapa`!=7) AND `clave_organizacion`='".$claveorganizacion."' AND usuario=  '".$claveagente."'";
$resultadoopt = mysql_query($sqloverdueopt, $db);
$overdueopt = mysql_num_rows($resultadoopt);


$sqlrol="SELECT * FROM responsables WHERE id_responsable = '".$responsable."'";
$rsrol = mysql_query($sqlrol, $db);
$rwrol = mysql_num_rows($rsrol);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>crm</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../style.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="../../css/screen.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../../css/styleform.css" />
<link rel="stylesheet" type="text/css" href="../../css/ventanas-modales.css">

<script type="text/javascript" src="../../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/cmxform.js"></script>
<script type="text/javascript" src="../../js/FusionCharts.js"></script>
<script type="text/javascript" src="../../assets/ui/js/jquery.min.js" language="Javascript"></script>
<script type="text/javascript" src="../../assets/ui/js/lib.js" language="Javascript"></script>

<link rel="StyleSheet" href="estilos.css" type="text/css">
<link rel="icon" href="images/icon.ico" />

<script type="text/javascript"> 
function disablefunction() {
for(var i=0; i<document.forms[0].length;i++) {
document.forms[0].elements[i].disabled = true;
}
}
</script>

<script type="text/javascript">
$(document).ready(function(){
	
//Set default open/close settings
$('.acc_container').hide(); //Hide/close all containers
$('.acc_trigger:first').addClass('active').next().show(); //Add "active" class to first trigger, then show/open the immediate next container

//On Click
$('.acc_trigger').click(function(){
	if( $(this).next().is(':hidden') ) { //If immediate next container is closed...
		$('.acc_trigger').removeClass('active').next().slideUp(); //Remove all .acc_trigger classes and slide up the immediate next container
		$(this).toggleClass('active').next().slideDown(); //Add .acc_trigger class to clicked trigger and slide down the immediate next container
	}
	return false; //Prevent the browser jump to the link anchor
});

});
</script>

<script>
$(document).ready(function () {
  $("#monto_credito").change(function () {
	  var value = $(this).val();
	  $("#monto").val(value);
  });
  
  $("#interes_credito").change(function () {
	  var value = $(this).val();
	  $("#interes").val(value);
  });
  
});
</script>

<script>
function habilitar(value,id)
{
	console.log(value);
	if(value=="1")
	{
		// habilitamos
		document.getElementById(id+"_detalle").disabled=false;
	}else if(value=="0"){
		// deshabilitamos
		document.getElementById(id+"_detalle").disabled=true;
		document.getElementById(id+"_detalle").value="";
	}
}
</script>

<script language="javascript">  

function saldo_inicial2() {  
	var inicial1 = document.getElementById("FLU_inicial_mes1");  
    	var depositos1 = document.getElementById("FLU_depositos_mes1");  
    	var retiros1 = document.getElementById("FLU_retiros_mes1"); 
	var inicial2 = document.getElementById("FLU_inicial_mes2");
	inicial2.value = (parseFloat(inicial1.value)+parseFloat(depositos1.value)-parseFloat(retiros1.value)).toFixed(2);
}
function saldo_inicial3() {  
	var inicial2 = document.getElementById("FLU_inicial_mes2");  
    var depositos2 = document.getElementById("FLU_depositos_mes2");  
    var retiros2 = document.getElementById("FLU_retiros_mes2"); 
	var inicial3 = document.getElementById("FLU_inicial_mes3");
	inicial3.value = (parseFloat(inicial2.value)+parseFloat(depositos2.value)-parseFloat(retiros2.value)).toFixed(2);
}
function saldo_inicial4() {  
	var inicial3 = document.getElementById("FLU_inicial_mes3");  
    var depositos3 = document.getElementById("FLU_depositos_mes3");  
    var retiros3 = document.getElementById("FLU_retiros_mes3"); 
	var inicial4 = document.getElementById("FLU_inicial_mes4");
	inicial4.value = (parseFloat(inicial3.value)+parseFloat(depositos3.value)-parseFloat(retiros3.value)).toFixed(2);
}
function saldo_inicial5() {  
	var inicial4 = document.getElementById("FLU_inicial_mes4");  
    var depositos4 = document.getElementById("FLU_depositos_mes4");  
    var retiros4 = document.getElementById("FLU_retiros_mes4"); 
	var inicial5 = document.getElementById("FLU_inicial_mes5");
	inicial5.value = (parseFloat(inicial4.value)+parseFloat(depositos4.value)-parseFloat(retiros4.value)).toFixed(2);
}
function saldo_inicial6() {  
	var inicial5 = document.getElementById("FLU_inicial_mes5");  
    var depositos5 = document.getElementById("FLU_depositos_mes5");  
    var retiros5 = document.getElementById("FLU_retiros_mes5"); 
	var inicial6 = document.getElementById("FLU_inicial_mes6");
	inicial6.value = (parseFloat(inicial5.value)+parseFloat(depositos5.value)-parseFloat(retiros5.value)).toFixed(2);
}

function promedio_inicial() {  
	var inicial1 = document.getElementById("FLU_inicial_mes1");
	var inicial2 = document.getElementById("FLU_inicial_mes2");
	var inicial3 = document.getElementById("FLU_inicial_mes3");
	var inicial4 = document.getElementById("FLU_inicial_mes4");
	var inicial5 = document.getElementById("FLU_inicial_mes5"); 
	var promedio_inicial = document.getElementById("FLU_promedio_inicial");
	promedio_inicial.value = ((parseFloat(inicial2.value)+parseFloat(inicial3.value)+parseFloat(inicial4.value)+parseFloat(inicial5.value))/5).toFixed(2);
}

function promedio_depositos() {  
	var depositos1 = document.getElementById("FLU_depositos_mes1");
	var depositos2 = document.getElementById("FLU_depositos_mes2");
	var depositos3 = document.getElementById("FLU_depositos_mes3");
	var depositos4 = document.getElementById("FLU_depositos_mes4");
	var depositos5 = document.getElementById("FLU_depositos_mes5"); 
	var promedio_depositos = document.getElementById("FLU_promedio_depositos");
	promedio_depositos.value = ((parseFloat(depositos1.value)+parseFloat(depositos2.value)+parseFloat(depositos3.value)+parseFloat(depositos4.value)+parseFloat(depositos5.value))/5).toFixed(2);
}

function promedio_retiros() {  
	var retiros1 = document.getElementById("FLU_retiros_mes1");
	var retiros2 = document.getElementById("FLU_retiros_mes2");
	var retiros3 = document.getElementById("FLU_retiros_mes3");
	var retiros4 = document.getElementById("FLU_retiros_mes4");
	var retiros5 = document.getElementById("FLU_retiros_mes5"); 
	var promedio_retiros = document.getElementById("FLU_promedio_retiros");
	promedio_retiros.value = ((parseFloat(retiros1.value)+parseFloat(retiros2.value)+parseFloat(retiros3.value)+parseFloat(retiros4.value)+parseFloat(retiros5.value))/5).toFixed(2);
}

function promedio_promedio() {  
	var promedio1 = document.getElementById("FLU_promedio_mes1");
	var promedio2 = document.getElementById("FLU_promedio_mes2");
	var promedio3 = document.getElementById("FLU_promedio_mes3");
	var promedio4 = document.getElementById("FLU_promedio_mes4");
	var promedio5 = document.getElementById("FLU_promedio_mes5"); 
	var promedio_promedio = document.getElementById("FLU_promedio_promedio_total");
	promedio_promedio.value = ((parseFloat(promedio1.value)+parseFloat(promedio2.value)+parseFloat(promedio3.value)+parseFloat(promedio4.value)+parseFloat(promedio5.value))/5).toFixed(2);
}

function amortizacion() {  
	var monto = document.getElementById("monto_credito");
	var interes = document.getElementById("interes_credito");
	var plazo = document.getElementById("plazo_credito");
	var amortizacion = document.getElementById("amortizacion_credito");;
	amortizacion.value = Math.round(((Math.pow((1+((interes.value/12)/100)),plazo.value))*monto.value*((interes.value/12)/100)/(Math.pow((1+((interes.value/12)/100)),plazo.value)-1)),4);
}
  
</script>

<script type="text/javascript">

function mostrarReferencia()
{
	if(document.frmProceso.id_etapa.value==13)
	{
		document.getElementById('desdeotro').style.display='block';
	}
	else
	{
		document.getElementById('desdeotro').style.display='none';
	}
	if(document.frmProceso.id_etapa.value==11)
	{
		document.getElementById('motivo').style.display='block';
	}
	else
	{
		document.getElementById('motivo').style.display='none';
	}
}
</script>

<style>
.myform{
margin:0 auto;
width:938px;
padding:10px;
}
p, h1, form, button{border:0; margin:0; padding:0;}
.spacer{
	clear:both;
	height:1px;
	margin: 0 0 10px 0;
}

/* ----------- stylized ----------- */
#stylized{
	border:solid 1px #e3e3e3;
	background:#fff;
}
#stylized h1 {
	font-size:14px;
	font-weight:bold;
	margin-bottom:8px;
	color: #302369;
}
#stylized p{
	font-size:11px;
	color:#9FC733;
	margin-bottom:20px;
	border-bottom:solid 1px #9FC733;
	padding-bottom:10px;
}
#stylized label{
display:block;
font-weight:bold;
text-align:right;
width:140px;
float:left;
}
#stylized .small{
color:#9FC733;
display:block;
font-size:11px;
font-weight:normal;
text-align:right;
width:140px;
}
#stylized input{
	float:left;
	font-size:10px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:150px;
	margin:2px 5px 0 10px;
}

#stylized textarea{
	/*float:none;
	font-size:10px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:150px;
	margin:2px 5px 0 10px;*/
	float:none;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width: 96%;
	margin: 2px 15px 0 15px;
}

#stylized select{
	float:none;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:156px;
	margin:2px 0 5px 10px;
}

#stylized .select2{
	float:none;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:80px;
	margin:0 0 0 0;
}

#stylized .input2 {
	float:none;
	font-size:10px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:70px;
	margin:0 0 0 0;
	text-align: right;
}

#stylized .input3 {
	float:none;
	font-size:10px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:70px;
	margin:0 0 0 0;
	text-align: right;
}

#stylized .input4 {
	float:none;
	font-size:10px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:762px;
	margin:2px 0 5px 10px;
	text-align: left;
}

#stylized .input4 {
	float:none;
	font-size:10px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:771px;
	margin:2px 0 5px 10px;
	text-align: right;
}

#stylized .inputcen {
	float:none;
	font-size:10px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:500px;
	margin:0 0 0 220px;
	text-align: right;
}

#stylized .inputder {
	float:right;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:400px;
	margin:2px 15px 5px 0;
	text-align: right;
}

#stylized .inputizq {
	float:none;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:500px;
	margin: 2px 15px 0 15px;
	text-align: left;
}


#stylized button{
	clear:both;
	margin-left:406px;
	width:125px;
	height:31px;
	background:#666666 url(img/button.png) no-repeat;
	text-align:center;
	line-height:31px;
	color:#FFFFFF;
	font-size:11px;
	font-weight:bold;
}
</style>



</head>
<body>
<div id="headerbg">
  <div id="headerblank">
    <div id="header">
    	<div id="logo"></div>
        <div id="menu">
        <ul>
          <li><a href="../../index.php" class="dashboard" title="Mi Tablero"></a></li>
          <li><a href="../organizaciones/index.php" class="contactos" title="Organizaciones"></a></li>
          <li><a href="../actividades/calendario.php" class="actividades" title="Actividades"></a></li>
          <li><a href="../oportunidades/oportunidades.php" class="oportunidades" title="Oportunidades"></a></li>
          <li><a href="../reportes/index.php" class="reportes" title="Reportes"></a></li>
          <?php
		  if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador"){
		  ?>
          <li><a href="../configuracion/index.php" class="configuracion" title="Configuracion"></a></li>
          <?php
		  }
		  ?>
        </ul>
      </div>
      
      <div id="sesionlinks">Bienvenido <a href="modulos/agentes/detalles.php?agente=<?php echo $claveagente; ?>" class="sesionlinks"><?php echo $_SESSION["Nombre"]; ?> (<?php echo $_SESSION["Tipo"]; ?>)</a> | <a href="../../salir.php" class="sesionlinks">Cerrar sesión</a></div>
      
      <?php
	  $sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` LIKE '".$claveorganizacion."' ORDER BY id_organizacion ASC";
	  $resultorg= mysql_query ($sqlorg,$db);
	  $myroworg=mysql_fetch_array($resultorg);
	  //echo $myroworg[tipo_persona];
	  
	  $sqlopt="SELECT * FROM oportunidades WHERE id_oportunidad='".$_GET[id]."'";
	  $resultopt= mysql_query ($sqlopt,$db);
	  $myrowopt=mysql_fetch_array($resultopt);
	  ?>
      
      <div id="titulo"><?php echo $myroworg[organizacion]; ?></div>
      
      <div id="pageNav">
        <div id="pageNavWrapper">
			<ul class="pageNav">
                <li class="selected"><a href="oportunidades.php?organizacion=<?php echo $claveorganizacion;?>">Análisis de Crédito <span class="count important" <?php if($overdueopt==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueopt; ?></span></a></li>
            </ul> 
        </div>
      </div>
      
    </div>
  </div>
</div>
<div id="contentbg">
  <div id="contentblank">
    <div id="content">
      <div id="contentmid">
        <div class="midtxt">
        
        <?php
		
		$sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` LIKE '".$claveorganizacion."' ORDER BY id_organizacion ASC";
		$resultorg= mysql_query ($sqlorg,$db);

		while($myroworg=mysql_fetch_array($resultorg))
		{
			$empresa=$myroworg[organizacion];
			$clave=$myroworg[clave_unica];
			$claves=explode(",",$myroworg[clave_unica]);
			//echo count($claves);
			
			$clavescliente="(";
			for($i=0; $i<count($claves); $i++)//Venta para varias claves
			{
				$clavescliente.="`K_Cliente`='$claves[$i]'";
				if($i<count($claves)-1){$clavescliente.=" OR ";}else{$clavescliente.=")";}
			}
			
			//$diascontacto = diferencia_dias($myroworg[fecha_ultimo_contacto],$date);
			list($dias, $meses) = diferencia_dias($myroworg[fecha_ultimo_contacto],$date);
			
			//Semaforización de oportunidades
			if($dias>90){$resaltadocontacto="#FFBBBB";}
			elseif($dias>=31&&$dias<=90){$resaltadocontacto="#FFE784";}
			else{$resaltadocontacto="#BFE6B9";}

			//Telefonos de Organización
			$sqltelorg="SELECT * FROM `telefonos` WHERE `clave_registro` LIKE '".$claveorganizacion."' AND `tipo_registro` = 'O' ORDER BY id_telefono ASC";
			$resulttelorg= mysql_query ($sqltelorg,$db);
			
			//Emails de Organización
			$sqlemailorg="SELECT * FROM `correos` WHERE `clave_registro` LIKE '".$claveorganizacion."' AND `tipo_registro` = 'O' ORDER BY id_correo ASC";
			$resultemailorg= mysql_query ($sqlemailorg,$db);
			
			//Direcciones Web de Organización
			$sqlweborg="SELECT * FROM `direccionesweb` WHERE `clave_registro` LIKE '".$claveorganizacion."' AND `tipo_registro` = 'O' ORDER BY id_direccionweb ASC";
			$resultweborg= mysql_query ($sqlweborg,$db);
			
			//Domicilios de la Organización
			$sqldomorg="SELECT * FROM `domicilios` WHERE `clave_registro` LIKE '".$claveorganizacion."' AND `tipo_registro` = 'O' ORDER BY id_domicilio ASC";
			$resultdomorg= mysql_query ($sqldomorg,$db);
			
			//Contactos de la Organización
			$sqlconorg="SELECT * FROM `contactos` WHERE `clave_organizacion` LIKE '".$claveorganizacion."' ORDER BY id_contacto ASC";
			$myrowconorg= mysql_query ($sqlconorg,$db);
			$myrowconorg=mysql_fetch_array($myrowconorg);

            if($_GET[an])//Hay id de análisis, por lo tanto se muestra el capturado
			{
				$o="U";
				//Datos de oportunidad
				$sqlopt="SELECT * FROM  `oportunidades` WHERE `id_oportunidad`='".$_GET[id]."'";
				$rsopt= mysql_query($sqlopt,$db);
				$rwopt=mysql_fetch_array($rsopt);
				
				$total=mysql_num_rows($rsopt);
				$amortizacion=round(((pow((1+(($rwopt[interes]/12)/100)),$rwopt[plazo_credito]))*$rwopt[monto]*(($rwopt[interes]/12)/100)/(pow((1+(($rwopt[interes]/12)/100)),$rwopt[plazo_credito])-1)),2);
				
				$sqlanalisis="SELECT * FROM analisis WHERE id_analisis='".$_GET[an]."'";
				$rsanalisis= mysql_query($sqlanalisis,$db);
				$aprobado="checkv_16.png";
				$rechazado="reject_16.png";
				while($rwanalisis=mysql_fetch_array($rsanalisis))
				{
					$sqlcalificacion="SELECT * FROM calificacionanalisis WHERE id_analisis='".$_GET[an]."'";
					$rscalificacion= mysql_query($sqlcalificacion,$db);
					$rwcalificacion=mysql_fetch_array($rscalificacion);
					$poderes=0;$historial=0;$flujo=0;$garantia=0;$listas=0;$final=$poderes+$historial+$flujo+$garantia+$listas;
					if($rwcalificacion)
					{
						$poderes=$rwcalificacion[calificacion_poderes];$historial=$rwcalificacion[calificacion_historial];$flujo=$rwcalificacion[calificacion_flujo];$garantia=$rwcalificacion[calificacion_garantia];$listas=$rwcalificacion[calificacion_listas];$final=$poderes+$historial+$flujo+$garantia+$listas;
					}
					else
					{
						//Calificación poderes
						if($rwanalisis[POD_facultades_empresa]==1&&$rwanalisis[POD_poderes_representante]==1&&$rwanalisis[POD_facultades_garante]==1){$poderes=100;}else{$poderes=0;}
						//Calificación historial: Sin incidencias legales && saldo vigente
						if($rwanalisis[HIS_incidencias_solicitante]==0&&$rwanalisis[HIS_incidencias_representante]==0&&$rwanalisis[HIS_incidencias_accionista]==0&&$rwanalisis[HIS_vigente_solicitante]>0&&$rwanalisis[HIS_vigente_representante]>0&&$rwanalisis[HIS_vigente_accionista]>0){$historial=100;}else{$historial=0;}
						//Calificacion flujo
						if($rwanalisis[FLU_promedio_promedio]>=$amortizacion&&$rwanalisis[FLU_promedio_depositos]>=($rwopt[monto]*0.50)){$flujo=100;$color="#0099FF";$imagen="checkv_16.png";}else{$flujo=0;}
						//Calificación garantía
						if($rwanalisis[GAR_valor]>=$rwopt[monto]){$garantia=100;}else{$garantia=0;}
						//Calificación listas
						if($rwanalisis[LIS_listas_empresa]==0&&$rwanalisis[LIS_listas_representante]==0&&$rwanalisis[LIS_listas_accionista]==0&&$rwanalisis[LIS_listas_garante]==0&&$rwanalisis[LIS_google_empresa]==0&&$rwanalisis[LIS_google_representante]==0&&$rwanalisis[LIS_google_accionista]==0&&$rwanalisis[LIS_google_garante]==0){$listas=100;}else{$listas=0;}
						//Calificación final
						if($poderes==100&&$historial==100&&$flujo==100&&$garantia==100&&$listas==100){$final=100;}else{$final=0;}
					}
					
					if($rwopt[id_responsable]!=$responsable)
					{
						?>
                        <div id="alerta" style="margin-bottom: 0px; padding: 3px 3px 3px 3px; background-color: #FFf2f2; width: 100%; line-height: 20px; border-radius: 3px;">
                    <img src="../../images/exclamation.png" class="linkImage"/><span style='color:#FF0000; font-size:10px;'>No puedes realizar cambios en esta etapa del proceso</span>
                    </div>
                        <?php
					}

					?>
                        <div class="acc" style="width:100%;">
                        	
                            <h2 class="acc_trigger" style="width:100%;"><a href="#"><span class="highlight" style="background-color:#eee; font-weight:normal; width:100%; color:#333;"><img src='../../images/analisis_16.png' class='linkImage' />Análisis de Crédito</span></a></h2>
                            <div class="acc_container" style="width:100%;">
                                <div class="block">
                                	<?php
                                    if($responsable=="3")//Se muestra evaluación sólo si el usuario es de Dirección
                                    {
                                    ?>
                                    <table style="width:100%; margin-bottom:5px;">
                                        <tr>
                                        <td class="list-column-center" style="width:40%; padding-right:5px;">
                                        <div class="roundedpanel" style="height:55px; background-color:#E0F2FC; ?>;">
                                        
                                        <table style="width:100%; margin-bottom:5px; height:55px;">
                                        <tr>
                                        <td class="list-column-center" style="padding-right:5px;">
                                            <span style="font-size:10px;">Monto</span><br /><b style="font-size:13px;"><?php echo "$".number_format($rwopt[monto],2); ?></b>
                                        </td>
                                        <td class="list-column-center" style="padding-right:5px;">
                                            <span style="font-size:10px;">Plazo</span><br /><b style="font-size:13px;"><?php echo $rwopt[plazo_credito]; ?> meses</b>
                                        </td>
                                        <td class="list-column-center" style="padding-right:5px;">
                                            <span style="font-size:10px;">Interés</span><br /><b style="font-size:13px;"><?php echo $rwopt[interes]; ?>%</b>
                                        </td>
                                        <td class="list-column-center" style="padding-right:5px;">
                                            <span style="font-size:10px;">Amortización</span><br /><b style="font-size:13px;"><?php echo "$".number_format($amortizacion,2); ?></b>
                                        </td>
                                        </tr>
                                        </table>
                                        </div>
                                        </td>
                                        
                                        <td class="list-column-center" style="width:10%; padding-right:5px;">
                                        <div class="roundedpanel" style="height:55px; background-color:#<?php echo $color[$myrowetapa[numero_etapa]]; ?>;">
                                            <div class="roundedpanel-content" title="Poderes"><span style="font-size:10px; color:#000;">Poderes</span><br /><img src="../../images/<?php if($poderes==100){echo $aprobado;} else{echo $rechazado;}?>" class="entry-image"/></div>
                                        </div>
                                        </td>
                                        <td class="list-column-center" style="width:10%; padding-right:5px;">
                                        <div class="roundedpanel" style="height:55px; background-color:#<?php echo $color[$myrowetapa[numero_etapa]]; ?>;">
                                            <div class="roundedpanel-content" title="Historial Crediticio e Incidencias legales"><span style="font-size:10px; color:#000;">Historial</span><br /><img src="../../images/<?php if($historial==100){echo $aprobado;} else{echo $rechazado;}?>" /></div>
                                        </div>
                                        </td>
                                        <td class="list-column-center" style="width:10%; padding-right:5px;">
                                        <div class="roundedpanel" style="height:55px; background-color:#<?php echo $color[$myrowetapa[numero_etapa]]; ?>;">
                                            <div class="roundedpanel-content" title="Flujo"><span style="font-size:10px; color:#000;">Flujo</span><br /><img src="../../images/<?php if($flujo==100){echo $aprobado;} else{echo $rechazado;}?>" /></div>
                                        </div>
                                        </td>
                                        <td class="list-column-center" style="width:10%; padding-right:5px;">
                                        <div class="roundedpanel" style="height:55px; background-color:#<?php echo $color[$myrowetapa[numero_etapa]]; ?>;">
                                            <div class="roundedpanel-content" title="Garantía"><span style="font-size:10px; color:#000;">Garantía</span><br /><img src="../../images/<?php if($garantia==100){echo $aprobado;} else{echo $rechazado;}?>" /></div>
                                        </div>
                                        </td>
                                        <td class="list-column-center" style="width:10%; padding-right:5px;">
                                        <div class="roundedpanel" style="height:55px;">
                                            <div class="roundedpanel-content" title="<?php echo $myrowetapa[etapa]." (".$myrowetapa[probabilidad]."%)"; ?>"><span style="font-size:10px; color:#000;">Listas</span><br /><img src="../../images/<?php if($listas==100){echo $aprobado;} else{echo $rechazado;}?>" /></div>
                                        </div>
                                        </td>
                                        <td class="list-column-center" style="width:16%; padding-right:5px;">
                                        <div class="roundedpanel" style="height:55px;">
                                            <div class="roundedpanel-content" title="<?php if($final==100){echo "Aprobado";} else{echo "Rechazado";}?>"><img src="../../images/<?php if($final==100){echo "checkv_32.png";} else{echo "reject_20.png";}?>" /><br /></div>
                                        </div>
                                        </td>
                                        </tr>
                                    </table>
                                    
                                    <?php
									}
									
                                    ?>
                                    <!--FORMULARIO DE ANALISIS DE CRÉDITO: ACTUALIZACIÓN-->
                                    <div id="stylized" class="myform">
                                    <form id="form" name="form" method="post" action="insert.php" enctype="multipart/form-data">
                                    
                                    <h1>Poderes</h1>
                                    <p>Información sobre poderes</p>
                                    <table class="recordList" style="margin-top: 12px;">
                                    <thead>
                                    <tr>
                                    <th class="list-column-center" scope="col">¿La empresa tiene facultades para obligarse?</th>
                                    <th class="list-column-center" scope="col">¿El representante tiene poderes para firmar titulos de crédito?</th>
                                    <th class="list-column-center" scope="col"><span class="list-column-left">¿El garante tiene facultades?</span></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="odd-row">
                                    <td class="list-column-center"><select name="POD_facultades_empresa" id="POD_facultades_empresa">
                                    <option value="1" <?php if($rwanalisis[POD_facultades_empresa]=="1"){echo "selected";}?> >Sí</option>
                                    <option value="0" <?php if($rwanalisis[POD_facultades_empresa]=="0"){echo "selected";}?> >No</option>
                                    </select></td>
                                    <td class="list-column-center"><select name="POD_poderes_representante" id="POD_poderes_representante">
                                    <option value="1" <?php if($rwanalisis[POD_poderes_representante]=="1"){echo "selected";}?> >Sí</option>
                                    <option value="0" <?php if($rwanalisis[POD_poderes_representante]=="0"){echo "selected";}?> >No</option>
                                    </select></td>
                                    <td class="list-column-center"><select name="POD_facultades_garante" id="POD_facultades_garante">
                                    <option value="1" <?php if($rwanalisis[POD_facultades_garante]=="1"){echo "selected";}?> >Sí</option>
                                    <option value="0" <?php if($rwanalisis[POD_facultades_garante]=="0"){echo "selected";}?> >No</option>
                                    </select></td>
                                    </tr>
                                    </tbody>
                                    </table>
                                    <div class="spacer"></div>
                                    
                                    
                                    <h1>Historial Crediticio, e incidencias legales</h1>
                                    <p>Se obtiene 100 de calificación si no hay coincidencias en el buró de crédito, incidencias legales y solo si hay saldo vigente</p>
                                    <table class="recordList" style="margin-top: 12px;">
                                    <thead>
                                    <tr>
                                    <th class="list-column-center" scope="col">Buró de crédito</th>
                                    <th class="list-column-center" scope="col">Original</th>
                                    <th class="list-column-center" scope="col">Pago puntual</th>
                                    <th class="list-column-center" scope="col">Saldo vigente</th>
                                    <th class="list-column-center" scope="col">1 a 29 días</th>
                                    <th class="list-column-center" scope="col">30 a 89 días</th>
                                    <th class="list-column-center" scope="col">mayor a 90</th>
                                    <th class="list-column-center" scope="col">Calificación</th>
                                    <th class="list-column-center" scope="col">Máximo atraso</th>
                                    <th class="list-column-center" scope="col">Mes</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="odd-row">
                                    <td class="list-column-left"> Solicitante</td>
                                    <td class="list-column-center"><input type="text" class="input2" name="HIS_original_solicitante" id="HIS_original_solicitante" value="<?php echo $rwanalisis[HIS_original_solicitante];?>"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="HIS_puntual_solicitante" id="HIS_puntual_solicitante" value="<?php echo $rwanalisis[HIS_puntual_solicitante];?>"/></td>
                                    <td class="list-column-center"><input name="HIS_vigente_solicitante" type="text" class="input2" id="HIS_vigente_solicitante" value="<?php echo $rwanalisis[HIS_vigente_solicitante];?>"/></td>
                                    <td class="list-column-center"><input name="HIS_29_solicitante" type="text" class="input2" id="HIS_29_solicitante" value="<?php echo $rwanalisis[HIS_29_solicitante];?>"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="HIS_89_solicitante" id="HIS_89_solicitante" value="<?php echo $rwanalisis[HIS_89_solicitante];?>"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="HIS_90_solicitante" id="HIS_90_solicitante" value="<?php echo $rwanalisis[HIS_90_solicitante];?>"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="HIS_calificacion_solicitante" id="HIS_calificacion_solicitante" value="<?php echo $rwanalisis[HIS_calificacion_solicitante];?>"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="HIS_maximo_solicitante" id="HIS_maximo_solicitante" value="<?php echo $rwanalisis[HIS_maximo_solicitante];?>"/></td>
                                    <td class="list-column-center"><select name="HIS_mes_solicitante" id="HIS_mes_solicitante" class="select2">
                                    <option value="0"  <?php if($rwanalisis[HIS_mes_solicitante]==""){echo "selected";}?> >Mes</option>
                                    <option value="01" <?php if($rwanalisis[HIS_mes_solicitante]=="1"){echo "selected";}?> >Enero</option>
                                    <option value="02" <?php if($rwanalisis[HIS_mes_solicitante]=="2"){echo "selected";}?> >Febrero</option>
                                    <option value="03" <?php if($rwanalisis[HIS_mes_solicitante]=="3"){echo "selected";}?> >Marzo</option>
                                    <option value="04" <?php if($rwanalisis[HIS_mes_solicitante]=="4"){echo "selected";}?> >Abril</option>
                                    <option value="05" <?php if($rwanalisis[HIS_mes_solicitante]=="5"){echo "selected";}?> >Mayo</option>
                                    <option value="06" <?php if($rwanalisis[HIS_mes_solicitante]=="6"){echo "selected";}?> >Junio</option>
                                    <option value="07" <?php if($rwanalisis[HIS_mes_solicitante]=="7"){echo "selected";}?> >Julio</option>
                                    <option value="08" <?php if($rwanalisis[HIS_mes_solicitante]=="8"){echo "selected";}?> >Agosto</option>
                                    <option value="09" <?php if($rwanalisis[HIS_mes_solicitante]=="9"){echo "selected";}?> >Septiembre</option>
                                    <option value="10" <?php if($rwanalisis[HIS_mes_solicitante]=="10"){echo "selected";}?> >Octubre</option>
                                    <option value="11" <?php if($rwanalisis[HIS_mes_solicitante]=="11"){echo "selected";}?> >Noviembre</option>
                                    <option value="12" <?php if($rwanalisis[HIS_mes_solicitante]=="12"){echo "selected";}?> >Diciembre</option>
                                    </select></td>
                                    </tr>
                                    <tr>
                                    <td>Buró del solicitante</td>
                                    <td colspan="9"><input name="HIS_buro_solicitante" type="file" class="input4" style="margin-left:0px; width:780px;" id="HIS_buro_solicitante"/></td>
                                </tr>
                                    <tr class="odd-row">
                                    <td class="list-column-left">Representante Legal</td>
                                    <td class="list-column-center"><input type="text" class="input2" name="HIS_original_representante" id="HIS_original_representante" value="<?php echo $rwanalisis[HIS_original_representante];?>"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="HIS_puntual_representante" id="HIS_puntual_representante" value="<?php echo $rwanalisis[HIS_puntual_representante];?>"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="HIS_vigente_representante" id="HIS_vigente_representante" value="<?php echo $rwanalisis[HIS_vigente_representante];?>"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="HIS_29_representante" id="HIS_29_representante"value="<?php echo $rwanalisis[HIS_29_representante];?>" /></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="HIS_89_representante" id="HIS_89_representante"value="<?php echo $rwanalisis[HIS_89_representante];?>" /></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="HIS_90_representante" id="HIS_90l_representante" value="<?php echo $rwanalisis[HIS_90_representante];?>"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="HIS_calificacion_representante" id="HIS_calificacion_representante" value="<?php echo $rwanalisis[HIS_calificacion_representante];?>"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="HIS_maximo_representante" id="HIS_maximo_representante" value="<?php echo $rwanalisis[HIS_maximo_representante];?>"/></td>
                                    <td class="list-column-center"><select name="HIS_mes_representante" id="HIS_mes_representante" class="select2">
                                    <option value="0"  <?php if($rwanalisis[HIS_mes_representante]=="0"){echo "selected";}?> >Mes</option>
                                    <option value="01" <?php if($rwanalisis[HIS_mes_representante]=="1"){echo "selected";}?> >Enero</option>
                                    <option value="02" <?php if($rwanalisis[HIS_mes_representante]=="2"){echo "selected";}?> >Febrero</option>
                                    <option value="03" <?php if($rwanalisis[HIS_mes_representante]=="3"){echo "selected";}?> >Marzo</option>
                                    <option value="04" <?php if($rwanalisis[HIS_mes_representante]=="4"){echo "selected";}?> >Abril</option>
                                    <option value="05" <?php if($rwanalisis[HIS_mes_representante]=="5"){echo "selected";}?> >Mayo</option>
                                    <option value="06" <?php if($rwanalisis[HIS_mes_representante]=="6"){echo "selected";}?> >Junio</option>
                                    <option value="07" <?php if($rwanalisis[HIS_mes_representante]=="7"){echo "selected";}?> >Julio</option>
                                    <option value="08" <?php if($rwanalisis[HIS_mes_representante]=="8"){echo "selected";}?> >Agosto</option>
                                    <option value="09" <?php if($rwanalisis[HIS_mes_representante]=="9"){echo "selected";}?> >Septiembre</option>
                                    <option value="10" <?php if($rwanalisis[HIS_mes_representante]=="10"){echo "selected";}?> >Octubre</option>
                                    <option value="11" <?php if($rwanalisis[HIS_mes_representante]=="11"){echo "selected";}?> >Noviembre</option>
                                    <option value="12" <?php if($rwanalisis[HIS_mes_representante]=="12"){echo "selected";}?> >Diciembre</option>
                                    </select></td>
                                    </tr>
                                    <tr>
                                    <td>Buró del representante</td>
                                    <td colspan="9"><input name="HIS_buro_representante" type="file" class="input4" style="margin-left:0px; width:780px;" id="HIS_buro_representante"/></td>
                                </tr>
                                    <tr class="odd-row">
                                    <td class="list-column-left">Principal Accionista</td>
                                    <td class="list-column-center"><input type="text" class="input2" name="HIS_original_accionista" id="HIS_original_accionista" value="<?php echo $rwanalisis[HIS_original_accionista];?>"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="HIS_puntual_accionista" id="HIS_puntual_accionista" value="<?php echo $rwanalisis[HIS_puntual_accionista];?>"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="HIS_vigente_accionista" id="HIS_vigente_accionista" value="<?php echo $rwanalisis[HIS_vigente_accionista];?>"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="HIS_29_accionista" id="HIS_29_accionista" value="<?php echo $rwanalisis[HIS_29_accionista];?>"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="HIS_89_accionista" id="HIS_89_accionista" value="<?php echo $rwanalisis[HIS_89_accionista];?>"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="HIS_90_accionista" id="HIS_90_accionista" value="<?php echo $rwanalisis[HIS_90_accionista];?>"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="HIS_calificacion_accionista" id="HIS_calificacion_accionista" value="<?php echo $rwanalisis[HIS_calificacion_accionista];?>"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="HIS_maximo_accionista" id="HIS_maximo_accionista" value="<?php echo $rwanalisis[HIS_maximo_accionista];?>"/></td>
                                    <td class="list-column-center"><select name="HIS_mes_accionista" id="HIS_mes_accionista" class="select2">
                                    <option value="0"  <?php if($rwanalisis[HIS_mes_accionista]=="0"){echo "selected";}?> >Mes</option>
                                    <option value="01" <?php if($rwanalisis[HIS_mes_accionista]=="1"){echo "selected";}?> >Enero</option>
                                    <option value="02" <?php if($rwanalisis[HIS_mes_accionista]=="2"){echo "selected";}?> >Febrero</option>
                                    <option value="03" <?php if($rwanalisis[HIS_mes_accionista]=="3"){echo "selected";}?> >Marzo</option>
                                    <option value="04" <?php if($rwanalisis[HIS_mes_accionista]=="4"){echo "selected";}?> >Abril</option>
                                    <option value="05" <?php if($rwanalisis[HIS_mes_accionista]=="5"){echo "selected";}?> >Mayo</option>
                                    <option value="06" <?php if($rwanalisis[HIS_mes_accionista]=="6"){echo "selected";}?> >Junio</option>
                                    <option value="07" <?php if($rwanalisis[HIS_mes_accionista]=="7"){echo "selected";}?> >Julio</option>
                                    <option value="08" <?php if($rwanalisis[HIS_mes_accionista]=="8"){echo "selected";}?> >Agosto</option>
                                    <option value="09" <?php if($rwanalisis[HIS_mes_accionista]=="9"){echo "selected";}?> >Septiembre</option>
                                    <option value="10" <?php if($rwanalisis[HIS_mes_accionista]=="10"){echo "selected";}?> >Octubre</option>
                                    <option value="11" <?php if($rwanalisis[HIS_mes_accionista]=="11"){echo "selected";}?> >Noviembre</option>
                                    <option value="12" <?php if($rwanalisis[HIS_mes_accionista]=="12"){echo "selected";}?> >Diciembre</option>
                                    </select></td>
                                    </tr>
                                    <tr>
                                    <td>Buró del accionista</td>
                                    <td colspan="9"><input name="HIS_buro_accionista" type="file" class="input4" style="margin-left:0px; width:780px;" id="HIS_buro_accionista"/></td>
                                    </tr>
                                    </tbody>
                                    </table>
                                    
                                    <table class="recordList" style="margin-top: 12px;">
                                    <tbody>
                                    <tr class="odd-row">
                                    <td class="list-column-left"><label>Solicitante<span class="small">Indicencias legales</span></label></td>
                                    <td class="list-column-left">
                                    <select name="HIS_incidencias_solicitante" id="HIS_incidencias_solicitante" style="width:130px;" onchange="habilitar(this.value,this.id);">
                                    <option value="0" <?php if($rwanalisis[HIS_incidencias_solicitante]=="0"){echo "selected";}?> >Sin coincidencias</option>
                                    <option value="1" <?php if($rwanalisis[HIS_incidencias_solicitante]=="1"){echo "selected";}?> >Hay coincidencias</option>
                                    </select></td>
                                    <td class="list-column-left"><input type="text" style="width:592px;" name="HIS_incidencias_solicitante_detalle" id="HIS_incidencias_solicitante_detalle" value="<?php echo $rwanalisis[HIS_incidencias_solicitante_detalle];?>" <?php if($rwanalisis[HIS_incidencias_solicitante]=="0"){echo "disabled='disabled'";} ?>/></td>
                                    </tr>
                                    <tr class="odd-row">
                                    <td class="list-column-left"><label>Representante Legal<span class="small">Incidencias legales</span></label></td>
                                    <td class="list-column-left"><select name="HIS_incidencias_representante" id="HIS_incidencias_representante" style="width:130px;" onchange="habilitar(this.value,this.id);">
                                    <option value="0" <?php if($rwanalisis[HIS_incidencias_representante]=="0"){echo "selected";}?> >Sin coincidencias</option>
                                    <option value="1" <?php if($rwanalisis[HIS_incidencias_representante]=="1"){echo "selected";}?> >Hay coincidencias</option>
                                    </select></td>
                                    <td class="list-column-left"><input type="text" style="width:592px;" name="HIS_incidencias_representante_detalle" id="HIS_incidencias_representante_detalle" value="<?php echo $rwanalisis[HIS_incidencias_representante_detalle];?>" <?php if($rwanalisis[HIS_incidencias_representante]=="0"){echo "disabled='disabled'";} ?>/></td>
                                    </tr>
                                    <tr class="odd-row">
                                    <td class="list-column-left"><label>Principal Accionista<span class="small">Incidencias legales</span></label></td>
                                    <td class="list-column-left"><select name="HIS_incidencias_accionista" id="HIS_incidencias_accionista" style="width:130px;" onchange="habilitar(this.value,this.id);">
                                    <option value="0" <?php if($rwanalisis[HIS_incidencias_accionista]=="0"){echo "selected";}?> >Sin coincidencias</option>
                                    <option value="1" <?php if($rwanalisis[HIS_incidencias_accionista]=="1"){echo "selected";}?> >Hay coincidencias</option>
                                    </select></td>
                                    <td class="list-column-left"><input type="text" style="width:592px;" name="HIS_incidencias_accionista_detalle" id="HIS_incidencias_accionista_detalle" value="<?php echo $rwanalisis[HIS_incidencias_accionista_detalle];?>" <?php if($rwanalisis[HIS_incidencias_accionista]=="0"){echo "disabled='disabled'";} ?>/></td>
                                    </tr>
                                    </tbody>
                                    </table>
                                    
                                    <div class="spacer"></div>
                                    <div class="spacer"></div>
                                    
                                    <h1>Flujo</h1>
                                    <p>Se obtiene 100 si el promedio de los depósitos es mayor o igual al crédito solicitado y el saldo promedio es igual o mayor a la amortización que pagará de aprobarse el crédito.</p>
                                    
                                    <label>Cuenta<span class="small">Sólo números</span></label>
                                    <input type="text" name="FLU_cuenta" id="FLU_cuenta" value="<?php echo $rwanalisis[FLU_cuenta]; ?>"/>
                                    <label>Banco</label>
                                    <select name="FLU_banco" id="FLU_banco">
                                    <option value="" <?php if($rwanalisis[FLU_banco]==""){echo "selected";}?> >Seleccione</option>
                                    <?php
                                    //Llenar Select de Bancos		
                                    $sqlbanco="SELECT * FROM bancos ORDER BY `banco` ASC";
                                    $resultbanco= mysql_query ($sqlbanco,$db);
                                    while($myrowbanco=mysql_fetch_array($resultbanco))
                                    {
                                        ?>
                                        <option value="<?php echo $myrowbanco[banco]; ?>" <?php if($rwanalisis[FLU_banco]==$myrowbanco[banco]){echo "selected";}?>><?php echo $myrowbanco[banco]; ?></option>
                                        <?php
                                    }
                                    ?>
                                    </select>
                                    
                                    <div class="spacer"></div>
                                    
                                    <table class="recordList" style="margin-top: 12px;">
                                    <thead>
                                    <tr>
                                    <th class="list-column-center" scope="col"></th>
                                    <th class="list-column-center" scope="col">
                                    <select name="FLU_mes1" id="FLU_mes1" class="select2">
                                    <option value="0"  <?php if($rwanalisis[FLU_mes1]=="0"){echo "selected";}?> >Mes</option>
                                    <option value="01" <?php if($rwanalisis[FLU_mes1]=="1"){echo "selected";}?> >Enero</option>
                                    <option value="02" <?php if($rwanalisis[FLU_mes1]=="2"){echo "selected";}?> >Febrero</option>
                                    <option value="03" <?php if($rwanalisis[FLU_mes1]=="3"){echo "selected";}?> >Marzo</option>
                                    <option value="04" <?php if($rwanalisis[FLU_mes1]=="4"){echo "selected";}?> >Abril</option>
                                    <option value="05" <?php if($rwanalisis[FLU_mes1]=="5"){echo "selected";}?> >Mayo</option>
                                    <option value="06" <?php if($rwanalisis[FLU_mes1]=="6"){echo "selected";}?> >Junio</option>
                                    <option value="07" <?php if($rwanalisis[FLU_mes1]=="7"){echo "selected";}?> >Julio</option>
                                    <option value="08" <?php if($rwanalisis[FLU_mes1]=="8"){echo "selected";}?> >Agosto</option>
                                    <option value="09" <?php if($rwanalisis[FLU_mes1]=="9"){echo "selected";}?> >Septiembre</option>
                                    <option value="10" <?php if($rwanalisis[FLU_mes1]=="10"){echo "selected";}?> >Octubre</option>
                                    <option value="11" <?php if($rwanalisis[FLU_mes1]=="11"){echo "selected";}?> >Noviembre</option>
                                    <option value="12" <?php if($rwanalisis[FLU_mes1]=="12"){echo "selected";}?> >Diciembre</option>
                                    </select></th>
                                    <th class="list-column-center" scope="col">
                                    <select name="FLU_mes2" id="FLU_mes2" class="select2">
                                    <option value="0"  <?php if($rwanalisis[FLU_mes2]=="0"){echo "selected";}?> >Mes</option>
                                    <option value="01" <?php if($rwanalisis[FLU_mes2]=="1"){echo "selected";}?> >Enero</option>
                                    <option value="02" <?php if($rwanalisis[FLU_mes2]=="2"){echo "selected";}?> >Febrero</option>
                                    <option value="03" <?php if($rwanalisis[FLU_mes2]=="3"){echo "selected";}?> >Marzo</option>
                                    <option value="04" <?php if($rwanalisis[FLU_mes2]=="4"){echo "selected";}?> >Abril</option>
                                    <option value="05" <?php if($rwanalisis[FLU_mes2]=="5"){echo "selected";}?> >Mayo</option>
                                    <option value="06" <?php if($rwanalisis[FLU_mes2]=="6"){echo "selected";}?> >Junio</option>
                                    <option value="07" <?php if($rwanalisis[FLU_mes2]=="7"){echo "selected";}?> >Julio</option>
                                    <option value="08" <?php if($rwanalisis[FLU_mes2]=="8"){echo "selected";}?> >Agosto</option>
                                    <option value="09" <?php if($rwanalisis[FLU_mes2]=="9"){echo "selected";}?> >Septiembre</option>
                                    <option value="10" <?php if($rwanalisis[FLU_mes2]=="10"){echo "selected";}?> >Octubre</option>
                                    <option value="11" <?php if($rwanalisis[FLU_mes2]=="11"){echo "selected";}?> >Noviembre</option>
                                    <option value="12" <?php if($rwanalisis[FLU_mes2]=="12"){echo "selected";}?> >Diciembre</option>
                                    </select></th>
                                    <th class="list-column-center" scope="col">
                                    <select name="FLU_mes3" id="FLU_mes3" class="select2">
                                    <option value="0"  <?php if($rwanalisis[FLU_mes3]=="0"){echo "selected";}?> >Mes</option>
                                    <option value="01" <?php if($rwanalisis[FLU_mes3]=="1"){echo "selected";}?> >Enero</option>
                                    <option value="02" <?php if($rwanalisis[FLU_mes3]=="2"){echo "selected";}?> >Febrero</option>
                                    <option value="03" <?php if($rwanalisis[FLU_mes3]=="3"){echo "selected";}?> >Marzo</option>
                                    <option value="04" <?php if($rwanalisis[FLU_mes3]=="4"){echo "selected";}?> >Abril</option>
                                    <option value="05" <?php if($rwanalisis[FLU_mes3]=="5"){echo "selected";}?> >Mayo</option>
                                    <option value="06" <?php if($rwanalisis[FLU_mes3]=="6"){echo "selected";}?> >Junio</option>
                                    <option value="07" <?php if($rwanalisis[FLU_mes3]=="7"){echo "selected";}?> >Julio</option>
                                    <option value="08" <?php if($rwanalisis[FLU_mes3]=="8"){echo "selected";}?> >Agosto</option>
                                    <option value="09" <?php if($rwanalisis[FLU_mes3]=="9"){echo "selected";}?> >Septiembre</option>
                                    <option value="10" <?php if($rwanalisis[FLU_mes3]=="10"){echo "selected";}?> >Octubre</option>
                                    <option value="11" <?php if($rwanalisis[FLU_mes3]=="11"){echo "selected";}?> >Noviembre</option>
                                    <option value="12" <?php if($rwanalisis[FLU_mes3]=="12"){echo "selected";}?> >Diciembre</option>
                                    </select></th>
                                    <th class="list-column-center" scope="col"><select name="FLU_mes4" id="FLU_mes4" class="select2">
                                    <option value="0"  <?php if($rwanalisis[FLU_mes4]=="0"){echo "selected";}?> >Mes</option>
                                    <option value="01" <?php if($rwanalisis[FLU_mes4]=="1"){echo "selected";}?> >Enero</option>
                                    <option value="02" <?php if($rwanalisis[FLU_mes4]=="2"){echo "selected";}?> >Febrero</option>
                                    <option value="03" <?php if($rwanalisis[FLU_mes4]=="3"){echo "selected";}?> >Marzo</option>
                                    <option value="04" <?php if($rwanalisis[FLU_mes4]=="4"){echo "selected";}?> >Abril</option>
                                    <option value="05" <?php if($rwanalisis[FLU_mes4]=="5"){echo "selected";}?> >Mayo</option>
                                    <option value="06" <?php if($rwanalisis[FLU_mes4]=="6"){echo "selected";}?> >Junio</option>
                                    <option value="07" <?php if($rwanalisis[FLU_mes4]=="7"){echo "selected";}?> >Julio</option>
                                    <option value="08" <?php if($rwanalisis[FLU_mes4]=="8"){echo "selected";}?> >Agosto</option>
                                    <option value="09" <?php if($rwanalisis[FLU_mes4]=="9"){echo "selected";}?> >Septiembre</option>
                                    <option value="10" <?php if($rwanalisis[FLU_mes4]=="10"){echo "selected";}?> >Octubre</option>
                                    <option value="11" <?php if($rwanalisis[FLU_mes4]=="11"){echo "selected";}?> >Noviembre</option>
                                    <option value="12" <?php if($rwanalisis[FLU_mes4]=="12"){echo "selected";}?> >Diciembre</option>
                                    </select></th>
                                    <th class="list-column-center" scope="col"><select name="FLU_mes5" id="FLU_mes5" class="select2">
                                    <option value="0"  <?php if($rwanalisis[FLU_mes5]=="0"){echo "selected";}?> >Mes</option>
                                    <option value="01" <?php if($rwanalisis[FLU_mes5]=="1"){echo "selected";}?> >Enero</option>
                                    <option value="02" <?php if($rwanalisis[FLU_mes5]=="2"){echo "selected";}?> >Febrero</option>
                                    <option value="03" <?php if($rwanalisis[FLU_mes5]=="3"){echo "selected";}?> >Marzo</option>
                                    <option value="04" <?php if($rwanalisis[FLU_mes5]=="4"){echo "selected";}?> >Abril</option>
                                    <option value="05" <?php if($rwanalisis[FLU_mes5]=="5"){echo "selected";}?> >Mayo</option>
                                    <option value="06" <?php if($rwanalisis[FLU_mes5]=="6"){echo "selected";}?> >Junio</option>
                                    <option value="07" <?php if($rwanalisis[FLU_mes5]=="7"){echo "selected";}?> >Julio</option>
                                    <option value="08" <?php if($rwanalisis[FLU_mes5]=="8"){echo "selected";}?> >Agosto</option>
                                    <option value="09" <?php if($rwanalisis[FLU_mes5]=="9"){echo "selected";}?> >Septiembre</option>
                                    <option value="10" <?php if($rwanalisis[FLU_mes5]=="10"){echo "selected";}?> >Octubre</option>
                                    <option value="11" <?php if($rwanalisis[FLU_mes5]=="11"){echo "selected";}?> >Noviembre</option>
                                    <option value="12" <?php if($rwanalisis[FLU_mes5]=="12"){echo "selected";}?> >Diciembre</option>
                                    </select></th>
                                    <th class="list-column-center" scope="col"><select name="FLU_mes6" id="FLU_mes6" class="select2">
                                    <option value="0"  <?php if($rwanalisis[FLU_mes6]=="0"){echo "selected";}?> >Mes</option>
                                    <option value="01" <?php if($rwanalisis[FLU_mes6]=="1"){echo "selected";}?> >Enero</option>
                                    <option value="02" <?php if($rwanalisis[FLU_mes6]=="2"){echo "selected";}?> >Febrero</option>
                                    <option value="03" <?php if($rwanalisis[FLU_mes6]=="3"){echo "selected";}?> >Marzo</option>
                                    <option value="04" <?php if($rwanalisis[FLU_mes6]=="4"){echo "selected";}?> >Abril</option>
                                    <option value="05" <?php if($rwanalisis[FLU_mes6]=="5"){echo "selected";}?> >Mayo</option>
                                    <option value="06" <?php if($rwanalisis[FLU_mes6]=="6"){echo "selected";}?> >Junio</option>
                                    <option value="07" <?php if($rwanalisis[FLU_mes6]=="7"){echo "selected";}?> >Julio</option>
                                    <option value="08" <?php if($rwanalisis[FLU_mes6]=="8"){echo "selected";}?> >Agosto</option>
                                    <option value="09" <?php if($rwanalisis[FLU_mes6]=="9"){echo "selected";}?> >Septiembre</option>
                                    <option value="10" <?php if($rwanalisis[FLU_mes6]=="10"){echo "selected";}?> >Octubre</option>
                                    <option value="11" <?php if($rwanalisis[FLU_mes6]=="11"){echo "selected";}?> >Noviembre</option>
                                    <option value="12" <?php if($rwanalisis[FLU_mes6]=="12"){echo "selected";}?> >Diciembre</option>
                                    </select></th>
                                    <th class="list-column-center" scope="col">Promedio</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="odd-row">
                                    <td class="list-column-left">Saldo Inicial</td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_inicial_mes1" id="FLU_inicial_mes1" value="<?php echo $rwanalisis[FLU_inicial_mes1];?>" onchange="saldo_inicial2();promedio_inicial();"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_inicial_mes2" id="FLU_inicial_mes2" value="<?php echo $rwanalisis[FLU_inicial_mes2];?>" onchange="saldo_inicial3();promedio_inicial();"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_inicial_mes3" id="FLU_inicial_mes3" value="<?php echo $rwanalisis[FLU_inicial_mes3];?>" onchange="saldo_inicial4();promedio_inicial();"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_inicial_mes4" id="FLU_inicial_mes4" value="<?php echo $rwanalisis[FLU_inicial_mes4];?>" onchange="saldo_inicial5();promedio_inicial();"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_inicial_mes5" id="FLU_inicial_mes5" value="<?php echo $rwanalisis[FLU_inicial_mes5];?>" onchange="promedio_inicial();"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_inicial_mes6" id="FLU_inicial_mes6" value="<?php echo $rwanalisis[FLU_inicial_mes6];?>"/></td>
                                    <td class="list-column-center"><input name="FLU_promedio_inicial" type="text" class="input2" id="FLU_promedio_inicial" value="<?php echo $rwanalisis[FLU_promedio_inicial];?>"/></td>
                                    </tr>
                                    <tr class="odd-row">
                                    <td class="list-column-left">Depósitos</td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_depositos_mes1" id="FLU_depositos_mes1" value="<?php echo $rwanalisis[FLU_depositos_mes1];?>" onchange="saldo_inicial2();promedio_depositos();"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_depositos_mes2" id="FLU_depositos_mes2" value="<?php echo $rwanalisis[FLU_depositos_mes2];?>" onchange="saldo_inicial3();promedio_depositos();"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_depositos_mes3" id="FLU_depositos_mes3" value="<?php echo $rwanalisis[FLU_depositos_mes3];?>" onchange="saldo_inicial4();promedio_depositos();"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_depositos_mes4" id="FLU_depositos_mes4" value="<?php echo $rwanalisis[FLU_depositos_mes4];?>" onchange="saldo_inicial5();promedio_depositos();"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_depositos_mes5" id="FLU_depositos_mes5" value="<?php echo $rwanalisis[FLU_depositos_mes5];?>" onchange="saldo_inicial6();promedio_depositos();"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_depositos_mes6" id="FLU_depositos_mes6" value="<?php echo $rwanalisis[FLU_depositos_mes6];?>"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_promedio_depositos" id="FLU_promedio_depositos" value="<?php echo $rwanalisis[FLU_promedio_depositos];?>"/></td>
                                    </tr>
                                    <tr class="odd-row">
                                    <td class="list-column-left">Retiros</td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_retiros_mes1" id="FLU_retiros_mes1" value="<?php echo $rwanalisis[FLU_retiros_mes1];?>" onchange="saldo_inicial2();promedio_retiros();"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_retiros_mes2" id="FLU_retiros_mes2" value="<?php echo $rwanalisis[FLU_retiros_mes2];?>" onchange="saldo_inicial3();promedio_retiros();"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_retiros_mes3" id="FLU_retiros_mes3" value="<?php echo $rwanalisis[FLU_retiros_mes3];?>" onchange="saldo_inicial4();promedio_retiros();"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_retiros_mes4" id="FLU_retiros_mes4" value="<?php echo $rwanalisis[FLU_retiros_mes4];?>" onchange="saldo_inicial5();promedio_retiros();"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_retiros_mes5" id="FLU_retiros_mes5" value="<?php echo $rwanalisis[FLU_retiros_mes5];?>" onchange="saldo_inicial6();promedio_retiros();"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_retiros_mes6" id="FLU_retiros_mes6" value="<?php echo $rwanalisis[FLU_retiros_mes6];?>"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_promedio_retiros" id="FLU_promedio_retiros" value="<?php echo $rwanalisis[FLU_promedio_retiros];?>"/></td>
                                    </tr>
                                    <tr class="odd-row">
                                    <td class="list-column-left">Saldo Promedio</td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_promedio_mes1" id="FLU_promedio_mes1" value="<?php echo $rwanalisis[FLU_promedio_mes1];?>" onchange="promedio_promedio();"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_promedio_mes2" id="FLU_promedio_mes2" value="<?php echo $rwanalisis[FLU_promedio_mes2];?>" onchange="promedio_promedio();"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_promedio_mes3" id="FLU_promedio_mes3" value="<?php echo $rwanalisis[FLU_promedio_mes3];?>" onchange="promedio_promedio();"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_promedio_mes4" id="FLU_promedio_mes4" value="<?php echo $rwanalisis[FLU_promedio_mes4];?>" onchange="promedio_promedio();"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_promedio_mes5" id="FLU_promedio_mes5" value="<?php echo $rwanalisis[FLU_promedio_mes5];?>" onchange="promedio_promedio();"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_promedio_mes6" id="FLU_promedio_mes6" value="<?php echo $rwanalisis[FLU_promedio_mes6];?>"/></td>
                                    <td class="list-column-center"><input type="text" class="input2" name="FLU_promedio_promedio_total" id="FLU_promedio_promedio_total" value="<?php echo $rwanalisis[FLU_promedio_promedio];?>"/></td>
                                    </tr>
                                    </tbody>
                                    </table>
                                    
                                    <div class="spacer"></div>
                                    
                                    <h1>Garantía</h1>
                                    <p>Se obtiene 100 si la garantía cubre por lo menos tres veces el valor del crédito solicitado</p>
                                    
                                    <label>Nombre del propietario<span class="small">del inmueble en garantía</span></label>
                                    <input type="text" style="width:460px;" name="GAR_propietario" id="GAR_propietario" value="<?php echo $rwanalisis[GAR_propietario];?>"/>
                                    <label>Relación con la empresa</label>
                                    <input type="text" name="GAR_relacion" id="GAR_relacion" value="<?php echo $rwanalisis[GAR_relacion];?>"/>
                                    <div class="spacer"></div>
                                    <label>Domicilio<span class="small">Calle, Número, Colonia</span></label>
                                    <input type="text" class="input4" name="GAR_domicilio" id="GAR_domicilio" value="<?php echo $rwanalisis[GAR_domicilio];?>"/>
                                    <div class="spacer"></div>
                                    <label>Delegación</label>
                                    <input type="text" name="GAR_ciudad" id="GAR_ciudad" value="<?php echo $rwanalisis[GAR_ciudad];?>"/>
                                    <label>Estado</label>
                                    <input type="text" name="GAR_estado" id="GAR_estado" value="<?php echo $rwanalisis[GAR_estado];?>"/>
                                    <label>Código Postal</label>
                                    <input type="text" name="GAR_cp" id="GAR_cp" value="<?php echo $rwanalisis[GAR_cp];?>"/>
                                    <!--<img src="../../images/maps_16.png" class="linkImage" /><a href="mapa.php?id=<?php echo $myrowopt[id_oportunidad]; ?>&o=I&a=oP&organizacion=<?php echo $claveorganizacion; ?>" title="Agregar una nota a la oportunidad" class="clsVentanaIFrame clsBoton" rel="Agregar Nota">Ver mapa</a>-->
                                    <div class="spacer"></div>
                                    <label>Metros de Construcción<span class="small">Sólo números</span></label>
                                    <input type="text" name="GAR_construccion" id="GAR_construccion" value="<?php echo $rwanalisis[GAR_construccion];?>"/>
                                    <label>Metros de Terreno<span class="small">Sólo números</span></label>
                                    <input type="text" name="GAR_terreno" id="GAR_terreno" value="<?php echo $rwanalisis[GAR_terreno];?>"/>
                                    <label>Valor estimado<span class="small">Sólo números</span></label>
                                    <input type="text" name="GAR_valor" id="GAR_valor" value="<?php echo $rwanalisis[GAR_valor];?>"/>
                                    <div class="spacer"></div>
                                    
                                    <h1>Listados</h1>
                                    <p>Se obtiene 100 si la garantía cubre por lo menos tres veces el valor del crédito solicitado</p>
                                    
                                    <table class="recordList" style="margin-top: 12px;">
                                    <thead>
                                    <tr>
                                    <th class="list-column-center" scope="col"><img src="../../images/quienesquien.png" alt="" width="120" height="22" /></th>
                                    <th class="list-column-center" scope="col">&nbsp;</th>
                                    <th class="list-column-center" scope="col">&nbsp;</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="odd-row">
                                    <td class="list-column-left">Empresa</td>
                                    <td class="list-column-center">
                                    <select name="LIS_listas_empresa" id="LIS_listas_empresa" onchange="habilitar(this.value,this.id);">
                                    <option value="0" <?php if($rwanalisis[LIS_listas_empresa]=="0"){echo "selected";}?> >Sin coincidencias</option>
                                    <option value="1" <?php if($rwanalisis[LIS_listas_empresa]=="1"){echo "selected";}?> >Hay coincidencias</option>
                                    </select></td>
                                    <td class="list-column-center">
                                    <input name="LIS_listas_empresa_detalle" type="text" <?php if($rwanalisis[LIS_listas_empresa]=="0"){echo "disabled='disabled'";} ?> id="LIS_listas_empresa_detalle" style="width:592px;" value="<?php echo $rwanalisis[LIS_listas_empresa_detalle];?>" /></td>
                                    </tr>
                                    <tr class="odd-row">
                                    <td class="list-column-left">Representante Legal</td>
                                    <td class="list-column-center"><select name="LIS_listas_representante" id="LIS_listas_representante" onchange="habilitar(this.value,this.id);">
                                    <option value="0" <?php if($rwanalisis[LIS_listas_representante]=="0"){echo "selected";}?> >Sin coincidencias</option>
                                    <option value="1" <?php if($rwanalisis[LIS_listas_representante]=="1"){echo "selected";}?> >Hay coincidencias</option>
                                    </select></td>
                                    <td class="list-column-center">
                                    <input name="LIS_listas_representante_detalle" type="text" <?php if($rwanalisis[LIS_listas_representante]=="0"){echo "disabled='disabled'";} ?> id="LIS_listas_representante_detalle" style="width:592px;" value="<?php echo $rwanalisis[LIS_listas_representante_detalle];?>"/></td>
                                    </tr>
                                    <tr class="odd-row">
                                    <td class="list-column-left">Principal Accionista</td>
                                    <td class="list-column-center"><select name="LIS_listas_accionista" id="LIS_listas_accionista" onchange="habilitar(this.value,this.id);">
                                    <option value="0" <?php if($rwanalisis[LIS_listas_accionista]=="0"){echo "selected";}?> >Sin coincidencias</option>
                                    <option value="1" <?php if($rwanalisis[LIS_listas_accionista]=="1"){echo "selected";}?> >Hay coincidencias</option>
                                    </select></td>
                                    <td class="list-column-center">
                                    <input name="LIS_listas_accionista_detalle" type="text" id="LIS_listas_accionista_detalle" style="width:592px;" value="<?php echo $rwanalisis[LIS_listas_accionista_detalle];?>" <?php if($rwanalisis[LIS_listas_accionista]=="0"){echo "disabled='disabled'";} ?>/></td>
                                    </tr>
                                    <tr class="odd-row">
                                    <td class="list-column-left">Garante</td>
                                    <td class="list-column-center"><select name="LIS_listas_garante" id="LIS_listas_garante" onchange="habilitar(this.value,this.id);">
                                    <option value="0" <?php if($rwanalisis[LIS_listas_garante]=="0"){echo "selected";}?> >Sin coincidencias</option>
                                    <option value="1" <?php if($rwanalisis[LIS_listas_garante]=="1"){echo "selected";}?> >Hay coincidencias</option>
                                    </select></td>
                                    <td class="list-column-center">
                                    <input name="LIS_listas_garante_detalle" type="text" id="LIS_listas_garante_detalle" style="width:592px;" value="<?php echo $rwanalisis[LIS_listas_garante_detalle];?>" <?php if($rwanalisis[LIS_listas_garante]=="0"){echo "disabled='disabled'";} ?>/></td>
                                    </tr>
                                    <tr class="odd-row">
                                    <td class="list-column-left">&nbsp;</td>
                                    <td class="list-column-center">&nbsp;</td>
                                    <td class="list-column-center">&nbsp;</td>
                                    </tr>
                                    <tr class="odd-row">
                                    <td class="list-column-left"><img src="../../images/google.png" alt="" width="120" height="22" /></td>
                                    <td class="list-column-center">&nbsp;</td>
                                    <td class="list-column-center">&nbsp;</td>
                                    </tr>
                                    <tr class="odd-row">
                                    <td class="list-column-left">Empresa</td>
                                    <td class="list-column-center"><select name="LIS_google_empresa" id="LIS_google_empresa" onchange="habilitar(this.value,this.id);">
                                    <option value="0" <?php if($rwanalisis[LIS_google_empresa]=="0"){echo "selected";}?> >Sin coincidencias</option>
                                    <option value="1" <?php if($rwanalisis[LIS_google_empresa]=="1"){echo "selected";}?> >Hay coincidencias</option>
                                    </select></td>
                                    
                                    
                                    
                                    <td class="list-column-center">
                                    <input name="LIS_google_empresa_detalle" type="text" <?php if($rwanalisis[LIS_google_empresa]=="0"){echo "disabled='disabled'";} ?> id="LIS_google_empresa_detalle" style="width:592px;" value="<?php echo $rwanalisis[LIS_google_empresa_detalle];?>"/></td>
                                    </tr>
                                    <tr class="odd-row">
                                    <td class="list-column-left">Representante Legal</td>
                                    <td class="list-column-center"><select name="LIS_google_representante" id="LIS_google_representante" onchange="habilitar(this.value,this.id);">
                                    <option value="0" <?php if($rwanalisis[LIS_google_representante]=="0"){echo "selected";}?> >Sin coincidencias</option>
                                    <option value="1" <?php if($rwanalisis[LIS_google_representante]=="1"){echo "selected";}?> >Hay coincidencias</option>
                                    </select></td>
                                    <td class="list-column-center">
                                    <input name="LIS_google_representante_detalle" type="text" id="LIS_google_representante_detalle" style="width:592px;" value="<?php echo $rwanalisis[LIS_google_representante_detalle];?>" <?php if($rwanalisis[LIS_google_representante]=="0"){echo "disabled='disabled'";} ?>/></td>
                                    </tr>
                                    <tr class="odd-row">
                                    <td class="list-column-left">Principal Accionista</td>
                                    <td class="list-column-center"><select name="LIS_google_accionista" id="LIS_google_accionista" onchange="habilitar(this.value,this.id);">
                                    <option value="0" <?php if($rwanalisis[LIS_google_accionista]=="0"){echo "selected";}?> >Sin coincidencias</option>
                                    <option value="1" <?php if($rwanalisis[LIS_google_accionista]=="1"){echo "selected";}?> >Hay coincidencias</option>
                                    </select></td>
                                    <td class="list-column-center">
                                    <input name="LIS_google_accionista_detalle" type="text" id="LIS_google_accionista_detalle" style="width:592px;" value="<?php echo $rwanalisis[LIS_google_accionista_detalle];?>" <?php if($rwanalisis[LIS_google_accionista]=="0"){echo "disabled='disabled'";} ?>/></td>
                                    </tr>
                                    <tr class="odd-row">
                                    <td class="list-column-left">Garante</td>
                                    <td class="list-column-center"><select name="LIS_google_garante" id="LIS_google_garante" onchange="habilitar(this.value,this.id);">
                                    <option value="0" <?php if($rwanalisis[LIS_google_garante]=="0"){echo "selected";}?> >Sin coincidencias</option>
                                    <option value="1" <?php if($rwanalisis[LIS_google_garante]=="1"){echo "selected";}?>  >Hay coincidencias</option>
                                    </select></td>
                                    <td class="list-column-center">
                                    <input name="LIS_google_garante_detalle" type="text" id="LIS_google_garante_detalle" style="width:592px;" value="<?php echo $rwanalisis[LIS_google_garante_detalle];?>" <?php if($rwanalisis[LIS_google_garante]=="0"){echo "disabled='disabled'";} ?>/></td>
                                    </tr>
                                    </tbody>
                                    </table>
                                    
                                    <div class="spacer"></div>
                                    
                                    
                                    <div class="spacer"></div>
                                    
                                    <h1>Otros</h1>
                                    <p>Otra información de utilidad</p>
                                    <div class="spacer"></div>
                                    <label>Comentarios</label>
                                    <textarea name="OTR_comentarios" id="OTR_comentarios" cols="45" rows="5" class="input4"><?php echo $rwanalisis[OTR_comentarios];?></textarea>
                                    <div class="spacer"></div>
                                    
                                    
                                    <button type="submit">Grabar Análisis</button>
                                    <div class="spacer"></div>
                                    <input type="hidden" name="organizacion" id="organizacion"  value="<?php echo $_GET[organizacion]; ?>" /><!--organizacion-->
                                    <input type="hidden" name="oportunidad" id="oportunidad"  value="<?php echo $_GET[id]; ?>" /><!--oportunidad-->
                                    <input type="hidden" name="an" id="an"  value="<?php echo $_GET[an]; ?>" /><!--análisis-->
                                    <input type="hidden" name="a" id="a"  value="<?php echo $_GET[a]; ?>" /><!-- archivo: Oportunidades organizaciones -->
                                    <input type="hidden" name="o" id="o"  value="<?php echo $o; ?>" /><!-- operación: Update -->
                                    </form><!--FIN DE FORMULARIO DE ANÁLISIS DE CRÉDITO: ACTUALIZACIÓN-->
                                    </div> 
                                </div>
                            </div>
                            <?php
							
							$sqlrol="SELECT * FROM etapas WHERE id_etapa = '".$rwopt[id_etapa]."'";
							$rsrol = mysql_query($sqlrol, $db);
							$rwrol = mysql_fetch_array($rsrol);
							if($rwrol[id_responsable]!=$responsable)
							{
								echo "<script type='text/javascript'>disablefunction()</script>";
							}
							
                            if($responsable=="3")//Si el usuario es Dirección mostrar opción para modificar proceso de crédito
                            {
                            ?>    
                            <h2 class="acc_trigger" style="width:100%;"><a href="#"><span class="highlight" style="background-color:#eee; font-weight:normal; width:100%; color:#333;"><img src='../../images/refresh_16.png' class='linkImage' />Actualizar Proceso de Crédito</span></a></h2>
                            <div class="acc_container" style="width:100%;">
                                <div class="block">
                                <!--CALIFICACIÓN DEL PROCESO-->
                                <table style="width:100%; margin-bottom:5px;">
                                <tr>
                                <td class="list-column-center" style="width:40%; padding-right:5px;">
                                <div class="roundedpanel" style="height:55px; background-color:#E0F2FC; ?>;">
                                
                                <table style="width:100%; margin-bottom:5px; height:55px;">
                                <tr>
                                <td class="list-column-center" style="padding-right:5px;">
                                    <span style="font-size:10px;">Monto</span><br /><b style="font-size:13px;"><?php echo "$".number_format($rwopt[monto],2); ?></b>
                                </td>
                                <td class="list-column-center" style="padding-right:5px;">
                                    <span style="font-size:10px;">Plazo</span><br /><b style="font-size:13px;"><?php echo $rwopt[plazo_credito]; ?> meses</b>
                                </td>
                                <td class="list-column-center" style="padding-right:5px;">
                                    <span style="font-size:10px;">Interés</span><br /><b style="font-size:13px;"><?php echo $rwopt[interes]; ?>%</b>
                                </td>
                                <td class="list-column-center" style="padding-right:5px;">
                                    <span style="font-size:10px;">Amortización</span><br /><b style="font-size:13px;"><?php echo "$".number_format($amortizacion,2); ?></b>
                                </td>
                                </tr>
                                </table>
                                  </div>
                                  </td>
                                    
                                    <td class="list-column-center" style="width:10%; padding-right:5px;">
                                    <div class="roundedpanel" style="height:55px; background-color:#<?php echo $color[$myrowetapa[numero_etapa]]; ?>;">
                                        <div class="roundedpanel-content" title="Poderes"><span style="font-size:10px; color:#000;">Poderes</span><br /><img src="../../images/<?php if($poderes==100){echo $aprobado;} else{echo $rechazado;}?>" class="entry-image"/></div>
                                    </div>
                                    </td>
                                    <td class="list-column-center" style="width:10%; padding-right:5px;">
                                    <div class="roundedpanel" style="height:55px; background-color:#<?php echo $color[$myrowetapa[numero_etapa]]; ?>;">
                                        <div class="roundedpanel-content" title="Historial Crediticio e Incidencias legales"><span style="font-size:10px; color:#000;">Historial</span><br /><img src="../../images/<?php if($historial==100){echo $aprobado;} else{echo $rechazado;}?>" /></div>
                                    </div>
                                    </td>
                                    <td class="list-column-center" style="width:10%; padding-right:5px;">
                                    <div class="roundedpanel" style="height:55px; background-color:#<?php echo $color[$myrowetapa[numero_etapa]]; ?>;">
                                        <div class="roundedpanel-content" title="Flujo"><span style="font-size:10px; color:#000;">Flujo</span><br /><img src="../../images/<?php if($flujo==100){echo $aprobado;} else{echo $rechazado;}?>" /></div>
                                    </div>
                                    </td>
                                    <td class="list-column-center" style="width:10%; padding-right:5px;">
                                    <div class="roundedpanel" style="height:55px; background-color:#<?php echo $color[$myrowetapa[numero_etapa]]; ?>;">
                                        <div class="roundedpanel-content" title="Garantía"><span style="font-size:10px; color:#000;">Garantía</span><br /><img src="../../images/<?php if($garantia==100){echo $aprobado;} else{echo $rechazado;}?>" /></div>
                                    </div>
                                    </td>
                                    <td class="list-column-center" style="width:10%; padding-right:5px;">
                                    <div class="roundedpanel" style="height:55px;">
                                        <div class="roundedpanel-content" title="<?php echo $myrowetapa[etapa]." (".$myrowetapa[probabilidad]."%)"; ?>"><span style="font-size:10px; color:#000;">Listas</span><br /><img src="../../images/<?php if($listas==100){echo $aprobado;} else{echo $rechazado;}?>" /></div>
                                    </div>
                                    </td>
                                    <td class="list-column-center" style="width:16%; padding-right:5px;">
                                    <div class="roundedpanel" style="height:55px;">
                                        <div class="roundedpanel-content" title="<?php if($final==100){echo "Aprobado";} else{echo "Rechazado";}?>"><img src="../../images/<?php if($final==100){echo "checkv_32.png";} else{echo "reject_20.png";}?>" /><br /></div>
                                    </div>
                                    </td>
                                  </tr>
                                </table>
								<!--FIN DE CALIFICACIÓN DEL PROCESO-->
                                
                                    <div id="stylized" class="myform"><!--FORMULARIO DE ACTUALIZACIÓN DE PROCESO DE CRÉDITO-->
                                    <form name="frmProceso" method="POST" action="insert_terminos.php">
                                    <h1>Actualizar proceso de promoción</h1>
                                    <p></p>
                                    <label>Etapa</label>
                                    <select name="id_etapa" class="input4" id="id_etapa" onchange="mostrarReferencia();">
                                    <?php
                                    if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador"){$sqletapa="SELECT * FROM  `etapas` ORDER BY numero_etapa ASC";}else{$sqletapa="SELECT * FROM  `etapas` WHERE id_responsable = $responsable OR id_responsable=0 ORDER BY numero_etapa ASC";}
                                    $resultetapa=mysql_query($sqletapa,$db);
                                    while($myrowetapa=mysql_fetch_array($resultetapa))
                                    {
                                    ?>
                                        <option value="<?php echo $myrowetapa[id_etapa]; ?>" <?php if($myrowopt[id_etapa]==$myrowetapa[id_etapa]){echo "selected";}?> <?php if($myrowetapa[etapa_anterior]!=$myrowopt[id_etapa]&&$myrowetapa[id_etapa]!=11&&$myrowetapa[id_etapa]!=4){ echo "disabled style='color:#999;'"; } ?>><?php echo $myrowetapa[numero_etapa]." - ".$myrowetapa[etapa]; ?></option>
                                    <?php
                                    }
                                    ?>
                                    </select>
                                    <div class="spacer"></div>
                                    
                                    <!--div oculto TÉRMINOS Y CONDICIONES!-->
                                    <div id="desdeotro" style="background-color:#fff; margin: 0px 0px 10px 10px; border: 1px solid #e3e3e3; width: 910px; display:none;">
                                    <div class="spacer"></div>
                                    <h1>
                                    <input type="text" id="alternate" size="30" value="<?php if($_GET['fecha']){echo htmlentities(strftime("%A, %e %B, %Y", strtotime($_GET['fecha'])));}else{echo "México, D.F., a ".htmlentities(strftime("%e de %B de %Y", strtotime($date)));} ?>" class="inputder" style="border-color:#fff;" name="TER_fecha"/>
                                    </h1>
                                    <div class="spacer"></div>
                                    <h1>
                                    <input type="text" name="TER_destinatario" id="TER_destinatario" class="inputizq" value="<?php echo $myrowconorg[apellidos]." ".$myrowconorg[nombre]; ?>" style="border-color:#fff;"/>
                                    </h1>
                                    <input type="text" name="TER_empresa" id="TER_empresa" class="inputizq" value="<?php echo strtoupper ($myroworg[organizacion]); ?>" style="border-color:#fff;"/>
                                    <div class="spacer"></div>
                                    <textarea name="TER_textolibre" id="TER_textolibre" cols="45" rows="5" style="border-color:#fff;">Por medio de la presente me es grato informarle que Préstamo Empresarial Oportuno, S.A. de C.V., SOFOM, E.N.R. (PREMO) le ha pre aprobado un crédito a <?php echo $myroworg[organizacion]; ?>. A continuación encontrará los términos y condiciones en los que fue aprobado el crédito y el proceso a seguir para formalizarlo.</textarea>
                                    <div class="spacer"></div>
                                    <h1 style="margin: 0 0 10px 15px;"><u>TÉRMINOS Y CONDICIONES</u></h1>
                                    <div class="spacer"></div>
                                    <table style="width:100%; margin-bottom:5px;">
                                        <tr>
                                        <td class="list-column-center" style="width:40%; padding-right:5px;">
                                        <div class="roundedpanel" style="height:55px; background-color:#FFFFCC; ?>; margin: 0 15px 0 15px;">
                                        
                                        <table style="width:100%; margin-bottom:5px; height:55px;">
                                        <tr>
                                        <td class="list-column-center" style="padding-right:5px;">
                                            <span style="font-size:10px;">Monto</span><br /><input type="text" name="monto_credito" id="monto_credito" value="<?php echo $myrowopt[monto]; ?>" onchange="amortizacion();" style="background-color: #ffffe8; border: 0 solid #F1CA66; font-size: 13px; font-weight: bold; color: #666; text-align: center; width: 190px;"/>
                                        </td>
                                        <td class="list-column-center" style="padding-right:5px;">
                                            <span style="font-size:10px;">Plazo</span><br /><select name="plazo_credito" id="plazo_credito" onchange="amortizacion();" style="background-color: #ffffe8; border: 0 solid #F1CA66; font-size: 13px; font-weight: bold; color: #666; text-align: center; width: 190px;" >
                                        <option value="" <?php if($myrowopt[plazo_credito]==""){echo "selected";}?>>Sin especificar</option>
                                        <option value="24" <?php if($myrowopt[plazo_credito]=="24"){echo "selected";}?>>24 meses</option>
                                        <option value="60" <?php if($myrowopt[plazo_credito]=="60"){echo "selected";}?>>60 meses</option>
                                    </select>
                                        </td>
                                        <td class="list-column-center" style="padding-right:5px;">
                                            <span style="font-size:10px;">Interés</span><br /><input type="text" name="interes_credito" id="interes_credito" value="20" onchange="amortizacion();" style="background-color: #ffffe8; border: 0 solid #F1CA66; font-size: 13px; font-weight: bold; color: #666; text-align: center; width: 190px;"/>
                                        </td>
                                        <td class="list-column-center" style="padding-right:5px;">
                                            <span style="font-size:10px;">Amortización</span><br /><input type="text" name="amortizacion_credito" id="amortizacion_credito" value="<?php echo $amortizacion; ?>" style="background-color: #ffffe8; border: 0 solid #F1CA66; font-size: 13px; font-weight: bold; color: #666; text-align: center; width: 190px;"/>
                                        </td>
                                        </tr>
                                        </table>
                                        </div>
                                        </td>
                                      </tr>
                                    </table>

                                    
                                    <table class="recordList" style="width: 96%; margin: 2px 15px 0 15px; border-color:#fff;">
                                    <tbody>
                                    <tr class="odd-row">
                                      <td class="list-column-left" style="border-color:#fff;">Tipo de Crédito:</td>
                                      <td class="list-column-center" style="border-color:#fff;"><input name="TER_tipocredito" type="text" id="TER_tipocredito" style="width:592px; font-size:12px;" value="<?php echo $rwopt[LIS_google_accionista_detalle];?>" /></td>
                                    </tr>
                                    <tr class="odd-row">
                                      <td class="list-column-left" style="border-color:#fff;">Monto:</td>
                                      <td class="list-column-center" style="border-color:#fff;"><input name="TER_monto" type="text" id="TER_monto" style="width:592px; font-size:12px;" value="<?php echo $rwopt[monto];?>"/></td>
                                    </tr>
                                    <tr class="odd-row">
                                      <td class="list-column-left" style="border-color:#fff;">Tasa de Interés:</td>
                                      <td class="list-column-center" style="border-color:#fff;"><input name="TER_interes" type="text" id="TER_interes" style="width:592px;  font-size:12px;" value="<?php echo $rwopt[interes];?>"/></td>
                                    </tr>
                                    <tr class="odd-row">
                                      <td class="list-column-left" style="border-color:#fff;">Garantía:</td>
                                      <td class="list-column-center" style="border-color:#fff;"><input name="TER_garantia" type="text" id="TER_garantia" style="width:592px; font-size:12px;" value="Hipotecaria adicional al aval del representante legal"/></td>
                                    </tr>
                                    <tr class="odd-row">
                                      <td class="list-column-left" style="border-color:#fff;">Aforo:</td>
                                      <td class="list-column-center" style="border-color:#fff;"><input name="TER_aforo" type="text" id="aforo" style="width:592px; font-size:12px;" value="<?php echo $rwopt[LIS_google_accionista_detalle];?>"/></td>
                                    </tr>
                                    <tr class="odd-row">
                                      <td class="list-column-left" style="border-color:#fff;">Comisión por apertura:</td>
                                      <td class="list-column-center" style="border-color:#fff;"><input name="TER_comision" type="text" id="comision" style="width:592px; font-size:12px;" value="<?php echo $rwopt[LIS_google_accionista_detalle];?>"/></td>
                                    </tr>
                                    <tr class="odd-row">
                                      <td class="list-column-left" style="border-color:#fff;">Gastos de formalización:</td>
                                      <td class="list-column-center" style="border-color:#fff;"><input name="TER_formalizacion" type="text" id="TER_formalizacion" style="width:592px; font-size:12px;" value="<?php echo $rwopt[LIS_google_accionista_detalle];?>"/></td>
                                    </tr>
                                    <tr class="odd-row">
                                      <td class="list-column-left" style="border-color:#fff;">Estimación del valor de la garantía:</td>
                                      <td class="list-column-center" style="border-color:#fff;"><input name="TER_preciogarantia" type="text" id="TER_preciogarantia" style="width:592px; font-size:12px;" value="<?php echo $rwanalisis[GAR_valor];?>"/></td>
                                    </tr>
                                    <tr class="odd-row">
                                      <td class="list-column-left" style="border-color:#fff;">Vigencia:</td>
                                      <td class="list-column-center" style="border-color:#fff;"><input name="TER_vigencia" type="text" id="TER_vigencia" style="width:592px; font-size:12px;" value="Esta pre autorización tiene una vigencia de 30 días naturales"/></td>
                                    </tr>
                                    </tbody>
                                    </table>
                                    <h1>&nbsp; </h1>
                                    <h1 style="margin: 0 0 10px 15px;"><u>PROCESO PARA FORMALIZAR Y DISPONER DEL CRÉDITO</u></h1>
                                    <table class="recordList" style="width: 96%; margin: 2px 15px 0 15px;">
                                    <tbody>
                                    <tr class="odd-row">
                                      <td class="list-column-left">Expediente:</td>
                                      <td class="list-column-center"><input name="LIS_google_accionista_detalle12" type="text" id="LIS_google_accionista_detalle28" style="width:592px; border-color:#fff;" value="Se deberá proporcionar la siguiente información"/></td>
                                    </tr>
                                    </tbody>
                                    </table>
                                    <?php
									//Obtener referencia para depósito de seriedad
									$sqlref="SELECT * FROM `referencias` WHERE asignada!=1 ORDER BY referencia ASC LIMIT 1";
									$rsref= mysql_query ($sqlref,$db);
									$rwref=mysql_fetch_array($rsref);
										
									//Documentos de solicitante y garante
									$p=0;
									if($myroworg[tipo_persona]=="Moral")//Si el acreditado es moral, obtener datos de accionista y rep. legal
									{
										echo "Acreditado Moral";
										$personas[$p] ['rol_persona'] = 'Acreditado';
										$personas[$p] ['tipo_persona'] = 'Moral';
										$personas[$p] ['nombre'] = $myroworg[organizacion];

										$sqlrelacr="SELECT * FROM `relaciones` WHERE `clave_organizacion` = '".$claveorganizacion."' AND rol != 'Garante'";
										$rsrelacr= mysql_query ($sqlrelacr,$db);
										while($rwrelacr=mysql_fetch_array($rsrelacr))
										{
											$sqlconacr="SELECT * FROM contactos WHERE clave_contacto = '".$rwrelacr[clave_contacto]."'";
											$rsconacr= mysql_query ($sqlconacr,$db);
											$rwconacr=mysql_fetch_array($rsconacr);
											$p++;
											$personas[$p] ['rol_persona'] = 'Acreditado';
											$personas[$p] ['tipo_persona'] = $rwrelacr[rol];
											$personas[$p] ['nombre'] = $rwconacr[nombre_completo];
										}
									}
									else
									{
										$personas[$p] ['rol_persona'] = 'Acreditado';
										$personas[$p] ['tipo_persona'] = 'Física';
										$personas[$p] ['nombre'] = $myroworg[organizacion];
									}
									
									//Obtener Garante
									$sqlrelacion="SELECT * FROM relaciones LEFT JOIN (organizaciones) ON (relaciones.clave_relacion=organizaciones.clave_organizacion) WHERE relaciones.clave_oportunidad= '".$myrowopt[clave_oportunidad]."' AND relaciones.rol = 'Garante' ORDER BY relaciones.id_relacion ASC" ;
									$rsrelacion= mysql_query($sqlrelacion,$db);
									$rwrelacion=mysql_fetch_array($rsrelacion);
									if($rwrelacion)//Hay Garante
									{
										$p++;
										$personas[$p] ['rol_persona'] = $rwrelacion[rol];
										$personas[$p] ['tipo_persona'] = $rwrelacion[tipo_persona];
										$personas[$p] ['nombre'] = $rwrelacion[organizacion];
										
										if($rwrelacion[tipo_persona]=="Moral")//Si el garante es moral, obtener datos de accionista y rep. legal
										{
											$sqlrel="SELECT * FROM relaciones WHERE clave_organizacion = '".$rwrelacion[clave_relacion]."'";
											$rsrel= mysql_query ($sqlrel,$db);
											while($rwrel=mysql_fetch_array($rsrel))
											{
												$sqlcon="SELECT * FROM contactos WHERE clave_contacto = '".$rwrel[clave_contacto]."'";
												$rscon= mysql_query ($sqlcon,$db);
												$p++;
												while($rwcon=mysql_fetch_array($rscon))
												{
													$personas[$p] ['rol_persona'] = 'Garante';
													$personas[$p] ['tipo_persona'] = $rwrel[rol];
													$personas[$p] ['nombre'] = $rwcon[nombre_completo];
												}
											}
										}
									}//FIN DE IF GARANTE
									$width=round((100/($p+1)),0);
									?>
                                    
                                    
                                    <div class="spacer"></div>
                                    <table class="recordList" style="width: 96%; margin: 2px 15px 0 15px; font-size:10px; table-layout:fixed; overflow:auto;">
                                    <thead>
                                    <tr>
                                      <th class="list-column-left" scope="col">Documentación Requerida</th>
                                      <?php
									  foreach($personas as $persona)
									  {
										$rol="";
										if($persona['tipo_persona']!='Moral'&&$persona['tipo_persona']!='Física'){$rol=" (".$persona['tipo_persona'].")";}
										?>
                                        <th class="list-column-center" scope="col" style="white-space:normal;"><?php echo $persona['rol_persona'].$rol."<br />".$persona['nombre']; ?></th>
									  <?php
                                      }
									  ?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
									$sqldocumentos="SELECT DISTINCT(tipo_archivo) FROM `tiposarchivos` WHERE id_expediente='2' ORDER BY tipo_archivo ASC";
									$rsdocumentos= mysql_query($sqldocumentos,$db);
									while($rwdocumentos=mysql_fetch_array($rsdocumentos))
									{
									?>
                                    <tr class="odd-row">
                                      <td class="list-column-left" style="border-right:1px solid #E3E3E3;"><?php echo $rwdocumentos[tipo_archivo]; ?></td>
                                      <?php
									  foreach($personas as $persona)
									  {
										$sqldoc="SELECT * FROM `tiposarchivos` WHERE `tipo_archivo` LIKE '".$rwdocumentos[tipo_archivo]."' AND `id_expediente` = 2 AND `tipo_persona` LIKE '".$persona[tipo_persona]."' AND `rol_persona` LIKE '".$persona[rol_persona]."'";
										
										$rsdoc= mysql_query($sqldoc,$db);
										$rwdoc=mysql_fetch_array($rsdoc);
										if($rwdoc)
										{
										?>
											<td class="list-column-center" style="border-right:1px solid #E3E3E3;"><img src="../../images/aprovedblack_16.png" /></td>
									  	<?php
										}
										else
										{
										?>
	                                        <td class="list-column-center" style="border-right:1px solid #E3E3E3;"></td>	
                                        <?php	
										}
									  }
									  ?>		
                                    </tr>
                                    <?php
									}
									?>
                                    </tbody>
                                    </table>
                                    <div class="spacer"></div>
                                    <table class="recordList" style="width: 96%; margin: 2px 15px 0 15px;">
                                    <tbody>
                                    <tr class="odd-row">
                                      <td class="list-column-left">Proceso de revisión:</td>
                                      <td class="list-column-center"><textarea name="TER_procesorevision" type="text" id="TER_procesorevision" style="border-color:#fff; margin:2px 15px 0 10px; font-size:12px;">PREMO realizará una visita a las instalaciones de la empresa para conocer la operación y validar los documentos entregados</textarea></td>
                                    </tr>
                                    <tr class="odd-row">
                                      <td class="list-column-left">Fecha estimada de firma:</td>
                                      <td class="list-column-center"><input name="TER_fechafirma" type="text" id="TER_fechafirma" cols="45" rows="2" style="width:592px; border-color:#fff; font-size:12px;" value="Máximo 10 días hábiles a partir de que se haga el depósito de seriedad"/></td>
                                    </tr>
                                    <tr class="odd-row">
                                      <td class="list-column-left">Depósito de seriedad</td>
                                      <td class="list-column-center"><textarea name="TER_deposito" id="TER_deposito" cols="45" rows="5" style="border-color:#fff; margin:2px 15px 0 10px; font-size:12px;">Para iniciar el trámite es necesario que realice un anticipo de 15 mil pesos de los gastos de formalización. En caso de que el crédito no se formalice por causa injustificada de PREMO, será reembolsado. Si el crédito no se formaliza debido a que el inmueble proporcionado tenga algún gravamen o el representante legal y  o la empresa no tengan facultades para firmar, el anticipo no será reembolsado.</textarea></td>
                                    </tr>
                                    <tr class="odd-row">
                                      <td class="list-column-left">Referencia:</td>
                                      <td class="list-column-left" style="padding-left:18px;">Puede realizar el depósito de seriedad con la referencia: <?php echo $rwref[referencia]; ?>, a la cuenta de Banorte: 0806433934, CLABE: 072225008064339344, a nombre de: Préstamo Empresarial Oportuno S.A. de C.V. SOFOM ENR</td>
                                    </tr>
                                    </tbody>
                                    </table>
									<br /><br />
                                    <table>
                                      <tr class="odd-row">
                                      <td class="list-column-left">
                                        <div style="width: 100%; float: left; margin-left: 20px;">Atentamente</div>
                                        <div class="spacer"></div>
                                        <input type="text" name="TER_remitente" id="TER_remitente" value="<?php echo $_SESSION["Nombre"]; ?>" style="border-color:#fff; font-size:12px; text-align:left; padding-left: 8px;"/>
                                        <div class="spacer" style="margin-bottom:1px;"></div>
                                        <input type="text" name="TER_puesto" id="TER_puesto" style="border-color:#fff; font-size:12px; text-align:left; padding-left: 8px;" value="<?php echo $rwagt[responsable]; ?>"/>
                                        <div style="width: 100%; float: left; margin: 10px 0 0 20px;">Préstamo Empresarial Oportuno, S.A. de C.V., SOFOM, E.N.R.</div></td>
                                      </tr>
                                    </table>
                                    
                                    <input type="hidden" name="organizacion" id="organizacion"  value="<?php echo $_GET[organizacion]; ?>" />
                                    <input type="hidden" name="oportunidad" id="oportunidad"  value="<?php echo $_GET[id]; ?>" />
                                    <input type="hidden" name="TER_referencia" id="TER_referencia"  value="<?php echo $rwref[referencia]; ?>" />
                                    <input type="hidden" name="an" id="an"  value="<?php echo $_GET[an]; ?>" />
                                    <input type="hidden" name="a" id="a"  value="<?php echo $_GET[a]; ?>" /> 
                                    <input type="hidden" name="o" id="o"  value="<?php echo $o; ?>" />
                                    </div>
                                    
                                    <!--div oculto MOTIVO RECHAZO!-->
                                    <div id="motivo" style="background-color:#FFFFCC; padding-top:10px; margin:0 0 10px 0; display:none;">
                                    <label>Motivo de Rechazo: </label>
                                    <select id="motivo_rechazo" name="motivo_rechazo" class="input4">
                                      <option value="" selected="selected">Selecciona un motivo</option>
                                    <?php
                                    $sqlmotivos="SELECT * FROM  `motivosrechazo` WHERE `visible`=1 ORDER BY `id_motivorechazo` ASC";
                                    $resultmotivos=mysql_query($sqlmotivos,$db);
                                    while($myrowmotivos=mysql_fetch_array($resultmotivos))
                                    {
                                    ?>
                                      <option value="<?php echo $myrowmotivos[id_motivorechazo]; ?>"><?php echo $myrowmotivos[motivo_rechazo]; ?></option>
                                     <?php
                                    }
                                    ?>
                                    </select>
                                    <div class="spacer"></div>
                                    </div>
                                  
                                    <button type="submit" style="margin-left:422px;">Actualizar Proceso</button>
                                    <div class="spacer"></div>
                                    <input type="hidden" name="organizacion" id="organizacion"  value="<?php echo $claveorganizacion; ?>" /><!--organizacion-->
                            <input type="hidden" name="oportunidad" id="oportunidad"  value="<?php echo $_GET[id]; ?>" /><!--oportunidad-->
                            <input type="hidden" name="etapa" id="etapa"  value="<?php echo $myrowopt[id_etapa]; ?>" /><!--oportunidad-->
                            <input type="hidden" name="a" id="a"  value="<?php echo $_GET[a]; ?>"/><!--archivo: Oportunidades organizaciones -->
                            <input type="hidden" name="o" id="o"  value="U" /><!-- operación: Update -->
                            <!--Calificaciones de análisis de crédito-->
                            <input type="hidden" name="poderes" id="poderes" value="<?php echo $poderes; ?>" />
                            <input type="hidden" name="historial" id="historial" value="<?php echo $historial; ?>" />
                            <input type="hidden" name="flujo" id="flujo" value="<?php echo $flujo; ?>" />
                            <input type="hidden" name="garantia" id="garantia" value="<?php echo $garantia; ?>" />
                            <input type="hidden" name="listas" id="listas" value="<?php echo $listas; ?>" />
                            <input type="hidden" name="final" id="final" value="<?php echo $final; ?>" />
                            <input type="hidden" name="a" id="a" value="<?php echo $_GET[a]; ?>" />
                            <input type="hidden" name="id" id="id" value="<?php echo $_GET[id]; ?>" />
                            <input type="hidden" name="an" id="an" value="<?php echo $_GET[an]; ?>" />
                            <input type="hidden" name="organizacion" id="organizacion" value="<?php echo $claveorganizacion; ?>" />
                                    </form><!--FIN DE FORM ACTUALIZAR PROCESO-->
                                    </div>
                                          
                                </div>
                            </div>
                            <?php
							}
							?>
                        </div>
					<?php
				}
			}//fin if
			else
			{
				$o="I";
				?>
                <div id="stylized" class="myform">
                <form id="form" name="form" method="post" action="insert.php" enctype="multipart/form-data">
                    
                    <h1>Poderes</h1>
                    <p>Información sobre poderes</p>
                  <table class="recordList" style="margin-top: 12px;">
                    <thead>
                    <tr>
                    <th class="list-column-center" scope="col">¿La empresa tiene facultades para obligarse?</th>
                    <th class="list-column-center" scope="col">¿El representante tiene poderes para firmar titulos de crédito?</th>
                    <th class="list-column-center" scope="col"><span class="list-column-left">¿El garante tiene facultades?</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="odd-row">
                    <td class="list-column-center"><select name="POD_facultades_empresa" id="POD_facultades_empresa">
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                    </select></td>
                    <td class="list-column-center"><select name="POD_poderes_representante" id="POD_poderes_representante">
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                    </select></td>
                    <td class="list-column-center"><select name="POD_facultades_garante" id="POD_facultades_garante">
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                    </select></td>
                    </tr>
                    </tbody>
                  </table>
                    <div class="spacer"></div>
                    
                    
                    <h1>Historial Crediticio, e incidencias legales</h1>
                    <p>Se obtiene 100 de calificación si no hay coincidencias en el buró de crédito, incidencias legales y solo si hay saldo vigente</p>
                    <table class="recordList" style="margin-top: 12px;">
                    <thead>
                    <tr>
                    <th class="list-column-center" scope="col">Buró de crédito</th>
                    <th class="list-column-center" scope="col">Original</th>
                    <th class="list-column-center" scope="col">Pago puntual</th>
                    <th class="list-column-center" scope="col">Saldo vigente</th>
                    <th class="list-column-center" scope="col">1 a 29 días</th>
                    <th class="list-column-center" scope="col">30 a 89 días</th>
                    <th class="list-column-center" scope="col">mayor a 90</th>
                    <th class="list-column-center" scope="col">Calificación</th>
                    <th class="list-column-center" scope="col">Máximo atraso</th>
                    <th class="list-column-center" scope="col">Mes</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="odd-row">
                    <td class="list-column-left"> Solicitante</td>
                    <td class="list-column-center"><input type="text" class="input2" name="HIS_original_solicitante" id="HIS_original_solicitante"/></td>
                    <td class="list-column-center"><input type="text" class="input2" name="HIS_puntual_solicitante" id="HIS_puntual_solicitante"/></td>
                    <td class="list-column-center"><input name="HIS_vigente_solicitante" type="text" class="input2" id="HIS_vigente_solicitante"/></td>
                    <td class="list-column-center"><input name="HIS_29_solicitante" type="text" class="input2" id="HIS_29_solicitante"/></td>
                    <td class="list-column-center"><input type="text" class="input2" name="HIS_89_solicitante" id="HIS_89_solicitante"/></td>
                    <td class="list-column-center"><input type="text" class="input2" name="HIS_90_solicitante" id="HIS_90_solicitante"/></td>
                    <td class="list-column-center"><input type="text" class="input2" name="HIS_calificacion_solicitante" id="HIS_calificacion_solicitante"/></td>
                    <td class="list-column-center"><input type="text" class="input2" name="HIS_maximo_solicitante" id="HIS_maximo_solicitante"/></td>
                    <td class="list-column-center"><select name="HIS_mes_solicitante" id="HIS_mes_solicitante" class="select2">
                    <option value="0"  <?php if($rwanalisis[HIS_mes_solicitante]==""){echo "selected";}?> >Mes</option>
                    <option value="01" <?php if($rwanalisis[HIS_mes_solicitante]=="1"){echo "selected";}?> >Enero</option>
                    <option value="02" <?php if($rwanalisis[HIS_mes_solicitante]=="2"){echo "selected";}?> >Febrero</option>
                    <option value="03" <?php if($rwanalisis[HIS_mes_solicitante]=="3"){echo "selected";}?> >Marzo</option>
                    <option value="04" <?php if($rwanalisis[HIS_mes_solicitante]=="4"){echo "selected";}?> >Abril</option>
                    <option value="05" <?php if($rwanalisis[HIS_mes_solicitante]=="5"){echo "selected";}?> >Mayo</option>
                    <option value="06" <?php if($rwanalisis[HIS_mes_solicitante]=="6"){echo "selected";}?> >Junio</option>
                    <option value="07" <?php if($rwanalisis[HIS_mes_solicitante]=="7"){echo "selected";}?> >Julio</option>
                    <option value="08" <?php if($rwanalisis[HIS_mes_solicitante]=="8"){echo "selected";}?> >Agosto</option>
                    <option value="09" <?php if($rwanalisis[HIS_mes_solicitante]=="9"){echo "selected";}?> >Septiembre</option>
                    <option value="10" <?php if($rwanalisis[HIS_mes_solicitante]=="10"){echo "selected";}?> >Octubre</option>
                    <option value="11" <?php if($rwanalisis[HIS_mes_solicitante]=="11"){echo "selected";}?> >Noviembre</option>
                    <option value="12" <?php if($rwanalisis[HIS_mes_solicitante]=="12"){echo "selected";}?> >Diciembre</option>
                    </select></td>
                    </tr>
                    <tr>
                        <td>Buró del solicitante</td>
                        <td colspan="9"><input name="HIS_buro_solicitante" type="file" class="input4" style="margin-left:0px; width:780px;" id="HIS_buro_solicitante"/></td>
                    </tr>
                    <tr class="odd-row">
                    <td class="list-column-left">Representante Legal</td>
                    <td class="list-column-center"><input type="text" class="input2" name="HIS_original_representante" id="HIS_original_representante"/></td>
                    <td class="list-column-center"><input type="text" class="input2" name="HIS_puntual_representante" id="HIS_puntual_representante"/></td>
                    <td class="list-column-center"><input type="text" class="input2" name="HIS_vigente_representante" id="HIS_vigente_representante"/></td>
                    <td class="list-column-center"><input type="text" class="input2" name="HIS_29_representante" id="HIS_29_representante" /></td>
                    <td class="list-column-center"><input type="text" class="input2" name="HIS_89_representante" id="HIS_89_representante" /></td>
                    <td class="list-column-center"><input type="text" class="input2" name="HIS_90_representante" id="HIS_90l_representante"/></td>
                    <td class="list-column-center"><input type="text" class="input2" name="HIS_calificacion_representante" id="HIS_calificacion_representante"/></td>
                    <td class="list-column-center"><input type="text" class="input2" name="HIS_maximo_representante" id="HIS_maximo_representante"/></td>
                    <td class="list-column-center"><select name="HIS_mes_representante" id="HIS_mes_representante" class="select2">
                    <option value="0"  <?php if($rwanalisis[HIS_mes_representante]=="0"){echo "selected";}?> >Mes</option>
                    <option value="01" <?php if($rwanalisis[HIS_mes_representante]=="1"){echo "selected";}?> >Enero</option>
                    <option value="02" <?php if($rwanalisis[HIS_mes_representante]=="2"){echo "selected";}?> >Febrero</option>
                    <option value="03" <?php if($rwanalisis[HIS_mes_representante]=="3"){echo "selected";}?> >Marzo</option>
                    <option value="04" <?php if($rwanalisis[HIS_mes_representante]=="4"){echo "selected";}?> >Abril</option>
                    <option value="05" <?php if($rwanalisis[HIS_mes_representante]=="5"){echo "selected";}?> >Mayo</option>
                    <option value="06" <?php if($rwanalisis[HIS_mes_representante]=="6"){echo "selected";}?> >Junio</option>
                    <option value="07" <?php if($rwanalisis[HIS_mes_representante]=="7"){echo "selected";}?> >Julio</option>
                    <option value="08" <?php if($rwanalisis[HIS_mes_representante]=="8"){echo "selected";}?> >Agosto</option>
                    <option value="09" <?php if($rwanalisis[HIS_mes_representante]=="9"){echo "selected";}?> >Septiembre</option>
                    <option value="10" <?php if($rwanalisis[HIS_mes_representante]=="10"){echo "selected";}?> >Octubre</option>
                    <option value="11" <?php if($rwanalisis[HIS_mes_representante]=="11"){echo "selected";}?> >Noviembre</option>
                    <option value="12" <?php if($rwanalisis[HIS_mes_representante]=="12"){echo "selected";}?> >Diciembre</option>
                    </select></td>
                    </tr>
                    <tr>
                    <td>Buró del representate</td>
                    <td colspan="9"><input name="HIS_buro_representante" type="file" class="input4" style="margin-left:0px; width:780px;" id="HIS_buro_representante"/></td>
                </tr>
                    <tr class="odd-row">
                    <td class="list-column-left">Principal Accionista</td>
                    <td class="list-column-center"><input type="text" class="input2" name="HIS_original_accionista" id="HIS_original_accionista"/></td>
                    <td class="list-column-center"><input type="text" class="input2" name="HIS_puntual_accionista" id="HIS_puntual_accionista"/></td>
                    <td class="list-column-center"><input type="text" class="input2" name="HIS_vigente_accionista" id="HIS_vigente_accionista"/></td>
                    <td class="list-column-center"><input type="text" class="input2" name="HIS_29_accionista" id="HIS_29_accionista"/></td>
                    <td class="list-column-center"><input type="text" class="input2" name="HIS_89_accionista" id="HIS_89_accionista"/></td>
                    <td class="list-column-center"><input type="text" class="input2" name="HIS_90_accionista" id="HIS_90_accionista"/></td>
                    <td class="list-column-center"><input type="text" class="input2" name="HIS_calificacion_accionista" id="HIS_calificacion_accionista"/></td>
                    <td class="list-column-center"><input type="text" class="input2" name="HIS_maximo_accionista" id="HIS_maximo_accionista"/></td>
                    <td class="list-column-center"><select name="HIS_mes_accionista" id="HIS_mes_accionista" class="select2">
                    <option value="0"  <?php if($rwanalisis[HIS_mes_accionista]=="0"){echo "selected";}?> >Mes</option>
                    <option value="01" <?php if($rwanalisis[HIS_mes_accionista]=="1"){echo "selected";}?> >Enero</option>
                    <option value="02" <?php if($rwanalisis[HIS_mes_accionista]=="2"){echo "selected";}?> >Febrero</option>
                    <option value="03" <?php if($rwanalisis[HIS_mes_accionista]=="3"){echo "selected";}?> >Marzo</option>
                    <option value="04" <?php if($rwanalisis[HIS_mes_accionista]=="4"){echo "selected";}?> >Abril</option>
                    <option value="05" <?php if($rwanalisis[HIS_mes_accionista]=="5"){echo "selected";}?> >Mayo</option>
                    <option value="06" <?php if($rwanalisis[HIS_mes_accionista]=="6"){echo "selected";}?> >Junio</option>
                    <option value="07" <?php if($rwanalisis[HIS_mes_accionista]=="7"){echo "selected";}?> >Julio</option>
                    <option value="08" <?php if($rwanalisis[HIS_mes_accionista]=="8"){echo "selected";}?> >Agosto</option>
                    <option value="09" <?php if($rwanalisis[HIS_mes_accionista]=="9"){echo "selected";}?> >Septiembre</option>
                    <option value="10" <?php if($rwanalisis[HIS_mes_accionista]=="10"){echo "selected";}?> >Octubre</option>
                    <option value="11" <?php if($rwanalisis[HIS_mes_accionista]=="11"){echo "selected";}?> >Noviembre</option>
                    <option value="12" <?php if($rwanalisis[HIS_mes_accionista]=="12"){echo "selected";}?> >Diciembre</option>
                    </select></td>
                    </tr>
                    <tr>
                    <td>Buró del accionista</td>
                    <td colspan="9"><input name="HIS_buro_accionista" type="file" class="input4" style="margin-left:0px; width:780px;" id="HIS_buro_accionista"/></td>
                </tr>    
                    </tbody>
                    </table>
                    
                    <table class="recordList" style="margin-top: 12px;">
                    <tbody>
                    <tr class="odd-row">
                    <td class="list-column-left"><label>Solicitante<span class="small">Indicencias legales</span></label></td>
                    <td class="list-column-left">
                    <select name="HIS_incidencias_solicitante" id="HIS_incidencias_solicitante" style="width:130px;" onchange="habilitar(this.value,this.id);">
                    <option value="0" <?php if($rwanalisis[HIS_incidencias_solicitante]=="0"){echo "selected";}?> >Sin coincidencias</option>
                    <option value="1" <?php if($rwanalisis[HIS_incidencias_solicitante]=="1"){echo "selected";}?> >Hay coincidencias</option>
                    </select></td>
                    <td class="list-column-left"><input type="text" style="width:592px;" name="HIS_incidencias_solicitante_detalle" id="HIS_incidencias_solicitante_detalle" value="<?php echo $rwanalisis[HIS_incidencias_solicitante_detalle];?>" <?php if($rwanalisis[HIS_incidencias_solicitante]=="0"){echo "disabled='disabled'";} ?>/></td>
                    </tr>
                    <tr class="odd-row">
                      <td class="list-column-left"><label>Representante Legal<span class="small">Incidencias legales</span></label></td>
                      <td class="list-column-left"><select name="HIS_incidencias_representante" id="HIS_incidencias_representante" style="width:130px;" onchange="habilitar(this.value,this.id);">
                        <option value="0" <?php if($rwanalisis[HIS_incidencias_representante]=="0"){echo "selected";}?> >Sin coincidencias</option>
                        <option value="1" <?php if($rwanalisis[HIS_incidencias_representante]=="1"){echo "selected";}?> >Hay coincidencias</option>
                      </select></td>
                      <td class="list-column-left"><input type="text" style="width:592px;" name="HIS_incidencias_representante_detalle" id="HIS_incidencias_representante_detalle" value="<?php echo $rwanalisis[HIS_incidencias_representante_detalle];?>" <?php if($rwanalisis[HIS_incidencias_representante]=="0"){echo "disabled='disabled'";} ?>/></td>
                      </tr>
                    <tr class="odd-row">
                      <td class="list-column-left"><label>Principal Accionista<span class="small">Incidencias legales</span></label></td>
                      <td class="list-column-left"><select name="HIS_incidencias_accionista" id="HIS_incidencias_accionista" style="width:130px;" onchange="habilitar(this.value,this.id);">
                        <option value="0" <?php if($rwanalisis[HIS_incidencias_accionista]=="0"){echo "selected";}?> >Sin coincidencias</option>
                        <option value="1" <?php if($rwanalisis[HIS_incidencias_accionista]=="1"){echo "selected";}?> >Hay coincidencias</option>
                      </select></td>
                      <td class="list-column-left"><input type="text" style="width:592px;" name="HIS_incidencias_accionista_detalle" id="HIS_incidencias_accionista_detalle" value="<?php echo $rwanalisis[HIS_incidencias_accionista_detalle];?>" <?php if($rwanalisis[HIS_incidencias_accionista]=="0"){echo "disabled='disabled'";} ?>/></td>
                      </tr>
                    </tbody>
                    </table>
                    
                    <div class="spacer"></div>
                    <div class="spacer"></div>
                    
                    <h1>Flujo</h1>
                    <p>Se obtiene 100 si el promedio de los depósitos es mayor o igual al crédito solicitado y el saldo promedio es igual o mayor a la amortización que pagará de aprobarse el crédito.</p>
                    
                    <label>Cuenta<span class="small">Sólo números</span></label>
                    <input type="text" name="FLU_cuenta" id="FLU_cuenta" value="<?php echo $rwanalisis[FLU_cuenta]; ?>"/>
                    <label>Banco</label>
                    <select name="FLU_banco" id="FLU_banco">
                        <option value="" <?php if($rwanalisis[FLU_banco]==""){echo "selected";}?> >Seleccione</option>
                        <?php
                        //Llenar Select de Bancos		
                        $sqlbanco="SELECT * FROM bancos ORDER BY `banco` ASC";
                        $resultbanco= mysql_query ($sqlbanco,$db);
                        while($myrowbanco=mysql_fetch_array($resultbanco))
                        {
                            ?>
                            <option value="<?php echo $myrowbanco[banco]; ?>" <?php if($rwanalisis[FLU_banco]==$myrowbanco[banco]){echo "selected";}?>><?php echo $myrowbanco[banco]; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    
                    <div class="spacer"></div>
                    
                    <table class="recordList" style="margin-top: 12px;">
                    <thead>
                    <tr>
                    <th class="list-column-center" scope="col"></th>
                    <th class="list-column-center" scope="col">
                    <select name="FLU_mes1" id="FLU_mes1" class="select2">
                    <option value="0"  <?php if($rwanalisis[FLU_mes1]=="0"){echo "selected";}?> >Mes</option>
                    <option value="01" <?php if($rwanalisis[FLU_mes1]=="1"){echo "selected";}?> >Enero</option>
                    <option value="02" <?php if($rwanalisis[FLU_mes1]=="2"){echo "selected";}?> >Febrero</option>
                    <option value="03" <?php if($rwanalisis[FLU_mes1]=="3"){echo "selected";}?> >Marzo</option>
                    <option value="04" <?php if($rwanalisis[FLU_mes1]=="4"){echo "selected";}?> >Abril</option>
                    <option value="05" <?php if($rwanalisis[FLU_mes1]=="5"){echo "selected";}?> >Mayo</option>
                    <option value="06" <?php if($rwanalisis[FLU_mes1]=="6"){echo "selected";}?> >Junio</option>
                    <option value="07" <?php if($rwanalisis[FLU_mes1]=="7"){echo "selected";}?> >Julio</option>
                    <option value="08" <?php if($rwanalisis[FLU_mes1]=="8"){echo "selected";}?> >Agosto</option>
                    <option value="09" <?php if($rwanalisis[FLU_mes1]=="9"){echo "selected";}?> >Septiembre</option>
                    <option value="10" <?php if($rwanalisis[FLU_mes1]=="10"){echo "selected";}?> >Octubre</option>
                    <option value="11" <?php if($rwanalisis[FLU_mes1]=="11"){echo "selected";}?> >Noviembre</option>
                    <option value="12" <?php if($rwanalisis[FLU_mes1]=="12"){echo "selected";}?> >Diciembre</option>
                    </select></th>
                    <th class="list-column-center" scope="col">
                    <select name="FLU_mes2" id="FLU_mes2" class="select2">
                    <option value="0"  <?php if($rwanalisis[FLU_mes2]=="0"){echo "selected";}?> >Mes</option>
                    <option value="01" <?php if($rwanalisis[FLU_mes2]=="1"){echo "selected";}?> >Enero</option>
                    <option value="02" <?php if($rwanalisis[FLU_mes2]=="2"){echo "selected";}?> >Febrero</option>
                    <option value="03" <?php if($rwanalisis[FLU_mes2]=="3"){echo "selected";}?> >Marzo</option>
                    <option value="04" <?php if($rwanalisis[FLU_mes2]=="4"){echo "selected";}?> >Abril</option>
                    <option value="05" <?php if($rwanalisis[FLU_mes2]=="5"){echo "selected";}?> >Mayo</option>
                    <option value="06" <?php if($rwanalisis[FLU_mes2]=="6"){echo "selected";}?> >Junio</option>
                    <option value="07" <?php if($rwanalisis[FLU_mes2]=="7"){echo "selected";}?> >Julio</option>
                    <option value="08" <?php if($rwanalisis[FLU_mes2]=="8"){echo "selected";}?> >Agosto</option>
                    <option value="09" <?php if($rwanalisis[FLU_mes2]=="9"){echo "selected";}?> >Septiembre</option>
                    <option value="10" <?php if($rwanalisis[FLU_mes2]=="10"){echo "selected";}?> >Octubre</option>
                    <option value="11" <?php if($rwanalisis[FLU_mes2]=="11"){echo "selected";}?> >Noviembre</option>
                    <option value="12" <?php if($rwanalisis[FLU_mes2]=="12"){echo "selected";}?> >Diciembre</option>
                    </select></th>
                    <th class="list-column-center" scope="col">
                    <select name="FLU_mes3" id="FLU_mes3" class="select2">
                    <option value="0"  <?php if($rwanalisis[FLU_mes3]=="0"){echo "selected";}?> >Mes</option>
                    <option value="01" <?php if($rwanalisis[FLU_mes3]=="1"){echo "selected";}?> >Enero</option>
                    <option value="02" <?php if($rwanalisis[FLU_mes3]=="2"){echo "selected";}?> >Febrero</option>
                    <option value="03" <?php if($rwanalisis[FLU_mes3]=="3"){echo "selected";}?> >Marzo</option>
                    <option value="04" <?php if($rwanalisis[FLU_mes3]=="4"){echo "selected";}?> >Abril</option>
                    <option value="05" <?php if($rwanalisis[FLU_mes3]=="5"){echo "selected";}?> >Mayo</option>
                    <option value="06" <?php if($rwanalisis[FLU_mes3]=="6"){echo "selected";}?> >Junio</option>
                    <option value="07" <?php if($rwanalisis[FLU_mes3]=="7"){echo "selected";}?> >Julio</option>
                    <option value="08" <?php if($rwanalisis[FLU_mes3]=="8"){echo "selected";}?> >Agosto</option>
                    <option value="09" <?php if($rwanalisis[FLU_mes3]=="9"){echo "selected";}?> >Septiembre</option>
                    <option value="10" <?php if($rwanalisis[FLU_mes3]=="10"){echo "selected";}?> >Octubre</option>
                    <option value="11" <?php if($rwanalisis[FLU_mes3]=="11"){echo "selected";}?> >Noviembre</option>
                    <option value="12" <?php if($rwanalisis[FLU_mes3]=="12"){echo "selected";}?> >Diciembre</option>
                    </select></th>
                    <th class="list-column-center" scope="col"><select name="FLU_mes4" id="FLU_mes4" class="select2">
                    <option value="0"  <?php if($rwanalisis[FLU_mes4]=="0"){echo "selected";}?> >Mes</option>
                    <option value="01" <?php if($rwanalisis[FLU_mes4]=="1"){echo "selected";}?> >Enero</option>
                    <option value="02" <?php if($rwanalisis[FLU_mes4]=="2"){echo "selected";}?> >Febrero</option>
                    <option value="03" <?php if($rwanalisis[FLU_mes4]=="3"){echo "selected";}?> >Marzo</option>
                    <option value="04" <?php if($rwanalisis[FLU_mes4]=="4"){echo "selected";}?> >Abril</option>
                    <option value="05" <?php if($rwanalisis[FLU_mes4]=="5"){echo "selected";}?> >Mayo</option>
                    <option value="06" <?php if($rwanalisis[FLU_mes4]=="6"){echo "selected";}?> >Junio</option>
                    <option value="07" <?php if($rwanalisis[FLU_mes4]=="7"){echo "selected";}?> >Julio</option>
                    <option value="08" <?php if($rwanalisis[FLU_mes4]=="8"){echo "selected";}?> >Agosto</option>
                    <option value="09" <?php if($rwanalisis[FLU_mes4]=="9"){echo "selected";}?> >Septiembre</option>
                    <option value="10" <?php if($rwanalisis[FLU_mes4]=="10"){echo "selected";}?> >Octubre</option>
                    <option value="11" <?php if($rwanalisis[FLU_mes4]=="11"){echo "selected";}?> >Noviembre</option>
                    <option value="12" <?php if($rwanalisis[FLU_mes4]=="12"){echo "selected";}?> >Diciembre</option>
                    </select></th>
                    <th class="list-column-center" scope="col"><select name="FLU_mes5" id="FLU_mes5" class="select2">
                    <option value="0"  <?php if($rwanalisis[FLU_mes5]=="0"){echo "selected";}?> >Mes</option>
                    <option value="01" <?php if($rwanalisis[FLU_mes5]=="1"){echo "selected";}?> >Enero</option>
                    <option value="02" <?php if($rwanalisis[FLU_mes5]=="2"){echo "selected";}?> >Febrero</option>
                    <option value="03" <?php if($rwanalisis[FLU_mes5]=="3"){echo "selected";}?> >Marzo</option>
                    <option value="04" <?php if($rwanalisis[FLU_mes5]=="4"){echo "selected";}?> >Abril</option>
                    <option value="05" <?php if($rwanalisis[FLU_mes5]=="5"){echo "selected";}?> >Mayo</option>
                    <option value="06" <?php if($rwanalisis[FLU_mes5]=="6"){echo "selected";}?> >Junio</option>
                    <option value="07" <?php if($rwanalisis[FLU_mes5]=="7"){echo "selected";}?> >Julio</option>
                    <option value="08" <?php if($rwanalisis[FLU_mes5]=="8"){echo "selected";}?> >Agosto</option>
                    <option value="09" <?php if($rwanalisis[FLU_mes5]=="9"){echo "selected";}?> >Septiembre</option>
                    <option value="10" <?php if($rwanalisis[FLU_mes5]=="10"){echo "selected";}?> >Octubre</option>
                    <option value="11" <?php if($rwanalisis[FLU_mes5]=="11"){echo "selected";}?> >Noviembre</option>
                    <option value="12" <?php if($rwanalisis[FLU_mes5]=="12"){echo "selected";}?> >Diciembre</option>
                    </select></th>
                    <th class="list-column-center" scope="col"><select name="FLU_mes6" id="FLU_mes6" class="select2">
                    <option value="0"  <?php if($rwanalisis[FLU_mes6]=="0"){echo "selected";}?> >Mes</option>
                    <option value="01" <?php if($rwanalisis[FLU_mes6]=="1"){echo "selected";}?> >Enero</option>
                    <option value="02" <?php if($rwanalisis[FLU_mes6]=="2"){echo "selected";}?> >Febrero</option>
                    <option value="03" <?php if($rwanalisis[FLU_mes6]=="3"){echo "selected";}?> >Marzo</option>
                    <option value="04" <?php if($rwanalisis[FLU_mes6]=="4"){echo "selected";}?> >Abril</option>
                    <option value="05" <?php if($rwanalisis[FLU_mes6]=="5"){echo "selected";}?> >Mayo</option>
                    <option value="06" <?php if($rwanalisis[FLU_mes6]=="6"){echo "selected";}?> >Junio</option>
                    <option value="07" <?php if($rwanalisis[FLU_mes6]=="7"){echo "selected";}?> >Julio</option>
                    <option value="08" <?php if($rwanalisis[FLU_mes6]=="8"){echo "selected";}?> >Agosto</option>
                    <option value="09" <?php if($rwanalisis[FLU_mes6]=="9"){echo "selected";}?> >Septiembre</option>
                    <option value="10" <?php if($rwanalisis[FLU_mes6]=="10"){echo "selected";}?> >Octubre</option>
                    <option value="11" <?php if($rwanalisis[FLU_mes6]=="11"){echo "selected";}?> >Noviembre</option>
                    <option value="12" <?php if($rwanalisis[FLU_mes6]=="12"){echo "selected";}?> >Diciembre</option>
                    </select></th>
                    <th class="list-column-center" scope="col">Promedio</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="odd-row">
                    <td class="list-column-left">Saldo Inicial</td>
                    <td class="list-column-center"><input name="FLU_inicial_mes1" type="text" class="input2" id="FLU_inicial_mes1" onchange="saldo_inicial2();promedio_inicial();" value="0"/></td>
                    <td class="list-column-center"><input name="FLU_inicial_mes2" type="text" class="input2" id="FLU_inicial_mes2" onchange="saldo_inicial3();promedio_inicial();" value="0"/></td>
                    <td class="list-column-center"><input name="FLU_inicial_mes3" type="text" class="input2" id="FLU_inicial_mes3" onchange="saldo_inicial4();promedio_inicial();" value="0"/></td>
                    <td class="list-column-center"><input name="FLU_inicial_mes4" type="text" class="input2" id="FLU_inicial_mes4" onchange="saldo_inicial5();promedio_inicial();" value="0"/></td>
                    <td class="list-column-center"><input name="FLU_inicial_mes5" type="text" class="input2" id="FLU_inicial_mes5" onchange="promedio_inicial();" value="0"/></td>
                    <td class="list-column-center"><input name="FLU_inicial_mes6" type="text" class="input2" id="FLU_inicial_mes6" value="0"/></td>
                    <td class="list-column-center"><input name="FLU_promedio_inicial" type="text" class="input2" id="FLU_promedio_inicial" value="0"/></td>
                    </tr>
                    <tr class="odd-row">
                    <td class="list-column-left">Depósitos</td>
                    <td class="list-column-center"><input name="FLU_depositos_mes1" type="text" class="input2" id="FLU_depositos_mes1" onchange="saldo_inicial2();promedio_depositos();" value="0"/></td>
                    <td class="list-column-center"><input name="FLU_depositos_mes2" type="text" class="input2" id="FLU_depositos_mes2" onchange="saldo_inicial3();promedio_depositos();" value="0"/></td>
                    <td class="list-column-center"><input name="FLU_depositos_mes3" type="text" class="input2" id="FLU_depositos_mes3" onchange="saldo_inicial4();promedio_depositos();" value="0"/></td>
                    <td class="list-column-center"><input name="FLU_depositos_mes4" type="text" class="input2" id="FLU_depositos_mes4" onchange="saldo_inicial5();promedio_depositos();" value="0"/></td>
                    <td class="list-column-center"><input name="FLU_depositos_mes5" type="text" class="input2" id="FLU_depositos_mes5" onchange="saldo_inicial6();promedio_depositos();" value="0"/></td>
                    <td class="list-column-center"><input name="FLU_depositos_mes6" type="text" class="input2" id="FLU_depositos_mes6" value="0"/></td>
                    <td class="list-column-center"><input name="FLU_promedio_depositos" type="text" class="input2" id="FLU_promedio_depositos" value="0"/></td>
                    </tr>
                    <tr class="odd-row">
                    <td class="list-column-left">Retiros</td>
                    <td class="list-column-center"><input name="FLU_retiros_mes1" type="text" class="input2" id="FLU_retiros_mes1" onchange="saldo_inicial2();promedio_retiros();" value="0"/></td>
                    <td class="list-column-center"><input name="FLU_retiros_mes2" type="text" class="input2" id="FLU_retiros_mes2" onchange="saldo_inicial3();promedio_retiros();" value="0"/></td>
                    <td class="list-column-center"><input name="FLU_retiros_mes3" type="text" class="input2" id="FLU_retiros_mes3" onchange="saldo_inicial4();promedio_retiros();" value="0"/></td>
                    <td class="list-column-center"><input name="FLU_retiros_mes4" type="text" class="input2" id="FLU_retiros_mes4" onchange="saldo_inicial5();promedio_retiros();" value="0"/></td>
                    <td class="list-column-center"><input name="FLU_retiros_mes5" type="text" class="input2" id="FLU_retiros_mes5" onchange="saldo_inicial6();promedio_retiros();" value="0"/></td>
                    <td class="list-column-center"><input name="FLU_retiros_mes6" type="text" class="input2" id="FLU_retiros_mes6" value="0"/></td>
                    <td class="list-column-center"><input name="FLU_promedio_retiros" type="text" class="input2" id="FLU_promedio_retiros" value="0"/></td>
                    </tr>
                    <tr class="odd-row">
                    <td class="list-column-left">Saldo Promedio</td>
                    <td class="list-column-center"><input name="FLU_promedio_mes1" type="text" class="input2" id="FLU_promedio_mes1" onchange="promedio_promedio();" value="0"/></td>
                    <td class="list-column-center"><input name="FLU_promedio_mes2" type="text" class="input2" id="FLU_promedio_mes2" onchange="promedio_promedio();" value="0"/></td>
                    <td class="list-column-center"><input name="FLU_promedio_mes3" type="text" class="input2" id="FLU_promedio_mes3" onchange="promedio_promedio();" value="0"/></td>
                    <td class="list-column-center"><input name="FLU_promedio_mes4" type="text" class="input2" id="FLU_promedio_mes4" onchange="promedio_promedio();" value="0"/></td>
                    <td class="list-column-center"><input name="FLU_promedio_mes5" type="text" class="input2" id="FLU_promedio_mes5" onchange="promedio_promedio();" value="0"/></td>
                    <td class="list-column-center"><input name="FLU_promedio_mes6" type="text" class="input2" id="FLU_promedio_mes6" value="0" /></td>
                    <td class="list-column-center"><input name="FLU_promedio_promedio_total" type="text" class="input2" id="FLU_promedio_promedio_total" value="0" /></td>
                    </tr>
                    </tbody>
                    </table>
                    
                    <div class="spacer"></div>
                    
                    <h1>Garantía</h1>
                    <p>Se obtiene 100 si la garantía cubre por lo menos tres veces el valor del crédito solicitado</p>
                    
                  <label>Nombre del propietario<span class="small">del inmueble en garantía</span></label>
                    <input type="text" style="width:460px;" name="GAR_propietario" id="GAR_propietario"/>
                    <label>Relación con la empresa</label>
                    <input type="text" name="GAR_relacion" id="GAR_relacion"/>
                    <div class="spacer"></div>
                    <label>Domicilio<span class="small">Calle, Número, Colonia</span></label>
                  <input type="text" class="input4" name="GAR_domicilio" id="GAR_domicilio" />
                    <div class="spacer"></div>
                    <label>Delegación</label>
                  <input type="text" name="GAR_ciudad" id="GAR_ciudad" />
                    <label>Estado</label>
                  <input type="text" name="GAR_estado" id="GAR_estado" />
                    <label>Código Postal</label>
                  <input type="text" name="GAR_cp" id="GAR_cp" />
                    <div class="spacer"></div>
                    <label>Metros de Construcción<span class="small">Sólo números</span></label>
                  <input type="text" name="GAR_construccion" id="GAR_construccion"/>
                    <label>Metros de Terreno<span class="small">Sólo números</span></label>
                  <input type="text" name="GAR_terreno" id="GAR_terreno" />
                    <label>Valor estimado<span class="small">Sólo números</span></label>
                  <input type="text" name="GAR_valor" id="GAR_valor"/>
                    <div class="spacer"></div>
                    
                    <h1>Listados</h1>
                    <p>Se obtiene 100 si la garantía cubre por lo menos tres veces el valor del crédito solicitado</p>
                    
                    <table class="recordList" style="margin-top: 12px;">
                    <thead>
                    <tr>
                    <th class="list-column-center" scope="col"><img src="../../images/quienesquien.png" alt="" width="120" height="22" /></th>
                    <th class="list-column-center" scope="col">&nbsp;</th>
                    <th class="list-column-center" scope="col">&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="odd-row">
                    <td class="list-column-left">Empresa</td>
                    <td class="list-column-center">
                    <select name="LIS_listas_empresa" id="LIS_listas_empresa" onchange="habilitar(this.value,this.id);">
                    <option value="0" >Sin coincidencias</option>
                    <option value="1" >Hay coincidencias</option>
                    </select></td>
                    <td class="list-column-center">
                      <input name="LIS_listas_empresa_detalle" type="text" <?php if($rwanalisis[LIS_listas_empresa]=="0"){echo "disabled='disabled'";} ?> id="LIS_listas_empresa_detalle" style="width:592px;" value="<?php echo $rwanalisis[LIS_listas_empresa_detalle];?>" /></td>
                    </tr>
                    <tr class="odd-row">
                      <td class="list-column-left">Representante Legal</td>
                      <td class="list-column-center"><select name="LIS_listas_representante" id="LIS_listas_representante" onchange="habilitar(this.value,this.id);">
                        <option value="0" <?php if($rwanalisis[LIS_listas_representante]=="0"){echo "selected";}?> >Sin coincidencias</option>
                        <option value="1" <?php if($rwanalisis[LIS_listas_representante]=="1"){echo "selected";}?> >Hay coincidencias</option>
                      </select></td>
                      <td class="list-column-center">
                        <input name="LIS_listas_representante_detalle" type="text" <?php if($rwanalisis[LIS_listas_representante]=="0"){echo "disabled='disabled'";} ?> id="LIS_listas_representante_detalle" style="width:592px;" value="<?php echo $rwanalisis[LIS_listas_representante_detalle];?>"/></td>
                      </tr>
                    <tr class="odd-row">
                    <td class="list-column-left">Principal Accionista</td>
                    <td class="list-column-center"><select name="LIS_listas_accionista" id="LIS_listas_accionista" onchange="habilitar(this.value,this.id);">
                      <option value="0" <?php if($rwanalisis[LIS_listas_accionista]=="0"){echo "selected";}?> >Sin coincidencias</option>
                      <option value="1" <?php if($rwanalisis[LIS_listas_accionista]=="1"){echo "selected";}?> >Hay coincidencias</option>
                    </select></td>
                    <td class="list-column-center">
                      <input name="LIS_listas_accionista_detalle" type="text" id="LIS_listas_accionista_detalle" style="width:592px;" value="<?php echo $rwanalisis[LIS_listas_accionista_detalle];?>" <?php if($rwanalisis[LIS_listas_accionista]=="0"){echo "disabled='disabled'";} ?>/></td>
                    </tr>
                    <tr class="odd-row">
                      <td class="list-column-left">Garante</td>
                      <td class="list-column-center"><select name="LIS_listas_garante" id="LIS_listas_garante" onchange="habilitar(this.value,this.id);">
                        <option value="0" <?php if($rwanalisis[LIS_listas_garante]=="0"){echo "selected";}?> >Sin coincidencias</option>
                        <option value="1" <?php if($rwanalisis[LIS_listas_garante]=="1"){echo "selected";}?> >Hay coincidencias</option>
                      </select></td>
                      <td class="list-column-center">
                        <input name="LIS_listas_garante_detalle" type="text" id="LIS_listas_garante_detalle" style="width:592px;" value="<?php echo $rwanalisis[LIS_listas_garante_detalle];?>" <?php if($rwanalisis[LIS_listas_garante]=="0"){echo "disabled='disabled'";} ?>/></td>
                      </tr>
                    <tr class="odd-row">
                      <td class="list-column-left">&nbsp;</td>
                      <td class="list-column-center">&nbsp;</td>
                      <td class="list-column-center">&nbsp;</td>
                      </tr>
                    <tr class="odd-row">
                      <td class="list-column-left"><img src="../../images/google.png" alt="" width="120" height="22" /></td>
                      <td class="list-column-center">&nbsp;</td>
                      <td class="list-column-center">&nbsp;</td>
                      </tr>
                    <tr class="odd-row">
                      <td class="list-column-left">Empresa</td>
                      <td class="list-column-center"><select name="LIS_google_empresa" id="LIS_google_empresa" onchange="habilitar(this.value,this.id);">
                        <option value="0" <?php if($rwanalisis[LIS_google_empresa]=="0"){echo "selected";}?> >Sin coincidencias</option>
                        <option value="1" <?php if($rwanalisis[LIS_google_empresa]=="1"){echo "selected";}?> >Hay coincidencias</option>
                      </select></td>

                      <td class="list-column-center">
                        <input name="LIS_google_empresa_detalle" type="text" <?php if($rwanalisis[LIS_google_empresa]=="0"){echo "disabled='disabled'";} ?> id="LIS_google_empresa_detalle" style="width:592px;" value="<?php echo $rwanalisis[LIS_google_empresa_detalle];?>"/></td>
                      </tr>
                    <tr class="odd-row">
                      <td class="list-column-left">Representante Legal</td>
                      <td class="list-column-center"><select name="LIS_google_representante" id="LIS_google_representante" onchange="habilitar(this.value,this.id);">
                        <option value="0" <?php if($rwanalisis[LIS_google_representante]=="0"){echo "selected";}?> >Sin coincidencias</option>
                        <option value="1" <?php if($rwanalisis[LIS_google_representante]=="1"){echo "selected";}?> >Hay coincidencias</option>
                      </select></td>
                      <td class="list-column-center">
                        <input name="LIS_google_representante_detalle" type="text" id="LIS_google_representante_detalle" style="width:592px;" value="<?php echo $rwanalisis[LIS_google_representante_detalle];?>" <?php if($rwanalisis[LIS_google_representante]=="0"){echo "disabled='disabled'";} ?>/></td>
                      </tr>
                    <tr class="odd-row">
                      <td class="list-column-left">Principal Accionista</td>
                      <td class="list-column-center"><select name="LIS_google_accionista" id="LIS_google_accionista" onchange="habilitar(this.value,this.id);">
                        <option value="0" <?php if($rwanalisis[LIS_google_accionista]=="0"){echo "selected";}?> >Sin coincidencias</option>
                        <option value="1" <?php if($rwanalisis[LIS_google_accionista]=="1"){echo "selected";}?> >Hay coincidencias</option>
                      </select></td>
                      <td class="list-column-center">
                        <input name="LIS_google_accionista_detalle" type="text" id="LIS_google_accionista_detalle" style="width:592px;" value="<?php echo $rwanalisis[LIS_google_accionista_detalle];?>" <?php if($rwanalisis[LIS_google_accionista]=="0"){echo "disabled='disabled'";} ?>/></td>
                      </tr>
                    <tr class="odd-row">
                      <td class="list-column-left">Garante</td>
                      <td class="list-column-center"><select name="LIS_google_garante" id="LIS_google_garante" onchange="habilitar(this.value,this.id);">
                        <option value="0" <?php if($rwanalisis[LIS_google_garante]=="0"){echo "selected";}?> >Sin coincidencias</option>
                        <option value="1" <?php if($rwanalisis[LIS_google_garante]=="1"){echo "selected";}?>  >Hay coincidencias</option>
                      </select></td>
                      <td class="list-column-center">
                        <input name="LIS_google_garante_detalle" type="text" id="LIS_google_garante_detalle" style="width:592px;" value="<?php echo $rwanalisis[LIS_google_garante_detalle];?>" <?php if($rwanalisis[LIS_google_garante]=="0"){echo "disabled='disabled'";} ?>/></td>
                      </tr>
                    </tbody>
                    </table>
                    
                    <div class="spacer"></div>
                    
                    <div class="spacer"></div>
                                    
                    <h1>Otros</h1>
                    <p>Otra información de utilidad</p>
                    <div class="spacer"></div>
                    <label>Comentarios</label>
                    <textarea name="OTR_comentarios" id="OTR_comentarios" cols="45" rows="5" class="input4"><?php echo $rwanalisis[OTR_comentarios];?></textarea>
                    <div class="spacer"></div>
                    
                    <button type="submit">Actualizar Análisis</button>
                    <div class="spacer"></div>
                    <input type="hidden" name="organizacion" id="organizacion"  value="<?php echo $_GET[organizacion]; ?>" /><!--organizacion-->
                    <input type="hidden" name="oportunidad" id="oportunidad"  value="<?php echo $_GET[id]; ?>" /><!--oportunidad-->
                    <input type="hidden" name="an" id="an"  value="<?php echo $_GET[an]; ?>" /><!--análisis-->
                    <input type="hidden" name="a" id="a"  value="<?php echo $_GET[a]; ?>" /><!-- archivo: Oportunidades organizaciones -->
                    <input type="hidden" name="o" id="o"  value="<?php echo $o; ?>" /><!-- operación: Update -->
                  </form><!--FIN DE FORM INSERTAR ANÁLISIS-->
                </div>
				<?php
			}//fin else
		}//fin while
		?>
        </div><!--fin de midtxt-->
      </div>
    </div>
  </div>
</div>
<div id="footerbg">
  <div id="footerblank">
    <div id="footer">
      <div id="footerlinks">
      	<a href="" class="footerlinks">Dashboard</a> | 
        <a href="" class="footerlinks">Contactos</a> | 
        <a href="" class="footerlinks">Actividades</a> | 
        <a href="" class="footerlinks">Ventas</a> | 
        <a href="" class="footerlinks">Casos</a>
      </div>
      <div id="copyrights">© anabiosis. Todos los derechos reservados.</div>
    </div>
  </div>
  		
</div>

<script type="text/javascript" src="../../js/ext/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="../../js/ventanas-modales.js"></script>
</body>
</html>
