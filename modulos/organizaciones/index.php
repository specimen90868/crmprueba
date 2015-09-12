<?php
//error_reporting(E_ERROR);
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
$claveagente=$_SESSION[Claveagente];
$nivel=2;

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

elseif($action == "asignar")
{
	$contactos=0;
	foreach( $_POST['Asignados'] as $asignado)
	{
		$sqlasignado="UPDATE `organizaciones` SET `clave_agente`='$_POST[agente]', `asignado`='1' WHERE id_organizacion='".$asignado."'";
		mysql_query("SET NAMES UTF8");
		mysql_query ($sqlasignado,$db);
		$contactos++;
	}
	//Enviar mail
	$sqlpromotor="SELECT * FROM usuarios WHERE claveagente='".$_POST[agente]."'";
	$rspromotor= mysql_query($sqlpromotor,$db);
	$rwpromotor=mysql_fetch_array($rspromotor);
	$headers = "MIME-Version: 1.1\n";
	$headers .= "Content-type: text/plain; charset=UTF-8\n";
	$headers .= "From: alarmascrm@anabiosis.com.mx\n"; // remitente
	$headers .= "Return-Path: alarmascrm@anabiosis.com.mx\n"; // return-path
	$cuerpo = "Hola ".$rwpromotor[nombre]." el usuario ".$claveagente." te ha asignado ".$contactos." nuevo(s) contacto(s), no olvides darles seguimiento \n\n";
	$cuerpo .= "\nAdministrador del CRM";
	$asunto = $rwpromotor[nombre]." Tienes nuevos contactos asignados en el sistema";
	//mail("dmedina@am.com.mx",$asunto,$cuerpo,$headers);
	mail($rwpromotor[email],$asunto,$cuerpo,$headers);
	
}
elseif($action == "descartar")
{
	foreach( $_POST['Asignados'] as $asignado )
	{
		$sqlvalidar="SELECT * FROM `organizaciones` WHERE id_organizacion='".$asignado."'";
		$rsvalidar= mysql_query ($sqlvalidar,$db);
		$rwvalidar=mysql_fetch_array($rsvalidar);
		if($rwvalidar[procedencia]=="Website"&&$rwvalidar[asignado]==0)
		{
			//echo "Se borrarán los registros: ".$asignado;
			$sqlasignado="UPDATE `organizaciones` SET `estatus`='0' WHERE id_organizacion='".$asignado."'";
			mysql_query ($sqlasignado,$db);
		}
		else
		{
			$alerta[$asignado]="El registro procede del Website y ya ha sido asignado a un promotor. No puede ser descartado";
		}
	}
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
else{$_pagi_sql = "SELECT * FROM organizaciones WHERE estatus='1' ORDER BY fecha_ultimo_contacto ASC";}
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
    <?php include('../../header.php'); ?>
    <div id="titulo">Organizaciones y Contactos</div>

      
      <div id="pageNav">
        <div id="pageNavWrapper">
    
        <ul class="pageNav">
            <li class="selected"><a href="#">Contactos</a></li>
            <li class=""><a href="listas.php">Listas</a></li>
           	<?php
           	if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador")
            {
			?>
           	<li class=""><a href="../asignacion/contacto.php">Contacto Website</a></li>
            <li class=""><a href="../asignacion/precalifica.php">Precalifica Website</a></li>
            <?php
            }
			?>
        </ul> 
        <ul class="pageActions">
            <li class="item"><img src="../../images/add.png" class="linkImage" /><a href="forminsert.php">Nueva Organización</a></li>  
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
                    <option value="Persona">Persona</option>
                    <option value="Organizacion" selected="selected">Organización</option>
                    <option value="Email">Email</option>
                    <option value="Telefono">Teléfono</option>
                    <option value="Direccion">Dirección</option>
                    <!--<option value="Clave">Clave Única</option>
                    <option value="Social">Razón Social</option>
                    <option value="RFC">RFC</option>-->
				</select> 
                <input type="submit" value="Buscar">
                <?php if($_SESSION["Tipo"]!="Usuario")
				{
				?>
                <label>Agente: </label>
                <select name="agente" size="1" onchange="buscarorg(); return false">
                    <option value="" selected="selected">Todos</option>
                    <?php
					$sqlagt="SELECT * FROM usuarios ORDER BY claveagente";
                    $resultagt= mysql_query ($sqlagt,$db);
                    while($myrowagt=mysql_fetch_array($resultagt))
                    {
					?>
                        <option value="<?php echo $myrowagt[claveagente]; ?>"><?php echo $myrowagt[nombre]." ".$myrowagt[apellidopaterno]; ?></option>
                    <?php
					}
					?>
                </select>
				<?php
                }
				?>
                
              </div>
              
            </form>
        </fieldset>
        
        <fieldset class="fieldsetgde">
        <legend>Importar lista de contactos <img src="../../images/excel_16.png" class="linkImage" /><a href="../../formatos/importacion.xls">Descargar archivo de ejemplo</a></legend>
        	<form name="importa" method="post" action="<?php echo $PHP_SELF; ?>" enctype="multipart/form-data" >
            <label>Subir archivo: </label>
            <input type="file" name="excel" />
            <input type='submit' name='action'  value="upload"  /><span class="highlight" style="background-color:<?php echo $color; ?>; color:#FFF;"><?php echo $msj; ?></span>
            <!--<input type="hidden" value="upload" name="action" />-->
        	</form>
        </fieldset>

            <div id="resultadoorg">
            <fieldset class="fieldsetgde">
            <legend>Mostrando <?php echo $_pagi_info; ?> organizaciones</legend>
            
            <form action="<?php echo $PHP_SELF; ?>" method="post">
            <?php if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador"||$_SESSION["Tipo"]=="Supervisor")
			{
			?>
            <i>Para los elementos que están seleccionados,</i>
            <label>asignar a: </label>
                <img src="../../images/user_16.png" class="linkImage" />
                <select name="agente" size="1">
                <option value="" selected="selected">Seleccionar Promotor</option>
                <?php
                $sqlagt="SELECT * FROM usuarios WHERE tipo='Promotor' OR tipo='Supervisor' ORDER BY claveagente";
                $resultagt= mysql_query ($sqlagt,$db);
                while($myrowagt=mysql_fetch_array($resultagt))
                {
                ?>
                    <option value="<?php echo $myrowagt[claveagente]; ?>"><?php echo $myrowagt[nombre]." ".$myrowagt[apellidopaterno]; ?></option>
                <?php
                }
                ?>
                </select>
                <img src="../../images/assigned_16.png" class="linkImage" />    
            <input type='submit' name='action' value="asignar" style="background-color: #9FC733; border: 1px solid #9FC733; color: #fff; border-radius: 5px;"/>
            <img src="../../images/trash_16.png" class="linkImage" />
            <input type='submit' name='action' value="descartar" style="background-color: #FF0000; border: 1px solid #FF0000; color: #fff; border-radius: 5px;"/>
            <?php
			}
			?>
            <table id="" class="recordList">
            <tbody>
			<?php
            while($myroworg=mysql_fetch_array($_pagi_result))
            {
                //Telefonos de Organización
				$sqltelorg="SELECT * FROM `telefonos` WHERE `clave_registro` LIKE '".$myroworg[clave_organizacion]."' AND `tipo_registro` = 'O' ORDER BY id_telefono ASC LIMIT 1";
				$resulttelorg= mysql_query ($sqltelorg,$db);
				$telorg=""; $tipotelorg="";
				while($myrowtelorg=mysql_fetch_array($resulttelorg))
				{	
					$telorg=$myrowtelorg[telefono];
					$tipotelorg=$myrowtelorg[tipo_telefono];
				}
				if($alerta[$myroworg['id_organizacion']]){$celda="#FFf2f2";}else{$celda="#FFFFFF";}
				
				//Emails de Organización
				$sqlemailorg="SELECT * FROM `correos` WHERE `clave_registro` LIKE '".$myroworg[clave_organizacion]."' AND `tipo_registro` = 'O' ORDER BY id_correo ASC LIMIT 1";
				$resultemailorg= mysql_query ($sqlemailorg,$db);
				$mailorg="";
				while($myrowmailorg=mysql_fetch_array($resultemailorg))
				{	
					$mailorg=$myrowmailorg[correo];
				}
				
				//Domicilios de la Organización
				$sqldomorg="SELECT * FROM `domicilios` WHERE `clave_registro` LIKE '".$myroworg[clave_organizacion]."' AND `tipo_registro` = 'O' ORDER BY id_domicilio ASC LIMIT 1";
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
				$sqlrfc="SELECT * FROM `razonessociales` WHERE `clave_registro` LIKE '".$myroworg[clave_organizacion]."' AND `tipo_registro` = 'O' ORDER BY id_razonsocial ASC LIMIT 1";
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
				
				if($myroworg[fecha_ultimo_contacto])
				{
					list($dias, $meses) = diferencia_dias($myroworg[fecha_ultimo_contacto],$date);
					if($dias>90){$ultimocontacto= $meses." meses"; $resaltadocontacto="#FF7F7F";}
					elseif($dias>=31&&$dias<=90){$ultimocontacto= $meses." meses"; $resaltadocontacto="#FFCC00";}
					else{if($meses==1){$ultimocontacto=$meses. "mes";}else{$ultimocontacto= $dias." días"; $resaltadocontacto="#86CE79";}}
				}
				else {$ultimocontacto="Ninguno"; $resaltadocontacto="#FF7F7F";} 
				
				//Datos del Agente
				$sqlagente="SELECT * FROM `usuarios` WHERE `claveagente` LIKE '".$myroworg[clave_agente]."'";
				$resultagente= mysql_query ($sqlagente,$db);
				while($myrowagente=mysql_fetch_array($resultagente))
            	{
					$agente=$myrowagente[nombre]." ".$myrowagente[apellidopaterno];
				}
				//Contactos de la organización
				$sqlconorg="SELECT * FROM `contactos` WHERE `clave_organizacion` LIKE '".$myroworg[clave_organizacion]."' ORDER BY id_contacto ASC";
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
				$sqlopt="SELECT * FROM `oportunidades` WHERE `clave_organizacion` = '".$myroworg[clave_organizacion]."' AND (`id_etapa`!=10 AND `id_etapa`!=11) ORDER BY `fecha_captura` ASC";
				$resultopt= mysql_query($sqlopt,$db);
				//Ciclo que recorre los datos de las  halladas en la consulta
				$myrowopt=mysql_fetch_array($resultopt);
				$id_etapa = $myrowopt[id_etapa];
				//Consultar detalles de responsable y color para la etapa en la que se encuentra la oportunidad listada
				$sqletp="SELECT * FROM `etapas` WHERE `id_etapa` = '".$id_etapa."'";
				$resultetp= mysql_query($sqletp,$db);
				$myrowetp=mysql_fetch_array($resultetp);
				$etapa = $myrowetp[etapa];
				$probabilidad = $myrowetp[probabilidad];
				$sqlcolor="SELECT * FROM  `responsables` WHERE id_responsable = $myrowetp[id_responsable]";
				$resultcolor=mysql_query($sqlcolor,$db);
				$myrowcolor=mysql_fetch_array($resultcolor);
				
				//Relaciones de los contactos
				$sqlrelacion="SELECT * FROM `relaciones` WHERE `clave_organizacion` LIKE '".$myroworg[clave_organizacion]."' ORDER BY id_rol ASC" ;
				$rsrelacion= mysql_query ($sqlrelacion,$db);
				$rel="";
				while($rwrelacion=mysql_fetch_array($rsrelacion))
				{
					$sqlrelcol="SELECT * FROM roles WHERE id_rol = '".$rwrelacion[id_rol]."'";
					$rscolor= mysql_query ($sqlrelcol,$db);
					$rwcolor=mysql_fetch_array($rscolor);
					$rel.="<span class='highlight' style='background:".$rwcolor[color]."' title='".$rwrelacion[rol]."'>".$rwrelacion[rol][0]."</span> ";
				}
				
				?>
				<tr class="even-row" style="background-color:<?php echo $celda; ?>">
                <?php
				if(($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador"))
				{
					?>
                    <td class="list-column-checkbox"><input type="checkbox" name="Asignados[]" id="Asignados[]" value="<?php echo $myroworg['id_organizacion']; ?>" /></td>
                    <?php
				}
				?>
                <td class="list-column-picture">
				<img class="picture-thumbnail" src="../../images/<?php if($myroworg[tipo_organizacion]=='Garante'){ echo "gar_avatar_32.png"; ?> <?php } else{ echo "org_avatar_32.png"; } ?>" width="32" height="32" alt="" />
                    
                </td>
                <td class=" list-column-left">
                    <a class="keytext" href="detalles.php?organizacion=<?php echo $myroworg[clave_organizacion]; ?>" style="text-transform:uppercase;"><?php echo resaltar($_GET[busqueda],$myroworg[organizacion]); ?></a> <?php if($myroworg[tipo_persona]){?> <span class="highlight"><?php echo $myroworg[tipo_persona][0]; ?></span> <?php } ?> <?php if($myroworg[clave_unica]){?><span class="highlight"><?php echo $myroworg[clave_unica]; ?></span> <?php } ?> <span class="highlight" style="background-color:<?php echo $resaltadocontacto; ?>;" title="Último contacto registrado"><?php echo $ultimocontacto; ?></span> <span class="highlight" style="background-color:<?php echo $resaltadocontacto; ?>;" title="Último contacto registrado"><?php echo $myroworg[fecha_ultimo_contacto]; ?></span>
					<?php echo $rel; if($myrowopt){?><span class='highlight' style='background-color:#<?php echo $myrowcolor[color]; ?>' title='Etapa del proceso abierto'><?php echo $etapa; ?></span><?php }?>
                    <br />
                    <?php echo $domicilio; ?>
                    <br />
                    <span class="subtext">Etiquetado como:
                        <span class="nobreaktext"><?php echo $myroworg[tipo_organizacion]; ?></span>,
                    </span>
                    <span class="subtext">Procedencia del contacto:
                        <span class="nobreaktext"><?php echo $myroworg[procedencia]; ?></span>
                    </span>
                    <br />
					<?php if($alerta[$myroworg['id_organizacion']]){?>
                    <img src="../../images/exclamation.png" class="linkImage"/><span style='color:#FF0000; font-size:10px;'><?php echo $alerta[$myroworg['id_organizacion']]; ?></span>
                    <?php } ?>
                </td>
                
                <td class=" list-column-left">
                    <a target="" href="mailto:<?php echo $mailorg; ?>"><?php echo $mailorg; ?></a><br /><?php if($telorg){echo format_Telefono($telorg)." (".$tipotelorg.")"; }else{echo format_Telefono($teloficina)." (Oficina)"; }?><br />
					<?php 
					if($_SESSION["Tipo"]!="Promotor")
					{
						if($myroworg[asignado]==1)
						{
							?><span class="highlight" style="background-color:#9FC733"><?php echo $agente;?></span>
                        <?php 
						}
						else
						{
							?><span class="highlight">No asignado</span>
                        <?php 
						}
					}
						?>
                </td>
                </tr>
				<?php
            }
            while($myrowcon=mysql_fetch_array($resultcon))
            {
                ?>
                <tr class="even-row">
                <td class="list-column-picture"><img class="picture-thumbnail" src="../../images/person_avatar_32.png" width="32" height="32" alt="picture" /></td>
                <td class=" list-column-left">
                        <a class="keytext" href="detalles.php?organizacion=<?php echo $myrowcon[clave_organizacion]; ?>"><?php echo $myrowcon[apellidos]." ".$myrowcon[nombre] ; ?></a> <?php echo $myrowcon[puesto]; ?>
                            en <a href="detalles.php?organizacion=<?php echo $myroworg[clave_organizacion]; ?>"><?php echo $myrowcon[organizacion]; ?></a></td>
                <td class=" list-column-left">
                <a target="" href="mailto:denisse.ge@hotmail.com">correo</a>
                    <br />
                Teléfono (Tipo)
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
