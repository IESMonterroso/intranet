<?
session_start();
include("../config.php");
if($_SESSION['autentificado']!='1')
{
session_destroy();
header("location:http://$dominio/intranet/salir.php");	
exit;
}
registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);
$profes = $_SESSION['profi'];


// ENVIO DEL FORMULARIO
if(isset($_POST['enviar'])) {
	
	$profesor = $_POST['profesor'];
	$estado = $_POST['estado'];
	$unidad = $_POST['unidad'];
	$alumno = $_POST['alumno'];
	$carrito = $_POST['carrito'];
	$numeroserie = $_POST['numeroserie'];
	$exp_fecha = explode('-', $_POST['fecha']);
	$fecha = $exp_fecha[2].'-'.$exp_fecha[1].'-'.$exp_fecha[0];
	$hora = $_POST['hora'];
	$descripcion = htmlspecialchars($_POST['descripcion']);
	
	if(empty($descripcion) || strlen(trim($descripcion))<8) { 
		$msg_error = 'El campo de descripci�n de la incidencia es obligatorio.';
	}
	else {
		$result = mysql_query("INSERT INTO partestic (unidad,carro,nserie,fecha,hora,alumno,profesor,descripcion,estado) VALUES	('".$unidad."','".$carrito."','".$numeroserie."','".$fecha."','".$hora."','".$alumno."','".$profesor."','".$descripcion."','".$estado."')");
		
		if(!$result) {
			$msg_error = 'La incidencia no se ha podido registrar. Error: '.mysql_error();
		}
		else {
			$direccion = "admin@$dominio";
			$tema = "Nuevo parte de incidencia";
			$texto = "Datos de la incidencia:
			Grupo --> '$unidad';
			Carrito --> '$carrito';
			N� de Serie --> '$numeroserie';
			Fecha --> '$fecha';
			Hora --> '$hora';
			Alumno --> '$alumno';
			Profesor --> '$profesor';
			Descripci�n --> '$descripcion';
			Estado --> '$estado';
			";
			mail($direccion, $tema, $texto); 
			
			$msg_success = 'La incidencia ha sido registrada.';
		}
	}
}

include("../menu.php");
include("menu.php");
?>

