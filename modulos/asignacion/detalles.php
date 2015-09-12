<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
include ("../../includes/FusionCharts.php");
extract($_POST);
$claveagente=$_SESSION[Claveagente];
$claveorganizacion=$_GET[organizacion];
$numeroagente=$claveagente;

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

if($_GET[a]=="C")
{
	$sqlcontacto="SELECT * FROM `contactanos` WHERE `serial` = '".$_GET[id]."'";
	$rscontacto= mysql_query ($sqlcontacto,$dbanabiosis);
}
else
{
	//**********Consulta a la tabla de omnis, es una medida temporal**********
	$sqlcontacto="SELECT * FROM `formularioprecalifica` WHERE `id_precalifica` = '".$_GET[id]."'";
	$rscontacto= mysql_query ($sqlcontacto,$dbanabiosis);
	}


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
	$sqlasignado="UPDATE `formularioprecalifica` SET `descartado`='1' WHERE id_precalifica='".$asignado."'";
	//mysql_query ($sqlasignado,$dbanabiosis);
	$rwcontacto=mysql_fetch_array($rscontacto);
	
	$sqlplantilla="SELECT * FROM plantillas WHERE id_plantilla='".$_POST[tipo_contacto]."'";
	$rsplantilla= mysql_query ($sqlplantilla,$db);
	$rwplantilla=mysql_fetch_array($rsplantilla);
	$headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
	
	//$headers = "MIME-Version: 1.1\n";
	//$headers .= "Content-type: text/plain; charset=UTF-8\n";
	$headers .= "From: Premo <noreply@premo.mx>\n"; // remitente
	$headers .= "Return-Path: noreply@premo.mx\n"; // return-path
	
	$message = '
<html>
<head>
  <title>Compara opciones de crédito para ti</title>
</head>
<body>
  <p>Hola '.$rwcontacto[nombre].', tenemos alternativas de crédito que se ajustan a tus necesidades, revisa la sección de '.$rwplantilla[tipo_contacto].' haciendo clic en el siguiente enlace</p>
  <p><a href="'.$rwplantilla[liga].'">'.$rwplantilla[tipo_contacto].'</a></p>
  <p>Préstamo Empresarial Oportuno</p>	
</body>
</html>
';
	$asunto = $rwcontacto[nombre]." tenemos opciones de crédito para ti";
	mail("denmed2210@gmail.com",$asunto,$message,$headers);
	//mail($rwcontacto[correo_electronico],$asunto,$message,$headers);
}

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

<!--Tooltip-->
<script languaje="javascript" src="XMLHttpRequest.js" type="text/javascript"></script>
<script languaje="javascript" src="scripts.js" type="text/javascript"></script>
<link rel="StyleSheet" href="estilos.css" type="text/css">

<link rel="icon" href="images/icon.ico" />

<script src="../../js/tooltip/jquery.min.js" type="text/javascript"></script>
<script src="../../js/tooltip/jqueryTooltip.js" type="text/javascript"></script>
<style>
/* Tooltips */
a.tooltip:hover { 
text-decoration:none;
} 

a.tooltip span {
display:none; 
margin:0 0 0 10px; 
padding:5px 5px; 
} 

