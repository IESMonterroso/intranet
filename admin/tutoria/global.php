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


if((stristr($_SESSION['cargo'],'1') == TRUE or stristr($_SESSION['cargo'],'8') == TRUE) and strstr($tutor," ==> ")==TRUE){
$tr = explode(" ==> ",$tutor);
$tutor = $tr[0];
$unidad = $tr[1];
	}
else{
$SQL = "select unidad from FTUTORES where tutor = '$tutor'";
	$result = mysql_query($SQL);
	$row = mysql_fetch_array($result);
	$unidad = $row[0];
	
}

include("../../menu.php");
include("menu.php");
?>

	<div class="container">
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Tutor�a de <?php echo $unidad; ?> <small><?php echo $tutor; ?></small></h2>
		</div>
		
		
		<!-- SCAFFOLDING -->
		<div class="row">
			
			<div class="col-sm-12">
				
				<?php include("control.php"); ?>
				
			</div>
			
		</div>
		
		
		<div class="row">
		
			<!-- COLUMNA IZQUIERDA -->
			<div class="col-sm-4">
				
				<div class="well">
					
					<?php include("faltas.php"); ?>
					
				</div><!-- /.well -->
				
				<div class="well">
					
					<?php include("mensajes.php"); ?>
					
				</div><!-- /.well -->
				
				<div class="well">
					
					<?php include("tareas.php"); ?>
					
				</div><!-- /.well -->
				
			</div><!-- /.col-sm-4 -->
			
			
			
			<!-- COLUMNA CENTRAL -->
			<div class="col-sm-4">
				
				<div class="well">
					
					<?php include("fechorias.php"); ?>
					
				</div><!-- /.well -->
				
				<div class="well">
					
					<?php include("actividades.php"); ?>
					
				</div><!-- /.well -->
				
			</div><!-- /.col-sm-4 -->
			
			
			
			<!-- COLUMNA DERECHA -->
			<div class="col-sm-4">
				
				<div class="well">
					
					<?php include("informes.php"); ?>
					
				</div><!-- /.well -->
				
				<div class="well">
					
					<?php include("ultimos.php"); ?>
					
				</div><!-- /.well -->
				
			</div><!-- /.col-sm-4 -->
			
		
		</div><!-- /.row -->
		
	</div><!-- /.container -->
  
<?php include("../../pie.php"); ?>

</body>
</html>
