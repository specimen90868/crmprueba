<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");

$claveagente=$_SESSION[Claveagente];

extract($_POST);
if ($action == "upload")
{
	//cargamos el archivo al servidor con el mismo nombre
	//solo le agregue el sufijo bak_ 
	$archivo = $_FILES['excel']['name'];
	$tipo = $_FILES['excel']['type'];
	$destino = "bak_".$archivo;
	if (copy($_FILES['excel']['tmp_name'],$destino)) echo "Archivo Cargado Con Éxito";
	else echo "Error Al Cargar el Archivo";

	if (file_exists ("bak_".$archivo))
	{ 
		require_once 'Excel/reader.php';
		// ExcelFile($filename, $encoding);
		$data = new Spreadsheet_Excel_Reader();
		// Set output Encoding.
		$data->setOutputEncoding('CP1251');
		$data->read($destino);
		error_reporting(E_ALL ^ E_NOTICE);
		$insertados=0; $errores=0;
		for ($i = 4; $i <= count($data->sheets[0]["cells"]); $i++) 
		{
			$serial = $data->sheets[0]["cells"][$i][1];
			$sid = $data->sheets[0]["cells"][$i][2];
			$date = explode(" ",$data->sheets[0]["cells"][$i][3]);
			$fecha = $date[1]." ".$date[3].":00";
			$hora = date("Y-m-d H:i:s",  strtotime($fecha));
			$draft = $data->sheets[0]["cells"][$i][4];
			$direccion_ip = $data->sheets[0]["cells"][$i][5];
			$uid = $data->sheets[0]["cells"][$i][6];
			$usuario = $data->sheets[0]["cells"][$i][7];
			$nombre = addslashes($data->sheets[0]["cells"][$i][8]);
			$telefono = $data->sheets[0]["cells"][$i][9];
			$email = $data->sheets[0]["cells"][$i][10];
			$empresa = addslashes($data->sheets[0]["cells"][$i][11]);
			$asunto = addslashes($data->sheets[0]["cells"][$i][12]);
			$medio = $data->sheets[0]["cells"][$i][13];
			$otro_medio = addslashes($data->sheets[0]["cells"][$i][14]);
			
			$sql = "INSERT INTO `contactanos`(`serial`, `sid`, `hora`, `draft`, `direccion_ip`, `uid`, `usuario`, `nombre`, `telefono`, `email`, `empresa`, `asunto`, `medio`, `otro_medio`, `asignado`) VALUES ('$serial','$sid','$hora','$draft','$direccion_ip','$uid','$usuario','$nombre','$telefono','$email','$empresa','$asunto','$medio','$otro_medio','0')";	
			echo $sql."\n\n";
			$rsinsert = mysql_query($sql);
			mysql_query("SET NAMES 'utf8'");
			if ( $rsinsert === false ){
				 echo "El registro ".$sid." no fue insertado<br />";
				 $errores++;
			}
			else{$insertados++;}
		} //fin de for
		echo "<strong><center>ARCHIVO IMPORTADO CON EXITO, EN TOTAL ".$insertados." REGISTROS Y ".$errores." ERRORES</center></strong>";
		unlink($destino);		
	}//fin if existe archivo
//si por algo no cargo el archivo bak_ 
else
{
	echo "Necesitas primero importar el archivo";
}


}//fin de accion upload
elseif($action == "asignar")
{
	foreach( $_POST['Asignados'] as $asignado )
	{
		$claveorganizacion = generateKey();
		$clavecontacto = generateKey();
		$sqlcontactoweb="SELECT * FROM contactanos WHERE sid='".$asignado."'";
		$rscontactoweb= mysql_query ($sqlcontactoweb,$db);
        $rwrscontactoweb=mysql_fetch_array($rscontactoweb);

		$sqlorganizaciones="INSERT INTO `organizaciones` (`id_organizacion` , `clave_organizacion` , `tipo_registro` , `organizacion` , `clave_unica` , `fecha_fundacion` ,`procedencia` , `tipo_organizacion` , `clave_agente` , `giro` , `estatus` , `logo` , `capturo` , `fecha_captura` , `hora_captura` , `modifico` , `fecha_modificacion` , `hora_modificiacion` , `usuario_ultimo_contacto` , `fecha_ultimo_contacto` , `hora_ultimo_contacto` , `observaciones`, `tipo_persona`, `clave_web`, `forma_contacto`,`asignado`) VALUES 
		(NULL , '$claveorganizacion', 'O', '$rwrscontactoweb[empresa]', '', '', 'Website', 'Prospecto', '$_POST[agente]', '', '', '', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '', '', '$rwrscontactoweb[sid]', '', '1')";
		
		$sqlcontactos="INSERT INTO `contactos` (`id_contacto`, `clave_contacto`, `tipo_registro`, `clave_unica`, `clave_organizacion`, `organizacion`, `apellidos`, `nombre`, `nombre_completo`, `rep_legal`, `tipo_persona`, `genero`, `edad`, `fecha_nacimiento`, `dia_cumpleanios`, `mes_cumpleanios`, `ocupacion`, `puesto`, `telefono_casa`, `telefono_oficina`, `telefono_celular`, `telefono_otro1`, `telefono_otro2`, `titulo`, `rfc`, `curp`, `procedencia`, `escolaridad`, `estado_civil`, `potencial`, `tipo_contacto`, `clave_agente`, `estatus`, `aprobado`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `usuario_ultimo_contacto`, `fecha_ultimo_contacto`, `hora_ultimo_contacto`, `observaciones`) VALUES (NULL, '$clavecontacto', 'C', '', '$claveorganizacion', '$rwrscontactoweb[empresa]','', '$rwrscontactoweb[nombre]', '$rwrscontactoweb[nombre]', '', '', '', '', '', '', '', '','', '', '$rwrscontactoweb[telefono]', '', '', '', '', '', '', '', '', '', '', 'Prospecto', '$_POST[agente]', '', '', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '$rwrscontactoweb[asunto]')";
		
		$sqlemailorganizaciones="INSERT INTO `correos` (`id_correo`, `tipo_registro`, `clave_registro`, `tipo_correo`, `correo`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `observaciones`) VALUES (NULL, 'O', '$clavecontacto', 'Trabajo', '$rwrscontactoweb[email]', '$_POST[agente]', NOW(), NOW(), '$claveagente', NOW(), NOW(), '')";
		
		$sqlasignado="UPDATE `contactanos` SET `asignado`='1' WHERE sid='".$asignado."'";
		
		mysql_query ($sqlorganizaciones,$db);
		mysql_query ($sqlcontactos,$db);
		mysql_query ($sqlemailorganizaciones,$db);
		mysql_query ($sqlasignado,$db);
	}
}

