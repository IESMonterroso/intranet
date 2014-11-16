<?
session_start();
include("../../../config.php");
include("../../../config/version.php");

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



$profe = $_SESSION['profi'];

if(!(stristr($_SESSION['cargo'],'1') == TRUE)) {
	header("location:http://$dominio/intranet/salir.php");
	exit;	
}

$PLUGIN_DATATABLES = 1;

include("../../../menu.php"); 
?>
	
	<div class="container">
	
	<?php
	$query_accesos = mysqli_query($db_con, "SELECT profesor, count(*) AS accesos, (SELECT fecha FROM reg_intranet as r WHERE r.profesor=reg_intranet.profesor ORDER BY fecha DESC LIMIT 1) AS fecha_acceso FROM `reg_intranet` GROUP BY profesor ORDER BY `profesor` ASC");
	?>
		
		<!-- TITULO DE LA PAGINA -->
		
		<div class="page-header">
			<h2 class="page-title" align="center">Informe de accesos a la Intranet</h2>
		</div>
		
		
		<!-- CONTENIDO DE LA PAGINA -->
		
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
			
			    <div class="no_imprimir">
			      <a href="../../index.php" class="btn btn-default">Volver</a>
			      <a href="#" class="btn btn-primary" onclick="print()"><i class="fa fa-print"></i> Imprimir</a>
			      <br><br>
			    </div>
				
				<table class="table table-bordered table-condensed table-striped datatable">
					<thead>
						<tr>
							<th>Profesor/a</th>
							<th>Total accesos</th>
							<th>Fecha �ltimo acceso</th>
						</tr>
					</thead>
					<tbody>
					  <?php while ( $row = mysqli_fetch_object($query_accesos) ): ?>
					  	<tr>
					  		<td><?php echo $row->profesor; ?></td>
					  		<td><?php echo $row->accesos; ?></td>
					  		<td><?php echo $row->fecha_acceso; ?></td>
					  	</tr>
					  <?php endwhile; ?>
					</tbody>
				</table>
				
			</div><!-- /.col-sm-12 -->
		</div><!-- /.row -->
	  
	</div><!-- /.container -->
	
	<br>

<?php include('../../../pie.php'); ?>

	<script>
	$(document).ready(function() {
		var table = $('.datatable').DataTable({
			"paging":   true,
	    "ordering": true,
	    "info":     false,
	    
			"lengthMenu": [[15, 35, 50, -1], [15, 35, 50, "Todos"]],
			
			"order": [[ 2, "desc" ]],
			
			"language": {
			            "lengthMenu": "_MENU_",
			            "zeroRecords": "No se ha encontrado ning�n resultado con ese criterio.",
			            "info": "P�gina _PAGE_ de _PAGES_",
			            "infoEmpty": "No hay resultados disponibles.",
			            "infoFiltered": "(filtrado de _MAX_ resultados)",
			            "search": "Buscar: ",
			            "paginate": {
			                  "first": "Primera",
			                  "next": "�ltima",
			                  "next": "",
			                  "previous": ""
			                }
			        }
		});
	});
	</script>
	
</body>
</html>