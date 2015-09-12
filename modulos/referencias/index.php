<?php
//error_reporting(E_ERROR);
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
$claveagente=$_SESSION[Claveagente];

$msj="";
extract($_POST);
if ($action == "upload")
{
	$archivo = $_FILES['excel']['name'];
	$tipo = $_FILES['excel']['type'];
	$destino = "bak_".$archivo;
	if (!copy($_FILES['excel']['tmp_name'],$destino)) {$msj="Error al cargar el archivo. "; $color="#F00";}
	
	if (file_exists ("bak_".$archivo))
	{ 
		require_once 'Excel/reader.php';
		$data = new Spreadsheet_Excel_Reader();
		// Set output Encoding.
		$data->setOutputEncoding('CP1251');
		$data->read($destino);
		error_reporting(E_ALL ^ E_NOTICE);
		$insertados=0; $errores=0;
		for ($i = 2; $i <= count($data->sheets[0]["cells"]); $i++) 
		{
			$organizacion=utf8_encode($data->sheets[0]["cells"][$i][1]);
			$clave_unica=$data->sheets[0]["cells"][$i][2];
			$fecha_fundacion=$data->sheets[0]["cells"][$i][3];
			$procedencia=utf8_encode($data->sheets[0]["cells"][$i][4]);
			if(!$data->sheets[0]["cells"][$i][5]){$tipo_organizacion='Prospecto';}else{$tipo_organizacion=$data->sheets[0]["cells"][$i][5];}
			$clave_agente=$data->sheets[0]["cells"][$i][6];
			if(!$clave_agente){$promotor=$claveagente;}else{$promotor=$clave_agente;}
			$giro=utf8_encode($data->sheets[0]["cells"][$i][7]);
			$estatus=$data->sheets[0]["cells"][$i][8];
			$tipo_persona=$data->sheets[0]["cells"][$i][9];
			$clave_web=$data->sheets[0]["cells"][$i][10];
			$forma_contacto=utf8_encode($data->sheets[0]["cells"][$i][11]);
			$apellidos=utf8_encode($data->sheets[0]["cells"][$i][12]);
			$nombre=utf8_encode($data->sheets[0]["cells"][$i][13]);
			$rep_legal=$data->sheets[0]["cells"][$i][14];
			$dia_cumpleanios=$data->sheets[0]["cells"][$i][15];
			$mes_cumpleanios=$data->sheets[0]["cells"][$i][16];
			$puesto=utf8_encode($data->sheets[0]["cells"][$i][17]);
			$telefono_casa=$data->sheets[0]["cells"][$i][18];
			$telefono_oficina=$data->sheets[0]["cells"][$i][19];
			$telefono_celular=$data->sheets[0]["cells"][$i][20];
			$correo=$data->sheets[0]["cells"][$i][21];
			$titulo=$data->sheets[0]["cells"][$i][22];
			$rfc=$data->sheets[0]["cells"][$i][23];
			$curp=$data->sheets[0]["cells"][$i][24];
			if(!$data->sheets[0]["cells"][$i][25]){$tipo_domicilio='Principal';}else{$tipo_domicilio=$data->sheets[0]["cells"][$i][25];}
			$domicilio=utf8_encode($data->sheets[0]["cells"][$i][26]);
			$ciudad=utf8_encode($data->sheets[0]["cells"][$i][27]);
			$estado=utf8_encode($data->sheets[0]["cells"][$i][28]);
			$pais=utf8_encode($data->sheets[0]["cells"][$i][29]);
			$cp=$data->sheets[0]["cells"][$i][30];
	
			$claveorganizacion = generateKey();
			$sqlorganizaciones="INSERT INTO `organizaciones` (`id_organizacion` , `clave_organizacion` , `tipo_registro` , `organizacion` , `clave_unica` , `fecha_fundacion` ,`procedencia` , `tipo_organizacion` , `clave_agente` , `giro` , `estatus` , `logo` , `capturo` , `fecha_captura` , `hora_captura` , `modifico` , `fecha_modificacion` , `hora_modificiacion` , `usuario_ultimo_contacto` , `fecha_ultimo_contacto` , `hora_ultimo_contacto` , `observaciones`, `tipo_persona`, `clave_web`, `forma_contacto`,`asignado`) VALUES 
	(NULL , '$claveorganizacion', 'O', '$organizacion', '$clave_unica', '$fecha_fundacion', '$procedencia', '$tipo_organizacion', '$promotor', '$giro', '1', '', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '', '$tipo_persona', '$clave_web', '$forma_contacto', '0')";
			$clavecontacto = generateKey();	
			$sqlcontactos="INSERT INTO `contactos` (`id_contacto`, `clave_contacto`, `tipo_registro`, `clave_unica`, `clave_organizacion`, `organizacion`, `apellidos`, `nombre`, `nombre_completo`, `rep_legal`, `tipo_persona`, `genero`, `edad`, `fecha_nacimiento`, `dia_cumpleanios`, `mes_cumpleanios`, `ocupacion`, `puesto`, `telefono_casa`, `telefono_oficina`, `telefono_celular`, `telefono_otro1`, `telefono_otro2`, `titulo`, `rfc`, `curp`, `procedencia`, `escolaridad`, `estado_civil`, `potencial`, `tipo_contacto`, `clave_agente`, `estatus`, `aprobado`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `usuario_ultimo_contacto`, `fecha_ultimo_contacto`, `hora_ultimo_contacto`, `observaciones`) VALUES (NULL, '$clavecontacto', 'C', '$clave_unica', '$claveorganizacion', '$organizacion','$apellidos', '$nombre', '', '$rep_legal', '', '', '', '', '$dia_cumpleanios', '$mes_cumpleanios', '','$puesto', '', '$telefono_oficina', '$telefono_celular', '', '', '$titulo', '$rfc', '$curp', '$procedencia', '', '', '', '$tipo_organizacion', '$promotor', '1', '', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '')";
			$sqldomorganizaciones="INSERT INTO `domicilios` (`id_domicilio`, `tipo_registro`, `clave_registro`, `tipo_domicilio`, `domicilio`, `ciudad`, `estado`, `pais`, `cp`, `latitud`, `longitud`, `mapa`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `observaciones`) VALUES (NULL, 'O', '$claveorganizacion', '$tipo_domicilio', '$domicilio', '$ciudad', '$estado', '$pais', '$cp', '', '', '', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '')";
			$sqlemailcontactos="INSERT INTO `correos` (`id_correo`, `tipo_registro`, `clave_registro`, `tipo_correo`, `correo`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `observaciones`) VALUES (NULL, 'C', '$clavecontacto', 'Trabajo', '$correo', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '')";
			
			//echo $sqlorganizaciones."\n\n";
			$rsorg = mysql_query($sqlorganizaciones);
			$rscon = mysql_query($sqlcontactos);
			$rsmail = mysql_query($sqlemailcontactos);
			$rsdom = mysql_query($sqldomorganizaciones);
			mysql_query("SET NAMES 'utf8'");
			if ( $rsorg === false ){
				 $msj.= "El registro ".$organizacion." no fue insertado<br />";
				 $errores++;
			}
			else{$insertados++;$color="#302369";}
		} //fin de for
		$msj.= "Archivo importado con éxito, ".$insertados." registros insertados y ".$errores." errores";
		unlink($destino);		
	}//fin if existe archivo
	//si por algo no cargo el archivo bak_ 
	else
	{
	$msj.= "Necesitas elegir un archivo para importar. ";
	}

}//fin de accion upload

