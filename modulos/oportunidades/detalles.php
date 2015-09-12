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

<style>
.myform{
margin:0 auto;
width:660px;
padding:10px;
}
p, h1, form, button{border:0; margin:0; padding:0;}
.spacer{clear:both; height:1px;}

/* ----------- stylized ----------- */
#stylized{
	border:solid 1px #e3e3e3;
	background:#fff;
}
#stylized h1 {
font-size:14px;
font-weight:bold;
margin-bottom:8px;
}
#stylized p{
	font-size:11px;
	color:#666666;
	margin-bottom:20px;
	border-bottom:solid 1px #e3e3e3;
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
color:#666666;
display:block;
font-size:11px;
font-weight:normal;
text-align:right;
width:140px;
}
#stylized input{
	float:left;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:150px;
	margin:2px 0 20px 10px;
}

#stylized select{
	float:left;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:156px;
	margin:2px 0 20px 10px;
}

#stylized .select2{
	float:left;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:120px;
	margin:2px 0 20px 10px;
}

#stylized .input2 {
	float:left;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:456px;
	margin:2px 10px 20px 10px;
}

#stylized button{
clear:both;
margin-left:150px;
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
                <li class="selected"><a href="#">Resumen</a></li>
                <li class=""><a href="expediente.php?id=<?php echo $_GET[id]; ?>&o=U&a=oP&an=<?php echo $_GET[an]; ?>&organizacion=<?php echo $claveorganizacion; ?>">Expediente Preliminar</a></li>
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
			$resultcolor=mysql_query($sqlcolor,$db);
			$myrowcolor=mysql_fetch_array($resultcolor);
			
			if($myrowetapa[id_responsable]!=$responsable){?><span class="highlight" style="background-color:#<?php echo $myrowcolor[color];?>;"><?php echo $myrowetapa[etapa]; ?> (<?php echo $myrowetapa[probabilidad]; ?>%)</span><?php }else{}
			
			?>
			
			<table style="width:100%; margin-bottom:5px;">
			<tr>
			   <?php
				//Por qué etapas ha pasado la oportunidad
				$sqlproc="SELECT * FROM  `etapasoportunidades` WHERE clave_oportunidad = '".$myrowopt[clave_oportunidad]."' ORDER BY id_etapa ASC";
				$sqletapa="SELECT * FROM  `etapas` ORDER BY id_etapa";
				$resultetapa=mysql_query($sqletapa,$db);
				while($myrowetapa=mysql_fetch_array($resultetapa))
				{
					$sqlcolor="SELECT * FROM  `responsables` WHERE id_responsable = $myrowetapa[id_responsable]";
					$resultcolor=mysql_query($sqlcolor,$db);
					$myrowcolor=mysql_fetch_array($resultcolor);
					$resultproc=mysql_query($sqlproc,$db);
					while($myrowproc=mysql_fetch_array($resultproc))
					{
						if($myrowetapa[numero_etapa]==$myrowproc[id_etapa]){$color[$myrowproc[id_etapa]]=$myrowcolor[color];}else{if($myrowetapa[numero_etapa]>$myrowopt[id_etapa]){$color[$myrowetapa[numero_etapa]]="D6D6D6";}else{$color[$myrowproc[id_etapa]]="D6D6D6";}}
					}
					?>
					<td class="list-column-center" style="width:10%; padding-right:5px;">
					<div class="roundedpanel" style="height:55px; background-color:#<?php echo $color[$myrowetapa[numero_etapa]]; ?>;">
						<div class="roundedpanel-content" title="<?php echo $myrowetapa[etapa]." (".$myrowetapa[probabilidad]."%)"; ?>"><span style="font-size:14px; color:#FFF;"><?php echo $myrowetapa[numero_etapa]; ?></span><br /><span style="color:#FFF; font-size:9px;"><?php echo $myrowcolor[responsable]; ?></span></div>
					</div>
					</td>
					<?php
				}
				?>
			</tr>
		</table>
			
			<div id="stylized" class="myform">
			<form id="form" name="form" method="post" action="update.php" enctype="multipart/form-data">
			<h1>Actualizar proceso de promoción</h1>
			<p></p>
			
			<?php
			if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador")
			{
				?>
				<label>Promotor
				</label>
				<select id="promotor" name="promotor" class="input2">
				<option value="0">Sin Asignar</option>
				<?php
				$sqlagt="SELECT * FROM usuarios WHERE tipo = 'Promotor' ORDER BY apellidopaterno ASC";
				$resultagt= mysql_query ($sqlagt,$db);
				while($myrowagt=mysql_fetch_array($resultagt))
				{
					?>
					<option value="<?php echo $myrowagt[claveagente]; ?>" <?php if($myrowagt[claveagente]==$myrowopt[usuario]){echo "selected";}?>><?php echo $myrowagt[apellidopaterno]." ".$myrowagt[apellidomaterno]." ".$myrowagt[nombre]; ?></option>
					<?php
				}
				?>
				</select>
				<div class="spacer"></div>
				<?php
			}
			?>
			
			<label>Etapa
			</label>
			<select name="id_etapa" class="input2" id="id_etapa">
			<?php
			if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador"){$sqletapa="SELECT * FROM  `etapas` ORDER BY id_etapa";}else{$sqletapa="SELECT * FROM  `etapas` WHERE id_responsable = $responsable ORDER BY id_etapa";}
			$resultetapa=mysql_query($sqletapa,$db);
			while($myrowetapa=mysql_fetch_array($resultetapa))
			{
			?>
				<option value="<?php echo $myrowetapa[id_etapa]; ?>" <?php if($myrowopt[id_etapa]==$myrowetapa[id_etapa]){echo "selected";}?> <?php if($myrowetapa[etapa_anterior]!=$myrowopt[id_etapa]){ echo "disabled style='color:#999;'"; } ?>><?php echo $myrowetapa[numero_etapa]." - ".$myrowetapa[etapa]; ?></option>
			<?php
			}
			?>
			</select>
			<div class="spacer"></div>
			
			<label>Tipo de Crédito
			</label>
			<select name="tipo_credito" id="tipo_credito">
				<option value="" <?php if($myrowopt[tipo_credito]==""){echo "selected";}?>>Sin especificar</option>
				<option value="Revolvente" <?php if($myrowopt[tipo_credito]=="Revolvente"){echo "selected";}?>>Revolvente</option>
			</select>
			
			<label>Monto <span class="small">Monto del crédito solicitado</span>
			</label>
			<input type="text" name="monto_credito" id="monto_credito" value="<?php echo $myrowopt[monto]; ?>"/>
			<div class="spacer"></div>
			
			<label>Plazo:
			</label>
			<select name="plazo_credito" id="plazo_credito">
				<option value="" <?php if($myrowopt[plazo_credito]==""){echo "selected";}?>>Sin especificar</option>
				<option value="24" <?php if($myrowopt[plazo_credito]=="24"){echo "selected";}?>>24 meses</option>
				<option value="60" <?php if($myrowopt[plazo_credito]=="60"){echo "selected";}?>>60 meses</option>
			</select>
			<div class="spacer"></div>
			
			<label>Destino del Crédito
			</label>
			<input type="text" class="input2" name="destino_credito" id="destino_credito" value="<?php echo $myrowopt[destino_credito]; ?>"/>
		  
			<button type="submit">Grabar</button>
			<div class="spacer"></div>
			<input type="hidden" name="organizacion" id="organizacion"  value="<?php echo $claveorganizacion; ?>" /><!--organizacion-->
	<input type="hidden" name="oportunidad" id="oportunidad"  value="<?php echo $_GET[id]; ?>" /><!--oportunidad-->
	<input type="hidden" name="a" id="a"  value="<?php echo $_GET[a]; ?>" /><!-- archivo: Oportunidades organizaciones -->
	<input type="hidden" name="o" id="o"  value="<?php echo $_GET[o]; ?>" /><!-- operación: Update -->
			</form>
			</div>
			<?php
		}//fin de while oportunidades
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
