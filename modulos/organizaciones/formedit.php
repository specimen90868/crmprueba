<?php
include ("../../seguridad.php");
include ("../../config/config.php");

$m=""; $e=""; $d="";
$claveagente=$_SESSION[Claveagente];
$claveorganizacion=$_GET[organizacion];
$nivel=2;
$numeroagente=$claveagente;

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
<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../../css/styleform.css" />
<link rel="stylesheet" type="text/css" href="../../css/ventanas-modales.css">

<script type="text/javascript" src="js/cmxform.js"></script>
<link rel="icon" href="images/icon.ico" />

</head>
<body>
<?php include('../../header.php'); ?>
    
    <div id="titulo">Editar Organización</div>
    
    <div id="pageNav">
        <div id="pageNavWrapper">
			<ul class="pageNav">
                <li class=""><a href="detalles.php?organizacion=<?php echo $claveorganizacion;?>">Resumen</a></li>
                <li class=""><a href="#">Archivos</a></li>
                <li class=""><a href="oportunidades.php?organizacion=<?php echo $claveorganizacion;?>">Oportunidades <span class="count important" <?php if($overdueopt==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueopt; ?></span></a></li>
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
			$persona=$myroworg[tipo_persona];
			$clave=$myroworg[clave_unica];
			$tipo=$myroworg[tipo_organizacion];
			$fundacion=$myroworg[fecha_fundacion];
			$procedencia=$myroworg[procedencia];
			$website=$myroworg[clave_web];
			$formascontacto=$myroworg[forma_contacto];
			$promotor=$myroworg[clave_agente];
			$idorganizacion=$myroworg[id_organizacion];
			
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
			
			//Razones Sociales de la Organización
			$sqlrfcorg="SELECT * FROM `razonessociales` WHERE `clave_registro` LIKE '".$claveorganizacion."' AND `tipo_registro` = 'O' ORDER BY id_razonsocial ASC";
			$resultrfcorg= mysql_query ($sqlrfcorg,$db);
			
			//Contactos de la Organización
			$sqlconorg="SELECT * FROM `contactos` WHERE `clave_organizacion` LIKE '".$claveorganizacion."' ORDER BY id_contacto ASC";
			$resultconorg= mysql_query ($sqlconorg,$db);
		}
		?>
        
        
      <form action="" method="post">
      <fieldset class="fieldsetgde">
        <legend>Organización</legend>
<table id="stages:stages" class="recordList">
<thead>
<tr>
<th class="list-column-left" scope="col">Nombre</th>
<th class="list-column-center" scope="col">Persona</th>
<th class="list-column-center" scope="col">Clave</th>
<th class="list-column-center" scope="col">Tipo</th>
<th class="list-column-center" scope="col">Fundación</th>
<th class="list-column-center" scope="col">Procedencia</th>
<th class="list-column-center" scope="col">Clave Website</th>
<th class="list-column-center" scope="col">Formas de contacto</th>
<th class="list-column-center" scope="col">Promotor</th>
<th class="list-column-center" scope="col"></th>
</tr>
</thead> 

    <tbody>
    <tr class="odd-row">
    <td class="list-column-left"><?php echo $empresa; ?></td>
    <td class="list-column-left"><?php echo $persona; ?></td>
    <td class=" list-column-center"><?php echo $clave; ?></td>
    <td class=" list-column-center"><?php echo $tipo; ?></td>
    <td class=" list-column-center"><?php if($fundacion!='0000-00-00'){echo $fundacion;} else{echo "No registrado"; }?></td>
    <td class=" list-column-center"><?php echo $procedencia; ?></td>
    <td class=" list-column-center"><?php echo $website; ?></td>
    <td class=" list-column-center"><?php echo $formascontacto; ?></td>
    <td class=" list-column-center"><?php echo $promotor; ?></td>
    <td class=" list-column-center"><a href="editarregistro.php?organizacion=<?php echo $claveorganizacion; ?>&id=<?php echo $idorganizacion; ?>&t=O&o=U" class="clsVentanaIFrame clsBoton" rel="Editar Registro"> Editar</a></td>
    </tr>
    </tbody>
</table>

      </fieldset>
      
      <fieldset class="fieldsetgde">
        <legend>Contactos</legend>
       
