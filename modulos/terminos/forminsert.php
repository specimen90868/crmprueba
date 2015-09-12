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
<script type="text/javascript" src="../../js/FusionCharts.js"></script>
<script type="text/javascript" src="../../assets/ui/js/jquery.min.js" language="Javascript"></script>
<script type="text/javascript" src="../../assets/ui/js/lib.js" language="Javascript"></script>

<link rel="StyleSheet" href="estilos.css" type="text/css">
<link rel="icon" href="images/icon.ico" />

<!-- page specific scripts -->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>

<script>
$(function() {
	$( "#date" ).datepicker({
		showButtonPanel: false,
        minDate: '+0d',
		showOn: "button",
		buttonImage: "../../images/calendar.gif",
		buttonImageOnly: true,
		dateFormat: "yy-mm-dd",
		dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
		dayNames: [ "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado" ],
		monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
		nextText: "Siguiente",
		prevText: "Anterior",
		altField: "#alternate",
		altFormat: "DD, d MM, yy"
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
	float:right;
	font-size:10px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:150px;
	margin:2px 5px 0 10px;
}

#stylized .input2 {
	float:none;
	font-size:12px;
	padding:0 0 0 0;
	margin:0 0 0 0;
	width: 20px;
	text-align: center;
}

#stylized textarea{
	float:none;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:920px;
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
	margin:0 0 0 0;
	text-align: right;
}

#stylized .inputizq {
	float:none;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:500px;
	margin:2px 0 5px 10px;
	text-align: left;
}

