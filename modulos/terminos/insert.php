<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
include ("../../includes/FusionCharts.php");

$claveagente=$_SESSION[Claveagente];
$responsable=$_SESSION[Rol];
$claveorganizacion=$_POST[organizacion];

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

<!-- page specific scripts -->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<script>
$(function() {
	$( "#date" ).datepicker({
		showButtonPanel: false,
		buttonImage: "../../images/calendar.gif",
		buttonImageOnly: true,
		dateFormat: "yy-mm-dd",
		dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
		dayNames: [ "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado" ],
		monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
		nextText: "Siguiente",
		prevText: "Anterior",
		altField: "#alternate",
		altFormat: "DD, d MM, yy",
		changeMonth: true,
      	changeYear: true
	});
});
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
                <li class=""><a href="#">Resumen</a></li>
                <li class=""><a href="checklistview.php?organizacion=<?php echo $claveorganizacion;?>">Checklist</a></li>
                <li class=""><a href="#">Archivos</a></li>
                <li class="selected"><a href="oportunidades.php?organizacion=<?php echo $claveorganizacion;?>">Oportunidades <span class="count important" <?php if($overdueopt==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueopt; ?></span></a></li>
                <li class=""><a href="actividades.php?organizacion=<?php echo $claveorganizacion;?>">Actividades <span class="count important" <?php if($overdueact==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueact; ?></span></a></li>
                <li class=""><a href="venta.php?organizacion=<?php echo $claveorganizacion;?>">Venta</a></li>
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
			$resultconorg= mysql_query ($sqlconorg,$db);
			
			//print_r($_POST);

			switch($_POST[o])
            {
				case 'I'://Insertar análisis
					$claveanalisis = generateKey();
					$sqlanalisis="INSERT INTO `analisis`(`id_analisis`, `clave_analisis`, `id_oportunidad`, `clave_organizacion`, `POD_facultades_empresa`, `POD_poderes_representante`, `POD_facultades_garante`, `HIS_original_solicitante`, `HIS_puntual_solicitante`, `HIS_vigente_solicitante`, `HIS_29_solicitante`, `HIS_89_solicitante`, `HIS_90_solicitante`, `HIS_calificacion_solicitante`, `HIS_maximo_solicitante`, `HIS_mes_solicitante`, `HIS_original_representante`, `HIS_puntual_representante`, `HIS_vigente_representante`, `HIS_29_representante`, `HIS_89_representante`, `HIS_90_representante`, `HIS_calificacion_representante`, `HIS_maximo_representante`, `HIS_mes_representante`, `HIS_original_accionista`, `HIS_puntual_accionista`, `HIS_vigente_accionista`, `HIS_29_accionista`, `HIS_89_accionista`, `HIS_90_accionista`, `HIS_calificacion_accionista`, `HIS_maximo_accionista`, `HIS_mes_accionista`, `HIS_incidencias_solicitante`, `HIS_incidencias_solicitante_detalle`, `HIS_incidencias_representante`, `HIS_incidencias_representante_detalle`, `HIS_incidencias_accionista`, `HIS_incidencias_accionista_detalle`, `FLU_cuenta`, `FLU_banco`, `FLU_mes1`, `FLU_inicial_mes1`, `FLU_depositos_mes1`, `FLU_retiros_mes1`, `FLU_promedio_mes1`, `FLU_mes2`, `FLU_inicial_mes2`, `FLU_depositos_mes2`, `FLU_retiros_mes2`, `FLU_promedio_mes2`, `FLU_mes3`, `FLU_inicial_mes3`, `FLU_depositos_mes3`, `FLU_retiros_mes3`, `FLU_promedio_mes3`, `FLU_mes4`, `FLU_inicial_mes4`, `FLU_depositos_mes4`, `FLU_retiros_mes4`, `FLU_promedio_mes4`, `FLU_mes5`, `FLU_inicial_mes5`, `FLU_depositos_mes5`, `FLU_retiros_mes5`, `FLU_promedio_mes5`, `FLU_mes6`, `FLU_inicial_mes6`, `FLU_depositos_mes6`, `FLU_retiros_mes6`, `FLU_promedio_mes6`, `FLU_promedio_inicial`, `FLU_promedio_depositos`, `FLU_promedio_retiros`, `FLU_promedio_promedio`, `GAR_propietario`, `GAR_relacion`, `GAR_domicilio`, `GAR_ciudad`, `GAR_estado`, `GAR_cp`, `GAR_construccion`, `GAR_terreno`, `GAR_valor`, `LIS_listas_empresa`, `LIS_listas_empresa_detalle`, `LIS_listas_representante`, `LIS_listas_representante_detalle`, `LIS_listas_accionista`, `LIS_listas_accionista_detalle`, `LIS_listas_garante`, `LIS_listas_garante_detalle`, `LIS_google_empresa`, `LIS_google_empresa_detalle`, `LIS_google_representante`, `LIS_google_representante_detalle`, `LIS_google_accionista`, `LIS_google_accionista_detalle`, `LIS_google_garante`, `LIS_google_garante_detalle`, `usuario`, `fecha`) VALUES (NULL,'$claveanalisis','$_POST[oportunidad]','$claveorganizacion', '$_POST[POD_facultades_empresa]', '$_POST[POD_poderes_representante]', '$_POST[POD_facultades_garante]', '$_POST[HIS_original_solicitante]', '$_POST[HIS_puntual_solicitante]', '$_POST[HIS_vigente_solicitante]', '$_POST[HIS_29_solicitante]', '$_POST[HIS_89_solicitante]', '$_POST[HIS_90_solicitante]', '$_POST[HIS_calificacion_solicitante]', '$_POST[HIS_maximo_solicitante]', '$_POST[HIS_mes_solicitante]', '$_POST[HIS_original_representante]', '$_POST[HIS_puntual_representante]', '$_POST[HIS_vigente_representante]', '$_POST[HIS_29_representante]', '$_POST[HIS_89_representante]', '$_POST[HIS_90_representante]', '$_POST[HIS_calificacion_representante]', '$_POST[HIS_maximo_representante]', '$_POST[HIS_mes_representante]', '$_POST[HIS_original_accionista]', '$_POST[HIS_puntual_accionista]', '$_POST[HIS_vigente_accionista]', '$_POST[HIS_29_accionista]', '$_POST[HIS_89_accionista]', '$_POST[HIS_90_accionista]', '$_POST[HIS_calificacion_accionista]', '$_POST[HIS_maximo_accionista]', '$_POST[HIS_mes_accionista]', '$_POST[HIS_incidencias_solicitante]', '$_POST[HIS_incidencias_solicitante_detalle]', '$_POST[HIS_incidencias_representante]', '$_POST[HIS_incidencias_representante_detalle]', '$_POST[HIS_incidencias_accionista]', '$_POST[HIS_incidencias_accionista_detalle]', '$_POST[FLU_cuenta]', '$_POST[FLU_banco]', '$_POST[FLU_mes1]', '$_POST[FLU_inicial_mes1]', '$_POST[FLU_depositos_mes1]', '$_POST[FLU_retiros_mes1]', '$_POST[FLU_promedio_mes1]', '$_POST[FLU_mes2]', '$_POST[FLU_inicial_mes2]', '$_POST[FLU_depositos_mes2]', '$_POST[FLU_retiros_mes2]', '$_POST[FLU_promedio_mes2]', '$_POST[FLU_mes3]', '$_POST[FLU_inicial_mes3]', '$_POST[FLU_depositos_mes3]', '$_POST[FLU_retiros_mes3]', '$_POST[FLU_promedio_mes3]', '$_POST[FLU_mes4]', '$_POST[FLU_inicial_mes4]', '$_POST[FLU_depositos_mes4]', '$_POST[FLU_retiros_mes4]', '$_POST[FLU_promedio_mes4]', '$_POST[FLU_mes5]', '$_POST[FLU_inicial_mes5]', '$_POST[FLU_depositos_mes5]', '$_POST[FLU_retiros_mes5]', '$_POST[FLU_promedio_mes5]', '$_POST[FLU_mes6]', '$_POST[FLU_inicial_mes6]', '$_POST[FLU_depositos_mes6]', '$_POST[FLU_retiros_mes6]', '$_POST[FLU_promedio_mes6]', '$_POST[FLU_promedio_inicial]', '$_POST[FLU_promedio_depositos]', '$_POST[FLU_promedio_retiros]', '$_POST[FLU_promedio_promedio]', '$_POST[GAR_propietario]', '$_POST[GAR_relacion]', '$_POST[GAR_domicilio]', '$_POST[GAR_ciudad]', '$_POST[GAR_estado]', '$_POST[GAR_cp]', '$_POST[GAR_construccion]', '$_POST[GAR_terreno]', '$_POST[GAR_valor]', '$_POST[LIS_listas_empresa]', '$_POST[LIS_listas_empresa_detalle]', '$_POST[LIS_listas_representante]', '$_POST[LIS_listas_representante_detalle]', '$_POST[LIS_listas_accionista]', '$_POST[LIS_listas_accionista_detalle]', '$_POST[LIS_listas_garante]', '$_POST[LIS_listas_garante_detalle]', '$_POST[LIS_google_empresa]', '$_POST[LIS_google_empresa_detalle]', '$_POST[LIS_google_representante]', '$_POST[LIS_google_representante_detalle]', '$_POST[LIS_google_accionista]', '$_POST[LIS_google_accionista_detalle]', '$_POST[LIS_google_garante]', '$_POST[LIS_google_garante_detalle]', '$claveagente', NOW())";
					
					mysql_query($sqlanalisis,$db);
					header("Location: http://www.anabiosiscrm.com.mx/premo/index.php"); 
					exit;
					break;
				
				case 'U':
					$sqlanalisis="UPDATE `analisis` SET 
					`POD_facultades_empresa`='$_POST[POD_facultades_empresa]',
					`POD_poderes_representante`='$_POST[POD_poderes_representante]',
					`POD_facultades_garante`='$_POST[POD_facultades_garante]',
					`HIS_original_solicitante`='$_POST[HIS_original_solicitante]',
					`HIS_puntual_solicitante`='$_POST[HIS_puntual_solicitante]',
					`HIS_vigente_solicitante`='$_POST[HIS_vigente_solicitante]',
					`HIS_29_solicitante`='$_POST[HIS_29_solicitante]',
					`HIS_89_solicitante`='$_POST[HIS_89_solicitante]',
					`HIS_90_solicitante`='$_POST[HIS_90_solicitante]',
					`HIS_calificacion_solicitante`='$_POST[HIS_calificacion_solicitante]',
					`HIS_maximo_solicitante`='$_POST[HIS_maximo_solicitante]',
					`HIS_mes_solicitante`='$_POST[HIS_mes_solicitante]',
					`HIS_original_representante`='$_POST[HIS_original_representante]',
					`HIS_puntual_representante`='$_POST[HIS_puntual_representante]',
					`HIS_vigente_representante`='$_POST[HIS_vigente_representante]',
					`HIS_29_representante`='$_POST[HIS_29_representante]',
					`HIS_89_representante`='$_POST[HIS_89_representante]',
					`HIS_90_representante`='$_POST[HIS_90_representante]',
					`HIS_calificacion_representante`='$_POST[HIS_calificacion_representante]',
					`HIS_maximo_representante`='$_POST[HIS_maximo_representante]',
					`HIS_mes_representante`='$_POST[HIS_mes_representante]',
					`HIS_original_accionista`='$_POST[HIS_original_accionista]',
					`HIS_puntual_accionista`='$_POST[HIS_puntual_accionista]',
					`HIS_vigente_accionista`='$_POST[HIS_vigente_accionista]',
					`HIS_29_accionista`='$_POST[HIS_29_accionista]',
					`HIS_89_accionista`='$_POST[HIS_89_accionista]',
					`HIS_90_accionista`='$_POST[HIS_90_accionista]',
					`HIS_calificacion_accionista`='$_POST[HIS_calificacion_accionista]',
					`HIS_maximo_accionista`='$_POST[HIS_maximo_accionista]',
					`HIS_mes_accionista`='$_POST[HIS_mes_accionista]',
					`HIS_incidencias_solicitante`='$_POST[HIS_incidencias_solicitante]',
					`HIS_incidencias_solicitante_detalle`='$_POST[HIS_incidencias_solicitante_detalle]',
					`HIS_incidencias_representante`='$_POST[HIS_incidencias_representante]',
					`HIS_incidencias_representante_detalle`='$_POST[HIS_incidencias_representante_detalle]',
					`HIS_incidencias_accionista`='$_POST[HIS_incidencias_accionista]',
					`HIS_incidencias_accionista_detalle`='$_POST[HIS_incidencias_accionista_detalle]',
					`FLU_cuenta`='$_POST[FLU_cuenta]',
					`FLU_banco`='$_POST[FLU_banco]',
					`FLU_mes1`='$_POST[FLU_mes1]',
					`FLU_inicial_mes1`='$_POST[FLU_inicial_mes1]',
					`FLU_depositos_mes1`='$_POST[FLU_depositos_mes1]',
					`FLU_retiros_mes1`='$_POST[FLU_retiros_mes1]',
					`FLU_promedio_mes1`='$_POST[FLU_promedio_mes1]',
					`FLU_mes2`='$_POST[FLU_mes2]',
					`FLU_inicial_mes2`='$_POST[FLU_inicial_mes2]',
					`FLU_depositos_mes2`='$_POST[FLU_depositos_mes2]',
					`FLU_retiros_mes2`='$_POST[FLU_retiros_mes2]',
					`FLU_promedio_mes2`='$_POST[FLU_promedio_mes2]',
					`FLU_mes3`='$_POST[FLU_mes3]',
					`FLU_inicial_mes3`='$_POST[FLU_inicial_mes3]',
					`FLU_depositos_mes3`='$_POST[FLU_depositos_mes3]',
					`FLU_retiros_mes3`='$_POST[FLU_retiros_mes3]',
					`FLU_promedio_mes3`='$_POST[FLU_promedio_mes3]',
					`FLU_mes4`='$_POST[FLU_mes4]',
					`FLU_inicial_mes4`='$_POST[FLU_inicial_mes4]',
					`FLU_depositos_mes4`='$_POST[FLU_depositos_mes4]',
					`FLU_retiros_mes4`='$_POST[FLU_retiros_mes4]',
					`FLU_promedio_mes4`='$_POST[FLU_promedio_mes4]',
					`FLU_mes5`='$_POST[FLU_mes5]',
					`FLU_inicial_mes5`='$_POST[FLU_inicial_mes5]',
					`FLU_depositos_mes5`='$_POST[FLU_depositos_mes5]',
					`FLU_retiros_mes5`='$_POST[FLU_retiros_mes5]',
					`FLU_promedio_mes5`='$_POST[FLU_promedio_mes5]',
					`FLU_mes6`='$_POST[FLU_mes6]',
					`FLU_inicial_mes6`='$_POST[FLU_inicial_mes6]',
					`FLU_depositos_mes6`='$_POST[FLU_depositos_mes6]',
					`FLU_retiros_mes6`='$_POST[FLU_retiros_mes6]',
					`FLU_promedio_mes6`='$_POST[FLU_promedio_mes6]',
					`FLU_promedio_inicial`='$_POST[FLU_promedio_inicial]',
					`FLU_promedio_depositos`='$_POST[FLU_promedio_depositos]',
					`FLU_promedio_retiros`='$_POST[FLU_promedio_retiros]',
					`FLU_promedio_promedio`='$_POST[FLU_promedio_promedio]',
					`GAR_propietario`='$_POST[GAR_propietario]',
					`GAR_relacion`='$_POST[GAR_relacion]',
					`GAR_domicilio`='$_POST[GAR_domicilio]',
					`GAR_ciudad`='$_POST[GAR_ciudad]',
					`GAR_estado`='$_POST[GAR_estado]',
					`GAR_cp`='$_POST[GAR_cp]',
					`GAR_construccion`='$_POST[GAR_construccion]',
					`GAR_terreno`='$_POST[GAR_terreno]',
					`GAR_valor`='$_POST[GAR_valor]',
					`LIS_listas_empresa`='$_POST[LIS_listas_empresa]',
					`LIS_listas_empresa_detalle`='$_POST[LIS_listas_empresa_detalle]',
					`LIS_listas_representante`='$_POST[LIS_listas_representante]',
					`LIS_listas_representante_detalle`='$_POST[LIS_listas_representante_detalle]',
					`LIS_listas_accionista`='$_POST[LIS_listas_accionista]',
					`LIS_listas_accionista_detalle`='$_POST[LIS_listas_accionista_detalle]',
					`LIS_listas_garante`='$_POST[LIS_listas_garante]',
					`LIS_listas_garante_detalle`='$_POST[LIS_listas_garante_detalle]',
					`LIS_google_empresa`='$_POST[LIS_google_empresa]',
					`LIS_google_empresa_detalle`='$_POST[LIS_google_empresa_detalle]',
					`LIS_google_representante`='$_POST[LIS_google_representante]',
					`LIS_google_representante_detalle`='$_POST[LIS_google_representante_detalle]',
					`LIS_google_accionista`='$_POST[LIS_google_accionista]',
					`LIS_google_accionista_detalle`='$_POST[LIS_google_accionista_detalle]',
					`LIS_google_garante`='$_POST[LIS_google_garante]',
					`LIS_google_garante_detalle`='$_POST[LIS_google_garante_detalle]',`usuario`='$claveagente',`fecha`=NOW() WHERE `id_analisis`='".$_POST[an]."'";
					echo $sqlanalisis;
					mysql_query($sqlanalisis,$db);
					header("Location: http://www.anabiosiscrm.com.mx/premo/index.php");

					break;
			}
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
