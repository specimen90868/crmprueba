<?php
include ("../../seguridad.php");
include ("../../config/config.php");

$claveagente=$_SESSION[Claveagente];
$nivel=2;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>crm</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../style.css"/>
<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../../css/styleform.css" />
<script type="text/javascript" src="../../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/cmxform.js"></script>
<link rel="icon" href="images/icon.ico" />


<script type="text/javascript">
 
$(document).ready(function(){
 
    ////FUNCIONES PARA LOS CONTACTOS////
	var counter = 2;
 
    $("#addButton").click(function () {
 
	if(counter>10){
            alert("Only 10 textboxes allow");
            return false;
	}   
 
	var newTextBoxDiv = $(document.createElement('div'))
	     .attr("id", 'TextBoxDiv' + counter);
 
	newTextBoxDiv.after().html('<div id="camposmini"><select id="CONtitulo'+ counter + '" name="CONtitulo' + counter +'" class="myinputstylemini">' +
				 '<option value="Sr.">Sr.</option>' +
                 '<option value="Lic.">Lic.</option>' +
                 '<option value="Ing.">Ing.</option>' +
                 '<option value="Arq.">Arq.</option>' +
                 '<option value="Dr.">Dr.</option>' +
                 '<option value="C.P.">C.P.</option></select>' +
			'</div>' +
			'<div id="campos"><input type="text" name="CONnombre' + counter + '" id="CONnombre' + counter + '" value="" class="myinputstyle"></div>' + 
		    '<div id="campos"><input type="text" name="CONapellido' + counter + '" id="CONapellido' + counter + '" value="" class="myinputstyle"></div>' +
			'<div id="campos"><input type="text" name="CONpuesto' + counter + '" id="CONpuesto' + counter + '" value="" class="myinputstyle"></div>' +
			'<div id="campos"><input type="text" name="CONemail' + counter + '" id="CONemail' + counter + '" value="" class="myinputstyle"></div>' +
			'<div id="campos"><input type="text" name="CONcelular' + counter + '" id="CONcelular' + counter + '" value="" class="myinputstyle"></div>' +
			'<div id="camposmini"><select id="CONrep_legal'+ counter + '" name="CONrep_legal' + counter +'" class="myinputstylemini">' +
				'<option value="">Selecciona</option>' +
				'<option value="Acreditado">Acreditado</option>' +
				'<option value="Representante Legal">Representante Legal</option>' +
				'<option value="Accionista">Accionista</option></select>' +
			'</div>' +
			'<div id="campos"><select id="CONdianac'+ counter + '" name="CONdianac' + counter +'" class="myinputstylemini">' +
				'<option value="0">--</option>' +
				'<option value="01">01</option>' +
				'<option value="02">02</option>' +
				'<option value="03">03</option>' +
				'<option value="04">04</option>' +
				'<option value="05">05</option>' +
				'<option value="06">06</option>' +
				'<option value="07">07</option>' +
				'<option value="08">08</option>' +
				'<option value="09">09</option>' +
				'<option value="10">10</option>' +
				'<option value="11">11</option>' +
				'<option value="12">12</option>' +
				'<option value="13">13</option>' +
				'<option value="14">14</option>' +
				'<option value="15">15</option>' +
				'<option value="16">16</option>' +
				'<option value="17">17</option>' +
				'<option value="18">18</option>' +
				'<option value="19">19</option>' +
				'<option value="20">20</option>' +
				'<option value="21">21</option>' +
				'<option value="22">22</option>' +
				'<option value="23">23</option>' +
				'<option value="24">24</option>' +
				'<option value="25">25</option>' +
				'<option value="26">26</option>' +
				'<option value="27">27</option>' +
				'<option value="28">28</option>' +
				'<option value="29">29</option>' +
				'<option value="30">30</option>' +
				'<option value="31">31</option></select>' +
			'<select id="CONmesnac'+ counter + '" name="CONmesnac' + counter +'" class="myinputstylemini">' +
				'<option value="0">--</option>' +
				'<option value="01">01</option>' +
				'<option value="02">02</option>' +
				'<option value="03">03</option>' +
				'<option value="04">04</option>' +
				'<option value="05">05</option>' +
				'<option value="06">06</option>' +
				'<option value="07">07</option>' +
				'<option value="08">08</option>' +
				'<option value="09">09</option>' +
				'<option value="10">10</option>' +
				'<option value="11">11</option>' +
				'<option value="12">12</option>' +
             '</select></div>');

	newTextBoxDiv.appendTo("#TextBoxesGroup");

	counter++;
     });//Fin de addButton
 
     $("#removeButton").click(function () {
	if(counter==2){
          alert("No puedes remover todas los contactos");
          return false;
       }   
 
	counter--;
 
        $("#TextBoxDiv" + counter).remove();
 
     });//Fin de removeButton
 
     $("#getButtonValue").click(function () {
 
	var msg = '';
	for(i=1; i<counter; i++){
	  msg += "\n Nombre " + i + " : " + $('#nombre' + i).val();
	  msg += "\n Apellido " + i + " : " + $('#apellido' + i).val();
	  msg += "\n Puesto " + i + " : " + $('#puesto' + i).val();
	  msg += "\n Email " + i + " : " + $('#email' + i).val();
	  msg += "\n Celular " + i + " : " + $('#celular' + i).val();
	  msg += "\n Oficina " + i + " : " + $('#oficina' + i).val();
	}
    	  alert(msg);
     });//fin de getButtonValue
	 
	 ////FUNCIONES PARA LOS TELEFONOS////
	 
	 var countertel = 2;
 
    $("#addButtonTel").click(function () {
 
	if(countertel>10){
            alert("Only 10 textboxes allow");
            return false;
	}   
 
	var newTextBoxDivTel = $(document.createElement('div'))
	     .attr("id", 'TextBoxDivTel' + countertel);
 
	newTextBoxDivTel.after().html('<div id="telefonos"><div id="campos"><input type="text" name="TELtelefono' + countertel + '" id="TELtelefono' + countertel + '" value="" class="myinputstyle" ></div>' +
			'<div id="campos"><select id="TELtipo_telefono'+ countertel + '" name="TELtipo_telefono' + countertel +'" class="myinputstyle">' +
				'<option value="Oficina">Oficina</option>' +
				'<option value="Directo">Directo</option>' +
				'<option value="Fax">Fax</option>' +
				'<option value="Celular">Celular</option>' +
				'<option value="Nextel">Nextel</option>' +
             '</select></div></div>');

	newTextBoxDivTel.appendTo("#TextBoxesGroupTel");

	countertel++;
     });//Fin de addButton
 
	 
	 $("#removeButtonTel").click(function () {
	if(countertel==2){
          alert("No more textbox to remove");
          return false;
       }   
 
	countertel--;
 
        $("#TextBoxDivTel" + countertel).remove();
 
     });//Fin de removeButton
 
     $("#getButtonValueTel").click(function () {
 
	var msg = '';
	for(i=1; i<countertel; i++){
	  msg += "\n Teléfono " + i + " : " + $('#telefono' + i).val();
	  msg += "\n Tipo " + i + " : " + $('#tipo_telefono' + i + ' option:selected').val();
	}
    	  alert(msg);
     });//fin de getButtonValue
	 
	 /////////////////////////////////////////////////
	 
	 ////FUNCIONES PARA LOS EMAIL////
	 
	 var counteremail = 2;
 
    $("#addButtonEmail").click(function () {
 
	if(counteremail>10){
            alert("Only 10 textboxes allow");
            return false;
	}   
 
	var newTextBoxDivEmail = $(document.createElement('div'))
	     .attr("id", 'TextBoxDivEmail' + counteremail);
 
	newTextBoxDivEmail.after().html('<div id="telefonos"><input type="text" name="CORemailo' + counteremail + '" id="CORemailo' + counteremail +'" value="" class="myinputstylemediano"></div>');

	newTextBoxDivEmail.appendTo("#TextBoxesGroupEmail");

	counteremail++;
     });//Fin de addButton
 
	 
	 $("#removeButtonEmail").click(function () {
	if(counteremail==2){
          alert("No more textbox to remove");
          return false;
       }   
 
	counteremail--;
 
        $("#TextBoxDivEmail" + counteremail).remove();
 
     });//Fin de removeButton
 
     $("#getButtonValueEmail").click(function () {
 
	var msg = '';
	for(i=1; i<counteremail; i++){
	  msg += "\n Email " + i + " : " + $('#emailo' + i).val();
	}
    	  alert(msg);
     });//fin de getButtonValue
	 
	 /////////////////////////////////////////////////
	 
	 ////FUNCIONES PARA LAS WEBPAGE Y REDES SOCIALES////
	 
	 var counterwebaddress = 2;
 
    $("#addButtonWebAddress").click(function () {
 
	if(counterwebaddress>10){
            alert("Only 10 textboxes allow");
            return false;
	}   
 
	var newTextBoxDivWebAddress = $(document.createElement('div'))
	     .attr("id", 'TextBoxDivWebAddress' + counterwebaddress);
 
	newTextBoxDivWebAddress.after().html('<div id="telefonos"><input type="text" name="WEBdireccion_web' + counterwebaddress + '" id="WEBdireccion_web' + counterwebaddress + '" value="" class="myinputstylemediano" >' +
			' <select id="WEBtipo_direccion_web'+ counterwebaddress + '" name="WEBtipo_direccion_web' + counterwebaddress +'" class="myinputstyle">' +
				'<option value="Website">Website</option>' +
				'<option value="Skype">Skype</option>' +
				'<option value="Twitter">Twitter</option>' +
				'<option value="LinkedIn">LinkedIn</option>' +
				'<option value="Facebook">Facebook</option>' +
				'<option value="Xing">Xing</option>' +
				'<option value="Blog">Blog</option>' +
				'<option value="Google+">Google+</option>' +
				'<option value="Flickr">Flickr</option>' +
				'<option value="GitHub">GitHub</option>' +
				'<option value="Youtube">Youtube</option>' +
             '</select></div>');
			 
	newTextBoxDivWebAddress.appendTo("#TextBoxesGroupWebAddress");

	counterwebaddress++;
     });//Fin de addButton
 
	 
	 $("#removeButtonWebAddress").click(function () {
	if(counterwebaddress==2){
          alert("No more textbox to remove");
          return false;
       }   
 
	counterwebaddress--;
 
        $("#TextBoxDivWebAddress" + counterwebaddress).remove();
 
     });//Fin de removeButton
 
     $("#getButtonValueWebAddress").click(function () {
 
	var msg = '';
	for(i=1; i<counterwebaddress; i++){
	  msg += "\n Web Address " + i + " : " + $('#direccion_web' + i).val();
	  msg += "\n Tipo " + i + " : " + $('#tipo_direccion_web' + i + ' option:selected').val();
	}
    	  alert(msg);
     });//fin de getButtonValue
	 
	 /////////////////////////////////////////////////
	 
	 ////FUNCIONES PARA LOS DOMICILIOS////
	 
	 var counterdomicilio = 2;
 
    $("#addButtonDomicilio").click(function () {
 
	if(counterdomicilio>10){
            alert("Only 10 textboxes allow");
            return false;
	}   
 
	var newTextBoxDivDomicilio = $(document.createElement('div'))
	     .attr("id", 'TextBoxDivDomicilios' + counterdomicilio);
 
	newTextBoxDivDomicilio.after().html('<div id="telefonos"><div id="campos">'+
                    '<select id="DOMtipo_domicilio'+ counterdomicilio + '" name="DOMtipo_domicilio'+ counterdomicilio + '" class="myinputstyle">'+
                      '<option value="Principal">Principal</option>'+
                      '<option value="Sucursal">Sucursal</option>'+
                      '<option value="Fiscal">Fiscal</option>'+
                    '</select></div>'+
'<div id="camposgde"><input type="text" name="DOMdomicilio'+ counterdomicilio + '" id="DOMdomicilio'+ counterdomicilio + 
'" class="myinputstylegde" value="Calle, Número, Colonia" style="color:#999"></div>'+
                  '</div><div id="telefonos">'+
                  '<div id="campos"><input type="text" name="DOMciudad'+ counterdomicilio + '" id="DOMciudad'+ counterdomicilio + '" value="Delegación" class="myinputstyle" style="color:#999"></div>'+
                  '<div id="campos"><input type="text" name="DOMestado'+ counterdomicilio + '" id="DOMestado'+ counterdomicilio +
				  '" value="Estado" class="myinputstyle" style="color:#999"></div>'+
                  '<div id="camposchico"><input type="text" name="DOMcp'+ counterdomicilio + '" id="DOMcp'+ counterdomicilio + '" value="Código Postal" class="myinputstylechico" style="color:#999"></div></div>');
			 
			 
			 
	newTextBoxDivDomicilio.appendTo("#TextBoxesGroupDomicilios");

	counterdomicilio++;
     });//Fin de addButton
 
	 
	 $("#removeButtonDomicilio").click(function () {
	if(counterdomicilio==2){
          alert("No more textbox to remove");
          return false;
       }   
 
	counterdomicilio--;
 
        $("#TextBoxDivDomicilios" + counterdomicilio).remove();
 
     });//Fin de removeButton
 
     $("#getButtonValueDomicilio").click(function () {
 
	var msg = '';
	for(i=1; i<counterdomicilio; i++){
	  msg += "\n Tipo " + i + " : " + $('#tipo_domicilio' + i + ' option:selected').val();
	  msg += "\n Domicilio " + i + " : " + $('#domicilio' + i).val();
	  msg += "\n Delegación " + i + " : " + $('#ciudad' + i).val();
	  msg += "\n Estado " + i + " : " + $('#estado' + i).val();
	  msg += "\n CP " + i + " : " + $('#cp' + i).val();
	}
    	  alert(msg);
     });//fin de getButtonValue
	 
	 /////////////////////////////////////////////////////
	 
	 ////FUNCIONES PARA LAS RAZONES SOCIALES Y LOS RFC////
	 
	 var counterrfc = 2;
 
    $("#addButtonRFC").click(function () {
 
	if(counterrfc>10){
            alert("Only 10 textboxes allow");
            return false;
	}   
 
	var newTextBoxDivRFC = $(document.createElement('div'))
	     .attr("id", 'TextBoxDivRFC' + counterrfc);
 
	newTextBoxDivRFC.after().html('<div id="telefonos"><div id="camposmediano"><input type="text" name="RFCrazon_social' + counterrfc + '" id="RFCrazon_social' + counterrfc + '" value="" class="myinputstylemediano2" ></div><div id="campos"><input type="text" name="RFCrfc' + counterrfc + '" id="RFCrfc' + counterrfc + '" value="" class="myinputstyle" ></div>' +
			' </div>');
			 
	newTextBoxDivRFC.appendTo("#TextBoxesGroupRFC");

	counterrfc++;
     });//Fin de addButton
 
	 
	 $("#removeButtonRFC").click(function () {
	if(counterrfc==2){
          alert("No more textbox to remove");
          return false;
       }   
 
	counterrfc--;
 
        $("#TextBoxDivRFC" + counterrfc).remove();
 
     });//Fin de removeButton
 
     $("#getButtonValueRFC").click(function () {
 
	var msg = '';
	for(i=1; i<counterrfc; i++){
	  msg += "\n Razón Social " + i + " : " + $('#razon_social' + i).val();
	  msg += "\n RFC " + i + " : " + $('#rfc' + i).val();
	}
    	  alert(msg);
     });//fin de getButtonValue
	 
  });