#stylized button{
	clear:both;
	margin-left:456px;
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
                <li class=""><a href="../oportunidades/expediente.php?id=<?php echo $_GET[id]; ?>&o=U&a=oP&an=<?php echo $_GET[an]; ?>&organizacion=<?php echo $claveorganizacion; ?>">Expediente Preliminar</a></li>
                <li class=""><a href="oportunidades.php?organizacion=<?php echo $claveorganizacion;?>">Análisis de Crédito <span class="count important" <?php if($overdueopt==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueopt; ?></span></a></li>
                <li class="selected"><a href="../terminos/forminsert.php?organizacion=<?php echo $claveorganizacion;?>">Términos y condiciones<span class="count important" <?php if($overdueopt==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueopt; ?></span></a></li>
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
		//Datos de oportunidad
		$sqlopt="SELECT * FROM  `oportunidades` WHERE `id_oportunidad`='".$_GET[id]."'";
		$rsopt= mysql_query($sqlopt,$db);
		$total=mysql_num_rows($rsopt);
		while($rwopt=mysql_fetch_array($rsopt))
		{
			$amortizacion=round(((pow((1+($rwopt[interes]/100)),$rwopt[plazo_credito]))*$rwopt[monto]*($rwopt[interes]/100)/(pow((1+($rwopt[interes]/100)),$rwopt[plazo_credito])-1)),2);
			$sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` LIKE '".$claveorganizacion."' ORDER BY id_organizacion ASC";
			$resultorg= mysql_query ($sqlorg,$db);
			//Contactos de la Organización
			$sqlconorg="SELECT * FROM `contactos` WHERE `clave_organizacion` LIKE '".$claveorganizacion."' ORDER BY id_contacto ASC";
			$resultorg= mysql_query ($sqlconorg,$db);
			$myrowconorg=mysql_fetch_array($resultorg);
			$empresa=$myroworg[organizacion];
			
			?>
            <div id="stylized" class="myform">
            <form id="form" name="form" method="post" action="insert.php" enctype="multipart/form-data">
            <div><img src="../../images/encabezado.jpg" width="938" height="70" /></div>
            <div class="spacer"></div>
            <h1>
            <input type="text" id="alternate" size="30" value="<?php if($_GET['fecha']){echo htmlentities(strftime("%A, %e %B, %Y", strtotime($_GET['fecha'])));}else{echo htmlentities(strftime("%A, %e %B, %Y", strtotime($date)));} ?>" class="inputder"/>
            </h1>
            <div class="spacer"></div>
            <h1>
            <input type="text" name="FLU_cuenta3" id="FLU_cuenta3" class="inputizq" value="<?php echo $myrowconorg[apellidos]." ".$myrowconorg[nombre]; ?>"/>
            </h1>
            <input type="text" name="FLU_cuenta4" id="FLU_cuenta4" class="inputizq" value="<?php echo $myroworg[organizacion]; ?>"/>
            <div class="spacer"></div>
            <textarea name="textarea2" id="textarea2" cols="45" rows="5"></textarea>
            <div class="spacer"></div>
            <h1>Términos y condiciones</h1>
            <table class="recordList" style="margin-top: 12px;">
            <tbody>
            <tr class="odd-row">
              <td class="list-column-left">Tipo de Crédito:</td>
              <td class="list-column-center"><input name="LIS_google_accionista_detalle11" type="text" id="LIS_google_accionista_detalle19" style="width:592px;" value="<?php echo $rwopt[LIS_google_accionista_detalle];?>"/></td>
            </tr>
            <tr class="odd-row">
              <td class="list-column-left">Monto:</td>
              <td class="list-column-center"><input name="LIS_google_accionista_detalle11" type="text" id="LIS_google_accionista_detalle20" style="width:592px;" value="<?php echo $rwopt[monto];?>"/></td>
            </tr>
            <tr class="odd-row">
              <td class="list-column-left">Tasa de Interés:</td>
              <td class="list-column-center"><input name="LIS_google_accionista_detalle11" type="text" id="LIS_google_accionista_detalle21" style="width:592px;" value="<?php echo $rwopt[interes];?>"/></td>
            </tr>
            <tr class="odd-row">
              <td class="list-column-left">Garantía:</td>
              <td class="list-column-center"><input name="LIS_google_accionista_detalle11" type="text" id="LIS_google_accionista_detalle22" style="width:592px;" value="<?php echo $rwopt[LIS_google_accionista_detalle];?>"/></td>
            </tr>
            <tr class="odd-row">
              <td class="list-column-left">Aforo:</td>
              <td class="list-column-center"><input name="LIS_google_accionista_detalle11" type="text" id="LIS_google_accionista_detalle23" style="width:592px;" value="<?php echo $rwopt[LIS_google_accionista_detalle];?>"/></td>
            </tr>
            <tr class="odd-row">
              <td class="list-column-left">Comisión por apertura:</td>
              <td class="list-column-center"><input name="LIS_google_accionista_detalle11" type="text" id="LIS_google_accionista_detalle24" style="width:592px;" value="<?php echo $rwopt[LIS_google_accionista_detalle];?>"/></td>
            </tr>
            <tr class="odd-row">
              <td class="list-column-left">Gastos de formalización:</td>
              <td class="list-column-center"><input name="LIS_google_accionista_detalle11" type="text" id="LIS_google_accionista_detalle25" style="width:592px;" value="<?php echo $rwopt[LIS_google_accionista_detalle];?>"/></td>
            </tr>
            <tr class="odd-row">
              <td class="list-column-left">Estimación del valor de la garantía:</td>
              <td class="list-column-center"><input name="LIS_google_accionista_detalle11" type="text" id="LIS_google_accionista_detalle26" style="width:592px;" value="<?php echo $rwanalisis[LIS_google_accionista_detalle];?>"/></td>
            </tr>
            <tr class="odd-row">
              <td class="list-column-left">Vigencia:</td>
              <td class="list-column-center"><input name="LIS_google_accionista_detalle11" type="text" id="LIS_google_accionista_detalle27" style="width:592px;" value="<?php echo $rwanalisis[LIS_google_accionista_detalle];?>"/></td>
            </tr>
            </tbody>
            </table>
            <h1>&nbsp; </h1>
            <h1>Proceso para formalizar y disponer del crédito</h1>
            <table class="recordList" style="margin-top: 12px;">
            <tbody>
            <tr class="odd-row">
              <td class="list-column-left">Expediente:</td>
              <td class="list-column-center"><input name="LIS_google_accionista_detalle12" type="text" id="LIS_google_accionista_detalle28" style="width:592px;" value="<?php echo $rwanalisis[LIS_google_accionista_detalle];?>"/></td>
            </tr>
            </tbody>
            </table>
            <div class="spacer"></div>
            <table class="recordList" style="margin-top: 12px;">
            <thead>
            <tr>
              <th class="list-column-center" scope="col">Solicitante</th>
              <th class="list-column-center" scope="col">Garante</th>
              <th class="list-column-center" scope="col">Accionistas</th>
              <th class="list-column-center" scope="col">Representante Legal</th>
              <th class="list-column-center" scope="col">Documentación Requerida</th>
            </tr>
            </thead>
            <tbody>
            <tr class="odd-row">
              <td class="list-column-center"><input type="checkbox" name="checkbox21" id="checkbox21" class="input2"/></td>
              <td class="list-column-center"><input type="checkbox" name="checkbox21" id="checkbox22" class="input2"/></td>
              <td class="list-column-center"><input type="checkbox" name="checkbox21" id="checkbox23" class="input2"/></td>
              <td class="list-column-center"><input type="checkbox" name="checkbox21" id="checkbox24" class="input2"/></td>
              <td class="list-column-center">Solicitud de Crédito</td>
            </tr>
            <tr class="odd-row">
              <td class="list-column-center"><input type="checkbox" name="checkbox21" id="checkbox25" class="input2"/></td>
              <td class="list-column-center"><input type="checkbox" name="checkbox21" id="checkbox26" class="input2"/></td>
              <td class="list-column-center"><input type="checkbox" name="checkbox21" id="checkbox27" class="input2"/></td>
              <td class="list-column-center"><input type="checkbox" name="checkbox21" id="checkbox28" class="input2"/></td>
              <td class="list-column-center">Autorización para consultar al buró de crédito</td>
            </tr>
            <tr class="odd-row">
              <td class="list-column-center"><input type="checkbox" name="checkbox21" id="checkbox29" class="input2"/></td>
              <td class="list-column-center"><input type="checkbox" name="checkbox21" id="checkbox30" class="input2"/></td>
              <td class="list-column-center"><input type="checkbox" name="checkbox21" id="checkbox31" class="input2"/></td>
              <td class="list-column-center"><input type="checkbox" name="checkbox21" id="checkbox32" class="input2"/></td>
              <td class="list-column-center">Identificaciones oficiales</td>
            </tr>
            <tr class="odd-row">
              <td class="list-column-center"><input type="checkbox" name="checkbox21" id="checkbox33" class="input2"/></td>
              <td class="list-column-center"><input type="checkbox" name="checkbox21" id="checkbox34" class="input2"/></td>
              <td class="list-column-center"><input type="checkbox" name="checkbox21" id="checkbox35" class="input2"/></td>
              <td class="list-column-center"><input type="checkbox" name="checkbox21" id="checkbox36" class="input2"/></td>
              <td class="list-column-center" id="FLU_promedio_promedio">Comprobante de domicilio vigente (antigüedad no mayor a tres meses)</td>
            </tr>
            <tr class="odd-row">
              <td class="list-column-center"><input type="checkbox" name="checkbox21" id="checkbox37" class="input2"/></td>
              <td class="list-column-center"><input type="checkbox" name="checkbox21" id="checkbox38" class="input2"/></td>
              <td class="list-column-center"><input type="checkbox" name="checkbox21" id="checkbox39" class="input2"/></td>
              <td class="list-column-center"><input type="checkbox" name="checkbox21" id="checkbox40" class="input2"/></td>
              <td class="list-column-center" id="FLU_promedio_promedio2">Acta de matrimonio. Aplica para acreditado y dueño de las garantías</td>
            </tr>
            <tr class="odd-row">
              <td class="list-column-center">&nbsp;</td>
              <td class="list-column-center">&nbsp;</td>
              <td class="list-column-center">&nbsp;</td>
              <td class="list-column-center">&nbsp;</td>
              <td class="list-column-center" id="FLU_promedio_promedio3">&nbsp;</td>
            </tr>
            </tbody>
            </table>
            <div class="spacer"></div>
            <table class="recordList" style="margin-top: 12px;">
            <tbody>
            <tr class="odd-row">
              <td class="list-column-left">Proceso de revisión:</td>
              <td class="list-column-center"><input name="LIS_google_accionista_detalle13" type="text" id="LIS_google_accionista_detalle29" style="width:592px;" value="<?php echo $rwanalisis[LIS_google_accionista_detalle];?>"/></td>
            </tr>
            <tr class="odd-row">
              <td class="list-column-left">Fecha estimada de firma:</td>
              <td class="list-column-center"><input name="LIS_google_accionista_detalle14" type="text" id="LIS_google_accionista_detalle30" style="width:592px;" value="<?php echo $rwanalisis[LIS_google_accionista_detalle];?>"/></td>
            </tr>
            <tr class="odd-row">
              <td class="list-column-left">Depósito de seriedad</td>
              <td class="list-column-center"><input name="LIS_google_accionista_detalle15" type="text" id="LIS_google_accionista_detalle31" style="width:592px;" value="<?php echo $rwanalisis[LIS_google_accionista_detalle];?>"/></td>
            </tr>
            </tbody>
            </table>
            <div class="spacer"></div>
            Atentamente
            <div class="spacer"></div>
            <input type="text" name="FLU_cuenta5" id="FLU_cuenta5" class="inputcen" />
            <div class="spacer"></div>
            <input type="text" name="FLU_cuenta6" id="FLU_cuenta6" class="inputcen"/>
            <div class="spacer"></div>
            <button type="submit">Grabar</button>
            <div class="spacer"></div>
            <input type="hidden" name="organizacion" id="organizacion"  value="<?php echo $_GET[organizacion]; ?>" /><!--organizacion-->
            <input type="hidden" name="oportunidad" id="oportunidad"  value="<?php echo $_GET[id]; ?>" /><!--oportunidad-->
            <input type="hidden" name="an" id="an"  value="<?php echo $_GET[an]; ?>" /><!--análisis-->
            <input type="hidden" name="a" id="a"  value="<?php echo $_GET[a]; ?>" /><!-- archivo: Oportunidades organizaciones -->
            <input type="hidden" name="o" id="o"  value="<?php echo $o; ?>" /><!-- operación: Update -->
            </form>
            </div>
            <?php
		}
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
</body>
