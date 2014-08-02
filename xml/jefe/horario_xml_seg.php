<?
session_start();
include("../../config.php");
if($_SESSION['autentificado']!='1')
{
	session_destroy();
	header("location:http://$dominio/intranet/salir.php");
	exit;
}
registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);
if(!(stristr($_SESSION['cargo'],'1') == TRUE))
{
	header("location:http://$dominio/intranet/salir.php");
	exit;
}
?>
<?
if(!(stristr($_SESSION['cargo'],'1') == TRUE))
{
	header("location:http://$dominio/intranet/salir.php");
	exit;
}

//echo $mens;
if(isset($_POST['enviar'])){	
	
	$uploads_dir = '../../varios/';
    if ($error == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES["archivo"]["tmp_name"];
        $name = $_FILES["archivo"]["name"];
        move_uploaded_file($tmp_name, $uploads_dir.$name);
    }
$dep = $_POST['depurar'];	
header("Location:"."exportarHorariosSeneca.php?horarios=1&archivo_origen=$name&depurar=$dep"); 
break;
	}
	
?>
<?
include("../../menu.php");

?>
<br />
<div class="page-header" align="center">
<h2>Administraci�n. <small> Importaci�n del Horario y Preparaci�n de la Exportaci�n del Horario a S�neca</small></h2>
</div>
<br />
<div class="container">

<div class="row">

<div class="well well-large" style="width:600px;margin:auto;text-align:left">

<form class="form-horizontal" method="POST"
	enctype="multipart/form-data" action="horario_xml.php">
<fieldset><legend>Exportaci�n a S�neca de los Horarios del Centro </legend>

<p class="help-block">
Este m�dulo realiza dos funciones. 
Por un lado importa el Horario de los Profesores en la Base de datos y modifica los datos para ser lo m�s compatible con S�neca.
Por otro lado tambi�n se encarga de preparar el archivo que crean los programas de Horarios (Horwin, etc.) para subirlo a S�neca, evitando tener que registrar manualmente los horarios de cada profesor. La adaptaci�n que realiza este m�dulo es conveniente, ya que la compatibilidad con S�neca de los generadores de horarios tiene limitaciones (C�digo �nico de las asignaturas de Bachillerato, Diversificaci�n, etc.). Es necesario tener a mano el archivo en formato XML que se exporta desde Horwin. El resultado de esta operaci�n es la descarga del archivo modificado (<strong>Importacion_horarios_seneca.xml</strong>) preparado para subir a S�neca<br />
La opci�n <strong>Modo Depuraci�n </strong> permite ver los mensajes de error y advertencias varias que afectan al horario de Horwin. Tambi�n presenta los cambios que se han realizadoen el Horario y en el archivo XML para adpatarlo a las necesidades de S�neca. Es conveniente echarle un vistazo antes de subir los Horarios a S�neca. Con esta opci�n activada no se descarga ning�n archivo, s�lo se ven los problemas.
</p>

<br>

<div class="well">
<label for="file1">Selecciona el archivo descargado desde Horwin: </label>
<hr />
<input type="file" name="archivo" accept="text/xml" id="file1" required /> 
<hr />
<label for="depurar">Modo Depuraci�n 
<input type="radio" name="depurar" id = "depurar" value="1" />
</label>
</div>

<div align="center">
<input class="btn btn-primary btn-block" type="submit" name='enviar' value='Procesar datos' />
</div>
<br>

</fieldset>
</form>

</div>
</div>
</div>
<? include("../../pie.php");?>
