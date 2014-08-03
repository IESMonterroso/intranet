<?
session_start();
include("../../config.php");
if($_SESSION['autentificado']!='1')
{
	session_destroy();
	header("location:http://$dominio/intranet/salir.php");
	exit;
}

if($_SESSION['cambiar_clave']) {
	header('Location:'.'http://'.$dominio.'/intranet/clave.php');
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
	

include("../../menu.php");

?>


<div class="container">
	
	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2>Administraci�n <small>Preparaci�n de la importaci�n del horario a S�neca</small></h2>
	</div>
	
	<!-- SCAFFOLDING -->
	<div class="row">
	
		<!-- COLUMNA IZQUIERDA -->
		<div class="col-sm-6">
			
			<div class="well">
				
				<form enctype="multipart/form-data" method="post" action="">
					<fieldset>
						<legend>Preparaci�n de la importaci�n del horario a S�neca</legend>
						
						<div class="form-group">
						  <label for="archivo"><span class="text-info">ExportacionHorarios.xml</span></label>
						  <input type="file" id="archivo" name="archivo" accept="text/xml">
						</div>
						
						<div class="checkbox">
						  <label>
						  	<input type="checkbox" id="depurar" name="depurar">
						  	Modo depuraci�n
						  </label>
						</div>
						
						<br>
						
					  <button type="submit" class="btn btn-primary" name="enviar">Aceptar</button>
					  <a class="btn btn-default" href="../index.php">Cancelar</a>
				  </fieldset>
				</form>
				
			</div><!-- /.well -->
			
		</div><!-- /.col-sm-6 -->
		
		
		<div class="col-sm-6">
			
			<h3>Informaci�n sobre la importaci�n</h3>
			
			<p>Este m�dulo se encarga de preparar el archivo de importaci�n que genera la aplicaci�n de horarios para subirlo a S�neca, evitando tener que registrar manualmente los horarios de cada profesor. La adaptaci�n que realiza este m�dulo es conveniente, ya que la compatibilidad con S�neca de los generadores de horarios tiene limitaciones (c�digo �nico de las asignaturas de Bachillerato, Diversificaci�n, etc.). Es necesario tener el fichero en formato XML que se exporta desde Horw.</p>
			
			<p>La opci�n <strong>Modo depuraci�n</strong> permite ver los mensajes de error y advertencias que afectan al horario de Horw. Tambi�n se mostrar� los cambios que se han realizado para adaptado a las necesidades de S�neca. Es conveniente consultar los cambios antes de subir el horario a S�neca. Si la opci�n est� marcada no generar� ning�n fichero de descarga.</p>
			
		</div>
		
	
	</div><!-- /.row -->
	
</div><!-- /.container -->
  
<?php include("../../pie.php"); ?>
	
</body>
</html>
