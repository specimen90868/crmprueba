<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");

/** Clases necesarias */
include('../../Classes/PHPExcel.php');
include('../../Classes/PHPExcel/Reader/Excel2007.php');

$claveagente=$_SESSION[Claveagente];

$_pagi_sql="SELECT * FROM webform_submitted_data JOIN webform_submissions ON webform_submissions.sid=webform_submitted_data.sid JOIN webform_component ON webform_component.nid=webform_submitted_data.nid WHERE webform_submitted_data.nid='4' GROUP BY webform_component.cid";

$_pagi_cuantos = 10;
$_pagi_nav_num_enlaces= 30;
$nid=4;
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
          <li><a href="../modulos/organizaciones/index.php" class="contactos" title="Organizaciones"></a></li>
          <li><a href="../modulos/actividades/calendario.php" class="actividades" title="Actividades"></a></li>
          <li><a href="../modulos/oportunidades/oportunidades.php" class="oportunidades" title="Oportunidades"></a></li>
          <li><a href="../modulos/reportes/index.php" class="reportes" title="Reportes"></a></li>
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
      

  <div id="titulo">Organizaciones y Contactos</div>

      
      <div id="pageNav">
        <div id="pageNavWrapper">
    
        <ul class="pageNav">
            <li class="selected"><a href="#">Contacto</a></li>
            <li class=""><a href="listas.php">Precalifica</a></li>
        </ul> 
        <ul class="pageActions">
            <li class="item"><img src="../../images/add.png" class="linkImage" /><a href="forminsert.php">Nueva Organización</a></li>
            <li class="item"><img src="../../images/add.png" class="linkImage" /><a href="#">Nuevo Contacto</a></li>  
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
            <form name="importa" method="post" action="<?php echo $PHP_SELF; ?>" enctype="multipart/form-data" >
            <label>Subir archivo: </label>
            <input type="file" name="excel" />
            <input type='submit' name='enviar'  value="Importar"  />
            <input type="hidden" value="upload" name="action" />
            </form>
            
<?php 
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
	////////////////////////////////////////////////////////
	if (file_exists ("bak_".$archivo))
	{ 
		// Cargando la hoja de cálculo
		$objReader = new PHPExcel_Reader_Excel2007();
		
		$objPHPExcel = $objReader->load("bak_".$archivo);
		$objFecha = new PHPExcel_Shared_Date();       
		
		// Asignar hoja de excel activa
		$objPHPExcel->setActiveSheetIndex(0);

		for ($i = 4; $i <= count($objPHPExcel->sheets[0]["cells"]); $i++) 
		{
			$serial = $objPHPExcel->sheets[0]["cells"][$i][1];
			$sid = $objPHPExcel->sheets[0]["cells"][$i][2];
			$hora = $objPHPExcel->sheets[0]["cells"][$i][3];
			$draft = $objPHPExcel->sheets[0]["cells"][$i][4];
			$direccion_ip = $objPHPExcel->sheets[0]["cells"][$i][5];
			$uid = $objPHPExcel->sheets[0]["cells"][$i][6];
			$usuario = $objPHPExcel->sheets[0]["cells"][$i][7];
			$nombre = $objPHPExcel->sheets[0]["cells"][$i][8];
			$telefono = $objPHPExcel->sheets[0]["cells"][$i][9];
			$email = $objPHPExcel->sheets[0]["cells"][$i][10];
			$empresa = $objPHPExcel->sheets[0]["cells"][$i][11];
			$asunto = $objPHPExcel->sheets[0]["cells"][$i][12];
			$medio = $objPHPExcel->sheets[0]["cells"][$i][13];
			$otro_medio = $objPHPExcel->sheets[0]["cells"][$i][14];
			
			$sql = "INSERT INTO `contactanos`(`serial`, `sid`, `hora`, `draft`, `direccion_ip`, `uid`, `usuario`, `nombre`, `telefono`, `email`, `empresa`, `asunto`, `medio`, `otro_medio`, `asignado`) VALUES ('$serial','$sid','$hora','$draft','$direccion_ip','$uid','$usuario','$nombre','$telefono','$email','$empresa','$asunto','$medio','$otro_medio','0')";	
				
			echo $sql."\n";
			mysql_query($sql);
		} 
				
	}//fin if existe archivo
//si por algo no cargo el archivo bak_ 
else
{
	echo "Necesitas primero importar el archivo";
}

echo "<strong><center>ARCHIVO IMPORTADO CON EXITO, EN TOTAL $campo REGISTROS Y $errores ERRORES</center></strong>";
//una vez terminado el proceso borramos el 
//archivo que esta en el servidor el bak_
unlink($destino);
}//fin de accion upload
?>
            
        </fieldset>

            <div id="resultadoorg">
            <fieldset class="fieldsetgde">
            <legend>Mostrando <?php echo $_pagi_info; ?> registros</legend>
            <table id="j_id81:searchresults" class="recordList">
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
				
				//Checklist
				$sqlchecklist="SELECT * FROM `checklist` WHERE `clave_organizacion` LIKE '".$myroworg[clave_organizacion]."' ORDER BY id_checklist ASC";
				$resultchecklist= mysql_query ($sqlchecklist,$db);
				$check = mysql_num_rows($resultchecklist);
				
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
					if($myrowconorg[dia_cumpleanios]!='0'&&$myrowconorg[dia_cumpleanios]!='0')
					{
						$totcump++;
					}	
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
                    <a class="keytext" href="detalles.php?organizacion=<?php echo $myroworg[clave_organizacion]; ?>" style="text-transform:uppercase;"><?php echo $myroworg[organizacion]; ?></a> <span class="highlight"><?php echo $myroworg[clave_unica]; ?></span> <span class="highlight" style="background-color:<?php echo $resaltadocontacto; ?>;"><?php echo $ultimocontacto; ?></span><?php if($myroworg[fecha_fundacion]!='0000-00-00'){?><img src="../../images/awardstar.png" /><?php }else{?><img src="../../images/awardstarbn.png" /><?php }?>
                    <?php
                    if($totcont!=0)
					{
						if($totcump==$totcont)
						{
							?><img src="../../images/cake.png" /><?php 
						}
						else
						{
							?><img src="../../images/cakebn.png" /><?php 
						}
					}
					else
					{
						?><img src="../../images/userbn.png" /><?php
					}
						?>
                    <?php if($numrfc!=0){?><img src="../../images/invoice.png" /><?php }else {?> <img src="../../images/invoicebn.png" /> <?php }?> <?php if($check!=0){?><img src="../../images/checklist.png" /><?php }else {?> <img src="../../images/checklistbn.png" /> <?php }if($_SESSION["Tipo"]=="Sistema"){?> <a href="delete.php?organizacion=<?php echo $myroworg[clave_organizacion]; ?>&id=<?php echo $myroworg[id_organizacion]; ?>&t=O&o=D" class="clsVentanaIFrame clsBoton" rel="Eliminar Registro" style="color:#F00;"> Eliminar</a> <?php }?>
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

		<script type="text/javascript" src="../../js/ext/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="../../js/ventanas-modales.js"></script>
</body>
