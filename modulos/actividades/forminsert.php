
<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");
require_once("../../xajax/xajax.inc.php"); //incluimos la librelia xajax

$claveagente=$_SESSION[Claveagente];
$claveorganizacion=$_GET[organizacion];
$nivel=2;
$_POST = array();

$Fecha=getdate();
$date=date("Y-m-d");
$Anio=$Fecha["year"]; 
$Mes=$Fecha["mon"];
$month=date("m"); 
$Dia=$Fecha["mday"]; 
$Hora=$Fecha["hours"].":".$Fecha["minutes"].":".$Fecha["seconds"];
$Anio_anterior=$Anio-1;

//Oportunidades atrasadas cierre anterior a la fecha actual y abiertas
$sqloverdueopt="SELECT * FROM `oportunidades` WHERE `fecha_cierre_esperado` < '".$date."' AND (`id_etapa`!=6 AND `id_etapa`!=7) AND `clave_organizacion`='".$claveorganizacion."' AND usuario=  '".$claveagente."'";
$resultadoopt = mysql_query($sqloverdueopt, $db);
$overdueopt = mysql_num_rows($resultadoopt);

//Actividades atrasadas
$sqloverdueact="SELECT * FROM `actividades` WHERE `fecha` < '".$date."' AND `completa`!=1 AND `clave_organizacion`='".$claveorganizacion."' AND usuario=  '".$claveagente."'";
$resultadoact = mysql_query($sqloverdueact, $db);
$overdueact = mysql_num_rows($resultadoact); 

