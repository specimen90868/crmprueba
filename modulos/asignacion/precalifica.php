<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");

$claveagente=$_SESSION[Claveagente];
$nivel=2;

extract($_POST);
if($action == "asignar")
{
	foreach( $_POST['Asignados'] as $asignado )
	{
		$claveorganizacion = generateKey();
		$clavecontacto = generateKey();
		$sqlcontactoweb="SELECT * FROM formularioprecalifica WHERE id_precalifica='".$asignado."'";
		if (!mysql_query ($sqlcontactoweb,$dbanabiosis)) 
		{ 
		die("No se ha podido conectar a la BD: " . mysql_error()); 
		}
		
		$rscontactoweb= mysql_query ($sqlcontactoweb,$dbanabiosis);
        $rwcontactoweb=mysql_fetch_array($rscontactoweb);
		
		$nombre=$rwcontactoweb[nombre];
		$telefono=$rwcontactoweb[telefono_oficina];
		$celular=$rwcontactoweb[celular];
		$email=$rwcontactoweb[correo_electronico];
		if($rwcontactoweb[tipo_persona]=="01"){$empresa=$rwcontactoweb[nombre]; $tipopersona="Física";}else{$empresa=$rwcontactoweb[razon_social]; $tipopersona="Moral";}
		$antiguedad=$rwcontactoweb[antiguedad];
		$asunto=$rwcontactoweb[destino_credito];
		$monto=$rwcontactoweb[monto_credito];
		$garantia=$rwcontactoweb[garantia_hipotecaria];
		$ventas=$rwcontactoweb[ventas_anuales];
		
		$sqlorganizaciones="INSERT INTO `organizaciones` (`id_organizacion` , `clave_organizacion` , `tipo_registro` , `organizacion` , `clave_unica` , `fecha_fundacion` ,`procedencia` , `tipo_organizacion` , `clave_agente` , `giro` , `estatus` , `logo` , `capturo` , `fecha_captura` , `hora_captura` , `modifico` , `fecha_modificacion` , `hora_modificiacion` , `usuario_ultimo_contacto` , `fecha_ultimo_contacto` , `hora_ultimo_contacto` , `observaciones`, `tipo_persona`, `clave_web`, `forma_contacto`,`asignado`) VALUES 
		(NULL , '$claveorganizacion', 'O', '$empresa', '', '', 'Website', 'Prospecto', '$_POST[agente]', '', '1', '', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '$asunto', '$tipopersona', '$asignado', '', '1')";
		
		$sqlcontactos="INSERT INTO `contactos` (`id_contacto`, `clave_contacto`, `tipo_registro`, `clave_unica`, `clave_organizacion`, `organizacion`, `apellidos`, `nombre`, `nombre_completo`, `rep_legal`, `tipo_persona`, `genero`, `edad`, `fecha_nacimiento`, `dia_cumpleanios`, `mes_cumpleanios`, `ocupacion`, `puesto`, `telefono_casa`, `telefono_oficina`, `telefono_celular`, `telefono_otro1`, `telefono_otro2`, `titulo`, `rfc`, `curp`, `procedencia`, `escolaridad`, `estado_civil`, `potencial`, `tipo_contacto`, `clave_agente`, `estatus`, `aprobado`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `usuario_ultimo_contacto`, `fecha_ultimo_contacto`, `hora_ultimo_contacto`, `observaciones`) VALUES (NULL, '$clavecontacto', 'C', '', '$claveorganizacion', '$empresa','', '$nombre', '$nombre', '', '', '', '', '', '', '', '','', '', '$telefono', '$celular', '', '', '', '', '', '$medio', '', '', '', 'Prospecto', '$_POST[agente]', '1', '', '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '$claveagente', NOW(), NOW(), '$asunto')";
		
		$sqlemailorganizaciones="INSERT INTO `correos` (`id_correo`, `tipo_registro`, `clave_registro`, `tipo_correo`, `correo`, `capturo`, `fecha_captura`, `hora_captura`, `modifico`, `fecha_modificacion`, `hora_modificacion`, `observaciones`) VALUES (NULL, 'O', '$clavecontacto', 'Trabajo', '$email', '$_POST[agente]', NOW(), NOW(), '$claveagente', NOW(), NOW(), '')";
		
		$sqlasignado="UPDATE `formularioprecalifica` SET `asignado`='1' WHERE id_precalifica='".$asignado."'";
		mysql_query("SET NAMES UTF8");
		mysql_query ($sqlorganizaciones,$db);
		mysql_query ($sqlcontactos,$db);
		mysql_query ($sqlemailorganizaciones,$db);
		mysql_query ($sqlasignado,$dbanabiosis);
	}
}
elseif($action == "descartar")
{
	foreach( $_POST['Asignados'] as $asignado )
	{
		//echo "Se borrarán los registros: ".$asignado;
		$sqlasignado="UPDATE `formularioprecalifica` SET `descartado`='1' WHERE id_precalifica='".$asignado."'";
		mysql_query ($sqlasignado,$dbanabiosis);
	}
}