<div class="container">
	
	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2>Centro TIC <small>Registro de incidencias</small></h2>
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
	<div class="row">
	
		<!-- COLUMNA IZQUIERDA -->
		<div class="col-sm-6">
			
			<div class="well">
				
				<form method="post" action="">
					<fieldset>
						<legend>Registro de incidencia</legend>
						
						<input type="hidden" name="profesor" value="<?php echo $_SESSION['profi']; ?>">
						<input type="hidden" name="estado" value="activo">
						
						<div class="row">
							<!--FORMLISTACURSOS
							<div class="col-sm-6">
								<div class="form-group">
									<label for="curso">Curso</label>
								</div>
							</div>
							FORMLISTACURSOS//-->
							
							<div class="col-sm-12">
								<div class="form-group">
								  <label for="unidad">Unidad</label>
									<?php $result = mysql_query("SELECT DISTINCT unidad, SUBSTRING(unidad,2,1) AS orden FROM alma ORDER BY orden ASC"); ?>
									<?php if(mysql_num_rows($result)): ?>
									<select class="form-control" id="unidad" name="unidad" onchange="submit()">
										<option></option>
										<?php while($row = mysql_fetch_array($result)): ?>
										<option value="<?php echo $row['unidad']; ?>" <?php echo ($row['unidad'] == $unidad) ? 'selected' : ''; ?>><?php echo $row['unidad']; ?></option>
										<?php endwhile; ?>
										<?php mysql_free_result($result); ?>
									</select>
									<?php else: ?>
									<select class="form-control" name="unidad" disabled>
										<option></option>
									</select>
									<?php endif; ?>
								</div>
							</div>
						</div>
						
						<div class="form-group">
						  <label for="alumno">Alumno/a</label>
						  <?php $result = mysql_query("SELECT DISTINCT APELLIDOS, NOMBRE, CLAVEAL FROM FALUMNOS WHERE unidad='$unidad' ORDER BY APELLIDOS ASC"); ?>
						  <?php if(mysql_num_rows($result)): ?>
						  <select class="form-control" id="alumno" name="alumno">
						  	<option></option>
						  	<?php while($row = mysql_fetch_array($result)): ?>
						  	<option value="<?php echo $row['APELLIDOS'].', '.$row['NOMBRE'].' --> '.$row['CLAVEAL']; ?>" <?php echo (isset($nombre) && $row['APELLIDOS'].', '.$row['NOMBRE'].' --> '.$row['CLAVEAL'] == $alumno) ? 'selected' : ''; ?>><?php echo $row['APELLIDOS'].', '.$row['NOMBRE']; ?></option>
						  	<?php endwhile; ?>
						  	<?php mysql_free_result($result); ?>
						  </select>
						  <?php else: ?>
						  <select class="form-control" name="alumno" disabled>
						  	<option></option>
						  </select>
						  <?php endif; ?>
						</div>
					  
					  <div class="row">
					  	<div class="col-sm-2">
				  			<div class="form-group">
				  				<label for="carrito">Carrito</label>
				  				<input type="text" class="form-control" id="carrito" name="carrito" maxlength="1">
				  			</div>
					  	</div>
					  	
					  	<div class="col-sm-2">
					  		<div class="form-group">
					  			<label for="numeroserie">Ordenador</label>
					  			<input type="text" class="form-control" id="numeroserie" name="numeroserie" maxlength="3">
					  		</div>
					  	</div>
					  	
					  	<div class="col-sm-6">
					  		<div class="form-group">
					  			<label for="fecha">Fecha</label>
					  			 <div class="input-group">
					  			 	<input type="text" class="form-control" id="fecha" name="fecha" value="<?php echo date('d-m-Y'); ?>" data-date-format="dd-mm-yyyy">
					  			  <span class="input-group-addon">
					  			  	<span class="fa fa-calendar fa-fw"></span>
					  			  </span>
					  			</div> 
					  		</div>
					  	</div>
					  	
					  	<div class="col-sm-2">
					  		<div class="form-group">
					  			<label for="hora">Hora</label>
					  			<input type="number" class="form-control" id="hora" name="hora" min="1" max="6" value="1" maxlength="1">
					  		</div>
					  	</div>
					  </div>
					   
					  <div id="form-group-descripcion" class="form-group">
					    <label for="descripcion">Descripci�n de la incidencia</label>
					    <textarea class="form-control" id="descripcion" name="descripcion" placeholder="Describa brevemente la incidencia del ordenador..." rows="6"></textarea>
					  </div>
					  
					  <button type="submit" class="btn btn-primary" name="enviar">Registrar</button>
					  <button type="reset" class="btn btn-default">Cancelar</button>
				  </fieldset>
				</form>
				
			</div><!-- /.well -->
			
		</div><!-- /.col-sm-6 -->
		
		
		<!-- COLUMNA DERECHA -->
		<div class="col-sm-6">
			
			<h3>Informaci�n</h3>
			
			<p>En esta p�gina se dan de alta los problemas que pod�is tener con los ordenadores, tanto port�tiles como fijos.</p>
			
			<p>Los fallos pueden ser de dos tipos: bien sucede que la m�quina o alguna de sus partes presenta problemas (la m�quina no enciende, se ha fastidiado la pantalla o el teclado, etc); o bien el Sistema Operativo o alguna de sus aplicaciones no funcionan. Cualquiera de las dos clases de problemas se registran aqu�.</p>
			
			<p>Si despu�s de haber enviado los datos quer�is modificarlos por alguna raz�n, el enlace "Listar, editar o eliminar los �ltimos partes de incidencias" os permitir� seleccionar vuestra incidencia y editarla o eliminarla.</p>
			
			<p>Y una recomendaci�n: si el problema ha sido causado por el mal uso de un Alumno, registrar el asunto en el modulo de Problemas de Convivencia de la Intranet.</p>
			
			<br>
			
			<a class="btn btn-info" href="clista.php">Editar las incidencias registradas.</a>
		
		</div>
		
	
	</div><!-- /.row -->
	
</div><!-- /.container -->
  
<?php include("../pie.php"); ?>
	
	<?php if(isset($msg_error) && $msg_error): ?>
	<script>$("#form-group-descripcion").addClass("has-error");</script>
	<?php endif; ?>

	<script>  
	$(function ()  
	{ 
		$('#fecha').datepicker()
		.on('changeDate', function(ev){
			$('#fecha').datepicker('hide');
		});
		});  
	</script>

</body>
</html>
