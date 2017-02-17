<?php
session_start();
include("../../config.php");
// COMPROBAMOS LA SESION
if ($_SESSION['autentificado'] != 1) {
	$_SESSION = array();
	session_destroy();
	header('Location:'.'http://'.$dominio.'/intranet/salir.php');	
	exit();
}

registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);

if(!(stristr($_SESSION['cargo'],'1') == TRUE))
{
	header("location:http://$dominio/intranet/salir.php");
	exit;
}


include("../../menu.php");
?>

	<div class="container">
	
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Administraci�n <small>Importaci�n del horario / Generar XML de importaci�n para S�neca</small></h2>
		</div>
	
		
		<!-- SCAFFOLDING -->
			
			<div class="row">
			
				<!-- COLUMNA IZQUIERDA -->
				<div class="col-sm-6">
	
					<div class="well">
					
					<?php 
	// Recorremos la tabla Profesores bajada de S�neca
						$pro = mysqli_query($db_con, "select distinct asig, a_grupo, prof from horw where a_grupo = '2B-F' order by prof");
						while ($prf = mysqli_fetch_array($pro)) {
							$materia = $prf[0];
							$grupo = $prf[1];
							$profesor = $prf[2];
							$niv = mysqli_query($db_con, "select distinct curso from alma where unidad = '$grupo'");
							$nive = mysqli_fetch_array($niv);
							$nivel = $nive[0];
				
							mysqli_query($db_con, "INSERT INTO  profesores (
				`nivel` ,
				`materia` ,
				`grupo` ,
				`profesor`
				) VALUES ('$nivel', '$materia', '$grupo', '$profesor')");

						}
				
				
				
					echo '<p class="lead">Los datos han sido importados correctamente.</p>';
				
				
				
				?>
					
				</div>
				
			</div><!-- /.col-sm-6 -->
			
			
			<!-- COLUMNA DERECHA -->
			<div class="col-sm-6">
	
				<h3>Informaci�n sobre la importaci�n</h3>
				
				<p>Este apartado se encarga de importar los <strong>horarios generados por el programa generador de horarios</strong>.</p>
				
				<p>La opci�n <strong>Generar XML</strong> se encarga de comprobar la compatibilidad de los horarios con S�neca, evitando tener que corregir manualmente los horarios de cada
				profesor. El resultado es la descarga del archivo <strong>Importacion_horarios_seneca.xml</strong> preparado para subir a S�neca.</p>
				
				<p>Si la opci�n <strong>Modo depuraci�n</strong> se encuentra marcada se podr� consultar los <strong>problemas de compatibilidad</strong> que afectan al horario y podr�an dar problemas en S�neca. Se recomienda marcarla antes de importar el horario en S�neca. Con esta opci�n no se genera el archivo XML.</p>
	
			</div><!-- /.col-sm-6 -->
	
		</div><!-- /.row -->
		
	</div><!-- /.container -->


<?php include("../../pie.php"); ?>

	<script>
	function callprogress ( valor , tabla ) {
	  var job = document.getElementById("progress_job");
	  var bar = document.getElementById("progress");
	  
	  job.innerHTML = 'Importando '+tabla+'...';
	  bar.innerHTML = '<div class="progress-bar" role="progressbar" aria-valuenow="'+valor+'" aria-valuemin="0" aria-valuemax="100" style="width: '+valor+'%;"><span class="sr-only">'+valor+'% Completado</span></div>';
	  
	  if (valor == 100) {
	  	job.className = 'hidden';
	  	bar.className = 'hidden';
	  }
	}
	</script>

</body>
</html>
