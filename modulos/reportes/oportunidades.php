<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
include ("../../includes/FusionCharts.php");

$claveagente=$_SESSION[Claveagente];
$claveorganizacion=$_GET[organizacion];
$responsable=$_SESSION[Rol];

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
<script type="text/javascript" src="../../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/cmxform.js"></script>
<script type="text/javascript" src="../../js/FusionCharts.js"></script>
<script type="text/javascript" src="../../assets/ui/js/jquery.min.js" language="Javascript"></script>
<script type="text/javascript" src="../../assets/ui/js/lib.js" language="Javascript"></script>
<script type="text/javascript" src="funciones.js"></script>
<link rel="icon" href="images/icon.ico" />

<script type="text/javascript">
function avanzar(id,o,a,av,org)
{
	var a = confirm("¿Est\xe1 seguro de avanzar con las condicines actuales del proceso?");
	if(a)
	{
		var dir = "../oportunidades/update.php?id="+id+"&o="+o+"&a="+a+"&av="+av+"&organizacion="+org;
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
          <li><a href="index.php" class="contactos" title="Organizaciones"></a></li>
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
                <li class=""><a href="detalles.php?organizacion=<?php echo $claveorganizacion;?>">Resumen</a></li>
                <li class=""><a href="expediente.php?id=<?php echo $_GET[id]; ?>&o=U&a=oP&an=<?php echo $_GET[an]; ?>&organizacion=<?php echo $claveorganizacion; ?>">Archivos</a></li>
                <li class="selected"><a href="#">Oportunidades <span class="count important" <?php if($overdueopt==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueopt; ?></span></a></li>
                <li class=""><a href="actividades.php?organizacion=<?php echo $claveorganizacion;?>">Actividades <span class="count important" <?php if($overdueact==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueact; ?></span></a></li>
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
			?>
            <div id="lateral">
			<div id="projectbg">
			  	<div id="projectthumnail"><img class="picture-normal" src="../../images/org_avatar_70.png" width="70" height="70" alt="picture" />
				</div>
			  <div id="projecttxtblank">
				<div id="projecttxt"><h1><?php echo $empresa; ?></h1><br />
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
							<li class="email"><a href="<?php echo $myrowemailorg[correo]; ?>"><?php echo $myrowemailorg[correo]; ?></a><span class="type"><?php echo $myrowemailorg[tipo_correo]; ?></span></li>
							<?php
                        }
                        ?>
                    	</ul>
                        <ul class="contact-details">
						<?php
                        while($myrowweborg=mysql_fetch_array($resultweborg))
                        {
                            ?>
							<li class="address"><a href="<?php echo $myrowweborg[direccion]; ?>" target="_blank"><?php echo $myrowweborg[direccion]; ?></a></li>
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
        <legend>Contactos</legend>
            <table>
			<?php
            while($myrowconorg=mysql_fetch_array($resultconorg))
            {
                ?>
                <tr>
                    <td height="35" width="35"><img src="../../images/person_avatar_32.png" class="picture-thumbnail" width="32" height="32" /></td>
                    <td>
                        <a href="<?php echo $myrowconorg[id_contacto]; ?>"><?php echo $myrowconorg[nombre]." ".$myrowconorg[apellidos]; ?></a>
                        <span class="party-info-card party" id="<?php echo $myrowconorg[id_contacto]; ?>"><img src="../../images/vcard.png" class="linkImage" />
                        </span>
                      <br />
                            <span class="subtext"><?php echo $myrowconorg[puesto]; ?></span><br />
                            <span class="subtext"><?php if($myrowconorg[dia_cumpleanios]!='0'&&$myrowconorg[dia_cumpleanios]!='0'){echo $myrowconorg[dia_cumpleanios]." de ".$meses_espanol[$myrowconorg[mes_cumpleanios]];} else{echo "No registrado"; }?></span> <img src="<?php if($myrowconorg[dia_cumpleanios]!='0'&&$myrowconorg[dia_cumpleanios]!='0'){echo '../../images/cake.png';} else {echo '../../images/cakebn.png';} ?> " class="linkImage" />
                    </td>
                </tr>
                
				<?php
            }
            ?>
            </table>

		</fieldset>
        
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
		else
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
                <img class="picture-thumbnail" src="../../images/person_avatar_32.png" width="32" height="32" alt="" />
                </td>
                <td class=" list-column-left">El contacto aún no ha sido asignado</td>
                </tr>
                <?php
            }
            ?>
            </tbody>
            </table>
		<?php
		}
		?>
        </fieldset>
            </div>
            
            <div id="derecho">
            <fieldset class="fieldsethistorial">
    <legend>Filtar lista por</legend>
        <form name="frmbusquedaopt" method="post" action="" enctype="application/x-www-form-urlencoded">
          <div><input  type="hidden" id="dato" name="dato" onkeyup=";" onblur="" />
            <label>Mostrar: </label>
            <select name="estatusopt" size="1" onchange="buscaropt(); return false">
                <option value="" selected="selected">Todas</option>
                <option value="Abiertas">Abiertas</option>
                <option value="Cerradas">Cerradas</option>
                <option value="MesCerradas">Cerradas este mes</option>
                <option value="MesCaptura">Capturadas este mes</option>
                <option value="MesCierre">Para cerrar este mes</option>
                <option value="MesProximoCierre">Para cerrar mes próximo</option>
                <option value="SemanaCierre">Para cerrar esta semana</option>
                <option value="SemanaProximaCierre">Para cerrar la semana próxima</option>
                <option value="7">Etapa: Cierre (Venta ganada)</option>
                <option value="6">Etapa: Cierre (Venta perdida)</option>
                <option value="5">Etapa: Aceptación del Cliente</option>
                <option value="4">Etapa: Seguimiento</option>
                <option value="3">Etapa: Propuesta</option>
                <option value="2">Etapa: Presentación de Productos</option>
                <option value="1">Etapa: Concertar Cita</option>
            </select> 
          </div>
          <input  type="hidden" id="organizacion" name="organizacion" value="<?php echo $claveorganizacion; ?>" onkeyup=";" onblur="" />
        </form>
</fieldset>
   
<div id="resultado">
<fieldset class="fieldsethistorial">
<legend>Oportunidades</legend>

<?php
//Definir consulta de oportunidades
if($_SESSION["Tipo"]=="Promotor")
{
	 $_pagi_sql="SELECT * FROM `oportunidades` WHERE `usuario` = '".$claveagente."' AND `clave_organizacion` = '".$claveorganizacion."' ORDER BY `fecha_captura` ASC";
	 $sqlabiertos="SELECT * FROM `oportunidades` WHERE `usuario` = '".$claveagente."' AND `clave_organizacion` = '".$claveorganizacion."' AND (`id_etapa`!='10' OR `id_etapa`!='11') ORDER BY `fecha_captura` ASC";
}
else
{
	$_pagi_sql="SELECT * FROM `oportunidades` WHERE `clave_organizacion` = '".$claveorganizacion."' ORDER BY `fecha_captura` ASC";
	$sqlabiertos="SELECT * FROM `oportunidades` WHERE `clave_organizacion` = '".$claveorganizacion."' AND (`id_etapa`<>'10' OR `id_etapa`<>'11') ORDER BY `fecha_captura` ASC";
}
$resultopt= mysql_query($_pagi_sql,$db);
$resultabietos= mysql_query($sqlabiertos,$db);
echo $sqlabiertos;
$totopt=mysql_num_rows($resultabietos);
//Color de celda y vínculos
if($totopt!=0){$vinculo=0;}
else{$vinculo=1;}

if($vinculo==0){?><img src="../../images/add.png" class="linkImage"/><a class="rojo" href="#">Agregar Oportunidad </a> <span class="highlight" style="background-color: #FFE680; margin-bottom: 10px; text-align: center; color:#333; padding:3px 3px 3px 3px;">No puedes agregar más oportunidades, sólo se permite un proceso abierto a la vez</span><?php }else{?><img src="../../images/add.png" class="linkImage" /><a class="action" href="../oportunidades/forminsert.php?organizacion=<?php echo $claveorganizacion; ?>&o=I">Agregar Oportunidad</a><?php }?>

<table class="recordList" style="margin-top: 12px;">
<thead>
<tr>
<th class="list-column-left" scope="col">Oportunidad</th>
<th class="list-column-left" scope="col">Etapa</th>
</tr>
</thead>
<tbody>
<?php

//Ciclo que recorre los datos de las oportunidades halladas en la consulta
while($myrowopt=mysql_fetch_array($resultopt))
{
	$nombre_oportunidad = $myrowopt[productos];
	$descripcion_oportunidad = $myrowopt[descripcion_oportunidad];
	if($myrowopt[tipo_credito]){$tipo = $myrowopt[tipo_credito];}else{$tipo=" Tipo: sin especificar, ";}
	if($myrowopt[monto]){$monto = " por ".number_format($myrowopt[monto]);}else{$monto=" Monto: sin especificar, ";}
	if($myrowopt[plazo_credito]){$plazo = " a ".$myrowopt[plazo_credito]." meses";}else{$plazo=" Plazo: sin especificar";}
	$id_etapa = $myrowopt[id_etapa];
	//$dias = diferencia_dias($fecha_cierre_esperado,$date);
	
	//Consultar detalles de responsable y color para la etapa en la que se encuentra la oportunidad listada
	$sqletp="SELECT * FROM `etapas` WHERE `id_etapa` = '".$id_etapa."'";
	$resultetp= mysql_query($sqletp,$db);
	while($myrowetp=mysql_fetch_array($resultetp))
	{
		$etapa = $myrowetp[etapa];
		$anterior= $myrowetp[etapa_anterior];
		$siguiente= $myrowetp[etapa_siguiente];
		$probabilidad = $myrowetp[probabilidad];
		$sqlcolor="SELECT * FROM  `responsables` WHERE id_responsable = $myrowetp[id_responsable]";
		$resultcolor=mysql_query($sqlcolor,$db);
		$myrowcolor=mysql_fetch_array($resultcolor);
		
		//Color de celda y vínculos
		if($myrowetp[id_responsable]!=$responsable)
		{
			$celda="#F0F0F0";
			$vinculo=0;
		}
		else
		{
			$celda="#FFFFFF";
			$vinculo=1;
		}
	}//Fin de while de etapa
	
	//Verificar si se solicita algún expediente en la etapa de la oportunidad
	$sqlexp="SELECT * FROM expedientes WHERE id_etapa='".$id_etapa."' OR id_etapa='".$anterior."'";
	$resultexp= mysql_query ($sqlexp,$db);
	$myrowexp=mysql_fetch_array($resultexp);
	if($myrowexp)//Si hay expediente asociado a la etapa de la oportunidad, obtener los datos necesarios.
	{			
		//Obtener cuántos tipos de archivos tiene el expediente solicitado
		$sqltipos="SELECT * FROM tiposarchivos WHERE id_expediente='".$myrowexp[id_expediente]."'";
		$resulttipos= mysql_query ($sqltipos,$db);
		$totarch=mysql_num_rows($resulttipos);//archivos totales del expediente
		$myrowtipos=mysql_fetch_array($resulttipos);
		
		//Numeros para expediente
		$sqlarchivo="SELECT count(`id_archivo`)as total, aprobado FROM `archivos` WHERE id_oportunidad = '".$myrowopt[id_oportunidad]."' GROUP BY (`aprobado`)";
		$resultarchivo= mysql_query ($sqlarchivo,$db);
		$aprobados=0;$nrevisado=0;$rechazados=0;$cargados=0;
		while($myrowarchivo=mysql_fetch_array($resultarchivo))
		{						
			if($myrowarchivo[aprobado]==0){$nrevisado=$myrowarchivo[total];}elseif($myrowarchivo[aprobado]==1){$aprobados=$myrowarchivo[total];}else{$rechazados=$myrowarchivo[total];}
		}
		$cargados=$aprobados+$rechazados+$nrevisado;
	}
	
	switch($id_etapa)//Definir vínculo según etapa de la opurtunidad
	{
		case 1:
		case 2:
		case 3:
			$link="";
			break;
		case 4:
			if($cargados!=$totarch)//No se han subido todos los archivos del expediente
			{
				//Vínculo de expediente
				$documentos = "<img src='../../images/warning_16.png' width='16' height='16'  class='linkImage' /><a href='../expediente/forminsert.php?id=".$myrowopt[id_oportunidad]."&o=I&a=oP&e=".$myrowexp[id_expediente]."&organizacion=".$claveorganizacion."' id='addButton' class='rojo' title='Cargados(".$cargados.") de (".$totarch.") documentos'>".$myrowexp[expediente]."</a>";
			}
			else//Ya se han subido todos los archivos del expediente
			{
				$documentos = "<img src='../../images/warning_16.png' width='16' height='16'  class='linkImage' /><a href='../expediente/forminsert.php?id=".$myrowopt[id_oportunidad]."&o=I&a=oP&e=".$myrowexp[id_expediente]."&organizacion=".$claveorganizacion."' id='addButton' class='azul' title='Cargados(".$cargados.") de (".$totarch.") documentos'>".$myrowexp[expediente]."</a>";
				if($vinculo!=0)
				{
					$avanzar = '<img src="../../images/next_16.png" width="16" height="16"  class="linkImage" /><a onclick="avanzar(\''.$myrowopt[id_oportunidad].'\', \'U\', \'oP\',\''.$id_etapa.'\',\''.$claveorganizacion.'\')" href="#" id="addButton" class="azul" title="Solicitar validación de expediente">Avanzar</a>';
				}
			}
			break;
		case 5:		
			//Verificar si se capturo análisis
			$sqlanalisis="SELECT * FROM analisis WHERE id_oportunidad='".$myrowopt[id_oportunidad]."'";
			$rsanalisis= mysql_query ($sqlanalisis,$db);
			$totanalisis=mysql_num_rows($rsanalisis);
			$rwanalisis=mysql_fetch_array($rsanalisis);
			
			if($totanalisis!=0&&$aprobados==$totarch)//Todos aprobados y análisis capturado
			{
				$analisis="<img src='../../images/analisis_16.png' width='16' height='16'  class='linkImage' /><a href='../analisis/forminsert.php?id=".$myrowopt[id_oportunidad]."&o=I&a=oP&an=".$rwanalisis[id_analisis]."&organizacion=".$claveorganizacion."' id='addButton' class='azul'>Análisis de crédito</a>";
				$documentos="<img src='../../images/warning_16.png' width='16' height='16'  class='linkImage' /><a href='../expediente/formupdate.php?id=".$myrowopt[id_oportunidad]."&o=U&a=oP&e=".$myrowexp[id_expediente]."&organizacion=".$claveorganizacion."' id='addButton' class='azul' title='Aprobados(".$aprobados.") Rechazados(".$rechazados.") Sin revisar (".$nrevisado.") de ".$totarch." documentos'>".$myrowexp[expediente]."</a>";
				$avanzar="<img src='../../images/next_16.png' width='16' height='16'  class='linkImage' /><a onclick='avanzar('".$myrowopt[id_oportunidad]."', 'U', 'oP','".$id_etapa."','".$claveorganizacion."')' href='#' id='addButton' class='azul'>Avanzar</a>";	
			}
			else
			{
				$analisis="<img src='../../images/analisis_16.png' width='16' height='16'  class='linkImage' /><a href='../analisis/forminsert.php?id=".$myrowopt[id_oportunidad]."&o=I&a=oP&an=".$rwanalisis[id_analisis]."&organizacion=".$claveorganizacion."' id='addButton' class='rojo'>Análisis de crédito</a>";
				if($nrevisado==0)//Evaluar si todos los archivos fueron revisados
				{
					$retroceder="<img src='../../images/prev_16.png' width='16' height='16'  class='linkImage' /><a href='../oportunidades/update.php?id=".$myrowopt[id_oportunidad]."&o=U&a=oP&re=".$id_etapa."&organizacion=".$claveorganizacion."' id='addButton' class='azul'>Retroceder</a>";
					$documentos="<img src='../../images/warning_16.png' width='16' height='16'  class='linkImage' /><a href='../expediente/formupdate.php?id=".$myrowopt[id_oportunidad]."&o=U&a=oP&e=".$myrowexp[id_expediente]."&organizacion=".$claveorganizacion."' id='addButton' class='azul' title='Aprobados(".$aprobados.") Rechazados(".$rechazados.") Sin revisar (".$nrevisado.") de ".$totarch." documentos'>".$myrowexp[expediente]."</a>";
				}
				else
				{
					$documentos="<img src='../../images/warning_16.png' width='16' height='16'  class='linkImage' /><a href='../expediente/formupdate.php?id=".$myrowopt[id_oportunidad]."&o=U&a=oP&e=".$myrowexp[id_expediente]."&organizacion=".$claveorganizacion."' id='addButton' class='rojo' title='Aprobados(".$aprobados.") Rechazados(".$rechazados.") Sin revisar (".$nrevisado.") de ".$totarch." documentos'>".$myrowexp[expediente]."</a>";
				}
			}
			break;
		case '6':
			$link="";
			break;
		case '7':
			$link="";
			break;
		case '8':
			$link="";
			break;
		case '9':
			$link="";
			break;
		case '10':
			$link="";
			break;
		case '11':
			$link="";
			break;
	}
	$link = "<br />".$documentos." ".$analisis." ".$avanzar." ".$retroceder;
	?>
    <tr class="odd-row" style="background-color:<?php echo $celda; ?>;">
<td class="list-column-left">
                        <?php if($vinculo==1){?><a href="../oportunidades/forminsert.php?id=<?php echo $myrowopt[id_oportunidad]; ?>&o=U&a=oP&organizacion=<?php echo $claveorganizacion;?>"><?php echo $nombre_oportunidad." ".$tipo.$monto.$plazo; ?> </a> <?php } else { echo $nombre_oportunidad." ".$tipo.$monto.$plazo; } ?>
                        <br /><span class="subtext">Destino del crédito: <?php if($myrowopt[destino_credito]){echo $myrowopt[destino_credito];}else{echo "Sin especificar";} ?></span><?php echo $link; ?>
                        
                        </td>
<td class=" list-column-left"><span class="highlight" style="background-color:#<?php echo $myrowcolor[color];?>;">
                        <?php echo $etapa; ?> (<?php echo $probabilidad; ?>%)</span></td>
</tr>
    <?
}


?>

</tbody>
</table>
		
		<?php
		}
		?>

      </fieldset>
      </div>

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
