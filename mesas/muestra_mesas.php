<?php
require('../../bootstrap.php');
include("ayuda.php");
if(isset($_POST['tutor'])) {
	$exp_tutor = explode('==>', $_POST['tutor']);
	$_SESSION['mod_tutoria']['tutor'] = trim($exp_tutor[0]);
	$_SESSION['mod_tutoria']['unidad'] = trim($exp_tutor[1]);
}

// ESTRUCTURA DE LA CLASE, SE AJUSTA AL NUMERO DE ALUMNOS
$result = mysqli_query($db_con, "SELECT apellidos, nombre, claveal FROM alma WHERE unidad='".$_SESSION['mod_tutoria']['unidad']."' ORDER BY apellidos ASC, nombre ASC");
$n_alumnos = mysqli_num_rows($result);
mysqli_free_result($result);

if ($n_alumnos <= 36) $estructura_clase = '222';
elseif ($n_alumnos > 36 && $n_alumnos <= 42) $estructura_clase = '232';
elseif ($n_alumnos > 42) $estructura_clase = '242';


if ($estructura_clase == '242') { $mesas_col = 9; $mesas = 48; $col_profesor = 9; }
if ($estructura_clase == '232') { $mesas_col = 8; $mesas = 42; $col_profesor = 8; }
if ($estructura_clase == '222') { $mesas_col = 7; $mesas = 36; $col_profesor = 7; }


function al_con_nie($db_con, $var_nie, $var_grupo) {
	$result = mysqli_query($db_con, "SELECT CONCAT(nombre, ', ', apellidos) AS alumno FROM alma WHERE unidad='".$var_grupo."' AND claveal='".$var_nie."' ORDER BY apellidos ASC, nombre ASC LIMIT 1");
	$row = mysqli_fetch_array($result);
	mysqli_free_result($result);
	
	if ($row['alumno'] != ", ") {
		return($row['alumno'].', '.$row['nombre']);
	}
	else {
		return('');
	}
	
}


// ACTUALIZAR PUESTOS
if (isset($_POST['listOfItems'])){
	$result = mysqli_query($db_con, "UPDATE puestos_alumnos SET puestos='".$_POST['listOfItems']."' WHERE unidad='".$_SESSION['mod_tutoria']['unidad']."'");
	
	if(!$result) $msg_error = "La asignación de puestos en el aula no se ha podido actualizar. Error: ".mysqli_error($db_con);
	else $msg_success = "La asignación de puestos en el aula se ha actualizado correctamente.";	
}


// OBTENEMOS LOS PUESTOS
$result = mysqli_query($db_con, "SELECT * FROM puestos_alumnos WHERE unidad='".$_SESSION['mod_tutoria']['unidad']."' LIMIT 1");

if (mysqli_num_rows($result)) {
	$row = mysqli_fetch_array($result);
	$cadena_puestos = $row[1];
	mysqli_free_result($result);
}

$matriz_puestos = explode(';', $cadena_puestos);

foreach ($matriz_puestos as $value) {
	$los_puestos = explode('|', $value);
	
	if ($los_puestos[0] == 'allItems') {
		$sin_puesto[] = $los_puestos[1];
	}
	else {
		$con_puesto[$los_puestos[0]] = $los_puestos[1];
	}

}

