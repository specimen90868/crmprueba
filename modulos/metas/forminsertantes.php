<?php
include ("../../seguridad.php");
include ("../../config/config.php");
include ("../../util.php");

$claveagente=$_SESSION[Claveagente];
$claveorganizacion=$_GET[organizacion];
$numeroagente=number_format($claveagente);

$Fecha=getdate();
$date=date("Y-m-d");
echo $Anio;
$Anio=$Fecha["year"]; 
$Mes=$Fecha["mon"];
$month=date("m"); 
$Dia=$Fecha["mday"]; 
$Hora=$Fecha["hours"].":".$Fecha["minutes"].":".$Fecha["seconds"];
$Anio_anterior=$Anio-1;

$ultimodia= $Anio."-".$month."-".ultimo_dia($Mes,$Anio)." 00:00:00";
$primerdia= $Anio."-".$month."-01 00:00:00";

//Para organizaciones
$sqlorg="SELECT * FROM `organizaciones` WHERE `clave_organizacion` LIKE '".$claveorganizacion."'";
$resultorg= mysql_query ($sqlorg,$db);
while($myroworg=mysql_fetch_array($resultorg))
{
	$empresa=$myroworg[organizacion];
	$clave=$myroworg[clave_unica];
}

//Para etapas de oportunidades		
$sqletp="SELECT * FROM `etapas` ORDER BY numero_etapa ASC";
$resultetp= mysql_query ($sqletp,$db);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>crm</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../style.css"/>
<link rel="icon" href="images/icon.ico" />

<!-- page specific scripts -->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<script>
$(function() {
	$( "#date" ).datepicker({
		showButtonPanel: false,
		buttonImage: "../../images/calendar.gif",
		buttonImageOnly: true,
		dateFormat: "yy-mm-dd",
		dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
		dayNames: [ "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado" ],
		monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
		nextText: "Siguiente",
		prevText: "Anterior",
		altField: "#alternate",
		altFormat: "DD, d MM, yy",
		changeMonth: true,
      	changeYear: true
	});
});
</script>

<style>
.myform{
margin:0 auto;
width:936px;
padding:10px;
}
p, h1, form, button{border:0; margin:0; padding:0;}
.spacer{clear:both; height:1px;}

/* ----------- stylized ----------- */
#stylized{
	border:solid 1px #cccccc;
	background:#fff;
}
#stylized h1 {
	font-size:14px;
	font-weight:bold;
	margin-bottom:8px;
	color: #302369;
}
#stylized p{
	font-size:11px;
	color:#9FC733;
	margin-bottom:20px;
	border-bottom:solid 1px #9FC733;
	padding-bottom:10px;
}
#stylized label{
	display:block;
	font-weight:bold;
	text-align:right;
	width:120px;
	float:left;
}
#stylized .small{
	color:#666666;
	display:block;
	font-size:11px;
	font-weight:normal;
	text-align:right;
	width:120px;
}
#stylized input{
	float:left;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:90px;
	margin:2px 0 20px 10px;
}

#stylized select{
	float:left;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:90px;
	margin:2px 0 20px 10px;
}

#stylized .input2 {
	float:left;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:546px;
	margin:2px 10px 20px 10px;
}

