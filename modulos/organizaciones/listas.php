<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
$claveagente=$_SESSION[Claveagente];

if($_SESSION["Tipo"]=="Promotor"||$_SESSION["Tipo"]=="Supervisor"){$_pagi_sql = "SELECT * FROM organizaciones WHERE clave_agente ='".$claveagente."' ORDER BY organizaciones.organizacion ASC";}
else{$_pagi_sql = "SELECT * FROM organizaciones ORDER BY organizaciones.organizacion ASC";}
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
		  if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador"){
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
            <li class=""><a href="index.php">Contactos</a></li>
            <li class="selected"><a href="#">Listas</a></li>
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
            <form name="frmbusqueda" action="" onsubmit="buscarlista(); return false">
              <div>Termino a buscar
                <input type="text" id="dato_lista" name="dato_lista" onkeyup=";" onblur="" />
                <select name="tipo_lista" size="1" onchange="">
                    <option value="">Seleccionar</option>
                    <option value="Puesto">Puesto</option>
                    <option value="Giro">Giro</option>
                    <option value="Domicilio">Domicilio</option>
                    <option value="Delegacion">Delegación</option>
                    <option value="CP">Código Postal</option>
				</select> 
                <input type="submit" value="Buscar">
                
              </div>
              
            </form>
        </fieldset>

            <div id="resultadoorg">
            <fieldset class="fieldsetgde">
            <legend>Mostrando <?php echo $_pagi_info; ?> organizaciones</legend>
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
				
				if($myroworg[fecha_ultimo_contacto])
				{
					list($dias, $meses) = diferencia_dias($myroworg[fecha_ultimo_contacto],$date);
					//$diascontacto = diferencia_dias($myroworg[fecha_ultimo_contacto],$date);
					//Semaforización de oportunidades
					if($dias>90){$ultimocontacto= $meses." meses"; $resaltadocontacto="#FF7F7F";}
					elseif($dias>=31&&$dias<=90){$ultimocontacto= $meses." meses"; $resaltadocontacto="#FFCC00";}
					else{if($meses==1){$ultimocontacto=$meses. "mes";}else{$ultimocontacto= $dias." días"; $resaltadocontacto="#86CE79";}}
				}
				else {$ultimocontacto="Ninguno"; $resaltadocontacto="#FF7F7F";} 
				
				//Contactos de la Organización
				$sqlconorg="SELECT * FROM `contactos` WHERE `clave_organizacion` LIKE '".$myroworg[clave_organizacion]."' ORDER BY id_contacto ASC";
				$resultconorg= mysql_query ($sqlconorg,$db);
				
				//Datos del Agente
				$sqlagente="SELECT * FROM `usuarios` WHERE `claveagente` LIKE '".$myroworg[clave_agente]."'";
				$resultagente= mysql_query ($sqlagente,$db);
				while($myrowagente=mysql_fetch_array($resultagente))
            	{
					$agente=$myrowagente[nombre]." ".$myrowagente[apellidopaterno];
				}
				?>
				<tr class="even-row">
                <td class="list-column-picture">
                	
                	<?php
                /*$sqlarchivos="SELECT * FROM archivos WHERE tipo_registro='O' AND clave_registro='$myroworg[clave_organizacion]' AND tipo_archivo='Logotipo' AND (ext_archivo='JPG' OR ext_archivo='BMP' OR ext_archivo='PNG' OR ext_archivo='GIF')";
				$resultarc= mysql_query ($sqlarchivos,$db);
				$logotipo="";
				while($myrowarc=mysql_fetch_array($resultarc))
				{
					$logotipo=$myrowarc[archivo];
				}
				?>
                <?php if($logotipo){?><img class="picture-thumbnail" src="../../logos/<?php echo $logotipo; ?>" width="32" alt="" /><?php } else {?> <img class="picture-thumbnail" src="../../images/org_avatar_32.png" width="32" height="32" alt="" /> <?php }*/?>
				<img class="picture-thumbnail" src="../../images/org_avatar_32.png" width="32" height="32" alt="" />
                    
                </td>
                
                <td class=" list-column-left">
                    <a class="keytext" href="detalles.php?organizacion=<?php echo $myroworg[clave_organizacion]; ?>" style="text-transform:uppercase;"><?php echo $myroworg[organizacion]; ?></a> <span class="highlight"><?php echo $myroworg[clave_unica]; ?></span> <span class="highlight" style="background-color:<?php echo $resaltadocontacto; ?>;"><?php echo $ultimocontacto; ?></span>
                    <br />
                    <?php echo $domicilio; ?>
                    <br />
                    <span class="subtext">Etiquetado como:
                        <span class="nobreaktext"><?php echo $myroworg[tipo_organizacion]; ?></span>
                    </span>
                </td>
                
                <td class=" list-column-left">
                    <a target="" href="mailto:<?php echo $mailorg; ?>"><?php echo $mailorg; ?></a><br /><?php if($telorg){echo format_Telefono($telorg)." (".$tipotelorg.")"; }?><br /><b><?php if($_SESSION["Tipo"]!="Usuario"){echo $agente; }?></b>
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