a.tooltip:hover span {
display:inline; 
position:absolute; 
border:1px solid #cccccc; 
background:#ffffff; 
color:#666666; 
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
      
      <div id="sesionlinks">Bienvenido <a href="modulos/agentes/detalles.php?agente=<?php echo $claveagente; ?>" class="sesionlinks"><?php echo $_SESSION["Nombre"]; ?> (<?php echo $_SESSION["Tipo"]; ?>)</a> | <a href="../../salir.php" class="sesionlinks">Cerrar sesión</a></div>
      
      <?php
	  $sqlorg="SELECT * FROM `formularioprecalifica` WHERE `id_precalifica` LIKE '".$_GET[id]."'";
	  $resultorg= mysql_query ($sqlorg,$dbanabiosis);
	  $myroworg=mysql_fetch_array($resultorg);
	  ?>
      
      <div id="titulo" style="text-transform:uppercase;"><?php if($myroworg[tipo_persona]=="01"){echo $myroworg[nombre];}else{echo $myroworg[razon_social];} ?></div>
      
      <div id="pageNav">
        <div id="pageNavWrapper">
			<ul class="pageNav">
                <li class="selected"><a href="#">Detalles del Registro</a></li>
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
		
		if($_GET[a]=="C")
		{
			$sqlorg="SELECT * FROM `contactanos` WHERE `serial` = '".$_GET[id]."'";$resultorg= mysql_query ($sqlorg,$dbanabiosis);
		}
		else
		{
			//**********Consulta a la tabla de omnis, es una medida temporal**********
			$sqlorg="SELECT * FROM `formularioprecalifica` WHERE `id_precalifica` = '".$_GET[id]."'";$resultorg= mysql_query ($sqlorg,$dbanabiosis);
			}
		//$resultorg= mysql_query ($sqlorg,$db);
		while($myroworg=mysql_fetch_array($resultorg))
		{
			if($_GET[a]=="C")//Si el registro viene del formulario Contactanos de premo.mx
			{
				$empresa=$myroworg[empresa];
			}
			else//Viene del formulario Precalifica de premo.mx
			{
				if($myroworg[tipo_persona]=="01")
				{
					$empresa=$myroworg[nombre];
				}
				else
				{
					$empresa=$myroworg[razon_social];
				}
			}
			$registro=explode(" ",$myroworg[fecha]);
			list($dias, $meses) = diferencia_dias($registro[0],$date);
			
			//Semaforización de fechas
			if($dias>90){$resaltadocontacto="#FFBBBB";}
			elseif($dias>=31&&$dias<=90){$resaltadocontacto="#FFE784";}
			else{$resaltadocontacto="#BFE6B9";}

			?>
            <div id="lateral">
			<div id="projectbg">
			  	<div id="projectthumnail">
				<img class="picture-normal" src="../../images/org_avatar_70.png" width="70" height="70" alt="picture" />
                </div>
			  <div id="projecttxtblank">
				<div id="projecttxt"><h1 style="text-transform:uppercase;"><a href="http://google.com/search?q=<?php echo $empresa; ?>" target="_blank"><?php echo $empresa; ?></a></h1><br />
				</div>
			  </div>
              <div id="projectdetallestxtblank">
				<div id="projectdetallestxt">
                    	<ul class="contact-details">
						<?php
                        while($myrowtelorg=mysql_fetch_array($resulttelorg))
                        {
                            ?>
							<li class="phone"><?php echo $myrowtelorg[telefono]; ?> <span class="type"><?php echo $myrowtelorg[tipo_telefono]; ?></span></li>
							<?php
                        }
                        ?>
                    	</ul>
                        <ul class="contact-details">
						<?php
                        while($myrowemailorg=mysql_fetch_array($resultemailorg))
                        {
                            ?>
							<li class="email"><a href="mailto:<?php echo $myrowemailorg[correo]; ?>"><?php echo $myrowemailorg[correo]; ?></a><span class="type"><?php echo $myrowemailorg[tipo_correo]; ?></span></li>
							<?php
                        }
                        ?>
                    	</ul>
                        <ul class="contact-details">
						<?php
                        while($myrowweborg=mysql_fetch_array($resultweborg))
                        {
                            ?>
							<li class="address"><a href="http://<?php echo $myrowweborg[direccion]; ?>" target="_blank"><?php echo $myrowweborg[direccion]; ?></a></li>
							<?php
                        }
                        ?>
                    	</ul>
                        <ul class="contact-details">
						<?php
                        while($myrowdomorg=mysql_fetch_array($resultdomorg))
                        {
                            ?>
							<li class="address"><span class="type"><?php echo $myrowdomorg[tipo_domicilio];?></span><br /><?php echo $myrowdomorg[domicilio]; ?><br /><?php echo $myrowdomorg[ciudad]; ?> <?php echo $myrowdomorg[estado]; ?> <?php echo $myrowdomorg[cp]; ?> <?php echo $myrowdomorg[pais]; ?></li>
							<?php
                        }
                        ?>
                    	</ul>
                        
                        <ul class="formActions compact" style="margin-top: 10px;">
            <li><img src="https://d365sd3k9yw37.cloudfront.net/a/1349946707/theme/default/images/16x16/edit.png" class="linkImage" /><a href="formedit.php?organizacion=<?php echo $claveorganizacion;?>">Editar Organización
            </a>
            </li>
        </ul>
				</div>
			  </div>
			</div>
        
        <fieldset class="fieldsetlateral">
        <legend>Datos del Agente</legend>
        <?php if($myroworg[asignado]==1)
		{
			?>
            <table id="" class="recordList">
            <tbody>
            <?php 
            $sqlagente="SELECT * FROM `usuarios` WHERE `claveagente` LIKE '".$myroworg[clave_agente]."'";
            $resultagente= mysql_query ($sqlagente,$db);
            while($myrowagente=mysql_fetch_array($resultagente))
            {
                $agente=$myrowagente[apellidopaterno]." ".$myrowagente[apellidomaterno]." ".$myrowagente[nombre];
                ?>
                <tr class="even-row">
                <td class="list-column-picture">
                    
                <?php
                if($myrowagente[foto]){?><img class="picture-thumbnail" src="../../fotos/<?php echo $myrowagente[foto]; ?>" width="32" alt="" /><?php } else {?> <img class="picture-thumbnail" src="../../images/person_avatar_32.png" width="32" height="32" alt="" /> <?php }?>
                </td>
                <td class=" list-column-left">
                    <a href=""><?php echo $agente; ?></a><br /> <?php echo format_Telefono($myrowagente[teloficina]); ?> <span class="highlight" style="background-color: #9FC733;"><?php echo $myrowagente[extoficina]; ?></span><br /><a target="" href="mailto:<?php echo $myrowagente[email]; ?>"><?php echo $myrowagente[email]; ?></a><br />
                </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
            </table>
		<?php
		}
		else//PRECALIFICA
		{
			?>
            <table id="" class="recordList">
            <tbody>
                <tr class="even-row">
                <td class="list-column-picture">
                <img class="picture-thumbnail" src="../../images/person_avatar_32.png" width="32" height="32" alt="" />
                </td>
                <td class=" list-column-left">El contacto aún no ha sido asignado</td>
                </tr>
            </tbody>
            </table>
		<?php
		}
		?>
        </fieldset>
        
        <fieldset class="fieldsetlateral">
        	<legend>Acciones</legend>
            <form action="<?php echo $PHP_SELF; ?>" method="post">
 			<p>Calificar prospecto para: </p>	          
           	<?php
			$sqltipos="SELECT * FROM `plantillas`";
            $rsplantillas= mysql_query ($sqltipos,$db);
			while($rwplantillas=mysql_fetch_array($rsplantillas))
            {
				?>
                <ul class="contact-details">
                <li>
                <input type= "radio" name= "tipo_contacto" id= "tipo_contacto" value ="<?php echo $rwplantillas[id_plantilla]; ?>" />
         		<label for= "tipo_contacto"><?php echo $rwplantillas[tipo_contacto]; ?></label>
                </li>
                </ul>
                <?php
				
			}
			?>
           
            <img src="../../images/trash_16.png" class="linkImage" />
            <!--<input type="hidden" name="action" value="archivar">-->
            <input type='submit' name='action' value="descartar" style="background-color: #FF0000; border: 1px solid #FF0000; color: #fff; border-radius: 5px;"/>
            </form>
        
        </fieldset>
        
            </div>
            
            <div id="derecho">
            <table style="width:100%; margin-bottom:5px;">
                    <tr>
                        <td class="list-column-center" style="width:50%; padding-right:5px;">
                            <div class="roundedpanel" style="height:65px; background-color:<?php echo $resaltadocontacto; ?>;">
                                <div class="roundedpanel-content">
                                    Fecha del Registro<br />
                                        <b style="font-size:16px;"><?php if($myroworg[fecha_registro]){echo htmlentities(strftime("%e de %B de %Y", strtotime($myroworg[fecha_registro])));}else{echo "Ninguno";} ?></b>
                                </div>
                            </div>
                        </td>
                        <td class="list-column-center" style="width:50%; padding-left:5px; padding-right:5px;">
                            <div class="roundedpanel" style="height:65px;">
                                <div class="roundedpanel-content">
                                    Origen del Registro
                                    <br />
                                        <b style="font-size:16px;"><?php if($_GET[a]=="C") {echo "Contactanos Website";} else { echo "Precalifica Website"; }?></b>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
  		<?php
		
		switch($_GET[a])
		{
			case 'C'://Registro del formulario Contactanos de premo.mx
				?>
                <fieldset class="fieldsethistorial">
                <legend>Registro</legend>
                    <table class="recordList" style="margin-top: 12px;">
                    <thead>
                    </thead>
                    <tbody>
                        <tr class="odd-row">
                        <td class=" list-column-left">
                        <a class="keytext" href="modulos/organizaciones/detalles.php?organizacion=<?php echo $rwneworg[clave_organizacion]; ?>" style="text-transform:uppercase;"><?php echo $myroworg[empresa]; ?></a> <span class="highlight"><?php echo $rwneworg[clave_unica]; ?></span> <span class="highlight" style="background-color:<?php echo $resaltadocontacto; ?>;"><?php echo $ultimocontacto; ?></span>
                        <br /><br />
                        <?php echo $myroworg[asunto]; ?>
                        <br /><br />
                        <img src="../../images/mail_16.png" class="linkImage"/><a href="mailto:<?php echo $myroworg[email]; ?>"><?php echo $myroworg[email]; ?></a> <img src="../../images/tel_16.png" class="linkImage"/><?php echo $myroworg[telefono]; ?>
                        <br /><br />
                        </td>
                        </tr>
                    </tbody>
                    </table>
                </fieldset>
                <?php
				break;
			case 'P'://Registro del formulario Precalifica de premo.mx
				 $antiguedad = array();
				 $antiguedad["A"] = "0 a 2 años";
				 $antiguedad["B"] = "2 a 4 años";
				 $antiguedad["C"] = "4 a 8 años";
				 $antiguedad["D"] = "Más de 8 años";
				?>
                <fieldset class="fieldsethistorial">
                <legend>Datos Personales</legend>
                    <b>Nombre completo: </b><?php echo $myroworg[nombre]; ?><br />
                    <b>Puesto: </b><?php echo $myroworg[puesto]; ?><br />
                    <img src="../../images/mail_16.png" class="linkImage"/><a href="mailto:<?php echo $myroworg[correo_electronico]; ?>"><?php echo $myroworg[correo_electronico]; ?></a> <img src="../../images/tel_16.png" class="linkImage"/><?php echo $myroworg[telefono_oficina]; ?> <img src="../../images/cel_16.png" class="linkImage"/><?php echo $myroworg[celuar]; ?>
                </fieldset>
                
                <fieldset class="fieldsethistorial">
                <legend>Datos de la empresa</legend>
                    <b>Razón Social: </b><?php if($myroworg[tipo_persona]=="01"){echo "Persona Física";}else{echo $myroworg[razon_social];} ?><br />
                    <b>Antigüedad de la empresa: </b><?php echo $antiguedad[$myroworg[antiguedad]]; ?><br />
                    <img src="../../images/sales_16.png" class="linkImage"/><b>Ventas anuales: </b><?php echo number_format($myroworg[ventas_anuales],0); ?>
                </fieldset>
                
                <fieldset class="fieldsethistorial">
                <legend>Datos del préstamo</legend>
                    <img src="../../images/tel_16.png" class="linkImage"/><b>Monto del crédito: </b><?php echo number_format($myroworg[monto_credito],0); ?><br />
                    <b>Destino del crédito: </b><?php echo $myroworg[destino_credito]; ?><br />
                </fieldset>
                <?php
				break;
		}
		}
		?>

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