$_pagi_sql_join="SELECT * FROM webform_submitted_data JOIN webform_submissions ON webform_submissions.sid=webform_submitted_data.sid JOIN webform_component ON webform_component.nid=webform_submitted_data.nid WHERE webform_submitted_data.nid='4' GROUP BY webform_submissions.sid ORDER BY webform_submissions.submitted DESC";

$sql="SELECT d.data, d.sid, s.submitted, f.nid FROM webform_submitted_data d LEFT JOIN webform_submissions s ON d.sid = s.sidLEFT JOIN webform_component f ON f.nid = s.nid WHERE s.nid='4' ORDER BY s.submitted DESC";

$_pagi_sql="SELECT * FROM contactanos WHERE asignado='0' ORDER BY hora DESC";

$_pagi_cuantos = 10;
$_pagi_nav_num_enlaces= 30;
include("paginator.inc.php");

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

$str="Esto es una prueba  de cómo se vería Youtube luego de aplicar la función para youtube";    
//echo resaltar('YouTube', $str);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>crm</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../style.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../../css/styleform.css" />
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../../js/jquery-1.2.1.pack.js"></script>

<link rel="stylesheet" type="text/css" href="../../css/ventanas-modales.css">
<script type="text/javascript" src="js/cmxform.js"></script>
<script type="text/javascript" src="funciones.js"></script>
<link rel="icon" href="images/icon.ico" />

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
		  if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Administrador"]){
		  ?>
          <li><a href="../configuracion/index.php" class="configuracion" title="Configuracion"></a></li>
          <?php
		  }
		  ?>
        </ul>
      </div>
      
      <div id="sesionlinks">Bienvenido <a href="modulos/agentes/detalles.php?agente=<?php echo $claveagente; ?>" class="sesionlinks"><?php echo $_SESSION["Nombre"]; ?> (<?php echo $_SESSION["Tipo"]; ?>)</a> | <a href="../../salir.php" class="sesionlinks">Cerrar sesión</a></div>
      

  <div id="titulo">Organizaciones y Contactos</div>

      
      <div id="pageNav">
        <div id="pageNavWrapper">
    
        <ul class="pageNav">
            <li class=""><a href="../organizaciones/index.php">Contactos</a></li>
            <li class=""><a href="../organizaciones/listas.php">Listas</a></li>
           	<?php
           	if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador")
            {
			?>
           	<li class="selected"><a href="#">Contacto Website</a></li>
            <li class=""><a href="precalifica.php">Precalifica Website</a></li>
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
                <input type="text" id="dato" name="dato" onkeyup=";" onblur="" />
                <select name="tipo_registro" size="1" onchange="">
                  <option value="">Seleccionar</option>
                  <option value="Persona">Persona</option>
                  <option value="Organizacion" selected="selected">Organización</option>
                  <option value="Email">Email</option>
                  <option value="Telefono">Teléfono</option>
                  <option value="Direccion">Dirección</option>
                  <option value="Clave">Clave Única</option>
                  <option value="Social">Razón Social</option>
                  <option value="RFC">RFC</option>
                </select>
                <input type="submit" value="Buscar">
              </div>
            </form>
        </fieldset>
        
        <fieldset class="fieldsetgde">
        <legend>Importar lista de contactos</legend>
        	<form name="importa" method="post" action="<?php echo $PHP_SELF; ?>" enctype="multipart/form-data" >
            <label>Subir archivo: </label>
            <input type="file" name="excel" />
            <input type='submit' name='enviar'  value="Importar"  />
            <input type="hidden" value="upload" name="action" />
            </form>
        </fieldset>
        
            <div id="resultadoorg">
            <fieldset class="fieldsetgde">
            <legend>Mostrando <?php echo $_pagi_info; ?> registros</legend>
            <form action="<?php echo $PHP_SELF; ?>" method="post">
            <label>Asignar a: </label>
                <select name="agente" size="1">
                <option value="" selected="selected">Seleccionar</option>
                <?php
                $sqlagt="SELECT * FROM usuarios WHERE tipo = 'Promotor' OR tipo = 'Supervisor' ORDER BY claveagente";
                $resultagt= mysql_query ($sqlagt,$db);
                while($myrowagt=mysql_fetch_array($resultagt))
                {
                ?>
                    <option value="<?php echo $myrowagt[claveagente]; ?>"><?php echo $myrowagt[nombre]." ".$myrowagt[apellidopaterno]; ?></option>
                <?php
                }
                ?>
                </select>    
            <input type="hidden" value="asignar" name="action">
            <input type='submit' name='enviar'  value="asignar"  />
            <table id="j_id81:searchresults" class="recordList">
            <tbody>
			<?php
            while($myroworg=mysql_fetch_array($_pagi_result))
            {
				//Datos del Agente
				$sqlagente="SELECT * FROM `usuarios` WHERE `claveagente` LIKE '".$myroworg[clave_agente]."'";
				$resultagente= mysql_query ($sqlagente,$db);
				while($myrowagente=mysql_fetch_array($resultagente))
            	{
					$agente=$myrowagente[nombre]." ".$myrowagente[apellidopaterno];
				}
				
				list($dias, $meses) = diferencia_dias($myroworg[hora],$date);
				//$diascontacto = diferencia_dias($myroworg[fecha_ultimo_contacto],$date);
				//Semaforización de oportunidades
				if($dias>90){$ultimocontacto= $meses." meses"; $resaltadocontacto="#FF7F7F";}
				elseif($dias>=31&&$dias<=90){$ultimocontacto= $meses." meses"; $resaltadocontacto="#FFCC00";}
				else{if($meses==1){$ultimocontacto=$meses. "mes";}else{$ultimocontacto= $dias." días"; $resaltadocontacto="#86CE79";}}
				
				?>
				<tr class="even-row">
                <td class="list-column-checkbox"><input type="checkbox" name="Asignados[]" id="Asignados[]" value="<?php echo $myroworg['sid']; ?>" /></td>
                    <td class=" list-column-left">
                    <a class="keytext" href="detalles.php?organizacion=<?php echo $myroworg[clave_organizacion]; ?>" style="text-transform:uppercase;"><?php echo $myroworg[empresa]; ?></a> <span class="highlight"><?php echo $myroworg['sid']; ?></span> <span class="highlight" style="background-color:<?php echo $resaltadocontacto; ?>;"><?php echo $ultimocontacto; ?></span>
                    <br />
                    <?php echo $myroworg[asunto]; ?>
                    <br />
                    
                </td>
                
                <td class=" list-column-left">
                    <a target="" href="mailto:<?php echo $myroworg[email]; ?>"><?php echo $myroworg[email]; ?></a><br /><?php if($myroworg[telefono]){echo $myroworg[telefono]; }?><br />
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