<?php
if (mysql_num_rows($resultconorg))
{
	
?>
        
    <table id="stages:stages" class="recordList">
    <thead>
    <tr>
    <th class="list-column-left" scope="col">Título</th>
    <th class="list-column-left" scope="col">Nombre</th>
    <th class="list-column-left" scope="col">Apellido</th>
    <th class="list-column-left" scope="col">Puesto</th>
    <th class="list-column-left" scope="col">Email</th>
    <th class="list-column-left" scope="col">Celular</th>
    <th class="list-column-left" scope="col">Directo</th>
    <th class="list-column-left" scope="col">Roles</th>
    <th class="list-column-left" scope="col">Cumpleaños</th>
    <th class="list-column-left" scope="col"></th>
    </tr>
    </thead>

	<?php
    while($myrowconorg=mysql_fetch_array($resultconorg))
    {
		
		//Separar fecha de nacimiento
		$nacimiento=explode("-",$myrowconorg[fecha_nacimiento]);
		$anio=$nacimiento[0];$mes=$nacimiento[1];$dia=$nacimiento[2];
		
		$sqlemailcon="SELECT * FROM `correos` WHERE `clave_registro` LIKE '".$myrowconorg[clave_contacto]."'";
		$resultemailcon= mysql_query ($sqlemailcon,$db);
		$email=""; $e=""; //Limpiar variables
		while($myrowemailcon=mysql_fetch_array($resultemailcon))
		{
			$e=$myrowemailcon[id_correo];
			$email=$myrowemailcon[correo];
		}
		$sqltelcon="SELECT * FROM `telefonos` WHERE `clave_registro` LIKE '".$myrowconorg[clave_contacto]."' AND `tipo_registro` LIKE 'C'" ;
		$resulttelcon= mysql_query ($sqltelcon,$db);
		$celular=""; $directo=""; $m=""; $d=""; //Limpiar variables
		while($myrowtelcon=mysql_fetch_array($resulttelcon))
		{
			if($myrowtelcon[tipo_telefono]=="Celular")
			{
				$m=$myrowtelcon[id_telefono];
				$celular=$myrowtelcon[telefono];
			}
			elseif($myrowtelcon[tipo_telefono]=="Directo")
			{
				$d=$myrowtelcon[id_telefono];
				$directo=$myrowtelcon[telefono];
			}
		}
		//Relaciones de los contactos
		$sqlrelacion="SELECT * FROM `relaciones` WHERE `clave_contacto` LIKE '".$myrowconorg[clave_contacto]."' ORDER BY id_rol ASC" ;
		$rsrelacion= mysql_query ($sqlrelacion,$db);
		$rel="";
		while($rwrelacion=mysql_fetch_array($rsrelacion))
		{
			$sqlcolor="SELECT * FROM roles WHERE id_rol = '".$rwrelacion[id_rol]."'";
			$rscolor= mysql_query ($sqlcolor,$db);
			$rwcolor=mysql_fetch_array($rscolor);
			$rel.="<span class='highlight' style='background:".$rwcolor[color]."' title='".$rwrelacion[rol]."'>".$rwrelacion[rol][0]."</span> ";
		}
	?>
    
    <tbody>
        <tr class="odd-row">
        <td class="list-column-left"><?php echo $myrowconorg[titulo]; ?></td>
        <td class="list-column-left"><?php echo $myrowconorg[nombre]; ?></td>
        <td class="list-column-left"><?php echo $myrowconorg[apellidos]; ?></td>
        <td class="list-column-left"><?php echo $myrowconorg[puesto]; ?></td>
        <td class="list-column-left"><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></td>
        <td class="list-column-left"><?php echo $myrowconorg[telefono_celular]; ?></td>
        <td class="list-column-left"><?php echo $myrowconorg[telefono_oficina]; ?></td>
        <td class="list-column-left"><?php echo $rel; ?></td>
        <td class="list-column-left"><?php if($myrowconorg[dia_cumpleanios]!='0'&&$myrowconorg[dia_cumpleanios]!='0'){echo $myrowconorg[dia_cumpleanios]." de ".$meses_espanol[$myrowconorg[mes_cumpleanios]];} else{echo "No registrado"; }?></td>
        <td class="list-column-left"><a href="editarregistro.php?organizacion=<?php echo $claveorganizacion; ?>&id=<?php echo $myrowconorg[id_contacto]; ?>&t=C&o=U&e=<?php echo $e; ?>&m=<?php echo $m; ?>&d=<?php echo $d; ?>" class="clsVentanaIFrame clsBoton" rel="Editar Contacto"> Editar</a></td>
        </tr>
        </tbody>

    <?php
    }
    ?>
    </table>
<?php
}
?>
        <div class="prop"><img src="../../images/add.png" alt="" class="linkImage" /><a href="editarregistro.php?organizacion=<?php echo $claveorganizacion; ?>&t=C&o=I" class="clsVentanaIFrame clsBoton" rel="Editar Registro" >agregar contacto</a></div>
      </fieldset>

