<?php
require('../bootstrap.php');

$esTIC = 0;
if (file_exists("config.php")) {
	include("config.php");
	$esTIC = ($config['tic']['coordinador'] == $_SESSION['profi']) ? 1 : 0;
}

if (isset($_POST['profesor'])) $profesor = preg_replace('([^A-Za-z0-9 ])', '', $_POST['profesor']); else $profesor=$_SESSION['profi'];
if (isset($_POST['actividad'])) $actividad = preg_replace('([^A-Za-z0-9 ])', '', $_POST['actividad']);

include("../menu.php");
include("menu.php");
?>

	<div class="container">

		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Centro TIC <small>Asignación de mesas TIC</small></h2>
		</div>


		<!-- SCAFFOLDING -->
		<div class="row">

			<!-- COLUMNA CENTRAL -->
			<div class="col-sm-6 col-sm-offset-3">

				<div class="well">

					<form method="post" action="">

						<fieldset>

							<?php if (acl_permiso($_SESSION['cargo'], array('1')) || $esTIC): ?>
							<legend>Seleccione profesor, unidad y asignatura</legend>
							<?php else: ?>
							<legend>Seleccione unidad y asignatura</legend>
							<?php endif; ?>

							<!-- El equipo directivo y el coordinador TIC tienen acceso a las plantillas de todos los profesores -->
							<?php if (acl_permiso($_SESSION['cargo'], array('1')) || $esTIC): ?>
							<div class="form-group">
						    <label for="profesor">Profesor</label>
								<?php $result = mysqli_query($db_con, "SELECT DISTINCT nombre FROM departamentos WHERE departamento <> 'Admin' AND departamento <> 'Administracion' AND departamento <> 'Conserjeria' AND departamento <> 'Servicio Técnico y/o Mantenimiento' AND departamento <> '' ORDER BY nombre ASC"); ?>
						    <?php if (mysqli_num_rows($result)): ?>
						    <select class="form-control" id="profesor" name="profesor" onchange="submit()">
						    	<option></option>
						    	<?php while($row = mysqli_fetch_array($result)): ?>
						    	<option value="<?php echo $row['nombre']; ?>"<?php echo (isset($profesor) && $row['nombre'] == $profesor) ? 'selected' : ''; ?>><?php echo $row['nombre']; ?></option>
						    	<?php endwhile; ?>
						    </select>
						    <?php else: ?>
						    <select class="form-control" id="profesor" name="profesor" disabled>
						    	<option value=""></option>
						    </select>
						    <?php endif; ?>
						    <?php mysqli_free_result($result); ?>
						  </div>
							<?php endif; ?>

							<div class="form-group">
						    <label for="actividad">Unidad (Asignatura)</label>
						    <?php $result = mysqli_query($db_con, "SELECT id, a_asig, asig, a_grupo, c_asig, a_aula, dia, hora FROM horw WHERE a_asig NOT LIKE '' AND a_aula NOT LIKE '' AND prof='".$profesor."' GROUP BY a_grupo, a_asig ORDER BY dia, hora, a_grupo"); ?>
						    <?php if(mysqli_num_rows($result)): ?>
						    <select class="form-control" id="actividad" name="actividad" onchange="submit()">
						    	<option></option>
								<?php $dia=0; $hora=0;$cont=0;$grupo='';$k=0;$cadena=array();$cadena2=array();?>
						    	<?php while($row = mysqli_fetch_array($result)):?>
								<?php if(($row['dia']==$dia) && ($row['hora']==$hora)): ?>
								<?php		$grupo=$grupo.'+'.$row['a_grupo']; $casig = ($casig != $row['c_asig']) ? $casig.'+'.$row['c_asig'] : $casig; $cont=$cont+1; ?>
								<?php else: ?>
								<?php 		if($cont>0):?>
								<?php				$k=$k+1;$cadena[$k]=$profesor.'==>'.$grupo.'==>'.$asig.'==>'.$aula.'==>'.$casig.'==>'.$id; $cadena2[$k]=$grupo.' ('.$asig.')';?>
								<?php 		endif; ?>
								<?php		$id=$row['id'];$dia=$row['dia']; $hora=$row['hora']; $grupo=$row['a_grupo']; $asig=$row['asig']; $aula=$row['a_aula']; $casig=$row['c_asig']; $cont=1; ?>
								<?php endif; ?>		  
							   	<?php endwhile; ?>
								<?php $cadena[$k+1]=$profesor.'==>'.$grupo.'==>'.$asig.'==>'.$aula.'==>'.$casig.'==>'.$id; $cadena2[$k+1]=$grupo.' ('.$asig.')';?>
								<?php sort($cadena);?>
								<?php for($i = 0; $i <= $k; $i++){ ?>
										<?php $cadena2 = explode('==>', $cadena[$i]);?>
										<option value="<?php echo $cadena[$i]; ?>"<?php echo (isset($actividad) && $cadena[$i] == $actividad) ? 'selected' : ''; ?>><?php echo $cadena2[1].' ('.$cadena2[2].')'; ?></option>
								<?php }?>
							</select>
						    <?php else: ?>
						    <select class="form-control" id="actividad" name="actividad" disabled>
						    	<option value=""></option> 
						    </select>
						    <?php endif; ?>
						    <?php mysqli_free_result($result); ?>
						  </div>

							<button type="submit" class="btn btn-primary" formaction="mesas_tic_consulta.php" name="enviar">Consultar</button>
						</fieldset>
					</form>

				</div><!-- /.well -->

			</div><!-- /.col-sm-6 -->


		</div><!-- /.row -->

	</div><!-- /.container -->

<?php include("../pie.php"); ?>

</body>
</html>