//$_pagi_sql="SELECT * FROM  `precalifica` WHERE `asignado`='0' AND `trash`='0' ORDER BY `fecha` DESC";
$_pagi_sql="SELECT * FROM  `formularioprecalifica` WHERE `asignado`='0' AND `descartado`='0' ORDER BY `fecha_registro` DESC";

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
  <?php include('../../header.php'); ?>
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
           	<li class=""><a href="contacto.php">Contacto Website</a></li>
            <li class="selected"><a href="#">Precalifica Website</a></li>
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

            <div id="resultadoorg">
            <fieldset class="fieldsetgde">
            <legend>Mostrando <?php echo $_pagi_info; ?> registros</legend>
            <form action="<?php echo $PHP_SELF; ?>" method="post">
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
            <!--<input type="hidden" name="action" value="asignar">-->
            <input type='submit' name='action' value="asignar" style="background-color: #9FC733; border: 1px solid #9FC733; color: #fff; border-radius: 5px;"/>
            <img src="../../images/trash_16.png" class="linkImage" />
            <!--<input type="hidden" name="action" value="archivar">-->
            <input type='submit' name='action' value="descartar" style="background-color: #FF0000; border: 1px solid #FF0000; color: #fff; border-radius: 5px;"/>
            <table id="j_id81:searchresults" class="recordList">
            <tbody>
			<?php
			while($myroworg=mysql_fetch_array($_pagi_result))
            {
				$submitted=explode(" ",$myroworg[fecha_registro]);
				$registro=$submitted[0];
				list($dias, $meses) = diferencia_dias($registro,$date);
				//Semaforización de oportunidades
				if($dias>90){$ultimocontacto= $meses." meses"; $resaltadocontacto="#FF7F7F";}
				elseif($dias>=31&&$dias<=90){$ultimocontacto= $meses." meses"; $resaltadocontacto="#FFCC00";}
				else{if($meses==1){$ultimocontacto=$meses. "mes";}else{$ultimocontacto= $dias." días"; $resaltadocontacto="#86CE79";}}
				
				?>
				<tr class="even-row">
                <td class="list-column-checkbox"><input type="checkbox" name="Asignados[]" id="Asignados[]" value="<?php echo $myroworg['id_precalifica']; ?>" /></td>
                    <td class=" list-column-left">
                    <a class="keytext" href="detalles.php?id=<?php echo $myroworg[id_precalifica]; ?>&a=P" style="text-transform:uppercase;"><?php if($myroworg[tipo_persona]=="01"){echo $myroworg[nombre];}else{echo $myroworg[razon_social];} ?></a> <span class="highlight"><?php if($myroworg[tipo_persona]=="01"){echo "Física";}else{echo "Moral";} ?></span> <span class="highlight" style="background-color:<?php echo $resaltadocontacto; ?>;"><?php echo $ultimocontacto; ?></span>
                    <br />
                    <?php echo $myroworg[destino_credito]; ?>
                </td>
                
                <td class=" list-column-left">
                    <a target="" href="mailto:<?php echo $myroworg[correo_electronico]; ?>"><?php echo $myroworg[correo_electronico]; ?></a><br /><?php if($myroworg[telefono_oficina]){echo $myroworg[telefono_oficina]; }?><br />
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
