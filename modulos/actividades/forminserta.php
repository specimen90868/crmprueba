<?php
include("config/config.php");

$fecha=$_GET[fecha];
echo $fecha;
echo "Hola";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>crm</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../style.css"/>
<link rel="stylesheet" type="text/css" href="../../styleform.css"/>
<link href="../../css/contacto.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div style="text-align:center ">
<div class="form">
	<div class='contact-top'></div>
	<div class='contact-content'>
		<h1 class='contact-title'>Programar Evento:</h1>
            	<form id="chooseDateForm" name="chooseDateForm" method="post" action="insert.php">
          <table width="500" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>Disciplina Artistica:</td>
              <td><select name="disciplina" id="disciplina">
                <?php	
                $sqldisciplina="SELECT * FROM `disciplinas` ORDER BY iddisciplina ASC";
                echo $sqldisciplina;
				echo "disciplina aqui";
				$resultdisciplina= mysql_query ($sqldisciplina,$db_link);
                while($myrowdisciplina=mysql_fetch_array($resultdisciplina))
                {
                $cont++;
                ?>
                <option value="<?php echo $myrowdisciplina[iddisciplina]; ?>"><?php echo $myrowdisciplina[disciplina]; ?></option>
                <?php
                }
                ?>
              </select></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td width="121">Fecha del Evento:</td>
              <td width="619"><input name="fecha" type="text" class="one" id="fecha" value="<?php echo $_GET[fecha]; ?>"/></td>
              </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              </tr>
            <tr>
              <td>Espacio:</td>
              <td>
              <select name="espacio" id="espacio">
				<?php	
                $sql="SELECT * FROM `espacios` ORDER BY idespacio ASC";
                $result= mysql_query ($sql,$db_link);
                while($myrow=mysql_fetch_array($result))
                {
                $cont++;
                ?>
                <option value="<?php echo $myrow[idespacio]; ?>"><?php echo $myrow[espacio]; ?></option>
                <?php
                }
                ?>
              </select>              </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Nombre del Evento:</td>
              <td><input type="text" name="evento" id="evento" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Descripcion del Evento:</td>
              <td><label for="descripcion"></label>
                <textarea name="descripcion" id="descripcion" cols="45" rows="5"></textarea></td>
            </tr>
          </table>
          <p align="center">
            <input type="submit" name="Consultar" id="Consultar" value="Guardar Evento" />
          </p>
        </form>
            </div>
            </div>

</div>
</body>
</html>