</script>

</head>
<body>
    <?php include('../../header.php'); ?>
    <div id="titulo">Insertar Organización</div>
    
    <div id="pageNav">
        <div id="pageNavWrapper">
    
        <ul class="pageNav">
            <li class="selected"><a href="#">Contactos</a></li>
            <li class=""><a href="listas.php">Listas</a></li>
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
            <li class="item"><img src="../../images/add.png" class="linkImage" /><a href="index.php">Lista de Organizaciones</a></li>  
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
      <form action="insert.php" method="post">
      <fieldset class="fieldsetgde">
        <legend>Organización</legend>
         
        <div id="encabezados">
            <div id="etiquetas"><label>Nombre</label></div>
            <div id="etiquetas"></div>
            <div id="etiquetas"></div>
            <div id="etiquetas">Persona</div>
            <div id="etiquetas"><label>Clave</label> 
            de Cliente</div>
            <div id="etiquetas"><label>Tipo</label> 
            de Contacto</div>
            <div id="etiquetas"><label>Fundación</label>
            </div>
        </div>
        <div id="telefonos">
        <div id="camposmediano"><input id="ORGorganizacion" name="ORGorganizacion" type="text" class="myinputstylemediano2"></div>
        <div id="campos"><select id="ORGtipo_persona" name="ORGtipo_persona" class="myinputstyle">
            <option value="Física">Física</option>
            <option value="Moral">Moral</option>
          </select></div>
        <div id="campos"><input type="text" name="ORGclave" id="ORGclave" value="" class="myinputstyle"></div>
        <div id="campos"><select id="ORGtipo_organizacion" name="ORGtipo_organizacion" class="myinputstyle">
            <option value="Cliente">Cliente</option>
            <option value="Prospecto">Prospecto</option>
          </select></div>
        <div id="campos"><input type="text" name="ORGfundacion" id="ORGfundacion" value="" class="myinputstyle"></div>
        </div>
        
        <div id="encabezados">
            <div id="etiquetas">
              <label>Procedencia</label></div>
            <div id="etiquetas">Clave Website</div>
            <div id="etiquetas">
              <label>Formas de Contacto</label></div>
            <div id="etiquetas">Promotor</div>
            <div id="etiquetas"></div>
            <div id="etiquetas"></div>
            <div id="etiquetas"></div>
        </div>
        
        <div id="telefonos">
          <div id="campos"><select id="ORGprocedencia" name="ORGprocedencia" class="myinputstyle">
            <option value="">Selecciona</option>
            <option value="ANTAD">ANTAD</option>
            <option value="Base de Datos">Base de Datos</option>
            <option value="Habilitado FIRA">Habilitado FIRA</option>
            <option value="Llamada Website">Llamada Website</option>
            <option value="Revista">Revista</option>
            <option value="Website">Website</option>
        </select></div>
        <div id="campos"><input type="text" name="ORGclave_web" id="ORGclave_web" value="" class="myinputstyle"></div>
        <div id="campos"><select id="ORGforma_contacto" name="ORGforma_contacto" class="myinputstyle">
            <option value="Teléfono">Teléfono</option>
            <option value="Email">Email</option>
            <option value="Teléfono/Email">Teléfono/Email</option>
          </select></div>
        <div id="camposmediano"><select id="ORGpromotor" name="ORGpromotor" class="myinputstylemediano2">
            <option value="0">Sin Asignar</option>
            <?php
			//$sqlagt="SELECT * FROM usuarios WHERE tipo = 'Promotor' ORDER BY apellidopaterno ASC";
			$selected="";
			if($_SESSION["Tipo"]=="Promotor"){$sqlagt="SELECT * FROM usuarios WHERE tipo = 'Promotor' AND claveagente = '".$claveagente."' ORDER BY apellidopaterno ASC"; $selected="selected";}else{$sqlagt="SELECT * FROM usuarios WHERE tipo = 'Promotor' ORDER BY apellidopaterno ASC"; $selected="";}
			$resultagt= mysql_query ($sqlagt,$db);
			while($myrowagt=mysql_fetch_array($resultagt))
			{
				?>
				<option value="<?php echo $myrowagt[claveagente]; ?>" <?php if($_SESSION["Tipo"]=="Promotor"){echo "selected='".$selected."'";}?>><?php echo $myrowagt[apellidopaterno]." ".$myrowagt[apellidomaterno]." ".$myrowagt[nombre]; ?></option>
				<?php
			}
			?>
        </select></div>
        </div>

      </fieldset>
      
      <fieldset class="fieldsetgde">
        <legend>Contactos</legend>   
            <div>
            <div id="encabezados">
            	<div id="etiquetasmini">
            	  <label>Título</label></div>
                <div id="etiquetas"><label>Nombre</label></div>
                <div id="etiquetas">
                  <label>Apellidos</label>
                </div>
                <div id="etiquetas"><label>Puesto</label></div>
                <div id="etiquetas"><label>Email</label></div>
                <div id="etiquetas"><label>Celular</label></div>
                <div id="etiquetasmini"><label>Relación</label></div>
                <div id="etiquetas"><label>Cumpleaños</label>
                (dd/mm)</div>
            </div>
            <div id="contactos">
            <div id='TextBoxesGroup'>
                <div id="TextBoxDiv1">
                	<div id="camposmini"><select id="CONtitulo1" name="CONtitulo1" class="myinputstylemini">
                    	<option value="Sr.">Sr.</option>
                        <option value="Lic.">Lic.</option>
                        <option value="Ing.">Ing.</option>
                        <option value="Arq.">Arq.</option>
                        <option value="Dr.">Dr.</option>
                        <option value="C.P.">C.P.</option>
                    	</select></div>
                    <div id="campos"><input type="text" name="CONnombre1" id="CONnombre1" value="" class="myinputstyle"></div>
                    <div id="campos"><input type="text" name="CONapellido1" id="CONapellido1" value="" class="myinputstyle"></div>
                    <div id="campos"><input type="text" name="CONpuesto1" id="CONpuesto1" value="" class="myinputstyle"></div>
                    <div id="campos"><input type="text" name="CONemail1" id="CONemail1" value="" class="myinputstyle"></div>
                    <div id="campos"><input type="text" name="CONcelular1" id="CONcelular1" value="" class="myinputstyle"></div>
                    <div id="camposmini"><select id="CONrep_legal1" name="CONrep_legal1" class="myinputstylemini">
                        <option value="">Selecciona</option>
                        <option value="Acreditado">Acreditado</option>
                        <option value="Representante Legal">Representante Legal</option>
                        <option value="Accionista">Accionista</option>
                    	</select></div>
                    <div id="campos">
                    	<select id="CONdianac1" name="CONdianac1" class="myinputstylemini">
                        <option value="0">--</option>
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
                      <select id="CONmesnac1" name="CONmesnac1" class="myinputstylemini">
                        <option value="0">--</option>
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
                    
                    </div>
                    
                    
                </div>
            </div>
            </div>
            </div>
            
            
            
            <div id="herramientas"><img src="../../images/add.png" width="16" height="16" /><a href="#" id='addButton' class="register">agregar otro</a>