include("../../menu.php");
?>
	
	<style class="text/css">
	
	table tr td {
		vertical-align: top;
	}
	
	table tr td.active {
		background-color: #333;
	}
	
	#allItems {
		width: 100%;
		border: 1px solid #ecf0f1;
	}
	
	#allItems p {
		background-color: #2c3e50;
		color: #fff;
		font-weight: bold;
		padding: 4px 15px;
		margin-bottom: 4px;
	}
	
	#allItems ul li {
		background-color: #efefef;
		padding: 5px 15px;
		margin: 5px;
		font-size: 0.9em;
		cursor: move;
	}
	
	#dhtmlgoodies_mainContainer table tr td div {
		border: 1px solid #ecf0f1;
		margin: 0 5px 10px 5px;
	}
	
	#dhtmlgoodies_mainContainer table tr td div {
	
	<?php if ($estructura_clase == '242') echo 'width: 105px;'; ?> 
	<?php if ($estructura_clase == '232') echo 'width: 120px;'; ?> 
	<?php if ($estructura_clase == '222') echo 'width: 150px;'; ?> 
	}
	
	#dhtmlgoodies_mainContainer table tr td div p {
		background-color: #2c3e50;
		color: #fff;
		font-weight: bold;
		padding: 4px 2px;
		margin-bottom: 4px;
	}
	
	#dhtmlgoodies_mainContainer table tr td div ul {
		margin: 0 4px 4px 4px;
		min-height: 50px;
		background-color: #efefef;
	}
	
	#dhtmlgoodies_mainContainer table tr td div ul li {
		height: 100%;
		cursor: move;
	}
	
	
	#dhtmlgoodies_dragDropContainer .mouseover ul {
		background-color:#E2EBED;
		border: 1px solid #3FB618;
	}
	
	#dragContent {
		position: absolute;
		margin-top: -280px;
		margin-left: -150px;
		width: 150px;
		height: 60px;
		font-size: 0.8em;
		z-index: 2000;
		cursor: move;
	}
	
	.text-sm {
		font-size: 0.8em;
	}
	
	</style>

<div class="container">
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2><?php echo $_SESSION['mod_tutoria']['unidad']; ?> <small>Asignación de mesas</small></h2>
			<h4 class="text-info">Tutor/a: <?php echo nomprofesor($_SESSION['mod_tutoria']['tutor']); ?></h4>
		</div>
		
		
		<!-- MENSAJES -->
		<?php if(isset($msg_success) && $msg_success): ?>
		<div class="alert alert-success" role="alert">
			<?php echo $msg_success; ?>
		</div>
		<?php endif; ?>
		
		<?php if(isset($msg_error) && $msg_error): ?>
		<div class="alert alert-danger" role="alert">
			<?php echo $msg_error; ?>
		</div>
		<?php endif; ?>
		
		
		<!-- SCAFFOLDING -->
		<div id="dhtmlgoodies_dragDropContainer" class="row"> 
			
			<!-- Uso clase text/css para mejorar impresión-->
			<div id="dhtmlgoodies_mainContainer" class="text/css">
				
				<div class="table-responsive">
					<table>
						<?php for ($i = 1; $i < 7; $i++): ?>
						<tr>
							<?php for ($j = 1; $j < $mesas_col; $j++): ?>
							<td>
								<div><p class="text-center">Mesa <?php echo $mesas; ?></p>
									<ul id="<?php echo $mesas; ?>" class="list-unstyled text-sm">
										<?php if (isset($con_puesto[$mesas])): ?>
											<li id="<?php echo $con_puesto[$mesas]; ?>"><?php echo al_con_nie($db_con, $con_puesto[$mesas], $_SESSION['mod_tutoria']['unidad']); ?></li>		 
										<?php endif; ?>  
									</ul>
								</div>
							</td>
							<?php if ($j == 2 || $j == $mesas_col-3): ?>
							<td class="text-center active">|</td>
							<?php endif; ?>
							<?php $mesas--; ?>
							<?php endfor; ?>
						</tr>
						<?php endfor; ?>
						<tr>
							<td colspan="<?php echo $col_profesor; ?>">
							</td>
							<td class="text-center">
								<div>
									<p>Profesor/a</p>
									<br><br><br>
								</div>
							</td>
						</tr>
					</table>
				</div>
			
			</div><!-- /.text/css -->
		
		</div><!-- /.row --> 
		
		<br>
		
		<div class="row">
			
			<div class="col-sm-12">
				
				<div class="hidden-print">
						<a href="#" class="btn btn-default" onclick="javascript:print();">Imprimir</a>
						<a class="btn btn-default" href="sel_grupo_mesas.php">Volver</a>
				</div>
				
			</div><!-- /col-sm-12 -->
			
		</div><!-- /.row -->
	
	</div><!-- /.container -->


<?php include("../../pie.php"); ?>

	

</body>
</html>