elseif($action == "verificar")
{
	foreach( $_POST['Asignados'] as $asignado)
	{
		$sqlref="SELECT * FROM `referencias` WHERE `id_referencia`='".$asignado."'";
		$resultref= mysql_query ($sqlref,$db);
		$myrowref=mysql_fetch_array($resultref);
		
		$sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` LIKE '".$myrowref[clave_cliente]."'";
		$resultorg= mysql_query ($sqlorg,$db);
		$myroworg=mysql_fetch_array($resultorg);
		
		$sqlopt="SELECT * FROM `oportunidades` WHERE `clave_oportunidad` = '".$myrowref[clave_oportunidad]."'";
		$resultopt= mysql_query($sqlopt,$db);
		$myrowopt=mysql_fetch_array($resultopt);
		
		//Datos de la etapa en la que está
		$sqletp="SELECT * FROM `etapas` WHERE `id_etapa` = '".$myrowopt[id_etapa]."'";
		$resultetp= mysql_query($sqletp,$db);
		$myrowetp=mysql_fetch_array($resultetp);
		//Siguiente etapa
		$sqletps="SELECT * FROM `etapas` WHERE `id_etapa` = '".$myrowetp[etapa_siguiente]."'";
		$resultetps= mysql_query($sqletps,$db);
		$myrowetps=mysql_fetch_array($resultetps);
		
		//Actualizar el registro de la oportunidad para AVANZAR
		$sqlupdate="UPDATE `oportunidades` SET `marcado`='0', `motivo`='', `id_etapa`='$myrowetp[etapa_siguiente]', `fecha_modificacion` = NOW() WHERE `clave_oportunidad` = '".$myrowopt[clave_oportunidad]."'";
		//Registrar avance de la oportunidad
		$sqletapaoportun="INSERT INTO `etapasoportunidades` (`id_etapaoportunidad` , `clave_oportunidad` , `id_etapa` , `etapa` , `fecha` , `usuario` , `hora`) VALUES (NULL , '$myrowopt[clave_oportunidad]', '$myrowetp[etapa_siguiente]', '$myrowetps[etapa]', NOW(), '$claveagente', NOW())";
		$sqlverificar="UPDATE `referencias` SET `verificado`='1', `fecha_verificacion`=NOW() WHERE `id_referencia`='".$asignado."'";
		
		mysql_query("SET NAMES UTF8");
		mysql_query ($sqlupdate,$db);
		mysql_query ($sqletapaoportun,$db);
		mysql_query ($sqlverificar,$db);
	}
	//Enviar mail
	$sqlpromotor="SELECT * FROM usuarios WHERE claveagente='".$myrowopt[usuario]."'";
	$rspromotor= mysql_query($sqlpromotor,$db);
	$rwpromotor=mysql_fetch_array($rspromotor);
	$headers = "MIME-Version: 1.1\n";
	$headers .= "Content-type: text/plain; charset=UTF-8\n";
	$headers .= "From: alarmascrm@anabiosis.com.mx\n"; // remitente
	$headers .= "Return-Path: alarmascrm@anabiosis.com.mx\n"; // return-path
	$cuerpo = "Hola ".$rwpromotor[nombre].", el usuario ".$claveagente." te ha enviado un proceso a la etapa: ".$myrowetps[etapa].", no olvides darle(s) seguimiento \n\n";
	$cuerpo .= "\nAdministrador del CRM";
	$asunto = $rwpromotor[nombre]." Tienes nuevos procesos asignados en el sistema";
	mail("denisse.ge@hotmail.com",$asunto,$cuerpo,$headers);
	//mail($rwpromotor[email],$asunto,$cuerpo,$headers);
}

if($_SESSION["Tipo"]=="Promotor"||$_SESSION["Tipo"]=="Supervisor"){$bus = " AND clave_agente ='$claveagente' ";}
else{$bus = "";}
 
if($_GET[busqueda])
{
	switch($_GET[tipo])
	{
		case 'Persona':
			$_pagi_sql="SELECT * FROM contactos WHERE (apellidos LIKE '%$_GET[busqueda]%' OR nombre LIKE '%$_GET[busqueda]%')".$bus." AND estatus= '1' ORDER BY apellidos ASC";
			break;
		case 'Organizacion':
			$_pagi_sql="SELECT * FROM organizaciones WHERE organizacion LIKE '%$_GET[busqueda]%'".$bus." AND estatus= '1' ORDER BY fecha_ultimo_contacto ASC";
			break;
		case 'Email':
			if($_SESSION["Tipo"]=="Usuario"){$_pagi_sql="SELECT * FROM correos WHERE (correo LIKE '%$_GET[busqueda]%') AND capturo ='".$claveagente."' ORDER BY tipo_registro ASC";}
			else{$_pagi_sql="SELECT * FROM correos WHERE (correo LIKE '%$_GET[busqueda]%') ORDER BY id_correo ASC";}
			break;
		case 'Telefono':
			//Buscar teléfono en la tabla de teléfonos (dónde están los de organizaciones)
			$sql_tel="SELECT * FROM telefonos WHERE (telefono LIKE '%$_GET[busqueda]%')".$bus."ORDER BY id_telefono ASC";
			$resulttel= mysql_query ($sql_tel,$db);
			$numtel=mysql_num_rows($resulttel);
			//Si el teléfono no existe en la tabla de teléfonos, entonces buscarlo en la tabla de contactos
			if($numtel==0){$_pagi_sql="SELECT * FROM contactos WHERE (telefono_casa LIKE '%".$busqueda."%' OR telefono_oficina LIKE '%".$_GET[busqueda]."%' OR telefono_celular LIKE '%".$_GET[busqueda]."%' OR telefono_otro1 LIKE '%".$_GET[busqueda]."%' OR telefono_otro2 LIKE '%".$_GET[busqueda]."%')".$bus."ORDER BY apellidos ASC"; $tabla="contactos";}
			else{$_pagi_sql="SELECT * FROM telefonos WHERE (telefono LIKE '%$_GET[busqueda]%')".$bus."ORDER BY id_telefono ASC"; $tabla="telefonos";}
			break;
		case 'Direccion':
			$_pagi_sql="SELECT * FROM domicilios WHERE (domicilio LIKE '%$_GET[busqueda]%') ORDER BY id_domicilio ASC";
			break;
		case 'Clave':
			$_pagi_sql="SELECT * FROM organizaciones WHERE (clave_unica LIKE '%$_GET[busqueda]%') ORDER BY organizacion ASC";
			break;
		case 'Social':
			$_pagi_sql="SELECT DISTINCT(Anunciante),K_Cliente FROM ventas WHERE (`Anunciante` LIKE '%$_GET[busqueda]%') ORDER BY Anunciante ASC";
			break;
		case 'RFC':
			$_pagi_sql="SELECT DISTINCT(Rfc),K_Cliente,Anunciante FROM ventas WHERE (`Rfc` LIKE '%$_GET[busqueda]%') ORDER BY Rfc ASC";
			break;
	}
	//$_pagi_sql = "SELECT * FROM organizaciones WHERE organizacion LIKE '%$_GET[busqueda]%'".$bus."ORDER BY organizaciones.organizacion ASC";
}
else
{
	if($_SESSION["Tipo"]=="Promotor"||$_SESSION["Tipo"]=="Supervisor"){$_pagi_sql = "SELECT * FROM organizaciones WHERE clave_agente ='$claveagente' AND estatus='1' ORDER BY fecha_ultimo_contacto ASC";}
else{$_pagi_sql = "SELECT * FROM referencias ORDER BY fecha_asignacion DESC";}
}
$_pagi_cuantos = 10;
$_pagi_nav_num_enlaces= 30;
include("paginator.inc.php");

//echo $_pagi_sql;

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

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>crm</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Type" content="charset=UTF-8" /> 
<meta http-equiv="Content-Language" content="es-ES" />

<link rel="stylesheet" type="text/css" href="../../style.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../../css/styleform.css" />
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../../js/jquery-1.2.1.pack.js"></script>

<link rel="stylesheet" type="text/css" href="../../css/ventanas-modales.css">
<script type="text/javascript" src="js/cmxform.js"></script>
<script type="text/javascript" src="funciones.js"></script>
<link rel="icon" href="images/icon.ico" />

<script type="text/javascript">
function avanzar(id,o,a,av,org)
{
	var a = confirm("¿Est\xe1 seguro de avanzar con las condiciones actuales del proceso?");
	if(a)
	{
		var dir = "modulos/oportunidades/update.php?id="+id+"&o="+o+"&a="+a+"&av="+av+"&organizacion="+org;
		window.open(dir);
	}
	else return false;
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
      

  <div id="titulo">Referencias Bancarias</div>

      
      <div id="pageNav">
        <div id="pageNavWrapper">
    
        <ul class="pageNav">
            <li class="selected"><a href="#">Verificar Referencias</a></li>
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
        
        
        
        <fieldset class="fieldsetgde">
            <legend>Filtar lista por</legend>
            <form name="frmbusqueda" action="" onsubmit="buscarorg(); return false">
              <div>Termino a buscar:
                <input type="text" id="dato" name="dato" onkeyup=";" onblur="" value="<?php echo $_SESSION[Search]; ?>" />
                <select name="tipo_registro" size="1" onchange="">
                    <option value="">Seleccionar</option>
                    <option value="Referencia">Referencia</option>
                    <option value="Organizacion" selected="selected">Organización</option>
				</select> 
                <input type="submit" value="Buscar">
              </div>
              
            </form>
        </fieldset>
        
        <fieldset class="fieldsetgde">
        <legend>Importar lista de referencias <img src="../../images/excel_16.png" class="linkImage" /><a href="../../formatos/importacion.xls">Descargar archivo de ejemplo</a></legend>
        	<form name="importa" method="post" action="<?php echo $PHP_SELF; ?>" enctype="multipart/form-data" >
            <label>Subir archivo: </label>
            <input type="file" name="excel" />
            <input type='submit' name='action'  value="upload"  /><span class="highlight" style="background-color:<?php echo $color; ?>; color:#FFF;"><?php echo $msj; ?></span>
        	</form>
        </fieldset>

            <div id="resultadoorg">
            <fieldset class="fieldsetgde">
            <legend>Mostrando <?php echo $_pagi_info; ?> referencias</legend>
            
            <form action="<?php echo $PHP_SELF; ?>" method="post">
            <?php if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador"||$_SESSION["Tipo"]=="Supervisor")
			{
			?>
            <i>Para los elementos que están seleccionados,</i>
            <img src="../../images/assigned_16.png" class="linkImage" />    
            <input type='submit' name='action' value="verificar" style="background-color: #9FC733; border: 1px solid #9FC733; color: #fff; border-radius: 5px;"/>
            <?php
			}
			?>
            <table id="" class="recordList">
            <tbody>
			<?php
            while($myrowref=mysql_fetch_array($_pagi_result))
            {
                //Datos de la organización
				$sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` LIKE '".$myrowref[clave_cliente]."'";
				$resultorg= mysql_query ($sqlorg,$db);
				$myroworg=mysql_fetch_array($resultorg);
				
				//Estatus de la referencia
				if($myrowref[asignada]==0){$estatus="No asignada"; $color="#444444";$celda="#FFFFFF";}elseif($myrowref[descartado]==1){$estatus="Descartada"; $color="#FF7F7F";$celda="#FFf2f2";$highlight="#C1C1C1";}elseif($myrowref[verificado]==1){$estatus="Verificada"; $color="#9FC733";$celda="#FFFFFF";}else{$estatus="Sin verificar"; $color="#C1C1C1";$celda="#FFFFFF";}
				
				//Telefonos de Organización
				$sqltelorg="SELECT * FROM `telefonos` WHERE `clave_registro` LIKE '".$myrowref[clave_cliente]."' AND `tipo_registro` = 'O' ORDER BY id_telefono ASC LIMIT 1";
				$resulttelorg= mysql_query ($sqltelorg,$db);
				$telorg=""; $tipotelorg="";
				while($myrowtelorg=mysql_fetch_array($resulttelorg))
				{	
					$telorg=$myrowtelorg[telefono];
					$tipotelorg=$myrowtelorg[tipo_telefono];
				}
				//if($alerta[$myrowref['id_organizacion']]){$celda="#FFf2f2";}else{$celda="#FFFFFF";}
				
				//Emails de Organización
				$sqlemailorg="SELECT * FROM `correos` WHERE `clave_registro` LIKE '".$myrowref[clave_cliente]."' AND `tipo_registro` = 'O' ORDER BY id_correo ASC LIMIT 1";
				$resultemailorg= mysql_query ($sqlemailorg,$db);
				$mailorg="";
				while($myrowmailorg=mysql_fetch_array($resultemailorg))
				{	
					$mailorg=$myrowmailorg[correo];
				}
				
				//Domicilios de la Organización
				$sqldomorg="SELECT * FROM `domicilios` WHERE `clave_registro` LIKE '".$myrowref[clave_cliente]."' AND `tipo_registro` = 'O' ORDER BY id_domicilio ASC LIMIT 1";
				$resultdomorg= mysql_query ($sqldomorg,$db);
				$domicilio="";
				while($myrowdomorg=mysql_fetch_array($resultdomorg))
				{	
					$domicilio = $myrowdomorg[domicilio];
					if($myrowdomorg[ciudad])
					{
						$domicilio.=", ".$myrowdomorg[ciudad];
						if($myrowdomorg[estado])
						{
							$domicilio.=", ".$myrowdomorg[estado];
							if($myrowdomorg[cp])
							{
								$domicilio.=", ".$myrowdomorg[cp];
								if($myrowdomorg[pais])
								{
									$domicilio.=", ".$myrowdomorg[pais];
								}
							}
						}
					} 
				}
				
				//Razones sociales y RFC de Organización
				$sqlrfc="SELECT * FROM `razonessociales` WHERE `clave_registro` LIKE '".$myrowref[clave_cliente]."' AND `tipo_registro` = 'O' ORDER BY id_razonsocial ASC LIMIT 1";
				//echo $sqlrfc;
				$resultrfc= mysql_query ($sqlrfc,$db);
				$numrfc=mysql_num_rows($resultrfc);
				$razonsocial="";
				$rfc="";
				while($myrowrfc=mysql_fetch_array($resultrfc))
				{	
					$razonsocial=$myrowrfc[razon_social];
					$rfc=$myrowrfc[rfc];
				}

				//Datos del Agente
				$sqlagente="SELECT * FROM `usuarios` WHERE `claveagente` LIKE '".$myrowref[clave_agente]."'";
				$resultagente= mysql_query ($sqlagente,$db);
				while($myrowagente=mysql_fetch_array($resultagente))
            	{
					$agente=$myrowagente[nombre]." ".$myrowagente[apellidopaterno];
				}
				//Contactos de la organización
				$sqlconorg="SELECT * FROM `contactos` WHERE `clave_organizacion` LIKE '".$myrowref[clave_cliente]."'";
				$resultconorg= mysql_query ($sqlconorg,$db);
				$totcump=0;
				$totcont=mysql_num_rows($resultconorg);
				while($myrowconorg=mysql_fetch_array($resultconorg))
            	{
					$teloficina=$myrowconorg[telefono_oficina];
					if($myrowconorg[dia_cumpleanios]!='0'&&$myrowconorg[dia_cumpleanios]!='0')
					{
						$totcump++;
					}	
				}
				
				// para la organización
				$sqlopt="SELECT * FROM `oportunidades` WHERE `clave_oportunidad` = '".$myrowref[clave_oportunidad]."'";
				$resultopt= mysql_query($sqlopt,$db);
				$myrowopt=mysql_fetch_array($resultopt);
				$id_etapa = $myrowopt[id_etapa];
				//Consultar detalles de responsable y color para la etapa en la que se encuentra la oportunidad listada
				$sqletp="SELECT * FROM `etapas` WHERE `id_etapa` = '".$id_etapa."'";
				$resultetp= mysql_query($sqletp,$db);
				$myrowetp=mysql_fetch_array($resultetp);
				$etapa = $myrowetp[etapa];
				
				$sqlcolor="SELECT * FROM  `responsables` WHERE id_responsable = $myrowetp[id_responsable]";
				$resultcolor=mysql_query($sqlcolor,$db);
				$myrowcolor=mysql_fetch_array($resultcolor);
				
				//Datos del proceso
				$sqlproceso="SELECT * FROM `etapasoportunidades` WHERE `clave_oportunidad` = '".$myrowopt[clave_oportunidad]."' AND id_etapa='7'";
				//echo $sqlproceso;
				$rsproceso= mysql_query($sqlproceso,$db);
				$rwproceso=mysql_fetch_array($rsproceso);
				
				$sqlarchivos="SELECT * FROM archivos JOIN tiposarchivos ON (tiposarchivos.id_tipoarchivo = archivos.id_tipoarchivo) WHERE tiposarchivos.id_expediente='3' AND archivos.id_oportunidad='".$myrowopt[id_oportunidad]."'";
				$resultarchivos= mysql_query ($sqlarchivos,$db);
				$rwarchivos=mysql_fetch_array($resultarchivos);
				
				//Vigencia de la propuesta
				list($dias, $meses) = diferencia_dias($rwproceso[fecha],$date);
				$vigencia=30-$dias;
				
				if($dias<30)
				{
					if($vigencia<=5){$resaltadocontacto="#FF7F7F";}
					elseif($vigencia>=6&&$vigencia<=11){$resaltadocontacto="#FFCC00";}
					else{$resaltadocontacto="#86CE79";}
				}
				else
				{
					$vigencia="La propuesta ha expirado";$resaltadocontacto="#FF7F7F";
				}
				$archivo="<img src='../../images/acrobat_16.png' class='linkImage' /> <a href='../../expediente/".$rwarchivos[nombre]."' target='_blank'><span class='highlight' style='background-color:#".$myrowcolor[color]."; font-weight:normal;'> ".$rwarchivos[nombre_original]."</span></a>";
				if($myrowref[descartado]==0){$archivo.="<span class='count important' style='background-color:".$resaltadocontacto.";' title='Vigencia de la propuesta'>".$vigencia."</span>";}
				
				?>
				<tr class="even-row" style="background-color:<?php echo $celda; ?>">
                <?php
				if($myrowref[asignada]==1&&$myrowref[descartado]==0&&$myrowopt[id_etapa]==9)
				{
					?>
                    <td class="list-column-checkbox"><input type="checkbox" name="Asignados[]" id="Asignados[]" value="<?php echo $myrowref['id_referencia']; ?>" /></td>
                    <?php
				}
				else
				{
					?>
                    <td class="list-column-checkbox"></td>
                    <?php
				}
				?>
                <td class=" list-column-left">
                    <a class="keytext" href="detalles.php?organizacion=<?php echo $myrowref[clave_organizacion]; ?>" style="text-transform:uppercase;"><?php echo resaltar($_GET[busqueda],$myrowref[referencia]); ?></a> <?php if($myrowref[asignada]==1){?>asignada a <a class="keytext" href="../organizaciones/detalles.php?organizacion=<?php echo $myroworg[clave_organizacion]; ?>" style="text-transform:uppercase;"><?php echo $myroworg[organizacion]; ?></a> <?php } if($myrowref[clave_unica]){?><span class="highlight"><?php echo $myrowref[clave_unica]; ?></span> <?php }
					?>
					<?php echo $rel; if($myrowopt){?><span class='highlight' style='background-color:#<?php if($myrowopt[id_etapa]==11){echo "C1C1C1";} else{echo $myrowcolor[color];} ?>' title='Etapa del proceso abierto'><?php echo $etapa; ?></span><?php }?>
                    <br />
                    <span class="subtext">
                        <span class="nobreaktext"><?php if($domicilio){echo $domicilio;} if($rfc){echo $rfc;} ?></span>
                    </span>
                    <br />
                    <?php if($myrowref['asignada']==1){echo $archivo; }?>
                </td>
                <td class=" list-column-left">
					<span class="highlight" style="background-color:<?php echo $color; ?>"><?php echo $estatus;?></span>
                </td>
                </tr>
				<?php
            }
			?>
</tbody>
</table>
<?php //Incluimos la barra de navegación 
echo"<p align='center'>".$_pagi_navegacion."</p>"; ?>
		</form>
        </fieldset>
        </div>
        </div>
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