#stylized button{
	clear:both;
	margin-left:350px;
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
    
    <div id="titulo">Metas</div>
    
    <div id="pageNav">
        <div id="pageNavWrapper">
        <ul class="pageNav">
            <li class=""><a href="../configuracion/index.php">General</a></li>
            <li class=""><a href="../agentes/index.php">Agentes</a></li>
            <li class="selected"><a href="#">Metas</a></li>
            <li class=""><a href="../expediente/index.php">Expedientes</a></li>
        </ul> 
        <ul class="pageActions">
            <li class="item"><img src="../../images/add.png" class="linkImage" /><a href="index.php?o=I">Concentrado de Metas</a></li>  
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
		switch($_GET[o])
		{
			case 'I':
				?>
				<div id="stylized" class="myform">
                <form id="form" name="form" method="post" action="update.php" enctype="multipart/form-data">
                <label>Promotor Comercial:</label>
      <select name="agente" id="agente" class="input2">
                	<option value="0">Ninguno</option>
					<?php
					$sqlpub="SELECT * FROM  `usuarios` WHERE `tipo`= 'Promotor' AND `estatus`= '1' ORDER BY apellidopaterno";
					$resultpub=mysql_query($sqlpub,$db);
					while($myrowpub=mysql_fetch_array($resultpub))
					{
						$sqlcuota="SELECT * FROM  `cuotas` WHERE `clave_agente`= '".$myrowpub[claveagente]."' AND `anio`= '".$Anio."'";
						$resultcuota=mysql_query($sqlcuota,$db);
						$cuotas=mysql_fetch_array($resultcuota);
						?>
                    	<option value="<?php echo $myrowpub[claveagente]; ?>" <?php if($cuotas==0){?> style="background-color:#CCC;" <?php } ?>><?php echo $myrowpub[apellidopaterno]." ".$myrowpub[apellidomaterno]." ".$myrowpub[nombre]." (Ingreso: ".$myrowpub[ingreso].")"; ?></option>
                    	<?php
					}
					?>
                </select>
                <label>Año:</label>
                <select name="anio" id="anio">
					<?php
					$incremento=10;
					for($i=$Anio;$i<=$Anio+$incremento;$i++)
					{
						?>
                    	<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    	<?php
					}
					?>
                </select>
                <div class="spacer"></div>
                
                <h1>Mínimos trimestrales</h1>
                <p>Ingrese los montos mínimos a cada trimestre</p>
                <label>Primer Trimestre:<span class="small">ene-feb-mar</span></label>
                <input type="text" name="min1" id="min1" />
                <label>Segundo Trimestre:<span class="small">abr-may-jun</span></label>
                <input type="text" name="min2" id="min2" />
                <label>Tercer Trimestre:<span class="small">jul-ago-sep</span></label>
                <input type="text" name="min3" id="min3" />
                <label>Cuarto Trimestre:<span class="small">oct-nov-dic</span></label>
                <input type="text" name="min4" id="min4" />
                <div class="spacer"></div>
                
                <h1>Metas trimestrales</h1>
                <p>Ingrese los montos correspondientes a cada trimestre</p>
                <label>Primer Trimestre:<span class="small">ene-feb-mar</span></label>
                <input type="text" name="meta1" id="meta1" />
                <label>Segundo Trimestre:<span class="small">abr-may-jun</span></label>
                <input type="text" name="meta2" id="meta2" />
                <label>Tercer Trimestre:<span class="small">jul-ago-sep</span></label>
                <input type="text" name="meta3" id="meta3" />
                <label>Cuarto Trimestre:<span class="small">oct-nov-dic</span></label>
                <input type="text" name="meta4" id="meta4" />
                <div class="spacer"></div>
                <button type="submit">Grabar</button>
                <div class="spacer"></div>
                <input type="hidden" name="organizacion" id="organizacion"  value="<?php echo $claveorganizacion; ?>" />
                <input type="hidden" name="a" id="a"  value="M" /><!-- archivo: Agentes -->
                <input type="hidden" name="o" id="o"  value="I" /><!-- operación: Insert -->
                </form>
                </div>
			  <?php
				break;
			case 'U':
				$sqlusu="SELECT * FROM usuarios WHERE idagente='".$_GET[idusuario]."'";
				$resultusu= mysql_query ($sqlusu,$db);
				while($myrowusu=mysql_fetch_array($resultusu))
				{
					
				?>
                <div id="stylized" class="myform">
                <form id="form" name="form" method="post" action="update.php" enctype="multipart/form-data">
                <h1>Modificar usuario</h1>
                <p>Ingrese los datos básicos del usuario del CRM</p>
                
                <label>Nombre
                </label>
                <input type="text" name="nombre" id="nombre" value="<?php echo $myrowusu[nombre]; ?>" />
                
                <label>Paterno <span class="small">Apellido Paterno</span>
                </label>
                <input type="text" name="apellidopaterno" id="apellidopaterno" value="<?php echo $myrowusu[apellidopaterno]; ?>" />
                
                <label>Apellido Materno
                </label>
                <input type="text" name="apellidomaterno" id="apellidomaterno" value="<?php echo $myrowusu[apellidomaterno]; ?>" />
                
                <label>Título
                </label>
<select name="titulo">
                	<option value="" <?php if($myrowusu[titulo]==''){echo 'selected'; }?> >Ninguno</option>
<option value="Lic.">Lic.</option>
                    <option value="Ing."  <?php if($myrowusu[titulo]=='Ing.'){echo 'selected'; }?> >Ing.</option>
                    <option value="Arq."  <?php if($myrowusu[titulo]=='Arq.'){echo 'selected'; }?> >Arq.</option>
                </select>
                
                <label>Puesto
                </label>
                <input type="text" name="puesto" id="puesto" value="<?php echo $myrowusu[puesto]; ?>" />
                <label>Fecha de Nacimiento
                <span class="small">Seleccione del Calendario</span>
                </label>
                <input type="text" name="date" id="date" value="<?php echo $myrowusu[fechanacimiento]; ?>" />
                <div class="spacer"></div>
                
                <label>Tipo Usuario
                </label>
<select name="tipo" id="tipo">
                	<option value="Usuario" <?php if($myrowusu[tipo]=='Usuario'){echo 'selected'; }?> >Usuario</option>
                    <option value="Administrador" <?php if($myrowusu[tipo]=='Administrador'){echo 'selected'; }?> >Administrador</option>
                    <option value="Sistema" <?php if($myrowusu[tipo]=='Sistema'){echo 'selected'; }?> >Sistema</option>
                </select>
                <label>Producto
                <span class="small">Grupo de producto</span>
                </label>
              	<select name="id_grupoproducto" id="id_grupoproducto">
                	<option value="0">Ninguno</option>
					<?php
					$sqlgpopto="SELECT * FROM  `gruposproducto` ORDER BY id_grupoproducto";
					$resultgtopto=mysql_query($sqlgpopto,$db);
					while($myrowgpopto=mysql_fetch_array($resultgtopto))
					{
					?>
                  <option value="<?php echo $myrowgpopto[id_grupoproducto]; ?>" <?php if($myrowusu[id_grupoproducto]==$myrowgpopto[id_grupoproducto]){echo 'selected'; }?> ><?php echo $myrowgpopto[grupo_producto]; ?></option>
                    <?php
					}
					?>
                </select>
                <label>Facturación
                <span class="small">Grupo de facturación</span>
                </label>
              	<select name="id_grupofacturacion" id="id_grupofacturacion">
                	<option value="0">Ninguno</option>
					<?php
					$sqlgpofac="SELECT * FROM  `gruposfacturacion` ORDER BY id_grupofacturacion";
					$resultgpofac=mysql_query($sqlgpofac,$db);
					while($myrowgpofac=mysql_fetch_array($resultgpofac))
					{
					?>
                  <option value="<?php echo $myrowgpofac[id_grupofacturacion]; ?>" <?php if($myrowusu[id_grupofacturacion]==$myrowgpofac[id_grupofacturacion]){echo 'selected'; }?> ><?php echo $myrowgpofac[grupo_facturacion]." ("; if($myrowgpofac[limite_inferior]==0){ $limitesuperior=$myrowgpofac[limite_superior]+1; echo "menor a ".number_format($limitesuperior,0).")";}elseif($myrowgpofac[limite_superior]==0){echo "mayor a ".number_format($myrowgpofac[limite_inferior],0).")";}else{$limitesuperior=$myrowgpofac[limite_superior]+1; echo "de ".number_format($myrowgpofac[limite_inferior],0)." a ".number_format($limitesuperior,0).")";} ?></option>
                    <?php
					}
					?>
                </select>

                <label>Grupos
                <span class="small">Grupos del usuario</span>
                </label>
              	<select name="grupos[]" multiple size="6">
<?php
					$grupos=explode(",",$myrowusu[idgrupo]);
					$sqlgrupos="SELECT * FROM  `familiasproductos` ORDER BY id_familiaproducto";
					$resultgrupos=mysql_query($sqlgrupos,$db);
					while($myrowgrupos=mysql_fetch_array($resultgrupos))
					{
					?>
                    <option value="<?php echo $myrowgrupos[id_familiaproducto]; ?>" <?php foreach ($grupos as $grupo) {
						if($grupo==$myrowgrupos[id_familiaproducto]){echo 'selected';};} ?> ><?php echo $myrowgrupos[familia_producto]; ?></option>
                    <?php
					}
					?>
                </select>
                
                <label>Estatus
                </label>
                <select name="estatus" id="estatus">
               	  <option value="1" <?php if($myrowusu[estatus]=='1'){echo 'selected'; }?> >Activo</option>
                  <option value="0" <?php if($myrowusu[estatus]=='0'){echo 'selected'; }?> >Inactivo</option>
                </select>
                <label>Claves de venta
                <span class="small">separadas por comas</span>
                </label>
                <input type="text" name="claves" id="claves" value="<?php echo $myrowusu[claves]; ?>" />
                <div class="spacer"></div>
               
                <label>Teléfono de oficina
                </label>
                <input type="text" name="teloficina" id="teloficina" value="<?php echo $myrowusu[teloficina]; ?>" />
                <label>Extensión
                </label>
                <input type="text" name="extoficina" id="extoficina" value="<?php echo $myrowusu[extoficina]; ?>" />
                <div class="spacer"></div>
                
                <label>Nextel
                </label>
                <input type="text" name="nextel" id="nextel" value="<?php echo $myrowusu[nextel]; ?>" />
                <label>ID Nextel
                </label>
                <input type="text" name="idnextel" id="idnextel" value="<?php echo $myrowusu[idnextel]; ?>" />
                <div class="spacer"></div>
                
                <label>Directo
                </label>
                <input type="text" name="teldirecto" id="teldirecto" value="<?php echo $myrowusu[teldirecto]; ?>" />
                <label>Teléfono de casa
                </label>
                <input type="text" name="telcasa" id="telcasa" value="<?php echo $myrowusu[telcasa]; ?>" />
                <div class="spacer"></div>
                
                <label>Email
                <span class="small">Email de trabajo</span>
                </label>
                <input type="text" name="email" id="email" value="<?php echo $myrowusu[email]; ?>" />
                <label>Otro Email
                <span class="small">Email personal</span>
                </label>
                <input type="text" name="emailotro" id="emailotro" value="<?php echo $myrowusu[emailotro]; ?>" />
                <div class="spacer"></div>
                
                <label>Foto
                <span class="small">Fotografía del usuario</span>
                </label>
                <input name="archivofoto" type="file" class="input2" id="archivofoto" />
                <?php if($myrowusu[foto]){?><img class="picture-thumbnail" src="../../fotos/<?php echo $myrowusu[foto]; ?>" width="32" alt="" /><?php } else {?> <img class="picture-thumbnail" src="../../images/person_avatar_32.png" width="32" height="32" alt="" /> <?php }?>
                <div class="spacer"></div>
                <label>Firma
                <span class="small">Firma digitalizada</span>
                </label>
                <input name="archivofirma" type="file" class="input2" id="archivofirma" />
                <?php if($myrowusu[firma]){?> <img class="" src="../../firmas/<?php echo $myrowusu[firma]; ?>" alt="" /><?php } else {?>  <img class="picture-thumbnail" src="../../images/person_avatar_32.png" height="32" alt="" /> <?php }?>
                <div class="spacer"></div>
                
                <label>Usuario de acceso
                <span class="small">Clave de agente</span>
                </label>
                <input type="text" name="claveagente" id="claveagente" value="<?php echo $myrowusu[claveagente]; ?>" />
                <label>Password
                <span class="small">Contraseña del CRM</span>
                </label>
                <input type="password" name="contrasenia" id="contrasenia" value="<?php echo $myrowusu[contrasenia]; ?>" />
                <div class="spacer"></div>
                
                <button type="submit">Grabar</button>
                <div class="spacer"></div>
                <input type="hidden" name="usuario" id="usuario"  value="<?php echo $_GET[idusuario]; ?>" />
                <input type="hidden" name="a" id="a"  value="Ag" /><!-- archivo: Agentes -->
                <input type="hidden" name="o" id="o"  value="U" /><!-- operación: Insert -->
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
