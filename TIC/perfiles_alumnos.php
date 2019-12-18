<?php
require('../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}

if (isset($_POST['profe'])) $profe = preg_replace('([^A-Za-z0-9 ])', '', $_POST['profe']);
if (isset($_POST['curso'])) $curso = preg_replace('([^A-Za-z0-9 ])', '', $_POST['curso']);


include("../menu.php");
include("menu.php");
?>

<?php
if(isset($_POST['enviar'])) :
$exp_unidad = explode('-->',$curso);
$unidad = $exp_unidad[0];
$asignatura = $exp_unidad[3];
?>

	<div class="container">

		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Centro TIC <small>Perfiles de alumnos de <?php echo $unidad; ?></small></h2>
		</div>

		<div class="alert alert-info hidden-print">
			<h4>Cambio de contraseña</h4>
			Es conveniente que el alumno cambie la contraseña que el Centro le ha asignado en las Plataformas Moodle, Helvia o G Suite por una contraseña personal. Sólo entonces el alumno podrá tener la certeza de que el aceso a la Plataforma es realmente privado.
		</div>

		<br>

		<!-- SCAFFOLDING -->
		<div class="row">

			<!-- COLUMNA CENTRAL -->
			<div class="col-sm-12">

				<div class="table-responsive">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Alumno/a</th>
								<th>Usuario</th>
								<th>Contraseña<br>Gesuser</th>
								<th>Contraseña<br>Moodle</th>
								<th>Correo electrónico<br>G-Suite y Office 365</th>
								<th>Contraseña<br>G-Suite</th>
							</tr>
						</thead>
						<tbody>
							<?php
							// Comprobamos problema de varios códigos en Bachillerato y otro
							$asig_bach = mysqli_query($db_con,"select distinct codigo from materias where nombre like (select distinct nombre from materias where codigo = '$asignatura' limit 1) and grupo like '$unidad' and abrev not like '%\_%'");
							while($cod_bch = mysqli_fetch_array($asig_bach)){
								$cod_asignatura.= "combasi like '%$cod_bch[0]%' or ";
								}

							if (strlen($cod_asignatura)>1) {
									$codigo_asignatura = substr($cod_asignatura,0,strlen($cod_asignatura)-4);
								}
								else{
									$codigo_asignatura = "combasi like '%$asignatura%'";
								}

							$caracteres_no_permitidos = array('\'','-','á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'à', 'è', 'ì', 'ò', 'ù', 'À', 'È', 'Ì', 'Ò', 'Ù', 'á', 'ë', 'ï', 'ö', 'ü', 'Ä', 'Ë', 'Ï', 'Ö', 'Ü','ñ');
							$caracteres_permitidos = array('','','a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U','n');

							$codigo_asignatura = substr($cod_asignatura,0,strlen($cod_asignatura)-4);
							$result = mysqli_query($db_con, "SELECT claveal, apellidos, nombre FROM alma WHERE unidad = '".$unidad."' AND ".$codigo_asignatura." ORDER BY apellidos ASC, nombre ASC");
							while ($row = mysqli_fetch_array($result)): 
								$iniciales = strtolower(substr($row['nombre'], 0,1).substr($row['apellidos'], 0,1));
								$iniciales = str_ireplace($caracteres_no_permitidos, $caracteres_permitidos, $iniciales);
								$nombre = str_ireplace($caracteres_no_permitidos, $caracteres_permitidos, $row['nombre']);
								$apellidos = str_ireplace($caracteres_no_permitidos, $caracteres_permitidos, $row['apellidos']);
								if ($_SERVER['SERVER_NAME'] == "iesmonterroso.org") {
									$correo = "al.".$row['claveal'].'@'.$config['dominio'];
									$pass_gsuite = $iniciales."_".$row['claveal'];
								}
								else {
									$correo = $row['claveal'].'.alumno@'.$config['dominio'];
									$pass_gsuite = substr(sha1($row['claveal']),0,8);
								}
								
							?>
							<tr>
								<td><?php echo $row['apellidos'].', '.$row['nombre']; ?></td>
								<td><?php echo $row['claveal']; ?></td>
								<td><?php echo $row['claveal']; ?></td>
								<td><?php echo substr(sha1($row['claveal']),0,8); ?></td>
								<td><?php echo $correo; ?></td>
								<td><?php echo $pass_gsuite; ?></td>
							</tr>
							<?php endwhile; ?>
							<?php mysqli_free_result($result); ?>

						</tbody>
					</table>
				</div>

				<div class="hidden-print">
					<a href="#" class="btn btn-primary" onclick="javascript:print();">Imprimir</a>
					<a href="perfiles_alumnos.php" class="btn btn-default">Volver</a>
				</div>


			</div><!-- /.col-sm-6 -->


		</div><!-- /.row -->

	</div><!-- /.container -->

<?php else: ?>

	<div class="container">

		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Centro TIC <small>Perfiles de alumnos</small></h2>
		</div>


		<!-- SCAFFOLDING -->
		<div class="row">

			<!-- COLUMNA IZQUIERDA -->
			<div class="col-sm-6 col-sm-offset-3">

				<div class="well">

					<form method="post" action="">
						<fieldset>
							<legend>Perfiles de alumnos</legend>

							<?php if(stristr($_SESSION['cargo'],'1') == TRUE): ?>
							<div class="form-group">
						    <label for="profe">Profesor</label>
						    <?php $result = mysqli_query($db_con, "SELECT DISTINCT PROFESOR FROM profesores ORDER BY PROFESOR ASC"); ?>
						    <?php if(mysqli_num_rows($result)): ?>
						    <select class="form-control" id="profe" name="profe" onchange="submit()">
						    <option></option>
							    <?php while($row = mysqli_fetch_array($result)): ?>
							    <option value="<?php echo $row['PROFESOR']; ?>" <?php echo (isset($profe) && $profe == $row['PROFESOR']) ? 'selected' : ''; ?>><?php echo $row['PROFESOR']; ?></option>
							    <?php endwhile; ?>
							    <?php mysqli_free_result($result); ?>
							   </select>
							   <?php else: ?>
							   <select class="form-control" id="profe" name="profe" disabled>
							   	<option value=""></option>
							   </select>
							   <?php endif; ?>
						  </div>
						  <?php else: ?>
						  <?php $profe = $_SESSION['profi']; ?>
						  <?php endif; ?>

						  <div class="form-group">
						    <label for="curso">Unidad (Asignatura)</label>

						    <?php
						    $result = mysqli_query($db_con, "SELECT DISTINCT GRUPO, MATERIA, NIVEL, codigo FROM profesores, asignaturas WHERE materia = nombre AND abrev NOT LIKE '%\_%' AND PROFESOR = '$profe' AND nivel = curso ORDER BY grupo ASC"); ?>
						    <?php if(mysqli_num_rows($result)): ?>
						    <select class="form-control" id="curso" name="curso">
						      <?php while($row = mysqli_fetch_array($result)): ?>
						      <?php $key = $row['GRUPO'].'-->'.$row['MATERIA'].'-->'.$row['NIVEL'].'-->'.$row['codigo']; ?>
						      <option value="<?php echo $key; ?>" <?php echo (isset($curso) && $curso == $key) ? 'selected' : ''; ?>><?php echo $row['GRUPO'].' ('.$row['MATERIA'].')'; ?></option>
						      <?php endwhile; ?>
						      <?php mysqli_free_result($result); ?>
						     </select>
						     <?php else: ?>
						     <select class="form-control" id="profesor" name="profesor" disabled>
						     	<option value=""></option>
						     </select>
						     <?php endif; ?>
						  </div>

						  <button type="submit" class="btn btn-primary" name="enviar">Consultar</button>
					  </fieldset>
					</form>

				</div><!-- /.well -->

			</div><!-- /.col-sm-6 -->


		</div><!-- /.row -->

	</div><!-- /.container -->
<?php endif; ?>

<?php include("../pie.php"); ?>

</body>
</html>