<img src="../../images/delete.png" width="16" height="16" /><a href="#" id='removeButton' class="register">remover último</a></div>

<!--<img src="../../images/add.png" class="linkImage" /><a href="#" id='addButton'>agregar otro</a>
<img src="../../images/delete.png" class="linkImage" /><a href="#" id='removeButton' >remover último</a> -->
      </fieldset>
      
      <fieldset class="fieldsetgde">
        <legend>Detalles de la Organización</legend>
<div id="division">Números de Teléfono</div>   
            
            <div id='TextBoxesGroupTel'>
                <div id="TextBoxDivTel1">
                  <div id="telefonos">  
                    <div id="campos"><input type="text" name="TELtelefono1" id="TELtelefono1" value="" class="myinputstyle"></div>
                    <div id="campos"><select id="TELtipo_telefono1" name="TELtipo_telefono1" class="myinputstyle">
                        <option value="Oficina">Oficina</option>
                        <option value="Directo">Directo</option>
                        <option value="Fax">Fax</option>
                        <option value="Celular">Celular</option>
                        <option value="Nextel">Nextel</option>
                    </select></div>
                  </div>
                </div>
        	</div>
            
<div id="herramientas"><img src="../../images/add.png" width="16" height="16" /><a href="#" id='addButtonTel' class="register">agregar otro</a>
<img src="../../images/delete.png" width="16" height="16" /><a href="#" id='removeButtonTel' class="register">remover último</a></div>
<div id="division">Direcciones de Email</div>   
            <div id='TextBoxesGroupEmail'>
                <div id="TextBoxDivEmail1">
                    <div id="telefonos">
                    <input type="text" name="CORemailo1" id="CORemailo1" value="" class="myinputstylemediano">
                    </div>
                </div>
        	</div>