<fieldset class="fieldsetgde">
        <legend>Teléfonos</legend>

<?php
if (mysql_num_rows($resulttelorg))
{
?>
<table id="stages:stages" class="recordList">
<thead>
<tr>
<th class="list-column-left" scope="col">Teléfono</th>
<th class="list-column-center" scope="col">Tipo</th>
<th class="list-column-center" scope="col"></th>
</tr>
</thead> 

<?php
while($myrowtelorg=mysql_fetch_array($resulttelorg))
{
	
	$telefono=$myrowtelorg[telefono];
	$tipotel=$myrowtelorg[tipo_telefono];
	?>

    <tbody>
    <tr class="odd-row">
    <td class="list-column-left"><?php echo $telefono; ?></td>
    <td class=" list-column-center"><?php echo $tipotel; ?></td>
    <td class=" list-column-center"><?php echo $probabilidad; ?><a href="editarregistro.php?organizacion=<?php echo $claveorganizacion; ?>&id=<?php echo $myrowtelorg[id_telefono]; ?>&t=T&o=U" class="clsVentanaIFrame clsBoton" rel="Editar Registro"> Editar</a></td>
    </tr>
    </tbody>

    <?php
    
}

?>
</table>
<?php
}
?>
<div class="prop"><img src="../../images/add.png" alt="" class="linkImage" /><a href="editarregistro.php?organizacion=<?php echo $claveorganizacion; ?>&t=T&o=I" class="clsVentanaIFrame clsBoton" rel="Editar Registro" >agregar teléfono</a></div>

</fieldset>

<fieldset class="fieldsetgde">
        <legend>Direcciones de Correo Electrónico</legend> 
    
<?php
if (mysql_num_rows($resultemailorg))
{
?>
    <table id="stages:stages" class="recordList">
    <thead>
    <tr>
    <th class="list-column-left" scope="col">Correo Electrónico</th>
    <th class="list-column-center" scope="col">Tipo</th>
    <th class="list-column-center" scope="col"></th>
    </tr>
    </thead>

    <?php
    while($myrowemailorg=mysql_fetch_array($resultemailorg))
    {
        ?>
        <tbody>
        <tr class="odd-row">
        <td class="list-column-left"><a href="mailto:<?php echo $myrowemailorg[correo]; ?>"><?php echo $myrowemailorg[correo]; ?></a></td>
        <td class=" list-column-center"><?php echo $myrowemailorg[tipo_correo]; ?></td>
        <td class=" list-column-center"><a href="editarregistro.php?organizacion=<?php echo $claveorganizacion; ?>&amp;id=<?php echo $myrowemailorg[id_correo]; ?>&amp;t=E&amp;o=U" class="clsVentanaIFrame clsBoton" rel="Editar Registro"> Editar</a></td>
        </tr>
        </tbody>
        <?php
    }
    ?>
	</table>
<?php
}
?>
    
    <div class="prop"><img src="../../images/add.png" alt="" class="linkImage" /><a href="editarregistro.php?organizacion=<?php echo $claveorganizacion; ?>&t=E&o=I" class="clsVentanaIFrame clsBoton" rel="insertar Dirección de Correo Electrónico" >agregar dirección de correo electrónico</a></div>
    
    </fieldset>
     
    <fieldset class="fieldsetgde">
        <legend>Websites y Redes Sociales</legend>    
    
