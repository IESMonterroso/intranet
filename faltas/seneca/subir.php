<?php
require('../../bootstrap.php');


$profe = $_SESSION['profi'];
if(!(stristr($_SESSION['cargo'],'1') == TRUE))
{
header('Location:'.'http://'.$dominio.'/intranet/salir.php');
exit;	
}


include("../../menu.php");
include("../menu.php");
?>
<br />
<div align="center">
<div class="page-header">
  <h2>Faltas de Asistencia <small> Actualizar datos de S�neca</small></h2>
  </div>
<br />
<?php
if ($_POST['enviar']=="Aceptar") {
	// Eliminamos archivos antiguos si existieran.
	$d = dir("origen/");
     while($entry=$d->read()) {
     $nombre=$entry;

$ruta = "origen/".$nombre;
unlink($ruta);
     }
     $d->close();
     
	//Descomprimimos el archivo .zip con la fotos
include('../../lib/pclzip.lib.php');   
$archive = new PclZip($_FILES['archivo']['tmp_name']);  
      if ($archive->extract(PCLZIP_OPT_PATH, 'origen/') == 0) 
	  {
        die('<div align="center"><div class="alert alert-danger alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCI�N:</h5>
Ha surgido un problema al importar los archivos descargados desde S�neca. Parece que el directorio donde deben ser subidas no existe o no se puede escribir en �l. Comprueba que el directorio <em>/intranet/faltas/seneca/origen</em> existe y es posible escribir en �l, e int�ntalo de nuevo.
</div></div><br />'.$archive->errorInfo(true));
      }  
      else{
      	echo '<div align="center"><div class="alert alert-success alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
Los archivos han sido actualizados en el directorio <em>/intranet/faltas/seneca/origen/</em>. Es seguro crear los archivos para subir las faltas a S�neca.
</div></div><br />';
      }   
}
?>
<FORM ENCTYPE="multipart/form-data" ACTION="subir.php" METHOD="post">
  <div class="well-transparent well-large" style="width:480px; margin:auto;" align="left">
  <p class="text-info">
  Si el m�dulo de faltas de asistencia est� operativo, es necesario descargar desde S�neca el archivo que se utilizar� como base para posteriormente subir las faltas. Se descarga de Seneca desde &quot;Intercambio de Informacion --&gt; Exportacion desde Seneca --&gt; Exportacion de Faltas de Asistencia del Alumnado&quot;. Arriba a la derecha hay un icono para crear un nuevo documento con los datos de las faltas; seleccionar el d�a actual como comienzo y final; seleccionar todos los grupos del Centro y a�adirlos a la lista. Cuando hayais terminado, haced click en el icono de confirmaci�n y al cabo de un minuto volved a la p�gina de exportaci�n de faltas de asitencia y ver�is que se ha generado un archivo comprimido que pod�is descargaros. Es conveniente actualizar los archivos de vez en cuando.
  </p>
  <p class="text-info">Selecciona el archivo comprimido con los datos de las faltas de asistencia de los alumnos que has descargado desde S�neca</p><hr />
    <input type="file" name="archivo">
  
  <hr>
  <div align="center">
    <INPUT type="submit" name="enviar" value="Aceptar" class="btn btn-primary">
  </div>
</FORM>
</div>
        <?php	
include("../../pie.php");
?>   
</body>
</html>