<div id="herramientas"><img src="../../images/add.png" width="16" height="16" /><a href="#" id='addButtonEmail' class="register">agregar otro</a>
<img src="../../images/delete.png" width="16" height="16" /><a href="#" id='removeButtonEmail' class="register">remover último</a></div>

<div id="division">Websites y Redes Sociales</div>
            <div id='TextBoxesGroupWebAddress'>
                <div id="TextBoxDivWebAddress1">
                    <div id="telefonos">
                    <input type="text" name="WEBdireccion_web1" id="WEBdireccion_web1" value="" class="myinputstylemediano">
                    <select id="WEBtipo_direccion_web1" name="WEBtipo_direccion_web1" class="myinputstyle">
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
                    </div>
                </div>
        	</div>
<div id="herramientas"><img src="../../images/add.png" width="16" height="16" /><a href="#" id='addButtonWebAddress' class="register">agregar otro</a><img src="../../images/delete.png" width="16" height="16" /><a href="#" id='removeButtonWebAddress' class="register">remover último</a></div>

<div id="division">Domicilios</div>  
            <div id='TextBoxesGroupDomicilios'>
                <div id="TextBoxDivDomicilios1">
                    
                  <div id="telefonos">
                  <div id="campos">
                    <select id="DOMtipo_domicilio1" name="DOMtipo_domicilio1" class="myinputstyle">
                      <option value="Principal">Principal</option>
                      <option value="Sucursal">Sucursal</option>
                      <option value="Fiscal">Fiscal</option>
                    </select>
                  </div>
                  <div id="camposgde"><input type="text" name="DOMdomicilio1" id="DOMdomicilio1" class="myinputstylegde" value="Calle, Número, Colonia" onfocus = "if(this.value=='Calle, Número, Colonia') {this.value=''; this.style.color='#000'}"
  onblur="if(this.value=='') {this.value='Calle, Número, Colonia'; this.style.color='#999'}" style="color:#999"></div>
                  </div>
                  
                  <div id="telefonos">
                        <div id="campos"><input type="text" name="DOMciudad1" id="DOMciudad1" value="Delegación" class="myinputstyle" onfocus = "if(this.value=='Delegación') {this.value=''; this.style.color='#000'}"
  onblur="if(this.value=='') {this.value='Delegación'; this.style.color='#999'}" style="color:#999"></div>
                        <div id="campos"><input type="text" name="DOMestado1" id="DOMestado1" value="Estado" class="myinputstyle" onfocus = "if(this.value=='Estado') {this.value=''; this.style.color='#000'}"
  onblur="if(this.value=='') {this.value='Estado'; this.style.color='#999'}" style="color:#999"></div>
                    <div id="camposchico"><input type="text" name="DOMcp1" id="DOMcp1" value="Código Postal" class="myinputstylechico" onfocus = "if(this.value=='Código Postal') {this.value=''; this.style.color='#000'}"
  onblur="if(this.value=='') {this.value='Código Postal'; this.style.color='#999'}" style="color:#999"></div>
                        </div>

                </div>
        	</div>
