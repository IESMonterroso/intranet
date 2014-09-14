<?
session_start();
include("../../config.php");
// COMPROBAMOS LA SESION
if ($_SESSION['autentificado'] != 1) {
	$_SESSION = array();
	session_destroy();
	header('Location:'.'http://'.$dominio.'/intranet/salir.php');	
	exit();
}

if($_SESSION['cambiar_clave']) {
	header('Location:'.'http://'.$dominio.'/intranet/clave.php');
}

registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);

setlocale(LC_TIME, "es_ES");

include("../../menu.php");
include("menu.php");
?>

	<div class="container">
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Noticias <small>Noticias y novedades del centro</small></h2>
		</div>
		
		
		<div class="row">
			
			<div class="col-sm-12">
				
				<?php $result = mysqli_query($db_con, "SELECT slug, content, contact, timestamp FROM noticias WHERE id='".$_GET['id']."'"); ?>
				
				<?php if (mysqli_num_rows($result)): ?>
					
					<?php $row = mysqli_fetch_array($result); ?>
					<?php $exp_profesor = explode(',', $row['contact']); ?>
					<?php $profesor = $exp_profesor[1].' '.$exp_profesor[0]; ?>
					
					<h3 class="text-info"><?php echo $row['slug']; ?></h3>
					<h5 class="text-muted">Por <?php echo $profesor; ?> el <?php echo fecha_actual2($row['timestamp']); ?></h5>
					
					<br>
					
					<?php echo $row['content']; ?>
					
					<br>
					<br>
					
					<?php mysqli_free_result($result); ?>
					
					<div class="hidden-print">
						<a class="btn btn-primary" href="#" onclick="javascript:print();">Imprimir</a>
						<?php if(stristr($_SESSION['cargo'],'1') == TRUE || $_SESSION['profi'] == $row['contact']): ?>
						<a class="btn btn-info" href="redactar.php?id=<?php echo $_GET['id']; ?>">Editar</a>
						<a class="btn btn-danger" href="index.php?id=<?php echo $_GET['id']; ?>&borrar=1">Eliminar</a>
						<?php endif; ?>
						<a class="btn btn-default" href="index.php">Volver</a>
					</div>
					
				<?php else: ?>
					
					<h3>La noticia a la que intenta acceder no existe.</h3>
					<h4 class="text-muted">Ser� redirigido autom�ticamente a la p�gina anterior . . .</h4>
					
					<meta http-equiv="refresh" content="5;url=javascript:history.back()">
					
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					
				<?php endif; ?>
				
			</div><!-- /.col-sm-12 -->
					
		</div><!-- /.row -->
		
	</div><!-- /.container -->

<?php include("../../pie.php"); ?>

</body>
</html>
