<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
include ("../../includes/FusionCharts.php");

$claveagente=$_SESSION[Claveagente];
$responsable=$_SESSION[Rol];
$claveorganizacion=$_GET[organizacion];

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

//Actividades atrasadas
$sqloverdueact="SELECT * FROM `actividades` WHERE `fecha` < '".$date."' AND `completa`!=1 AND `clave_organizacion`='".$claveorganizacion."' AND usuario=  '".$claveagente."'";
$resultadoact = mysql_query($sqloverdueact, $db);
$overdueact = mysql_num_rows($resultadoact);

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
	promedio_inicial.value = ((parseFloat(inicial1.value)+parseFloat(inicial2.value)+parseFloat(inicial3.value)+parseFloat(inicial4.value)+parseFloat(inicial5.value))/5).toFixed(2);
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
	var promedio_promedio = document.getElementById("FLU_promedio_promedio");
	promedio_promedio.value = ((parseFloat(promedio1.value)+parseFloat(promedio2.value)+parseFloat(promedio3.value)+parseFloat(promedio4.value)+parseFloat(promedio5.value))/5).toFixed(2);
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
	float:none;
	font-size:10px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:150px;
	margin:2px 5px 0 10px;
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
	text-align: right;
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
          <li><a href="index.php" class="contactos" title="Organizaciones"></a></li>
          <li><a href="../actividades/calendario.php" class="actividades" title="Actividades"></a></li>
          <li><a href="../oportunidades/oportunidades.php" class="oportunidades" title="Oportunidades"></a></li>
          <li><a href="../ventas/index.php" class="ventas" title="Acumulado Anual"></a></li>
          <li><a href="../evaluaciones/evaluacion.php" class="evaluaciones" title="Evaluaciones Mensuales"></a></li>
          <li><a href="" class="casos" title="Archivos"></a></li>
          <li><a href="../reportes/index.php" class="reportes" title="Reportes"></a></li>
          <?php
		  if($_SESSION["Tipo"]=="Sistema"){
		  ?>
          <li><a href="../configuracion/index.php" class="configuracion" title="Configuracion"></a></li>
          <?php
		  }
		  ?>
        </ul>
      </div>
      
      <div id="sesionlinks">Bienvenido <a href="modulos/agentes/detalles.php?agente=<?php echo $claveagente; ?>" class="sesionlinks"><?php echo $_SESSION["Nombre"]; ?> (<?php echo $_SESSION["Tipo"]; ?>)</a> | <a href="salir.php" class="sesionlinks">Cerrar sesión</a></div>
      
      <?php
	  $sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` LIKE '".$claveorganizacion."' ORDER BY id_organizacion ASC";
	  $resultorg= mysql_query ($sqlorg,$db);
	  $myroworg=mysql_fetch_array($resultorg);
	  ?>
      
      <div id="titulo"><?php echo $myroworg[organizacion]; ?></div>
      
      <div id="pageNav">
        <div id="pageNavWrapper">
			<ul class="pageNav">
                <li class=""><a href="../oportunidades/detalles.php?id=<?php echo $_GET[id]; ?>&o=U&a=oP&an=<?php echo $_GET[an]; ?>&organizacion=<?php echo $claveorganizacion; ?>">Resumen</a></li>
                <li class="selected"><a href="expediente.php?id=<?php echo $_GET[id]; ?>&o=U&a=oP&an=<?php echo $_GET[an]; ?>&organizacion=<?php echo $claveorganizacion; ?>">Expediente Preliminar</a></li>
                <li class=""><a href="../analisis/forminsert.php?id=<?php echo $_GET[id]; ?>&o=U&a=oP&an=<?php echo $_GET[an]; ?>&e=<?php echo $_GET[e]; ?>&organizacion=<?php echo $claveorganizacion;?>">Análisis de Crédito <span class="count important" <?php if($overdueopt==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueopt; ?></span></a></li>
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
		?>
		<table class="recordList" style="margin-top: 12px;">
		<thead>
			<tr>
			<th class="list-column-center" scope="col"></th>
			<th class="list-column-left" scope="col">Tipo Documento</th>
			<th class="list-column-left" scope="col">Documento</th>
			</tr>
		</thead>
		<tbody>
		
		<?php
		$sqltipos="SELECT * FROM tiposarchivos WHERE id_expediente='".$myrowexp[id_expediente]."'";
		$resulttipos= mysql_query ($sqltipos,$db);
		$sqlopt="SELECT * FROM oportunidades WHERE id_oportunidad='".$_GET[id]."'";
		$resultopt= mysql_query ($sqlopt,$db);
		while($myrowopt=mysql_fetch_array($resultopt))
		{
			//Responsable de la etapa en la que está la oportunidad
			$sqletapa="SELECT * FROM  `etapas` WHERE id_etapa = $myrowopt[id_etapa] ORDER BY id_etapa";
			$resultetapa=mysql_query($sqletapa,$db);
			$myrowetapa=mysql_fetch_array($resultetapa);
			//Color del responsable por etapa
			$sqlcolor="SELECT * FROM  `responsables` WHERE id_responsable = $myrowetapa[id_responsable]";
			$rscolor=mysql_query($sqlcolor,$db);
			$rwcolor=mysql_fetch_array($rscolor);
			
			$sqlexp="SELECT * FROM archivos JOIN tiposarchivos ON (tiposarchivos.id_tipoarchivo = archivos.id_tipoarchivo) WHERE tiposarchivos.id_expediente=1 AND archivos.id_oportunidad='".$_GET[id]."'";
			$resultexp= mysql_query ($sqlexp,$db);
			$myrowexp=mysql_fetch_array($resultexp);
		
			while($myrowexp=mysql_fetch_array($resultexp))
			{
				//Verificar si hay archivo cargado
				$sqlarchivo="SELECT * FROM archivos WHERE id_tipoarchivo='".$myrowtipos[id_tipoarchivo]."' AND id_oportunidad='".$_GET[id]."'";
				$resultarchivo= mysql_query ($sqlarchivo,$db);
				$myrowarchivo=mysql_fetch_array($resultarchivo);
				//Revisar historial del archivo
				$sqlhist="SELECT * FROM historialarchivos WHERE clave_archivo='".$myrowexp[clave_archivo]."' AND id_oportunidad='".$_GET[id]."' ORDER BY fecha_actividad DESC LIMIT 1";
				$rshist= mysql_query ($sqlhist,$db);
				$rwhist=mysql_fetch_array($rshist);
				?>
				<tr class="odd-row" style="background-color:<?php echo $celda; ?>;">
				<td class="list-column-center"><img src="../../images/aproved_16.png"  /></td>
				<td class=" list-column-left"><?php echo $myrowexp[tipo_archivo];?>
				<td class=" list-column-left"><img src="../../images/acrobat_16.png"  class="linkImage" /> <a href="../../expediente/<?php echo $myrowexp[nombre]; ?>" target="_blank"><span class="highlight" style="background-color:#<?php echo $rwcolor[color];?>; font-weight:normal;"> <?php echo $myrowexp[nombre_original]; ?> </span></a></td>
				</tr>
			<?php
			}
		}
		?>
		</tbody>
		</table>
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
</body>
