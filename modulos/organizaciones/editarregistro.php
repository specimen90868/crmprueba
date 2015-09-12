<?php
include ("../../seguridad.php");
include ("../../config/config.php");

$claveagente=$_SESSION[Claveagente];
$claveorganizacion=$_GET[organizacion];

//$numeroagente=number_format($claveagente);
$numeroagente=$claveagente;

$meses_espanol = array(
    '01' => 'Enero',
    '02' => 'Febrero',
    '03' => 'Marzo',
    '04' => 'Abril',
    '05' => 'Mayo',
    '06' => 'Junio',
    '07' => 'Julio',
    '08' => 'Agosto',
    '09' => 'Septiembre',
    '10' => 'Octubre',
    '11' => 'Noviembre',
    '12' => 'Diciembre',
    ); 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../css/modalform.css"/>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<script>
$(function() {
	$( "#ORGfundacion" ).datepicker({
		/*showButtonPanel: false,
		showOn: "button",
		buttonImage: "../../images/calendar.gif",
		buttonImageOnly: false,*/
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

function mostrarReferencia()
{
	if(document.form1.CONrep_legal.value=='Garante'||document.form1.CONrep_legal.value=='Acreditado')
	{
		document.getElementById('motivo').style.display='block';
	}
	else
	{
		document.getElementById('motivo').style.display='none';
	}
}
</script>

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
	/*border:solid 1px #e3e3e3;*/
	margin:20px 0 0 0;
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
	width:160px;
	margin:2px 0 20px 10px;
}

#stylized select{
	float:left;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:160px;
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
	width:476px;
	margin:2px 10px 20px 10px;
}

#stylized .input3 {
	float:left;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #cccccc;
	width:75px;
	margin:2px 5px 20px 10px;
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

<title>Documento sin título</title>
</head>

<body>
<?php

switch($_GET[t])
{
	//ORGANIZACIONES
	case 'O':
		if($_GET[o]=="U")
		{
			$sqledit="SELECT * FROM organizaciones WHERE id_organizacion=$_GET[id]";
			$resultedit= mysql_query ($sqledit,$db);
			while($myrowedit=mysql_fetch_array($resultedit))
			{
				?>
				<div id="stylized" class="myform">
                <form action="update.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
                <label for="textfield">Organización:</label>
				<input type="text" name="ORGorganizacion" id="ORGorganizacion" value="<?php echo $myrowedit[organizacion]; ?>" class="input2"/>
                <div class="spacer"></div>
                <label for="textfield">Tipo Persona:</label>
<select id="ORGtipo_persona" name="ORGtipo_persona">
					<option value="Física" <?php if($myrowedit[tipo_persona]=='Fisica') echo "selected"; ?> >Física</option>
					<option value="Moral" <?php if($myrowedit[tipo_persona]=='Moral') echo "selected"; ?> >Moral</option>
				</select>
				<label for="textfield">Clave de cliente:</label>
				<input type="text" name="ORGclave" id="ORGclave" value="<?php echo $myrowedit[clave_unica]; ?>"/>
                <div class="spacer"></div>
				<label for="textfield">Tipo contacto:</label>
<select id="ORGtipo_organizacion" name="ORGtipo_organizacion" class="myinputstyle">
					<option value="Cliente" <?php if($myrowedit[tipo_organizacion]=='Cliente') echo "selected"; ?> >Cliente</option>
					<option value="Prospecto" <?php if($myrowedit[tipo_organizacion]=='Prospecto') echo "selected"; ?> >Prospecto</option>
				</select>
				<label for="textfield">Fundación:</label>
				<input type="text" name="ORGfundacion" id="ORGfundacion" value="<?php echo $myrowedit[fecha_fundacion]; ?>"/>
                <div class="spacer"></div>
                <label for="textfield">Procedencia:</label>
<select id="ORGprocedencia" name="ORGprocedencia" class="myinputstyle">
					<option value="ANTAD">ANTAD</option>
                    <option value="Base de Datos" <?php if($myrowedit[procedencia]=='Base de Datos') echo "selected"; ?>>Base de Datos</option>
                    <option value="Habilitado FIRA" <?php if($myrowedit[procedencia]=='Habilitado FIRA') echo "selected"; ?>>Habilitado FIRA</option>
                    <option value="Llamada Website" <?php if($myrowedit[procedencia]=='Llamada Website') echo "selected"; ?>>Llamada Website</option>
                    <option value="Revista" <?php if($myrowedit[procedencia]=='Revista') echo "selected"; ?>>Revista</option>
                    <option value="Website" <?php if($myrowedit[procedencia]=='Website') echo "selected"; ?>>Website</option>
				</select>
				<label for="textfield">Clave Website:</label>
				<input type="text" name="ORGclave_web" id="ORGclave_web" value="<?php echo $myrowedit[clave_web]; ?>"/>
                <div class="spacer"></div>
<label for="textfield">Formas de Contacto:</label><select id="ORGforma_contacto" name="ORGforma_contacto" class="">
                    <option value="Teléfono" <?php if($myrowedit[forma_contacto]=='Teléfono') echo "selected"; ?>>Teléfono</option>
                    <option value="Email" <?php if($myrowedit[forma_contacto]=='Email') echo "selected"; ?>>Email</option>
                    <option value="Teléfono/Email" <?php if($myrowedit[forma_contacto]=='Teléfono/Email') echo "selected"; ?>>Teléfono/Email</option>
                  </select>
                  <div class="spacer"></div>
                <button type="submit">Enviar</button>
                <div class="spacer"></div>
				<input type="hidden" name="organizacion" id="organizacion" value="<?php echo $_GET[organizacion]; ?>" />
                <input type="hidden" name="operacion" id="operacion" value="<?php echo $_GET[o]; ?>" />
	        	<input type="hidden" name="tipo" id="tipo" value="<?php echo $_GET[t]; ?>" />
                <input type="hidden" name="id" id="id" value="<?php echo $_GET[id]; ?>" />
				</form>
                </div>
				<?php
			}
		}
	break;
	//CONTACTOS
	case 'C':
		if($_GET[o]=="U")
		{
		$sqledit="SELECT * FROM contactos WHERE id_contacto=$_GET[id]";
		$resultedit= mysql_query ($sqledit,$db);
		while($myrowedit=mysql_fetch_array($resultedit))
		{
			//Separar fecha de nacimiento
			$nacimiento=explode("-",$myrowedit[fecha_nacimiento]);
			$anio=$nacimiento[0];$mes=$nacimiento[1];$dia=$nacimiento[2];
			$sqlemailcon="SELECT * FROM `correos` WHERE `clave_registro` LIKE '".$myrowedit[clave_contacto]."'";
			$resultemailcon= mysql_query ($sqlemailcon,$db);
			while($myrowemailcon=mysql_fetch_array($resultemailcon))
			{
				$email=$myrowemailcon[correo];
			}
			?>
				<div id="stylized" class="myform">               
                <form action="update.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
                    <label for="textfield">Nombre:</label>
                  <input name="CONnombre" type="text" id="CONnombre" value="<?php echo $myrowedit[nombre]; ?>"/>
                  <label for="textfield">Título:</label>
              <select id="CONtitulo" name="CONtitulo" class="input3">
                    	<option value="0" <?php if($myrowedit[titulo]=='0') echo "selected"; ?> >--</option>
                        <option value="Sr." <?php if($myrowedit[titulo]=='Sr.') echo "selected"; ?> >Sr.</option>
                        <option value="Sra." <?php if($myrowedit[titulo]=='Sra.') echo "selected"; ?> >Sra.</option>
                        <option value="Srita." <?php if($myrowedit[titulo]=='Srita.') echo "selected"; ?> >Srita.</option>
                        <option value="Lic." <?php if($myrowedit[titulo]=='Lic.') echo "selected"; ?> >Lic.</option>
                        <option value="Ing." <?php if($myrowedit[titulo]=='Ing.') echo "selected"; ?> >Ing.</option>
                        <option value="Arq." <?php if($myrowedit[titulo]=='Arq.') echo "selected"; ?> >Arq.</option>
                        <option value="Dr." <?php if($myrowedit[titulo]=='Dr.') echo "selected"; ?> >Dr.</option>
                        <option value="C.P." <?php if($myrowedit[titulo]=='C.P.') echo "selected"; ?> >C.P.</option>
                      </select>
                    <div class="spacer"></div>
                    <label for="textfield">Apellidos:</label>
                    <input name="CONapellido" type="text" class="input2" id="CONapellido" value="<?php echo $myrowedit[apellidos]; ?>"/>
                <div class="spacer"></div>
                    <label for="textfield">Puesto:</label>
                    <input type="text" name="CONpuesto" id="CONpuesto" value="<?php echo $myrowedit[puesto]; ?>"/>
                    <label for="textfield">Email:</label>
                    <input type="text" name="CONemail" id="CONemail" value="<?php echo $email; ?>"/>
                    <div class="spacer"></div>
                    <label for="textfield">Celular:</label>
                    <input type="text" name="CONcelular" id="CONcelular" value="<?php echo $myrowedit[telefono_celular]; ?>"/>
                    <label for="textfield">Directo:</label>
                    <input type="text" name="CONdirecto" id="CONdirecto" value="<?php echo $myrowedit[telefono_oficina]; ?>"/>
                    <div class="spacer"></div>
                    <label for="textfield">Cumpleaños:</label>
                    <select id="CONdianac" name="CONdianac" class="input3">
                    	<option value="0" <?php if($myrowedit[dia_cumpleanios]=='0') echo "selected"; ?> >día</option>
                        <option value="01" <?php if($myrowedit[dia_cumpleanios]=='01') echo "selected"; ?> >01</option>
                        <option value="02" <?php if($myrowedit[dia_cumpleanios]=='02') echo "selected"; ?> >02</option>
                        <option value="03" <?php if($myrowedit[dia_cumpleanios]=='03') echo "selected"; ?> >03</option>
                        <option value="04" <?php if($myrowedit[dia_cumpleanios]=='04') echo "selected"; ?> >04</option>
                        <option value="05" <?php if($myrowedit[dia_cumpleanios]=='05') echo "selected"; ?> >05</option>
                        <option value="06" <?php if($myrowedit[dia_cumpleanios]=='06') echo "selected"; ?> >06</option>
                        <option value="07" <?php if($myrowedit[dia_cumpleanios]=='07') echo "selected"; ?> >07</option>
                        <option value="08" <?php if($myrowedit[dia_cumpleanios]=='08') echo "selected"; ?> >08</option>
                        <option value="09" <?php if($myrowedit[dia_cumpleanios]=='09') echo "selected"; ?> >09</option>
                        <option value="10" <?php if($myrowedit[dia_cumpleanios]=='10') echo "selected"; ?> >10</option>
                        <option value="11" <?php if($myrowedit[dia_cumpleanios]=='11') echo "selected"; ?> >11</option>
                        <option value="12" <?php if($myrowedit[dia_cumpleanios]=='12') echo "selected"; ?> >12</option>
                        <option value="13" <?php if($myrowedit[dia_cumpleanios]=='13') echo "selected"; ?> >13</option>
                        <option value="14" <?php if($myrowedit[dia_cumpleanios]=='14') echo "selected"; ?> >14</option>
                        <option value="15" <?php if($myrowedit[dia_cumpleanios]=='15') echo "selected"; ?> >15</option>
                        <option value="16" <?php if($myrowedit[dia_cumpleanios]=='16') echo "selected"; ?> >16</option>
                        <option value="17" <?php if($myrowedit[dia_cumpleanios]=='17') echo "selected"; ?> >17</option>
                        <option value="18" <?php if($myrowedit[dia_cumpleanios]=='18') echo "selected"; ?> >18</option>
                        <option value="19" <?php if($myrowedit[dia_cumpleanios]=='19') echo "selected"; ?> >19</option>
                        <option value="20" <?php if($myrowedit[dia_cumpleanios]=='20') echo "selected"; ?> >20</option>
                        <option value="21" <?php if($myrowedit[dia_cumpleanios]=='21') echo "selected"; ?> >21</option>
                        <option value="22" <?php if($myrowedit[dia_cumpleanios]=='22') echo "selected"; ?> >22</option>
                        <option value="23" <?php if($myrowedit[dia_cumpleanios]=='23') echo "selected"; ?> >23</option>
                        <option value="24" <?php if($myrowedit[dia_cumpleanios]=='24') echo "selected"; ?> >24</option>
                        <option value="25" <?php if($myrowedit[dia_cumpleanios]=='25') echo "selected"; ?> >25</option>
                        <option value="26" <?php if($myrowedit[dia_cumpleanios]=='26') echo "selected"; ?> >26</option>
                        <option value="27" <?php if($myrowedit[dia_cumpleanios]=='27') echo "selected"; ?> >27</option>
                        <option value="28" <?php if($myrowedit[dia_cumpleanios]=='28') echo "selected"; ?> >28</option>
                        <option value="29" <?php if($myrowedit[dia_cumpleanios]=='29') echo "selected"; ?> >29</option>
                        <option value="30" <?php if($myrowedit[dia_cumpleanios]=='30') echo "selected"; ?> >30</option>
                        <option value="31" <?php if($myrowedit[dia_cumpleanios]=='31') echo "selected"; ?> >31</option>
                      </select>
                      <select id="CONmesnac" name="CONmesnac" class="input3">
                      	<option value="0" <?php if($myrowedit[mes_cumpleanios]=='0') echo "selected"; ?> >mes</option>
                        <option value="01" <?php if($myrowedit[mes_cumpleanios]=='01') echo "selected"; ?> >01</option>
                        <option value="02" <?php if($myrowedit[mes_cumpleanios]=='02') echo "selected"; ?> >02</option>
                        <option value="03" <?php if($myrowedit[mes_cumpleanios]=='03') echo "selected"; ?> >03</option>
                        <option value="04" <?php if($myrowedit[mes_cumpleanios]=='04') echo "selected"; ?> >04</option>
                        <option value="05" <?php if($myrowedit[mes_cumpleanios]=='05') echo "selected"; ?> >05</option>
                        <option value="06" <?php if($myrowedit[mes_cumpleanios]=='06') echo "selected"; ?> >06</option>
                        <option value="07" <?php if($myrowedit[mes_cumpleanios]=='07') echo "selected"; ?> >07</option>
                        <option value="08" <?php if($myrowedit[mes_cumpleanios]=='08') echo "selected"; ?> >08</option>
                        <option value="09" <?php if($myrowedit[mes_cumpleanios]=='09') echo "selected"; ?> >09</option>
                        <option value="10" <?php if($myrowedit[mes_cumpleanios]=='10') echo "selected"; ?> >10</option>
                        <option value="11" <?php if($myrowedit[mes_cumpleanios]=='11') echo "selected"; ?> >11</option>
                        <option value="12" <?php if($myrowedit[mes_cumpleanios]=='12') echo "selected"; ?> >12</option>
                      </select>
                    <label for="textfield">Relación:</label>
<select id="CONrep_legal[]" name="CONrep_legal[]" onchange="mostrarReferencia();" multiple size="1">
                        <option value="">Selecciona</option>
                        <?php
                        $sqlroles="SELECT * FROM  `roles` WHERE `visible`=1 ORDER BY `id_rol` ASC";
						$resultroles=mysql_query($sqlroles,$db);
                        while($myrowroles=mysql_fetch_array($resultroles))
                        {
                        ?>
                          <option value="<?php echo $myrowroles[id_rol]; ?>"><?php echo $myrowroles[rol]; ?></option>
                         <?php
						}
						?>
                      </select>
                <div class="spacer"></div>
                
                <div id="motivo" style="display:none;">
                <label title="del Garante o Acreditado">Tipo Persona: </label>
                <select id="tipo_persona" name="tipo_persona">
                  <option value="" selected="selected">Selecciona</option>
                  <option value="Física">Física</option>
                  <option value="Moral">Moral</option>
                </select>
                <div class="spacer"></div>
                </div>
                  
                    <button type="submit">Enviar</button>
                    <div class="spacer"></div>
                    <input type="hidden" name="organizacion" id="organizacion" value="<?php echo $_GET[organizacion]; ?>" />
                    <input type="hidden" name="id" id="id" value="<?php echo $_GET[id]; ?>" />
                    <input type="hidden" name="operacion" id="operacion" value="<?php echo $_GET[o]; ?>" />
                    <input type="hidden" name="tipo" id="tipo" value="<?php echo $_GET[t]; ?>" />
                    <input type="hidden" name="e" id="e" value="<?php echo $_GET[e]; ?>" />
                    <input type="hidden" name="m" id="m" value="<?php echo $_GET[m]; ?>" />
                    <input type="hidden" name="d" id="d" value="<?php echo $_GET[d]; ?>" />
                </form>
                </div>
            	<?php
		}//Fin de while 
		}
		else
		{
			if($_GET[o]=="I")
			{
				?>
                <div id="stylized" class="myform">
				<form action="update.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
                    <label for="textfield">Nombre:</label>
                <input type="text" name="CONnombre" id="CONnombre" />
                    <label for="textfield">Título:</label>
                    <select id="CONtitulo" name="CONtitulo" class="input3">
                    	<option value="0" <?php if($myrowedit[titulo]=='0') echo "selected"; ?> >--</option>
                        <option value="Sr." <?php if($myrowedit[titulo]=='Sr.') echo "selected"; ?> >Sr.</option>
                         <option value="Sra." <?php if($myrowedit[titulo]=='Sra.') echo "selected"; ?> >Sra.</option>
                          <option value="Srita." <?php if($myrowedit[titulo]=='Srita.') echo "selected"; ?> >Srita.</option>
                        <option value="Lic." <?php if($myrowedit[titulo]=='Lic.') echo "selected"; ?> >Lic.</option>
                        <option value="Ing." <?php if($myrowedit[titulo]=='Ing.') echo "selected"; ?> >Ing.</option>
                        <option value="Arq." <?php if($myrowedit[titulo]=='Arq.') echo "selected"; ?> >Arq.</option>
                        <option value="Dr." <?php if($myrowedit[titulo]=='Dr.') echo "selected"; ?> >Dr.</option>
                        <option value="C.P." <?php if($myrowedit[titulo]=='C.P.') echo "selected"; ?> >C.P.</option>
                      </select>
                    <div class="spacer"></div>
                    <label for="textfield">Apellidos:</label>
      <input type="text" name="CONapellido" id="CONapellido"  class="input2"/>
                    <div class="spacer"></div>
                    <label for="textfield">Puesto:</label>
                    <input type="text" name="CONpuesto" id="CONpuesto" />
                    <label for="textfield">Email:</label>
                    <input type="text" name="CONemail" id="CONemail" />
                    <div class="spacer"></div>
                    <label for="textfield">Celular:</label>
                    <input type="text" name="CONcelular" id="CONcelular" />
                    <label for="textfield">Directo:</label>
                    <input type="text" name="CONdirecto" id="CONdirecto" />
                    <div class="spacer"></div>
                    <label for="textfield">Cumpleaños:</label>
                    <select id="CONdianac" name="CONdianac" class="input3">
                        <option value="0">día</option>
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
                        <option value="24">24</option>
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                        <option value="31">31</option>
                      </select>
                      <select id="CONmesnac" name="CONmesnac" class="input3">
                        <option value="0">mes</option>
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
                      </select>
                    <label for="textfield">Relación:</label>
<select id="CONrep_legal" name="CONrep_legal" onchange="mostrarReferencia();" multiple="multiple">
                       <option value="">Selecciona</option>
                        <?php
                        $sqlroles="SELECT * FROM  `roles` WHERE `visible`=1 ORDER BY `id_rol` ASC";
						$resultroles=mysql_query($sqlroles,$db);
                        while($myrowroles=mysql_fetch_array($resultroles))
                        {
                        ?>
                          <option value="<?php echo $myrowroles[id_rol]; ?>"><?php echo $myrowroles[rol]; ?></option>
                         <?php
						}
						?>
                      </select>
                        <div class="spacer"></div>
                        <div id="motivo" style="display:none;">
                        <label title="del Garante o Acreditado">Tipo Persona: </label>
                        <select id="tipo_persona" name="tipo_persona">
                          <option value="" selected="selected">Selecciona</option>
                          <option value="Física">Física</option>
                          <option value="Moral">Moral</option>
                        </select>
                        <div class="spacer"></div>
                        </div>  
                    <button type="submit">Enviar</button>
                    <input type="hidden" name="organizacion" id="organizacion" value="<?php echo $_GET[organizacion]; ?>" />
                    <input type="hidden" name="operacion" id="operacion" value="<?php echo $_GET[o]; ?>" />
                    <input type="hidden" name="tipo" id="tipo" value="<?php echo $_GET[t]; ?>" />
				</form>
                </div>
				<?php
			}
	}
	break;
	//TELÉFONOS
	case 'T':
		if($_GET[o]=="U")
		{
			$sqledit="SELECT * FROM telefonos WHERE id_telefono=$_GET[id]";
			$resultedit= mysql_query ($sqledit,$db);
			while($myrowedit=mysql_fetch_array($resultedit))
			{
				?>
				<div id="stylized" class="myform">
                <form action="update.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
                <label for="textfield">Teléfono:</label>
				<input type="text" name="TELtelefono" id="TELtelefono" value="<?php echo $myrowedit[telefono]; ?>"/>
				<label for="textfield">Tipo:</label>
				<select id="TELtipo_telefono" name="TELtipo_telefono" class="myinputstyle">
					<option value="Oficina" <?php if($myrowedit[tipo_telefono]=='Oficina') echo "selected"; ?> >Oficina</option>
					<option value="Directo" <?php if($myrowedit[tipo_telefono]=='Directo') echo "selected"; ?> >Directo</option>
					<option value="Fax" <?php if($myrowedit[tipo_telefono]=='Fax') echo "selected"; ?> >Fax</option>
					<option value="Celular" <?php if($myrowedit[tipo_telefono]=='Celular') echo "selected"; ?> >Celular</option>
					<option value="Nextel" <?php if($myrowedit[tipo_telefono]=='Nextel') echo "selected"; ?> >Nextel</option>
				</select>
				<div class="spacer"></div>  
                <button type="submit">Enviar</button>
				<input type="hidden" name="organizacion" id="organizacion" value="<?php echo $_GET[organizacion]; ?>" />
                <input type="hidden" name="operacion" id="operacion" value="<?php echo $_GET[o]; ?>" />
	        	<input type="hidden" name="tipo" id="tipo" value="<?php echo $_GET[t]; ?>" />
                <input type="hidden" name="id" id="id" value="<?php echo $_GET[id]; ?>" />

				</form>
                </div>
				<?php
			}
		}
		else if($_GET[o]=="I")
		{
			?>
				<div id="stylized" class="myform">
                <form action="update.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
                <label for="textfield">Teléfono:</label>
				<input type="text" name="TELtelefono" id="TELtelefono" />
				<label for="textfield">Tipo:</label>
				<select id="TELtipo_telefono" name="TELtipo_telefono" class="myinputstyle">
					<option value="Oficina">Oficina</option>
					<option value="Directo">Directo</option>
					<option value="Fax">Fax</option>
					<option value="Celular">Celular</option>
					<option value="Nextel">Nextel</option>
				</select>
				<div class="spacer"></div>  
                <button type="submit">Enviar</button>
				<input type="hidden" name="organizacion" id="organizacion" value="<?php echo $_GET[organizacion]; ?>" />
                <input type="hidden" name="operacion" id="operacion" value="<?php echo $_GET[o]; ?>" />
	        	<input type="hidden" name="tipo" id="tipo" value="<?php echo $_GET[t]; ?>" />
				</form>
                </div>
				<?php	
		}
	break;
	//CORREOS ELECTRÓNICOS
	case 'E':
		if($_GET[o]=="U")
		{
			$sqledit="SELECT * FROM correos WHERE id_correo=$_GET[id]";
			$resultedit= mysql_query ($sqledit,$db);
			while($myrowedit=mysql_fetch_array($resultedit))
			{
				?>   
				<div id="stylized" class="myform">
				<form action="update.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
                <label for="textfield">Correo:</label>
				<input type="text" name="CORemailo" id="CORemailo" value="<?php echo $myrowedit[correo]; ?>"/>
				<label for="textfield">Tipo:</label>
				<input type="text" name="CORtipoo" id="CORtipoo" value="<?php echo $myrowedit[tipo_correo]; ?>"/>
				<div class="spacer"></div>  
                <button type="submit">Enviar</button>s
				<input type="hidden" name="organizacion" id="organizacion" value="<?php echo $_GET[organizacion]; ?>" />
                <input type="hidden" name="operacion" id="operacion" value="<?php echo $_GET[o]; ?>" />
	        	<input type="hidden" name="tipo" id="tipo" value="<?php echo $_GET[t]; ?>" />
                <input type="hidden" name="id" id="id" value="<?php echo $_GET[id]; ?>" />
				</form>
                </div>
				<?php
			}
		}
		else if($_GET[o]=="I")
		{
			?>
            <div id="stylized" class="myform">
            <form action="update.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
            <label for="textfield">Correo:</label>
            <input type="text" name="CORemailo" id="CORemailo" />
            <label for="textfield">Tipo:</label>
            <input type="text" name="CORtipoo" id="CORtipoo" />
            <div class="spacer"></div>  
            <button type="submit">Enviar</button>
            <input type="hidden" name="organizacion" id="organizacion" value="<?php echo $_GET[organizacion]; ?>" />
            <input type="hidden" name="operacion" id="operacion" value="<?php echo $_GET[o]; ?>" />
            </form>
            </div>
            <?php
		}
	break;
	//DIRECCIONES WEB
	case 'W':
		if($_GET[o]=="U")
		{
			$sqledit="SELECT * FROM direccionesweb WHERE id_direccionweb=$_GET[id]";
			$resultedit= mysql_query ($sqledit,$db);
			while($myrowedit=mysql_fetch_array($resultedit))
			{
				?>
                <div id="stylized" class="myform">
				<form action="update.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
                <label for="textfield">Dirección Web:</label>
				<input type="text" name="WEBdireccion_web" id="WEBdireccion_web" value="<?php echo $myrowedit[direccion]; ?>"/>
				<label for="textfield">Tipo:</label>
				<select id="WEBtipo_direccion_web" name="WEBtipo_direccion_web" class="myinputstyle">
					<option value="Website" <?php if($myrowedit[tipo_direccion]=='Website') echo "selected"; ?> >Website</option>
					<option value="Skype" <?php if($myrowedit[tipo_direccion]=='Skype') echo "selected"; ?> >Skype</option>
					<option value="Twitter" <?php if($myrowedit[tipo_direccion]=='Twitter') echo "selected"; ?> >Twitter</option>
					<option value="LinkedIn" <?php if($myrowedit[tipo_direccion]=='LinkedIn') echo "selected"; ?> >LinkedIn</option>
					<option value="Facebook" <?php if($myrowedit[tipo_direccion]=='Facebook') echo "selected"; ?> >Facebook</option>
					<option value="Xing" <?php if($myrowedit[tipo_direccion]=='Xing') echo "selected"; ?> >Xing</option>
					<option value="Blog" <?php if($myrowedit[tipo_direccion]=='Blog') echo "selected"; ?> >Blog</option>
					<option value="Google+" <?php if($myrowedit[tipo_direccion]=='Google+') echo "selected"; ?> >Google+</option>
					<option value="Flickr" <?php if($myrowedit[tipo_direccion]=='Flickr') echo "selected"; ?> >Flickr</option>
					<option value="GitHub" <?php if($myrowedit[tipo_direccion]=='GitHub') echo "selected"; ?> >GitHub</option>
					<option value="Youtube" <?php if($myrowedit[tipo_direccion]=='Youtube') echo "selected"; ?> >Youtube</option>
				</select>
				<div class="spacer"></div>  
                <button type="submit">Enviar</button>
				<input type="hidden" name="organizacion" id="organizacion" value="<?php echo $_GET[organizacion]; ?>" />
                <input type="hidden" name="operacion" id="operacion" value="<?php echo $_GET[o]; ?>" />
        		<input type="hidden" name="tipo" id="tipo" value="<?php echo $_GET[t]; ?>" />
                <input type="hidden" name="id" id="id" value="<?php echo $_GET[id]; ?>" />
				</form>
                </div>
				<?php
			}
		}
		else if($_GET[o]=="I")
		{
			?>
            <div id="stylized" class="myform">
            <form action="update.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
            <label for="textfield">Dirección Web:</label>
            <input type="text" name="WEBdireccion_web" id="WEBdireccion_web"/>
            <label for="textfield">Tipo:</label>
            <select id="WEBtipo_direccion_web" name="WEBtipo_direccion_web" class="myinputstyle">
                <option value="Website">Website</option>
                <option value="Skype">Skype</option>
                <option value="Twitter">Twitter</option>
                <option value="LinkedIn">LinkedIn</option>
                <option value="Facebook">Facebook</option>
                <option value="Xing">Xing</option>
                <option value="Blog">Blog</option>
                <option value="Google+">Google+</option>
                <option value="Flickr">Flickr</option>
                <option value="GitHub">GitHub</option>
                <option value="Youtube">Youtube</option>
            </select>
            <div class="spacer"></div>  
            <button type="submit">Enviar</button>
            <input type="hidden" name="organizacion" id="organizacion" value="<?php echo $_GET[organizacion]; ?>" />
            <input type="hidden" name="operacion" id="operacion" value="<?php echo $_GET[o]; ?>" />
        	<input type="hidden" name="tipo" id="tipo" value="<?php echo $_GET[t]; ?>" />
            </form>
            </div>
            <?php
		}
	break;
	//DOMICILIOS
	case 'D':
	if($_GET[o]=="U")
	{
		$sqledit="SELECT * FROM domicilios WHERE id_domicilio=$_GET[id]";
		$resultedit= mysql_query ($sqledit,$db);
		while($myrowedit=mysql_fetch_array($resultedit))
		{
			?>
			<div id="stylized" class="myform">
            <form action="update.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
            <label for="textfield">Tipo de Domicilio:</label>
			<select id="DOMtipo_domicilio" name="DOMtipo_domicilio" class="myinputstyle">
				<option value="Principal" <?php if($myrowedit[tipo_domicilio]=='Principal') echo "selected"; ?> >Principal</option>
				<option value="Sucursal" <?php if($myrowedit[tipo_domicilio]=='Sucursal') echo "selected"; ?> >Sucursal</option>
				<option value="Fiscal" <?php if($myrowedit[tipo_domicilio]=='Fiscal') echo "selected"; ?> >Fiscal</option>
			</select>
            <div class="spacer"></div>
            <label for="textfield">Dirección:</label>
			<input type="text" name="DOMdomicilio" id="DOMdomicilio" value="<?php echo $myrowedit[domicilio]; ?>" class="input2"/>
            <label for="textfield">Ciudad/Delegación:</label>
			<input type="text" name="DOMciudad" id="DOMciudad" value="<?php echo $myrowedit[ciudad]; ?>"/>
            <label for="textfield">Estado:</label>
			<input type="text" name="DOMestado" id="DOMestado" value="<?php echo $myrowedit[estado]; ?>"/>
            <label for="textfield">Código Postal:</label>
			<input type="text" name="DOMcp" id="DOMcp" value="<?php echo $myrowedit[cp]; ?>"/>
			<div class="spacer"></div>  
            <button type="submit">Enviar</button>
			<input type="hidden" name="organizacion" id="organizacion" value="<?php echo $_GET[organizacion]; ?>" />
            <input type="hidden" name="operacion" id="operacion" value="<?php echo $_GET[o]; ?>" />
        	<input type="hidden" name="tipo" id="tipo" value="<?php echo $_GET[t]; ?>" />
            <input type="hidden" name="id" id="id" value="<?php echo $_GET[id]; ?>" />
			</form>
            </div>
			<?php
		}
	}
	else if($_GET[o]=="I")
	{
		?>
        <div id="stylized" class="myform">
		<form action="update.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
            <label for="textfield">Tipo de Domicilio:</label>
            <select id="DOMtipo_domicilio" name="DOMtipo_domicilio" class="myinputstyle">
                <option value="Principal" >Principal</option>
                <option value="Sucursal" >Sucursal</option>
                <option value="Fiscal" >Fiscal</option>
            </select>
            <div class="spacer"></div> 
            <label for="textfield">Dirección:</label><input type="text" name="DOMdomicilio" id="DOMdomicilio" class="input2"/>
            <label for="textfield">Ciudad/Delegación:</label><input type="text" name="DOMciudad" id="DOMciudad"/>
            <label for="textfield">Estado:</label><input type="text" name="DOMestado" id="DOMestado"/>
            <label for="textfield">Código Postal:</label><input type="text" name="DOMcp" id="DOMcp"/>
            <div class="spacer"></div>  
            <button type="submit">Enviar</button>
            <input type="hidden" name="organizacion" id="organizacion" value="<?php echo $_GET[organizacion]; ?>" />
            <input type="hidden" name="operacion" id="operacion" value="<?php echo $_GET[o]; ?>" />
            <input type="hidden" name="tipo" id="tipo" value="<?php echo $_GET[t]; ?>" />
        </form>
        </div>
		<?php
	}
	break;
	//RFC Y RAZONES SOCIALES
	case 'R':
	if($_GET[o]=="U")
	{
		$sqledit="SELECT * FROM razonessociales WHERE id_razonsocial=$_GET[id]";
		$resultedit= mysql_query ($sqledit,$db);
		while($myrowedit=mysql_fetch_array($resultedit))
		{
			?>
            <div id="stylized" class="myform">
			<form action="update.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
            <!-- Código del Autocompletar , todo el código html necesario estra entre estos comentarios -->
            <label for="textfield">Razón Social:</label>
            <input type="text" id="quickfind" class="ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" name="quickfind" value="<?php echo $myrowedit[razon_social]; ?>" style="width:476px;">
            <!-- fin de codigo autocompletar -->
			<div class="spacer"></div>
            <label for="textfield">RFC:</label>
			<input type="text" name="RFCrfc" id="RFCrfc" value="<?php echo $myrowedit[rfc]; ?>"/>
			<div class="spacer"></div>  
            <button type="submit">Enviar</button>
			<input type="hidden" name="organizacion" id="organizacion" value="<?php echo $_GET[organizacion]; ?>" />
            <input type="hidden" name="operacion" id="operacion" value="<?php echo $_GET[o]; ?>" />
        	<input type="hidden" name="tipo" id="tipo" value="<?php echo $_GET[t]; ?>" />
            <input type="hidden" name="id" id="id" value="<?php echo $_GET[id]; ?>" />
			</form>
            </div>
			<?php
		}
	}
	else if($_GET[o]=="I")
	{
		?>
        <div id="stylized" class="myform">
        <form action="update.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
        <!-- Código del Autocompletar , todo el código html necesario estra entre estos comentarios -->
        <label for="textfield">Razón Social:</label>
        <input type="text" id="quickfind" class="ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" name="quickfind" style="width:476px;">
        <!-- fin de codigo autocompletar -->
        <div class="spacer"></div>
        <label for="textfield">RFC:</label>
        <input type="text" name="RFCrfc" id="RFCrfc"/>
        <div class="spacer"></div>  
        <button type="submit">Enviar</button>
        <input type="hidden" name="organizacion" id="organizacion" value="<?php echo $_GET[organizacion]; ?>" />
        <input type="hidden" name="operacion" id="operacion" value="<?php echo $_GET[o]; ?>" />
        <input type="hidden" name="tipo" id="tipo" value="<?php echo $_GET[t]; ?>" />
        </form>
        </div>
		<?php
	}
	break;
}
?>

<script>
$("#quickfind").autocomplete({
source: "search.php",
minLength: 2,//search after two characters
select: function(event,ui){
    //do something
    }
});
</script>

</body>
</html>