<div id="herramientas"><img src="../../images/add.png" width="16" height="16" /><a href="#" id='addButtonDomicilio' class="register">agregar otro</a><img src="../../images/delete.png" width="16" height="16" /><a href="#" id='removeButtonDomicilio' class="register">remover último</a></div>

<div id="division">Razones Sociales</div>

<div id="encabezados">
    <div id="etiquetas"><label>Razón Social</label></div>
    <div id="etiquetas"></div>
    <div id="etiquetas"></div>
    <div id="etiquetas">RFC</div>
    <div id="etiquetas"></div>
    <div id="etiquetas"></div>
</div>
<div id='TextBoxesGroupRFC'>
    <div id="TextBoxDivRFC1">
        <div id="telefonos">
            <div id="camposmediano"><input type="text" name="RFCrazon_social1" id="RFCrazon_social1" value="" class="myinputstylemediano2"></div>
            <div id="campos"><input type="text" name="RFCrfc1" id="RFCrfc1" value="" class="myinputstyle"></div>
        </div>
	</div>
</div>
<div id="herramientas"><img src="../../images/add.png" width="16" height="16" /><a href="#" id='addButtonRFC' class="register">agregar otro</a><img src="../../images/delete.png" width="16" height="16" /><a href="#" id='removeButtonRFC' class="register">remover último</a></div>

</fieldset>
      <p>
        <input type="submit" value="Guardar">
      </p>
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
