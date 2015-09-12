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
	text-align:left;
	width:140px;
	float:left;
	margin-left:10px;
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
	width:630px;
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
	margin-left:10px;
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
if($_GET[o]=="I")//INSERTAR NOTA
{
	?>
	<div id="stylized" class="myform">
	<form action="update_notas.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
	<label for="textfield">Texto de la nota:</label>
	<label for="textarea"></label><div class="spacer"></div>
	<textarea name="texto_nota" id="texto_nota" cols="45" rows="10" class="input2"></textarea>
	<div class="spacer"></div>
	<button type="submit">Enviar</button>
	<div class="spacer"></div>
    <input type="hidden" name="oportunidad" id="oportunidad" value="<?php echo $_GET[id]; ?>" />
	<input type="hidden" name="organizacion" id="organizacion" value="<?php echo $_GET[organizacion]; ?>" />
	<input type="hidden" name="operacion" id="operacion" value="<?php echo $_GET[o]; ?>" />
	<input type="hidden" name="nota" id="nota" value="<?php echo $_GET[nota]; ?>" />
	</form>
	</div>
	<?php
}
else//ACTUALIZAR NOTA
{
	$sqledit="SELECT * FROM notas WHERE id_nota=$_GET[nota]";
	$resultedit= mysql_query ($sqledit,$db);
	while($myrowedit=mysql_fetch_array($resultedit))
	{
		?>
		<div id="stylized" class="myform">
		<form action="update.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
		<label for="textfield">Texto de la nota:</label>
        <label for="textarea"></label><div class="spacer"></div>
        <textarea name="texto_nota" id="texto_nota" cols="45" rows="10" class="input2"><?php echo $myrowedit[nota]; ?></textarea>
		<div class="spacer"></div>
		<button type="submit">Enviar</button>
		<div class="spacer"></div>
		<input type="hidden" name="oportunidad" id="oportunidad" value="<?php echo $_GET[id]; ?>" />
        <input type="hidden" name="organizacion" id="organizacion" value="<?php echo $_GET[organizacion]; ?>" />
        <input type="hidden" name="operacion" id="operacion" value="<?php echo $_GET[o]; ?>" />
        <input type="hidden" name="nota" id="nota" value="<?php echo $_GET[nota]; ?>" />
		</form>
		</div>
		<?php
	}
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