<?php
if (mysql_num_rows($resultweborg))
{
?>
    
    <table id="stages:stages" class="recordList">
    <thead>
    <tr>
    <th class="list-column-left" scope="col">Dirección Web</th>
    <th class="list-column-center" scope="col">Tipo</th>
    <th class="list-column-center" scope="col"></th>
    </tr>
    </thead>
    
	<?php
    while($myrowweborg=mysql_fetch_array($resultweborg))
    {
        $tipoweb=$myrowweborg[tipo_direccion];
        ?>
        <tbody>
        <tr class="odd-row">
        <td class="list-column-left"><a href="http://<?php echo $myrowweborg[direccion]; ?>" target="_blank"><?php echo $myrowweborg[direccion]; ?></a></td>
        <td class="list-column-left"><?php echo $myrowweborg[tipo_direccion]; ?></td>
        <td class="list-column-left"><a href="editarregistro.php?organizacion=<?php echo $claveorganizacion; ?>&amp;id=<?php echo $myrowweborg[id_direccionweb]; ?>&amp;t=W&amp;o=U" class="clsVentanaIFrame clsBoton" rel="Editar Registro"> Editar</a></td>
        </tr>
        </tbody>
        <?php
    }
    ?>
    </table>
    
<?php
}
?>
    
    <div class="prop"><img src="../../images/add.png" alt="" class="linkImage" /><a href="editarregistro.php?organizacion=<?php echo $claveorganizacion; ?>&t=W&o=I" class="clsVentanaIFrame clsBoton" rel="Insertar Dirección Web" >agregar dirección web</a></div>
    
    </fieldset>  
            
    <fieldset class="fieldsetgde">
        <legend>Domicilios</legend> 
        
<?php
if (mysql_num_rows($resultdomorg))
{
?>

    <table id="stages:stages" class="recordList">
    <thead>
    <tr>
    <th class="list-column-left" scope="col">Tipo</th>
    <th class="list-column-left" scope="col">Domicilio</th>
    <th class="list-column-left" scope="col">Ciudad</th>
    <th class="list-column-left" scope="col">Estado</th>
    <th class="list-column-left" scope="col">CP</th>
    <th class="list-column-left" scope="col"></th>
    </tr>
    </thead>

	<?php
    while($myrowdomorg=mysql_fetch_array($resultdomorg))
    {
    $tipodom=$myrowdomorg[tipo_domicilio];
    ?>
    
    <tbody>
        <tr class="odd-row">
        <td class="list-column-left"><?php echo $myrowdomorg[tipo_domicilio]; ?></td>
        <td class="list-column-left"><?php echo $myrowdomorg[domicilio]; ?></td>
        <td class="list-column-left"><?php echo $myrowdomorg[ciudad]; ?></td>
        <td class="list-column-left"><?php echo $myrowdomorg[estado]; ?></td>
        <td class="list-column-left"><?php echo $myrowdomorg[cp]; ?></td>
        <td class="list-column-left"><a href="editarregistro.php?organizacion=<?php echo $claveorganizacion; ?>&amp;id=<?php echo $myrowdomorg[id_domicilio]; ?>&amp;t=D&amp;o=U" class="clsVentanaIFrame clsBoton" rel="Editar Registro"> Editar</a></td>
        </tr>
        </tbody>

    <?php
    }
    ?>
    </table>
    
<?php
}
?>
    
    <div class="prop"><img src="../../images/add.png" alt="" class="linkImage" /><a href="editarregistro.php?organizacion=<?php echo $claveorganizacion; ?>&t=D&o=I" class="clsVentanaIFrame clsBoton" rel="Insertar Domicilio" >agregar domicilio</a></div>
    
    </fieldset>
 
 <fieldset class="fieldsetgde">
        <legend>Razones Sociales</legend>
        
<?php
if (mysql_num_rows($resultrfcorg))
{
?>        
        <table id="stages:stages" class="recordList">
    <thead>
    <tr>
    <th class="list-column-left" scope="col">Razón Social</th>
    <th class="list-column-left" scope="col">RFC</th>
    <th class="list-column-left" scope="col"></th>
    </tr>
    </thead>

	<?php
    while($myrowrfcorg=mysql_fetch_array($resultrfcorg))
    {

	?>
    
    <tbody>
        <tr class="odd-row">
        <td class="list-column-left"><?php echo $myrowrfcorg[razon_social]; ?></td>
        <td class="list-column-left"><?php echo $myrowrfcorg[rfc]; ?></td>
        <td class="list-column-left"><a href="editarregistro.php?organizacion=<?php echo $claveorganizacion; ?>&id=<?php echo $myrowrfcorg[id_razonsocial]; ?>&t=R&o=U" class="clsVentanaIFrame clsBoton" rel="Editar RFC"> Editar</a></td>
        </tr>
        </tbody>

    <?php
    }
    ?>
    </table>
    
<?php
}
?>
        <div class="prop"><img src="../../images/add.png" alt="" class="linkImage" /><a href="editarregistro.php?organizacion=<?php echo $claveorganizacion; ?>&t=R&o=I" class="clsVentanaIFrame clsBoton" rel="Insertar RFC" >agregar RFC</a></div>

</fieldset>
      <!--<p><input type="submit" value="Guardar"></p>-->
    </form>
            
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