//Para organizaciones
$sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` LIKE '".$claveorganizacion."'";
$resultorg= mysql_query ($sqlorg,$db);
$myroworg=mysql_fetch_array($resultorg);

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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>crm</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../style.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../../css/styleform.css" />
<link rel="stylesheet" type="text/css" href="../../css/modalform.css"/>

<link rel="icon" href="images/icon.ico" />
<script type="text/javascript" src="js/cmxform.js"></script>

<!-- page specific scripts -->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>

<script>
$(function() {
	$( "#date" ).datepicker({
		showButtonPanel: false,
        minDate: '+0d',
		showOn: "button",
		buttonImage: "../../images/calendar.gif",
		buttonImageOnly: true,
		dateFormat: "yy-mm-dd",
		dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
		dayNames: [ "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado" ],
		monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
		nextText: "Siguiente",
		prevText: "Anterior",
		altField: "#alternate",
		altFormat: "DD, d MM, yy"
	});
});
</script>

<script type="text/javascript">
function validarForm(frmactividad) {
  nombre = document.getElementById("actividad").value;
  if(nombre == null || nombre.length == 0 || /^\s+$/.test(nombre) ) { //comprueba que no esté vacío
    frmactividad.actividad.focus();   
    alert('No has indicado un nombre para la actividad'); 
    return false; //devolvemos el foco
  }
  hora= document.getElementById("hora_actividad").selectedIndex;
  if(hora == null || hora == 0) { //comprueba que no esté vacío
    frmactividad.hora_actividad.focus();
    alert('No has indicado un rango válido para el horario actividad');
    return false;
  }
  minutos = document.getElementById("min_actividad").selectedIndex;
  if(minutos == null) { //comprueba que no esté vacío
    frmactividad.min_actividad.focus();
    alert('No has indicado un valor permitido');
    return false;
  }
  if(frmactividad.selOrganizacion.value.length==0) { //comprueba que no esté vacío
    frmactividad.selOrganizacion.focus();
    alert('No has indicado a qué organización pertenece esta actividad');
    return false;
  }
  return true;
}
</script>

<script>
function toggle(id) {
	if (document.getElementById(id).style.display == 'none') {
		document.getElementById(id).style.display = 'block';
	} else {
		document.getElementById(id).style.display = 'none';
	}
}
</script>

<script src="ajax.js"></script>

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


</head>
<body>
    
    <?php include('../../header.php'); ?>  
    <div id="titulo">Modificar/Insertar Actividad</div>
      
      <div id="pageNav">
        <div id="pageNavWrapper">
			<ul class="pageNav">
                <li class=""><a href="../organizaciones/detalles.php?organizacion=<?php echo $claveorganizacion;?>">Resumen</a></li>
                <li class=""><a href="#">Archivos</a></li>
                <li class=""><a href="../organizaciones/oportunidades.php?organizacion=<?php echo $claveorganizacion;?>">Oportunidades <span class="count important" <?php if($overdueopt==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueopt; ?></span></a></li>
                <li class="selected"><a href="#">Actividades <span class="count important" <?php if($overdueact==0){?> style="background-color:#88c97a;" <?php }?>><?php echo $overdueact; ?></span></a></li>
            </ul>
            <strong><ul class="pageActions">
            	<li class="item"><img src="../../images/add.png" class="linkImage" /><a href="../organizaciones/actividades.php?organizacion=<?php echo $claveorganizacion;?>">Lista de Actividades</a></li>  
        	</ul></strong> 
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
            
            <div id="lateral">
            <?php
			if($claveorganizacion)
			{
			?>
			<div id="projectbg">
			  	<div id="projectthumnail">
				<img class="picture-normal" src="../../images/org_avatar_70.png" width="70" height="70" alt="picture" />
                </div>
			  <div id="projecttxtblank">
				<div id="projecttxt"><h1><a href="http://google.com/search?q=<?php echo $empresa; ?>" target="_blank"><?php echo $empresa; ?></a></h1><br />
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
							<li class="address"><span class="type"><?php echo $myrowdomorg[tipo_domicilio];?></span> <?php echo $myrowdomorg[domicilio]; ?><br /><?php echo $myrowdomorg[ciudad]; ?> <?php echo $myrowdomorg[estado]; ?> <?php echo $myrowdomorg[cp]; ?> <?php echo $myrowdomorg[pais]; ?></li>
							<?php
                        }
                        ?>
                    	</ul>
                        
                        <ul class="formActions compact" style="margin-top: 10px;">
            <li><img src="https://d365sd3k9yw37.cloudfront.net/a/1349946707/theme/default/images/16x16/edit.png" class="linkImage" /><a href="../organizaciones/formedit.php?organizacion=<?php echo $claveorganizacion;?>">Editar Organización
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
				$title="Directo: ".format_Telefono($myrowconorg[telefono_oficina])." \nCelular: ".format_Telefono($myrowconorg[telefono_celular])." \nEmail: ".$myrowconorg[email];
				$title= htmlentities($title);
				?>
                <tr>
                    <td height="35" width="35"><img src="../../images/person_avatar_32.png" class="picture-thumbnail" width="32" height="32" /></td><td>

<a href="" title="<?php echo $title; ?>"><?php echo $myrowconorg[nombre]." ".$myrowconorg[apellidos]; ?></a><span class="" id="<?php echo $myrowconorg[id_contacto]; ?>"> <img src="../../images/vcard.png" class="linkImage" id="ficha"/></span><?php echo $rel; ?>
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
				$rol=$myrowagente[tipo];
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
			
        <?php
        }
		else
		{
			?>
		<!-- ACTIVIDADES PARA HACER HOY -->
          <fieldset class="fieldsetlateral">
              <legend>Mis Actividades del día</legend>
              <div class="prop"><img src="../../images/add.png" alt="" class="linkImage" /><a href="modulos/actividades/forminsert.php?o=I&a=D&organizacion=<?php echo $claveorganizacion; ?>">agregar actividad</a></div>
              <div class="grouped-list">
                    <ul class="list">
                            <?php
        if($_SESSION["Tipo"]=="Promotor"){$sqlact="SELECT * FROM `actividades` WHERE (`fecha` = '".$date."' AND `completa`='2') AND `usuario`=  '".$claveagente."'";}else{$sqlact="SELECT * FROM `actividades` WHERE (`fecha` = '".$date."' AND `completa`='2')";}
        $resultact= mysql_query ($sqlact,$db);
		
		while($myrowact=mysql_fetch_array($resultact))
		{
			$fecha=explode("-",$myrowact[fecha]);
			//$fechaact=$fecha[2]."/".$fecha[1]."/".$fecha[0];
			//$fechaact="2012/10/22 00:00:00";
			$time=explode(":",$myrowact["hora"]);
			$hora=$time[0].":".$time[1];
			?>             
            <li class="item">
            <div class="checkbox">
            <img src="../../images/incompleta16.png" />
            </div>
                <div class="detail">
                    <span class="text">
                        <span id="campo">
                            <span class="task-title"><span class="highlight"><?php echo $myrowact[tipo]; ?></span> <a href="modulos/actividades/forminsert.php?id=<?php echo $myrowact[id_actividad]; ?>&organizacion=<?php echo $myrowact[clave_organizacion]; ?>&o=U&a=D&fecha=<?php echo $myrowact[fecha];?>" class="" rel="Editar Registro" > <?php echo $myrowact[subtipo]; ?> <?php if ($myrowact[oportunidad]){echo "(".$myrowact[oportunidad].")";} ?></a>
                            </span>
                            <a href="modulos/organizaciones/detalles.php?organizacion=<?php echo $myrowact[clave_organizacion];?>"><?php echo $myrowact[organizacion];?></a>
                            <span class="more-detail"><?php echo $myrowact[descripcion]; ?></span>
                            <span class="task-time"><?php echo htmlentities(strftime("%a, %b, %e", strtotime($myrowact[fecha]))); ?>, <?php echo $hora;?>
                             </span>
                        </span>
                    </span>
                    <div class="text">
                    </div>
                </div>
            </li>
            <?php
			}
			?> 
            </ul>
        </div>
            </fieldset>
		<?php
        }
		?>
        
        
            </div><!-- Fin de lateral -->
            <div id="derecho">    
                
                <?php
switch($_GET[o])
{
	case 'I':
		?>
        <div id="stylized" class="myform">
        <form name="frmactividad" method="POST" action="update.php" onsubmit="return validarForm(this);">
        <h1>Agregar Actividad para <?php echo $empresa; ?></h1>
        	<p>Ingrese los datos de la actividad</p>
            <label>Actividad: </label><input type="text" name="actividad" id="actividad" class="input2"/>
            <div class="spacer"></div>
            <label>Descripción: </label><textarea name="descripcion" id="descripcion" cols="45" rows="5" class="input2"></textarea>
            <div class="spacer"></div>
           	<label>Categoría: </label>
                <select id="tipo_actividad" name="tipo_actividad">
                  <option value="Llamada">Llamada</option>
                  <option value="Email">Email</option>
                  <option value="Seguimiento">Seguimiento</option>
                  <option value="Cita">Cita</option>
                </select>
              <div class="spacer"></div>
              <label>Programada: </label>
              <input type="text" id="alternate" size="30" value="<?php if($_GET['fecha']){echo htmlentities(strftime("%A, %e %B, %Y", strtotime($_GET['fecha'])));}else{echo htmlentities(strftime("%A, %e %B, %Y", strtotime($date)));} ?>"/>
              <input type="text" name="date" id="date" value="<?php if($_GET['fecha']){echo $_GET['fecha'];}else{echo $date;} ?>" onchange="MostrarConsulta('consulta.php',this.value,'<?php echo $claveorganizacion; ?>'); return false"/>
              <input type="hidden" name="o" id="o" value="<?php if($_GET[o]){echo $_GET[o];}else{echo $_POST[o];}?>"  />
              <div class="spacer"></div>
              <label>Hora: </label>
                <select id="hora_actividad" name="hora_actividad">
                  <option value="">--</option>
                  <option value="">00</option>
                  <option value="01">01</option>
                  <option value="02">02</option>
                  <option value="03">03</option>
                  <option value="04">04</option>
                  <option value="05">05</option>
                  <option value="06">06</option>
                  <option value="07">07</option>
                  <option value="08">08</option>
                  <option value="09">09</option>
                  <option value="10">10</option>
                  <option value="11">11</option>
                  <option value="12">12</option>
                  <option value="13">13</option>
                  <option value="14">14</option>
                  <option value="15">15</option>
                  <option value="16">16</option>
                  <option value="17">17</option>
                  <option value="18">18</option>
                  <option value="19">19</option>
                  <option value="20">20</option>
                  <option value="21">21</option>
                  <option value="22">22</option>
                  <option value="23">23</option>
                </select>
                <select id="min_actividad" name="min_actividad">
                  <option value="">--</option>
                  <option value="00">00</option>
                  <option value="05">05</option>
                  <option value="10">10</option>
                  <option value="15">15</option>
                  <option value="20">20</option>
                  <option value="25">25</option>
                  <option value="30">30</option>
                  <option value="35">35</option>
                  <option value="40">40</option>
                  <option value="45">45</option>
                  <option value="50">50</option>
                  <option value="55">55</option>
                </select>
            <div class="spacer"></div> 
            <div id="resultado" style="margin:0 0 20px 145px; padding: 0 0 20px 0;"></div>
            <?php
			if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador"||$_SESSION["Tipo"]=="Supervisor")
			{
				
				?>
				<label>Promotor
				</label>
				<select id="promotor" name="promotor" class="input2">
				<option value="0" <?php if($myroworg[asignado]==0){echo "selected='".$selected."'";}?>>Sin Asignar</option>
				<?php
				if($myroworg[asignado]==1&&$rol=='Promotor'){$sqlagt="SELECT * FROM usuarios WHERE tipo = 'Promotor' AND claveagente = '".$myroworg[clave_agente]."' ORDER BY apellidopaterno ASC"; $selected="selected";}else{$sqlagt="SELECT * FROM usuarios WHERE tipo = 'Promotor' ORDER BY apellidopaterno ASC"; $selected="";}
				$resultagt= mysql_query ($sqlagt,$db);
				while($myrowagt=mysql_fetch_array($resultagt))
				{
					?>
					<option value="<?php echo $myrowagt[claveagente]; ?>" <?php if($myroworg[asignado]==1){echo "selected='".$selected."'";}?>><?php echo $myrowagt[apellidopaterno]." ".$myrowagt[apellidomaterno]." ".$myrowagt[nombre]; ?></option>
					<?php
				}
				?>
				</select>
				<div class="spacer"></div>
				<?php
			}
			?>   
			<label>Organización: </label>
		 	<?php
        		
        if($_GET[organizacion])//HAY CLAVE DE ORGANIZACION
        {
            //Datos de la organizacion
			$sqlorganizacion="SELECT * FROM organizaciones WHERE clave_organizacion ='".$claveorganizacion."'";
			$resultorganizacion= mysql_query ($sqlorganizacion,$db);
			while($myroworganizacion=mysql_fetch_array($resultorganizacion))
        	{
				$organizacion=$myroworganizacion[organizacion];
			}
			?>
            <input type="text" name="organizacion" size="25" id="organizacion" class="input2" value="<?php echo $organizacion; ?>"/>
            <div class="spacer"></div>
            	<label>Oportunidad: </label>
            <?php
			//Datos de las oportunidades abiertas para la organización
			$sqlopt="SELECT DISTINCT `clave_organizacion` AS claveorganizacion FROM oportunidades WHERE (id_etapa!=6 AND id_etapa!=7) AND usuario ='".$claveagente."' AND `clave_organizacion` ='".$claveorganizacion."' ORDER BY `fecha_cierre_esperado` DESC";
			
			$resultopt= mysql_query ($sqlopt,$db);
			$numopt = mysql_num_rows($resultopt);
			if($numopt)
			{
				?>
				<select  id="oportunidad" name="oportunidad" class="input2">
					<option value="" selected="selected">[Selecciona]</option>
				<?php
				while($myrowopt=mysql_fetch_array($resultopt))
				{
					$sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` LIKE '".$myrowopt[claveorganizacion]."'";
					$resultorg= mysql_query ($sqlorg,$db);
					while($myroworg=mysql_fetch_array($resultorg))
					{
						$empresa=$myroworg[organizacion];
						$clave=$myroworg[clave_unica];
					}
					?>
					  <optgroup label="<?php echo $empresa; ?>">
					<?php
					//Definir etapa de cierre de oportunidad
					$sqloptorg="SELECT * FROM `oportunidades` WHERE `clave_organizacion` = '".$myrowopt[claveorganizacion]."' AND (id_etapa!=10 AND id_etapa!=11) AND usuario ='".$claveagente."' ORDER BY fecha_cierre_esperado ASC";
					//$sqloptorg="SELECT * FROM `oportunidades` WHERE `clave_organizacion` = '".$myrowopt[claveorganizacion]."' AND usuario ='".$claveagente."' ORDER BY fecha_cierre_esperado ASC";
					
					$resultoptorg= mysql_query($sqloptorg,$db);
					while($myrowoptorg=mysql_fetch_array($resultoptorg))
					{
						$nombre_oportunidad = $myrowoptorg[productos];
						$descripcion_oportunidad = $myrowoptorg[descripcion_oportunidad];
						if($myrowoptorg[tipo_credito]){$tipo = $myrowoptorg[tipo_credito];}else{$tipo=" Tipo: sin especificar, ";}
						if($myrowoptorg[monto]){$monto = " por ".number_format($myrowoptorg[monto]);}else{$monto=" Monto: sin especificar, ";}
						if($myrowoptorg[plazo_credito]){$plazo = " a ".$myrowoptorg[plazo_credito]." meses";}else{$plazo=" Plazo: sin especificar";}
						
						?>
						<option value="<?php echo $myrowoptorg[id_oportunidad]; ?>"><?php echo $nombre_oportunidad." ".$tipo.$monto.$plazo; ?></option>
						<?php
					}//cierre while de oportunidades
					?>
					</optgroup>
					<?php
				}
				?>
			</select>
			<?php
			}
			else
			{
				?>
                <input type="text" name="oportunidad" id="oportunidad" class="input2" value="No hay oportunidades abiertas" disabled="disabled"/>
                <?php
			}
        }
        else //NO HAY CLAVE DE ORGANIZACIÓN
        {
            $sqlorganizacion="SELECT * FROM organizaciones WHERE clave_agente ='".$claveagente."' ORDER BY organizacion";
			$resultorganizacion= mysql_query ($sqlorganizacion,$db);
			?>
            <select  id="selOrganizacion" name="selOrganizacion" class="input2">
            	<option value="" selected="selected">[Selecciona]</option>
			<?php
            while($myroworganizacion=mysql_fetch_array($resultorganizacion))
        	{
				?>
				<option value="<?php echo $myroworganizacion[clave_organizacion]; ?>"><?php echo $myroworganizacion[organizacion]; ?></option>
				<?php
			}
			?>
			</select>
            <div class="spacer"></div>
            <div id="myDiv"></div>
        <?php	
        }
        ?>
        <div class="spacer"></div>
        <label>Completar</label>
        <input type="checkbox" onclick="toggle('Comments')" name="completar" id="completar">
        <div class="spacer"></div>
        <div id="Comments" style="display:none;">
            <label>Resultado: </label><textarea name="resultado" id="resultado" cols="45" rows="5" class="input2"></textarea>
        </div>
        <button type="submit">Grabar</button>
        <input type="hidden" name="organizacion" id="organizacion"  value="<?php echo $claveorganizacion; ?>" />
        <input type="hidden" name="a" id="a"  value="<?php if($_GET[a]){echo $_GET[a];}else{echo $_POST[a];}?>" /><!-- archivo: Calendario -->
        <input type="hidden" name="o" id="o"  value="I" /><!-- operacion: Insert -->
        </form>
        </div>
    <?php
		break;
	case 'U':
		$sqlact="SELECT * FROM actividades WHERE id_actividad='".$_GET[id]."'";
		$resultact= mysql_query ($sqlact,$db);
        while($myrowact=mysql_fetch_array($resultact))
		{
			$time=explode(":",$myrowact[hora]);
			$hora=$time[0];
			$min=$time[1];
			$resultadoact=$myrowact[resultado];
		?>
		<div id="stylized" class="myform">
        <form name="frmactividad" method="POST" action="update.php" onsubmit="return validarForm(this);">
        <h1>Detalles de la Actividad para <?php echo $empresa; ?></h1>
        	<p>Ingrese los datos de la actividad</p>
       		<label>Actividad: </label><input type="text" name="nombre_actividad" size="25" id="nombre_actividad" value="<?php echo $myrowact[subtipo]; ?>" <?php if($myrowact[completa]==1) echo "disabled"; ?> class="input2"/>
            <div class="spacer"></div>
            <label>Descripción: </label><textarea name="descripcion" id="descripcion" cols="45" rows="5" class="input2"><?php echo $myrowact[descripcion]; ?></textarea>
            <div class="spacer"></div>
            <label>Categoría: </label>
                <select id="tipo_actividad" name="tipo_actividad">
                  <option value="Llamada" <?php if($myrowact[tipo]=='Llamada') echo "selected"; ?> >Llamada</option>
                  <option value="Email" <?php if($myrowact[tipo]=='Email') echo "selected"; ?> >Email</option>
                  <option value="Seguimiento" <?php if($myrowact[tipo]=='Seguimiento') echo "selected"; ?> >Seguimiento</option>
                  <option value="Cita" <?php if($myrowact[tipo]=='Cita') echo "selected"; ?> >Cita</option>
                </select>
                <div class="spacer"></div>
              <label>Programada: </label>
              <input type="text" id="alternate" size="30" value="<?php echo htmlentities(strftime("%A, %e %B, %Y", strtotime($_GET['fecha']))); ?>"/>
              
              <input type="text" name="date" id="date" value="<?php if($_GET['fecha']){echo $_GET['fecha'];}else{echo $date;} ?>" onchange="MostrarConsulta('consulta.php',this.value,'<?php echo $claveorganizacion; ?>'); return false"/>
              
              <div class="spacer"></div>
              <label>Hora: </label>
                <select id="hora_actividad" name="hora_actividad">
                  <option value="">--</option>
                  <option value="" <?php if($hora=='00') echo "selected"; ?> >00</option>
                  <option value="01" <?php if($hora=='01') echo "selected"; ?> >01</option>
                  <option value="02" <?php if($hora=='02') echo "selected"; ?> >02</option>
                  <option value="03" <?php if($hora=='03') echo "selected"; ?> >03</option>
                  <option value="04" <?php if($hora=='04') echo "selected"; ?> >04</option>
                  <option value="05" <?php if($hora=='05') echo "selected"; ?> >05</option>
                  <option value="06" <?php if($hora=='06') echo "selected"; ?> >06</option>
                  <option value="07" <?php if($hora=='07') echo "selected"; ?> >07</option>
                  <option value="08" <?php if($hora=='08') echo "selected"; ?> >08</option>
                  <option value="09" <?php if($hora=='09') echo "selected"; ?> >09</option>
                  <option value="10" <?php if($hora=='10') echo "selected"; ?> >10</option>
                  <option value="11" <?php if($hora=='11') echo "selected"; ?> >11</option>
                  <option value="12" <?php if($hora=='12') echo "selected"; ?> >12</option>
                  <option value="13" <?php if($hora=='13') echo "selected"; ?> >13</option>
                  <option value="14" <?php if($hora=='14') echo "selected"; ?> >14</option>
                  <option value="15" <?php if($hora=='15') echo "selected"; ?> >15</option>
                  <option value="16" <?php if($hora=='16') echo "selected"; ?> >16</option>
                  <option value="17" <?php if($hora=='17') echo "selected"; ?> >17</option>
                  <option value="18" <?php if($hora=='18') echo "selected"; ?> >18</option>
                  <option value="19" <?php if($hora=='19') echo "selected"; ?> >19</option>
                  <option value="20" <?php if($hora=='20') echo "selected"; ?> >20</option>
                  <option value="21" <?php if($hora=='21') echo "selected"; ?> >21</option>
                  <option value="22" <?php if($hora=='22') echo "selected"; ?> >22</option>
                  <option value="23" <?php if($hora=='23') echo "selected"; ?> >23</option>
                </select>
                <select id="min_actividad" name="min_actividad">
                  <option value="">--</option>
                  <option value="00" <?php if($min=='00') echo "selected"; ?> >00</option>
                  <option value="05" <?php if($min=='05') echo "selected"; ?> >05</option>
                  <option value="10" <?php if($min=='10') echo "selected"; ?> >10</option>
                  <option value="15" <?php if($min=='15') echo "selected"; ?> >15</option>
                  <option value="20" <?php if($min=='20') echo "selected"; ?> >20</option>
                  <option value="25" <?php if($min=='25') echo "selected"; ?> >25</option>
                  <option value="30" <?php if($min=='30') echo "selected"; ?> >30</option>
                  <option value="35" <?php if($min=='35') echo "selected"; ?> >35</option>
                  <option value="40" <?php if($min=='40') echo "selected"; ?> >40</option>
                  <option value="45" <?php if($min=='45') echo "selected"; ?> >45</option>
                  <option value="50" <?php if($min=='50') echo "selected"; ?> >50</option>
                  <option value="55" <?php if($min=='55') echo "selected"; ?> >55</option>
                </select>
                <div class="spacer"></div>
            	<div id="resultado" style="margin:0 0 20px 145px; padding: 0 0 20px 0;"></div>
                <?php
			if($_SESSION["Tipo"]=="Sistema"||$_SESSION["Tipo"]=="Administrador"||$_SESSION["Tipo"]=="Supervisor")
			{
				
				?>
				<label>Promotor
				</label>
				<select id="promotor" name="promotor" class="input2">
				<option value="0" <?php if($myroworg[asignado]==0){echo "selected='".$selected."'";}?>>Sin Asignar</option>
				<?php
				if($myroworg[asignado]==1&&$rol=='Promotor'){$sqlagt="SELECT * FROM usuarios WHERE tipo = 'Promotor' AND claveagente = '".$myroworg[clave_agente]."' ORDER BY apellidopaterno ASC"; $selected="selected";}else{$sqlagt="SELECT * FROM usuarios WHERE tipo = 'Promotor' ORDER BY apellidopaterno ASC"; $selected="";}
				$resultagt= mysql_query ($sqlagt,$db);
				while($myrowagt=mysql_fetch_array($resultagt))
				{
					?>
					<option value="<?php echo $myrowagt[claveagente]; ?>" <?php if($myroworg[asignado]==1){echo "selected='".$selected."'";}?>><?php echo $myrowagt[apellidopaterno]." ".$myrowagt[apellidomaterno]." ".$myrowagt[nombre]; ?></option>
					<?php
				}
				?>
				</select>
				<div class="spacer"></div>
				<?php
			}
			?>
		<label>Oportunidad: </label>
		<?php
        //Para oportunidades		
        if($_GET[organizacion])
        {
            $sqlopt="SELECT DISTINCT `clave_organizacion` AS claveorganizacion FROM oportunidades WHERE (id_etapa!=6 AND id_etapa!=7) AND usuario ='".$claveagente."' AND `clave_organizacion` ='".$claveorganizacion."' ORDER BY `fecha_cierre_esperado` DESC";
        }
        else
        {
            $sqlopt="SELECT DISTINCT `clave_organizacion` AS claveorganizacion FROM oportunidades WHERE (id_etapa!=6 AND id_etapa!=7) AND usuario ='".$claveagente."' ORDER BY `fecha_cierre_esperado` DESC";
        }
        $resultopt= mysql_query ($sqlopt,$db);
        $numopt = mysql_num_rows($resultopt);
		if($numopt)
		{
			while($myrowopt=mysql_fetch_array($resultopt))
        	{
				$sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` LIKE '".$myrowopt[claveorganizacion]."'";
				$resultorg= mysql_query ($sqlorg,$db);
				while($myroworg=mysql_fetch_array($resultorg))
				{
					$empresa=$myroworg[organizacion];
					$clave=$myroworg[clave_unica];
				}
				?>
                  <select  id="oportunidad" name="oportunidad" class="input2">
                  <optgroup label="<?php echo strtoupper($empresa); ?>">
            	<?php
				//Definir etapa de cierre de oportunidad, de momento las pondré todas
				//$sqloptorg="SELECT * FROM `oportunidades` WHERE `clave_organizacion` = '".$myrowopt[claveorganizacion]."' AND (id_etapa!=6 AND id_etapa!=7) AND usuario ='".$claveagente."' ORDER BY fecha_cierre_esperado ASC";
				$sqloptorg="SELECT * FROM `oportunidades` WHERE `clave_organizacion` = '".$myrowopt[claveorganizacion]."' AND usuario ='".$claveagente."' ORDER BY fecha_cierre_esperado ASC";
				$resultoptorg= mysql_query($sqloptorg,$db);
				while($myrowoptorg=mysql_fetch_array($resultoptorg))
				{
					$nombre_oportunidad = $myrowoptorg[productos];
					$descripcion_oportunidad = $myrowoptorg[descripcion_oportunidad];
					if($myrowoptorg[tipo_credito]){$tipo = $myrowoptorg[tipo_credito];}else{$tipo=" Tipo: sin especificar, ";}
					if($myrowoptorg[monto]){$monto = " por ".number_format($myrowoptorg[monto]);}else{$monto=" Monto: sin especificar, ";}
					if($myrowoptorg[plazo_credito]){$plazo = " a ".$myrowoptorg[plazo_credito]." meses";}else{$plazo=" Plazo: sin especificar";}
					?>
					<option value="<?php echo $myrowoptorg[id_oportunidad]; ?>" <?php if($myrowoptorg[nombre_oportunidad]==$myrowact[oportunidad]) echo "selected"; ?>><?php echo $nombre_oportunidad." ".$tipo.$monto.$plazo; ?></option>
					<?php
            	}//cierre while de oportunidades
			}?>
			</optgroup></select>
            <div class="spacer"></div>
		<?php
		}
		else
		{
			?>
			<input type="text" name="actividad" id="actividad" class="input2" value="No hay oportunidades abiertas" disabled="disabled"/>
            <?php
		}
        ?>
        <?php
		if($myrowact[completa]==1)
		{
		?> 
            <label>Resultado: </label><textarea name="resultado" id="resultado" cols="45" rows="5" class="input2"><?php echo $resultadoact; ?></textarea>
            <div class="spacer"></div>   
    	<?php
		}
		else
		{
		?>
            <div class="spacer"></div>
            <label>Completar:</label>
            <input type="checkbox" onclick="toggle('Comments')" name="completar" id="completar">
            <div class="spacer"></div>
            <div id="Comments" style="display:none;">
            	<label>Resultado: </label><textarea name="resultado" id="resultado" cols="45" rows="5" class="input2"></textarea>
        	</div>   
        <?php	
		}
		?>
        <button type="submit">Grabar</button>
        <input type="hidden" name="organizacion" id="organizacion"  value="<?php echo $claveorganizacion; ?>" />
        <input type="hidden" name="actividad" id="actividad"  value="<?php echo $_GET[id]; ?>" />
        <input type="hidden" name="a" id="a"  value="<?php if($_GET[a]){echo $_GET[a];}else{echo $_POST[a];}?>" /><!-- archivo: Calendario -->
        <input type="hidden" name="o" id="o"  value="<?php if($_GET[o]){echo $_GET[o];}else{echo $_POST[o];}?>" /><!-- operación: Update -->
        </form>
        </div>
    	<?php
		}
		break;
}
?>
				</div>
                </div>
			</div>
		</div>
	</div>
</div>      
</body>
