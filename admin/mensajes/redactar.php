<?php
require('../../bootstrap.php');

$pr = $_SESSION['profi'];

if (isset($_POST['profes'])) {
	$profes = $_POST['profes'];
}
elseif (isset($_GET['profes'])) {
	$profes = $_GET['profes'];
}

$_SESSION['msg_block'] = 0;

$profeso = $_POST['profeso'];
$tutores = $_POST['tutores'];
$tutor = $_POST['tutor'];
$departamentos = $_POST['departamentos'];
$departamento = $_POST['departamento'];
$equipos = $_POST['equipos'];
$equipo = $_POST['equipo'];
$claustro = $_POST['claustro'];
$personal = $_POST['personal'];
$etcp = $_POST['etcp'];
$ca = $_POST['ca'];
$direccion = $_POST['direccion'];
$orientacion = $_POST['orientacion'];
$bilingue = $_POST['bilingue'];
$pas = $_POST['pas'];
$biblio = $_POST['biblio'];
$mantenimiento = $_POST['mantenimiento'];
$dfeie = $_POST['dfeie'];
$profesor = $_POST['profesor'];
$convivencia = $_POST['convivencia'];
$profesor_nocturno = $_POST['profesor_nocturno']; // Checkbox
$profesores_nocturnos = $_POST['profesores_nocturnos']; // Select
$profesor_diurno = $_POST['profesor_diurno']; // Checkbox
$profesores_diurnos = $_POST['profesores_diurnos']; // Select

if (isset($_POST['padres'])) {
	$padres = $_POST['padres'];
}
elseif (isset($_GET['padres'])) {
	$padres = $_GET['padres'];
}
else
{
$padres="";
}
if (isset($_POST['asunto'])) {
	$asunto = htmlspecialchars($_POST['asunto'], ENT_QUOTES, 'UTF-8');
}
elseif (isset($_GET['asunto'])) {
	$asunto = htmlspecialchars($_GET['asunto'], ENT_QUOTES, 'UTF-8');
}
else
{
$asunto="";
}
if (isset($_POST['texto'])) {
	$_texto = htmlspecialchars($_POST['texto'], ENT_QUOTES, 'UTF-8');
	$texto = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $_texto);

}
elseif (isset($_GET['texto'])) {
	$_texto = htmlspecialchars($_GET['texto'], ENT_QUOTES, 'UTF-8');
	$texto = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $_texto);
}
if (isset($_POST['origen'])) {
	$origen = $_POST['origen'];
}
elseif (isset($_GET['origen'])) {
	$origen = $_GET['origen'];
}
else
{
$origen="";
}


$verifica = $_GET['verifica'];
if($verifica){
 mysqli_query($db_con, "UPDATE mens_profes SET recibidoprofe = '1' WHERE id_profe = '$verifica'");
}


include("profesores.php");


include('../../menu.php');
include('menu.php');
$page_header = "Redactar mensaje";
?>
	<div class="container">


  	<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
	    <h2>Mensajes <small><?php echo $page_header; ?></small></h2>
	  </div>

	  <!-- MENSAJES -->
	  <?php if (isset($msg_error)): ?>
	  <div class="alert alert-danger">
	  	<?php echo $msg_error; ?>
	  </div>
	  <?php endif; ?>


	  <form id="formMensaje" method="post" action="" name="formulario" onsubmit="return checkAsunto(this);">

	  <!-- SCALLFODING -->
		<div class="row">

    	<!-- COLUMNA IZQUIERDA -->
      <div class="col-sm-7">

      	<div class="well">

      		<fieldset>
      			<legend>Redactar mensaje</legend>

      			<input type="hidden" name="token" value="<?php echo $token; ?>">

	      		<div class="form-group">
	      			<label for="asunto">Asunto</label>
	      			<input type="text" class="form-control" id="asunto" name="asunto" placeholder="Asunto del mensaje" value="<?php echo (isset($asunto)) ? $asunto : ''; ?>" maxlength="120" required autofocus>
	      		</div>

	      		<div class="form-group">
	      			<label for="texto" class="sr-only">Contenido</label>
	      			<textarea class="form-control" id="texto" name="texto" rows="10" maxlength="3000"><?php echo (isset($texto) && $texto) ? $texto : ''; ?></textarea>
	      		</div>

	      		<button type="submit" class="btn btn-primary" name="submit1">Enviar mensaje</button>
	      		<a href="index.php" class="btn btn-default">Volver</a>

      		</fieldset>

      	</div><!-- /.well-->

      </div><!-- /.col-sm-7 -->

      <!-- COLUMNA DERECHA -->
      <div class="col-sm-5">

      	<div id="grupos_destinatarios" class="well">

      		<fieldset>
      			<legend>Grupos de destinatarios</legend>

      			<input type="hidden" name="profesor" value="<?php echo $_SESSION['ide']; ?>">

            <div class="row">

            	<!-- COLUMNA IZQUIERDA -->
              <div class="col-sm-6">

								<h5>Por selección</h5>

                <div class="form-group">
                	<div class="checkbox">
                		<label>
                			<input id="profes" name="profes" type="checkbox" value="1" <?php if($profes=='1' and !$claustro) echo 'checked'; ?>> Personal del Centro
                		</label>
                	</div>
                </div>

                <div class="form-group">
                	<div class="checkbox">
                		<label>
                			<input id="tutores" name="tutores" type="checkbox" value="1" <?php if($tutores=='1' and !$claustro) echo 'checked'; ?>> Tutores
                		</label>
                	</div>
                </div>

                <div class="form-group">
                	<div class="checkbox">
                		<label>
                			<input id="departamentos" name="departamentos" type="checkbox" value="1" <?php if($departamentos=='1' and !$claustro) echo 'checked'; ?>> Departamentos
                		</label>
                	</div>
                </div>

                <div class="form-group">
                	<div class="checkbox">
                		<label>
                			<input id="equipos" name="equipos" type="checkbox" value="1" <?php if($equipos=='1' and !$claustro) echo 'checked'; ?>> Equipos educativos
                		</label>
                	</div>
                </div>

				<?php $result_profesores_nocturnos = mysqli_query($db_con, "SELECT DISTINCT `prof` FROM `horw` WHERE `hora` > 7 AND (`idactividad` = 1 OR `idactividad` = 636 OR `idactividad` = 476) AND `prof` <> '' ORDER BY `prof` ASC"); ?>
				<?php $existeProfesoresNocturnos = (mysqli_num_rows($result_profesores_nocturnos)) ? 1 : 0; ?>
				<?php if ($existeProfesoresNocturnos): ?>
				<div class="form-group">
                	<div class="checkbox">
                		<label>
                			<input id="profesor_nocturno" name="profesor_nocturno" type="checkbox" value="1" <?php if($profesor_nocturno=='1' and !$claustro) echo 'checked'; ?>> Profesorado nocturno
                		</label>
                	</div>
                </div>
				<?php endif; ?>

				<?php $result_profesores_diurnos = mysqli_query($db_con, "SELECT DISTINCT `prof` FROM `horw` WHERE `hora` < 8 AND (`idactividad` = 1 OR `idactividad` = 636 OR `idactividad` = 476) AND `prof` <> '' ORDER BY `prof` ASC"); ?>
				<?php $existeProfesoresDiurnos = (mysqli_num_rows($result_profesores_diurnos)) ? 1 : 0; ?>
				<?php if ($existeProfesoresDiurnos): ?>
				<div class="form-group">
                	<div class="checkbox">
                		<label>
                			<input id="profesor_diurno" name="profesor_diurno" type="checkbox" value="1" <?php if($profesor_diurno=='1' and !$claustro) echo 'checked'; ?>> Profesorado diurno
                		</label>
                	</div>
                </div>
				<?php endif; ?>

								<?php if((stristr($_SESSION['cargo'],'1') == TRUE || stristr($_SESSION['cargo'],'2') == TRUE) and $_SESSION['pagina_centro'] == 1): ?>
              	<div class="form-group">
              		<div class="checkbox">
              			<label>
              				<input id="padres" name="padres" type="checkbox" value="1" <?php if($padres=='1' and !$claustro) echo 'checked'; ?>> Familias y alumnos
              			</label>
              		</div>
              	</div>
              <?php endif; ?>

              </div>


              <!-- COLUMNA DERECHA -->
              <div class="col-sm-6">

								<h5>Por grupo</h5>

                  <div class="form-group">
                      <div class="checkbox">
                          <label>
                              <input id="personal" name="personal" type="checkbox" value="1" <?php if($personal=='1') echo 'checked'; ?>> Personal del centro
                          </label>
                      </div>
                  </div>
                  <div class="form-group">
                	<div class="checkbox">
                		<label>
                			<input id="claustro" name="claustro" type="checkbox" value="1" <?php if($claustro=='1') echo 'checked'; ?>> Todo el claustro
                		</label>
                	</div>
                </div>

              	<div class="form-group">
              		<div class="checkbox">
              			<label>
              				<input id="etcp" name="etcp" type="checkbox" value="1" <?php if($etcp=='1' and !$claustro) echo 'checked'; ?>> Jefes Departamento
              			</label>
              		</div>
              	</div>

              	<div class="form-group">
              		<div class="checkbox">
              			<label>
              				<input id="ca" name="ca" type="checkbox" value="1" <?php if($ca=='1' and !$claustro) echo 'checked'; ?>> Coordinadores Área
              			</label>
              		</div>
              	</div>

              	<div class="form-group">
              		<div class="checkbox">
              			<label>
              				<input id="direccion" name="direccion" type="checkbox" value="1" <?php if($direccion=='1' and !$claustro) echo 'checked'; ?>> Equipo directivo
              			</label>
              		</div>
              	</div>

								<div class="form-group">
									<div class="checkbox">
										<label>
											<input id="pas" name="pas" type="checkbox" value="1" <?php if($pas=='1' and !$claustro) echo 'checked'; ?>> Administración
										</label>
									</div>
								</div>

              	<div class="form-group">
              		<div class="checkbox">
              			<label>
              				<input id="orientacion" name="orientacion" type="checkbox" value="1" <?php if($orientacion=='1' and !$claustro) echo 'checked'; ?>> Orientación
              			</label>
              		</div>
              	</div>

              	<?php if(isset($config['mod_bilingue']) && $config['mod_bilingue']): ?>
              	<div class="form-group">
              		<div class="checkbox">
              			<label>
              				<input id="bilingue" name="bilingue" type="checkbox" value="1" <?php if($bilingue=='1' and !$claustro) echo 'checked'; ?>> Profesorado bilingüe
              			</label>
              		</div>
              	</div>
              	<?php endif; ?>

								<?php if(isset($config['mod_biblioteca']) && $config['mod_biblioteca']): ?>
                <div class="form-group">
                	<div class="checkbox">
                		<label>
                			<input id="biblio" name="biblio" type="checkbox" value="1" <?php if($biblio=='1' and !$claustro) echo 'checked'; ?>> Biblioteca
                		</label>
                	</div>
                </div>
                <?php endif; ?>

								<?php if(isset($config['mod_convivencia']) && $config['mod_convivencia']): ?>
                <div class="form-group">
                	<div class="checkbox">
                		<label>
                			<input id="convivencia" name="convivencia" type="checkbox" value="1" <?php if($convivencia=='1' and !$claustro) echo 'checked'; ?>> Aula de Convivencia
                		</label>
                	</div>
                </div>
                <?php endif; ?>

								<div class="form-group">
									<div class="checkbox">
										<label>
											<input id="dfeie" name="dfeie" type="checkbox" value="1" <?php if($dfeie=='1' and !$claustro) echo 'checked'; ?>> DFEIE
										</label>
									</div>
								</div>

								<?php $result_mantenimiento = mysqli_query($db_con, "SELECT `departamento` FROM `departamentos` WHERE `departamento` = 'Servicio Técnico y/o Mantenimiento'"); ?>
								<?php $existeDeptoMantenimiento = (mysqli_num_rows($result_mantenimiento)) ? 1 : 0; ?>
								<?php if ($existeDeptoMantenimiento): ?>
								<div class="form-group">
									<div class="checkbox">
										<label>
											<input id="mantenimiento" name="mantenimiento" type="checkbox" value="1" <?php if($mantenimiento=='1' and !$claustro) echo 'checked'; ?>> Servicio Técnico y/o Mantenimiento
										</label>
									</div>
								</div>
								<?php endif; ?>

              </div>
      		</fieldset>

      	</div>


				<!-- PERSONAL DEL CENTRO -->
				<div id="grupo_profesores" class="well <?php echo (isset($profes) && !empty($profes)) ? '' : 'hidden'; ?>">

					<fieldset>
						<legend>Selección de Personal</legend>

						<?php $s_origen = mb_strtoupper($origen); ?>

						<div class="form-group">
							<?php $result = mysqli_query($db_con, "SELECT DISTINCT nombre, idea FROM departamentos ORDER BY `nombre` ASC"); ?>
							<?php if(mysqli_num_rows($result)): ?>
							<select class="form-control" name="profeso[]" multiple="multiple" size="23">
								<?php while($row = mysqli_fetch_array($result)): ?>
								<option value="<?php echo $row['idea']; ?>;<?php echo $row['nombre']; ?>" <?php echo (isset($origen) && mb_strtoupper($origen) == mb_strtoupper($row['idea'])) ? 'selected' : ''; ?>><?php echo $row['nombre']; ?></option>
								<?php endwhile; ?>
								<?php mysqli_free_result($result); ?>
							</select>
							<?php else: ?>
							<select class="form-control" name="profeso[]" multiple="multiple" disabled>
								<option value=""></option>
							</select>
							<?php endif; ?>

							<div class="help-block">Mantén apretada la tecla <kbd>Ctrl</kbd> mientras haces clic con el ratón para seleccionar múltiples profesores.</div>
						</div>

					</fieldset>

				</div>

				<!-- TUTORES -->
				<div id="grupo_tutores" class="well <?php echo (isset($tutores) && !empty($tutores)) ? '' : 'hidden'; ?>">

					<fieldset>
						<legend>Seleccione tutores</legend>

						<div class="form-group">
							<?php $result = mysqli_query($db_con, "SELECT DISTINCT tutor, unidad, idea FROM FTUTORES, departamentos where tutor = nombre  ORDER BY unidad ASC"); ?>
							<?php if(mysqli_num_rows($result)): ?>
							<select class="form-control" name="tutor[]" multiple="multiple" size="23">
								<?php while($row = mysqli_fetch_array($result)): ?>
								<option value="<?php echo $row['idea']; ?> --> <?php echo $row['unidad']; ?>-"><?php echo $row['unidad']; ?> - <?php echo nomprofesor($row['tutor']); ?></option>
								<?php endwhile; ?>
								<?php mysqli_free_result($result); ?>
							</select>
							<?php else: ?>
							<select class="form-control" name="tutor[]" multiple="multiple" disabled>
								<option value=""></option>
							</select>
							<?php endif; ?>

							<div class="help-block">Mantén apretada la tecla <kbd>Ctrl</kbd> mientras haces clic con el ratón para seleccionar múltiples tutores.</div>
						</div>

					</fieldset>
				</div>

				<!-- JEFES DE DEPARTAMENTO -->
				<div id="grupo_departamentos" class="well <?php echo (isset($departamentos) && !empty($departamentos)) ? '' : 'hidden'; ?>">

					<fieldset>
						<legend>Seleccione departamentos</legend>

						<div class="form-group">
							<?php $result = mysqli_query($db_con, "SELECT DISTINCT departamento FROM departamentos where departamento <> 'Admin' and departamento <> 'Administracion' and departamento <> 'Conserjeria' AND departamento <> 'Servicio Técnico y/o Mantenimiento' AND departamento <> '' ORDER BY departamento ASC"); ?>
							<?php if(mysqli_num_rows($result)): ?>
							<select class="form-control" name="departamento[]" multiple="multiple" size="23">
								<?php while($row = mysqli_fetch_array($result)): ?>
								<option value="<?php echo $row['departamento']; ?>"><?php echo $row['departamento']; ?></option>
								<?php endwhile; ?>
								<?php mysqli_free_result($result); ?>
							</select>
							<?php else: ?>
							<select class="form-control" name="departamento[]" multiple="multiple" disabled>
								<option value=""></option>
							</select>
							<?php endif; ?>

							<div class="help-block">Mantén apretada la tecla <kbd>Ctrl</kbd> mientras haces clic con el ratón para seleccionar múltiples departamentos.</div>
						</div>

					</fieldset>
				</div>

				<!-- EQUIPOS EDUCATIVOS -->
				<div id="grupo_equipos" class="well <?php echo (isset($equipos) && !empty($equipos)) ? '' : 'hidden'; ?>">

					<fieldset>
						<legend>Seleccione equipos educativos</legend>

						<div class="form-group">
							<?php $result = mysqli_query($db_con, "SELECT DISTINCT grupo FROM profesores ORDER BY grupo ASC"); ?>
							<?php if(mysqli_num_rows($result)): ?>
							<select class="form-control" name="equipo[]" multiple="multiple" size="23">
								<?php while($row = mysqli_fetch_array($result)): ?>
								<option value="<?php echo $row['grupo']; ?>"><?php echo $row['grupo']; ?></option>
								<?php endwhile; ?>
								<?php mysqli_free_result($result); ?>
							</select>
							<?php else: ?>
							<select class="form-control" name="equipo[]" multiple="multiple" disabled>
								<option value=""></option>
							</select>
							<?php endif; ?>

							<div class="help-block">Mantén apretada la tecla <kbd>Ctrl</kbd> mientras haces clic con el ratón para seleccionar múltiples equipos educativos.</div>
						</div>

					</fieldset>
				</div>

				<?php if ($existeProfesoresNocturnos): ?>
				<!-- PROFESORADO NOCTURNO -->
				<div id="grupo_profesor_nocturno" class="well <?php echo (isset($profesor_nocturno) && !empty($profesor_nocturno)) ? '' : 'hidden'; ?>">

					<fieldset>
						<legend>Seleccione profesorado de nocturno</legend>

						<div class="form-group">
							<?php $result = mysqli_query($db_con, "SELECT DISTINCT `departamentos`.`nombre`, `departamentos`.`idea` FROM `horw` JOIN `departamentos` ON `horw`.`prof` = `departamentos`.`nombre` WHERE `horw`.`hora` > 7 AND (`horw`.`idactividad` = 1 OR `horw`.`idactividad` = 636 OR `horw`.`idactividad` = 476) ORDER BY `departamentos`.`nombre` ASC"); ?>
							<?php if(mysqli_num_rows($result)): ?>
							<select class="form-control" name="profesores_nocturnos[]" multiple="multiple" size="23">
								<?php while($row = mysqli_fetch_array($result)): ?>
								<option value="<?php echo $row['idea']; ?>"><?php echo $row['nombre']; ?></option>
								<?php endwhile; ?>
								<?php mysqli_free_result($result); ?>
							</select>
							<?php else: ?>
							<select class="form-control" name="profesores_nocturnos[]" multiple="multiple" disabled>
								<option value=""></option>
							</select>
							<?php endif; ?>

							<div class="help-block">Mantén apretada la tecla <kbd>Ctrl</kbd> mientras haces clic con el ratón para seleccionar múltiples profesores de nocturno.</div>
						</div>

					</fieldset>
				</div>
				<?php endif; ?>

				<?php if ($existeProfesoresNocturnos && $existeProfesoresDiurnos): ?>
				<!-- PROFESORADO DIURNO -->
				<div id="grupo_profesor_diurno" class="well <?php echo (isset($profesor_diurno) && !empty($profesor_diurno)) ? '' : 'hidden'; ?>">

					<fieldset>
						<legend>Seleccione profesorado de diurno</legend>

						<div class="form-group">
							<?php $result = mysqli_query($db_con, "SELECT DISTINCT `departamentos`.`nombre`, `departamentos`.`idea` FROM `horw` JOIN `departamentos` ON `horw`.`prof` = `departamentos`.`nombre` WHERE `horw`.`hora` < 8 AND (`horw`.`idactividad` = 1 OR `horw`.`idactividad` = 636 OR `horw`.`idactividad` = 476) ORDER BY `departamentos`.`nombre` ASC"); ?>
							<?php if(mysqli_num_rows($result)): ?>
							<select class="form-control" name="profesores_diurnos[]" multiple="multiple" size="23">
								<?php while($row = mysqli_fetch_array($result)): ?>
								<option value="<?php echo $row['idea']; ?>"><?php echo $row['nombre']; ?></option>
								<?php endwhile; ?>
								<?php mysqli_free_result($result); ?>
							</select>
							<?php else: ?>
							<select class="form-control" name="profesores_diurnos[]" multiple="multiple" disabled>
								<option value=""></option>
							</select>
							<?php endif; ?>

							<div class="help-block">Mantén apretada la tecla <kbd>Ctrl</kbd> mientras haces clic con el ratón para seleccionar múltiples profesores de diurno.</div>
						</div>

					</fieldset>
				</div>
				<?php endif; ?>
          <!-- CLAUSTRO DEL CENTRO -->
          <div id="grupo_claustro" class="well <?php echo (isset($claustro) && !empty($claustro)) ? '' : 'hidden'; ?>">

              <fieldset>
                  <legend>Claustro de profesores</legend>

                  <?php $result = mysqli_query($db_con, "SELECT DISTINCT nombre FROM departamentos where departamento not like 'Administracion' and departamento not like 'Admin' and departamento not like 'Conserjeria' ORDER BY `nombre` ASC"); ?>
                  <?php if(mysqli_num_rows($result)): ?>
                      <ul style="height: auto; max-height: 520px; overflow: scroll;">
                          <?php while($row = mysqli_fetch_array($result)): ?>
                              <li><?php echo $row['nombre'] ; ?></li>
                          <?php endwhile; ?>
                          <?php mysqli_free_result($result); ?>
                      </ul>
                  <?php endif; ?>

              </fieldset>
          </div>

				<!-- Personal del centro -->
				<div id="grupo_personal" class="well <?php echo (isset($personal) && !empty($personal)) ? '' : 'hidden'; ?>">

					<fieldset>
						<legend>Personal del centro</legend>

						<?php $result = mysqli_query($db_con, "SELECT DISTINCT nombre FROM departamentos where departamento not like 'admin' ORDER BY `nombre` ASC"); ?>
						<?php if(mysqli_num_rows($result)): ?>
						<ul style="height: auto; max-height: 520px; overflow: scroll;">
							<?php while($row = mysqli_fetch_array($result)): ?>
							<li><?php echo $row['nombre'] ; ?></li>
							<?php endwhile; ?>
							<?php mysqli_free_result($result); ?>
						</ul>
						<?php endif; ?>

					</fieldset>
				</div>

				<!-- FAMILIAS Y ALUMNOS -->
				<?php if(stristr($_SESSION['cargo'],'1') == TRUE || stristr($_SESSION['cargo'],'2') == TRUE): ?>

				<?php $sql_where = ""; ?>

				<?php if(stristr($_SESSION['cargo'],'2')): ?>
					<?php $result = mysqli_query($db_con, "SELECT unidad FROM FTUTORES WHERE tutor='$pr'"); ?>
					<?php $unidad = mysqli_fetch_array($result); ?>
					<?php $unidad = $unidad['unidad']; ?>
					<?php mysqli_free_result($result); ?>

					<?php $sql_where = "WHERE unidad='$unidad'"; ?>
				<?php endif; ?>

				<div id="grupo_padres" class="well <?php echo (isset($padres) && !empty($padres)) ? '' : 'hidden'; ?>">

					<fieldset>
						<legend>Familias y alumnos</legend>

						<div class="form-group">
							<?php $result = mysqli_query($db_con, "SELECT DISTINCT apellidos, nombre, unidad, claveal FROM alma $sql_where ORDER BY unidad ASC, apellidos ASC, nombre ASC"); ?>
							<?php if(mysqli_num_rows($result)): ?>
							<select class="form-control" name="padres[]" multiple="multiple" size="23">
								<?php while($row = mysqli_fetch_array($result)): ?>
								<option value="<?php echo $row['claveal']; ?>" <?php echo (isset($origen) && $origen == $row['apellidos'].', '.$row['nombre']) ? 'selected' : ''; ?>><?php echo $row['unidad'].' - '.$row['apellidos'].', '.$row['nombre']; ?></option>
								<?php endwhile; ?>
								<?php mysqli_free_result($result); ?>
							</select>
							<?php else: ?>
							<select class="form-control" name="padres[]" multiple="multiple" disabled>
								<option value=""></option>
							</select>
							<?php endif; ?>

							<div class="help-block">Mantén apretada la tecla <kbd>Ctrl</kbd> mientras haces clic con el ratón para seleccionar múltiples alumnos.</div>
						</div>

					</fieldset>
				</div>

				<?php endif; ?>

				<!-- JEFES DE DEPARTAMENTO -->
				<div id="grupo_etcp" class="well <?php echo (isset($etcp) && !empty($etcp)) ? '' : 'hidden'; ?>">

					<fieldset>
						<legend>Jefes de departamento</legend>

						<?php $result = mysqli_query($db_con, "SELECT DISTINCT `nombre` FROM `departamentos` WHERE `cargo` LIKE '%4%' ORDER BY `nombre` ASC"); ?>
						<?php if(mysqli_num_rows($result)): ?>
						<ul style="height: auto; max-height: 520px; overflow: scroll;">
							<?php while($row = mysqli_fetch_array($result)): ?>
							<li><?php echo $row['nombre'] ; ?></li>
							<?php endwhile; ?>
							<?php mysqli_free_result($result); ?>
						</ul>
						<?php endif; ?>

					</fieldset>
				</div>

				<!-- COORDINADORES DE AREA -->
				<div id="grupo_coordinadores" class="well <?php echo (isset($ca) && !empty($ca)) ? '' : 'hidden'; ?>">

					<fieldset>
						<legend>Coordinadores de área</legend>

						<?php $result = mysqli_query($db_con, "SELECT DISTINCT `nombre` FROM `departamentos` WHERE `cargo` LIKE '%9%' ORDER BY `nombre` ASC"); ?>
						<?php if(mysqli_num_rows($result)): ?>
						<ul style="height: auto; max-height: 520px; overflow: scroll;">
							<?php while($row = mysqli_fetch_array($result)): ?>
							<li><?php echo $row['nombre'] ; ?></li>
							<?php endwhile; ?>
							<?php mysqli_free_result($result); ?>
						</ul>
						<?php endif; ?>

					</fieldset>
				</div>


				<!-- EQUIPO DIRECTIVO -->
				<div id="grupo_directivo" class="well <?php echo (isset($direccion) && !empty($direccion)) ? '' : 'hidden'; ?>">

					<fieldset>
						<legend>Equipo directivo</legend>

						<?php $result = mysqli_query($db_con, "SELECT DISTINCT `nombre` FROM `departamentos` WHERE `cargo` LIKE '%1%' AND `nombre` <> 'Administrador' ORDER BY `nombre` ASC"); ?>
						<?php if(mysqli_num_rows($result)): ?>
						<ul style="height: auto; max-height: 520px; overflow: scroll;">
							<?php while($row = mysqli_fetch_array($result)): ?>
							<li><?php echo $row['nombre'] ; ?></li>
							<?php endwhile; ?>
							<?php mysqli_free_result($result); ?>
						</ul>
						<?php endif; ?>

					</fieldset>
				</div>

				<!-- PAS -->
				<div id="grupo_pas" class="well <?php echo (isset($pas) && !empty($pas)) ? '' : 'hidden'; ?>">

					<fieldset>
						<legend>Personal de Administración</legend>

						<?php $result = mysqli_query($db_con, "SELECT DISTINCT `nombre` FROM `departamentos` WHERE `cargo` LIKE '%7%' ORDER BY `nombre` ASC"); ?>
						<?php if(mysqli_num_rows($result)): ?>
						<ul style="height: auto; max-height: 520px; overflow: scroll;">
							<?php while($row = mysqli_fetch_array($result)): ?>
							<li><?php echo $row['nombre'] ; ?></li>
							<?php endwhile; ?>
							<?php mysqli_free_result($result); ?>
						</ul>
						<?php endif; ?>

					</fieldset>
				</div>

				<!-- ORIENTACION -->
				<div id="grupo_orientacion" class="well <?php echo (isset($orientacion) && !empty($orientacion)) ? '' : 'hidden'; ?>">

					<fieldset>
						<legend>Orientación</legend>

						<?php $result = mysqli_query($db_con, "SELECT DISTINCT `nombre` FROM `departamentos` WHERE `cargo` LIKE '%8%' ORDER BY `nombre` ASC"); ?>
						<?php if(mysqli_num_rows($result)): ?>
						<ul style="height: auto; max-height: 520px; overflow: scroll;">
							<?php while($row = mysqli_fetch_array($result)): ?>
							<li><?php echo $row['nombre'] ; ?></li>
							<?php endwhile; ?>
							<?php mysqli_free_result($result); ?>
						</ul>
						<?php endif; ?>

					</fieldset>
				</div>

				<?php if(isset($config['mod_bilingue']) && $config['mod_bilingue']): ?>
				<!-- BILINGÜE -->
				<div id="grupo_bilingue" class="well <?php echo (isset($bilingue) && !empty($bilingue)) ? '' : 'hidden'; ?>">

					<fieldset>
						<legend>Profesores Bilingüismo</legend>

						<?php $result = mysqli_query($db_con, "SELECT DISTINCT `nombre` FROM `departamentos` WHERE `cargo` LIKE '%a%' ORDER BY `nombre` ASC"); ?>
						<?php if(mysqli_num_rows($result)): ?>
						<ul style="height: auto; max-height: 520px; overflow: scroll;">
							<?php while($row = mysqli_fetch_array($result)): ?>
							<li><?php echo $row['nombre'] ; ?></li>
							<?php endwhile; ?>
							<?php mysqli_free_result($result); ?>
						</ul>
						<?php endif; ?>

					</fieldset>
				</div>
				<?php endif; ?>

				<!-- BIBLIOTECA -->
				<div id="grupo_biblioteca" class="well <?php echo (isset($biblio) && !empty($biblio)) ? '' : 'hidden'; ?>">

					<fieldset>
						<legend>Biblioteca</legend>

						<?php $result = mysqli_query($db_con, "SELECT DISTINCT `nombre` FROM `departamentos` WHERE `cargo` LIKE '%c%' ORDER BY `nombre` ASC"); ?>
						<?php if(mysqli_num_rows($result)): ?>
						<ul style="height: auto; max-height: 520px; overflow: scroll;">
							<?php while($row = mysqli_fetch_array($result)): ?>
							<li><?php echo $row['nombre'] ; ?></li>
							<?php endwhile; ?>
							<?php mysqli_free_result($result); ?>
						</ul>
						<?php endif; ?>

					</fieldset>
				</div>

				<?php if(isset($config['mod_convivencia']) && $config['mod_convivencia']):?>
				<!-- AULA DE CONVIVENCIA -->
				<div id="grupo_aulaconv" class="well <?php echo (isset($convivencia) && !empty($convivencia)) ? '' : 'hidden'; ?>">

					<fieldset>
						<legend>Aula de Convivencia</legend>

						<?php $result = mysqli_query($db_con, "SELECT `nombre` FROM `departamentos` WHERE `cargo` LIKE '%b%' ORDER BY `nombre` ASC");?>
						<?php if(mysqli_num_rows($result)): ?>
						<ul style="height: auto; max-height: 520px; overflow: scroll;">
							<?php while($row = mysqli_fetch_array($result)): ?>
							<li><?php echo $row['nombre'] ; ?></li>
							<?php endwhile; ?>
							<?php mysqli_free_result($result); ?>
						</ul>
						<?php endif; ?>

					</fieldset>
				</div>
				<?php endif; ?>

				<!-- DFEIE -->
				<div id="grupo_dfeie" class="well <?php echo (isset($dfeie) && !empty($dfeie)) ? '' : 'hidden'; ?>">

					<fieldset>
						<legend>Departamento de Formación, Evaluación e Innovación Educativa</legend>

						<?php $result = mysqli_query($db_con, "SELECT `nombre` FROM `departamentos` WHERE `cargo` LIKE '%f%' ORDER BY `nombre` ASC"); ?>
						<?php if(mysqli_num_rows($result)): ?>
						<ul style="height: auto; max-height: 520px; overflow: scroll;">
							<?php while($row = mysqli_fetch_array($result)): ?>
							<li><?php echo $row['nombre'] ; ?></li>
							<?php endwhile; ?>
							<?php mysqli_free_result($result); ?>
						</ul>
						<?php endif; ?>

					</fieldset>
				</div>

				<!-- SERVICIO TÉCNICO Y/O MANTENIMIENTO -->
				<?php if ($existeDeptoMantenimiento): ?>
				<div id="grupo_mantenimiento" class="well <?php echo (isset($mantenimiento) && !empty($mantenimiento)) ? '' : 'hidden'; ?>">

					<fieldset>
						<legend>Servicio Técnico y/o Mantenimiento</legend>

						<?php $result = mysqli_query($db_con, "SELECT DISTINCT `nombre` FROM `departamentos` WHERE `departamento` = 'Servicio Técnico y/o Mantenimiento' ORDER BY `nombre` ASC"); ?>
						<?php if(mysqli_num_rows($result)): ?>
						<ul style="height: auto; max-height: 520px; overflow: scroll;">
							<?php while($row = mysqli_fetch_array($result)): ?>
							<li><?php echo $row['nombre'] ; ?></li>
							<?php endwhile; ?>
							<?php mysqli_free_result($result); ?>
						</ul>
						<?php endif; ?>

					</fieldset>
				</div>
				<?php endif; ?>


			</div><!-- /.col-sm-5 -->

		</div><!-- /.row -->

		</form>

	</div><!-- /.container -->


<?php include("../../pie.php"); ?>

	<script>

	$(document).ready(function() {

		// Campos visibles

			//Personal
			$('#profes').change(function() {
				if(profes.checked==true) {
					$('#grupo_profesores').removeClass('hidden');
				}
				else {
					$('#grupo_profesores').addClass('hidden');
				}
			});

			// Tutores
			$('#tutores').change(function() {
				if(tutores.checked==true) {
					$('#grupo_tutores').removeClass('hidden');
				}
				else {
					$('#grupo_tutores').addClass('hidden');
				}
			});

			// Departamentos
			$('#departamentos').change(function() {
				if(departamentos.checked==true) {
					$('#grupo_departamentos').removeClass('hidden');
				}
				else {
					$('#grupo_departamentos').addClass('hidden');
				}
			});

			// Equipos
			$('#equipos').change(function() {
				if(equipos.checked==true) {
					$('#grupo_equipos').removeClass('hidden');
				}
				else {
					$('#grupo_equipos').addClass('hidden');
				}
			});

			// Nocturno
			$('#profesor_nocturno').change(function() {
				if(profesor_nocturno.checked==true) {
					$('#grupo_profesor_nocturno').removeClass('hidden');
				}
				else {
					$('#grupo_profesor_nocturno').addClass('hidden');
				}
			});

			// Nocturno
			$('#profesor_diurno').change(function() {
				if(profesor_diurno.checked==true) {
					$('#grupo_profesor_diurno').removeClass('hidden');
				}
				else {
					$('#grupo_profesor_diurno').addClass('hidden');
				}
			});
            //Personal
            $('#personal').change(function() {
                if(personal.checked==true){
                    $('#grupo_claustro').addClass('hidden');
                    $('#grupo_personal').removeClass('hidden');
                    $('#claustro').prop('disabled',true);
                    $('#profes').prop('disabled', true);
                    $('#tutores').prop('disabled', true);
                    $('#departamentos').prop('disabled', true);
                    $('#equipos').prop('disabled', true);
                    $('#pas').prop('disabled', true);
                    $('#biblio').prop('disabled', true);
                    $('#etcp').prop('disabled', true);
                    $('#ca').prop('disabled', true);
                    $('#direccion').prop('disabled', true);
                    $('#orientacion').prop('disabled', true);
                    $('#bilingue').prop('disabled', true);
                    $('#padres').prop('disabled', true);
                    $('#dfeie').prop('disabled', true);
                    $('#convivencia').prop('disabled', true);
                    $('#mantenimiento').prop('disabled', true);
                    $('#profesor_nocturno').prop('disabled', true);
                    $('#profesor_diurno').prop('disabled', true);
                }
                else {
                    $('#grupo_claustro').addClass('hidden');
                    $('#grupo_personal').addClass('hidden');
                    $('#claustro').prop('disabled',false);
                    $('#profes').prop('disabled', false);
                    $('#tutores').prop('disabled', false);
                    $('#departamentos').prop('disabled', false);
                    $('#equipos').prop('disabled', false);
                    $('#pas').prop('disabled', false);
                    $('#biblio').prop('disabled', false);
                    $('#etcp').prop('disabled', false);
                    $('#ca').prop('disabled', false);
                    $('#direccion').prop('disabled', false);
                    $('#orientacion').prop('disabled', false);
                    $('#bilingue').prop('disabled', false);
                    $('#padres').prop('disabled', false);
                    $('#dfeie').prop('disabled', false);
                    $('#convivencia').prop('disabled', false);
                    $('#mantenimiento').prop('disabled', false);
                    $('#profesor_nocturno').prop('disabled', false);
                    $('#profesor_diurno').prop('disabled', false);
                }
            });

			//Claustro
			$('#claustro').change(function() {
				if(claustro.checked==true) {
					$('#grupo_claustro').removeClass('hidden');
					$('#grupo_personal').addClass('hidden');
                    $('#personal').prop('disabled',true);
					$('#profes').prop('disabled', true);
					$('#tutores').prop('disabled', true);
					$('#departamentos').prop('disabled', true);
					$('#equipos').prop('disabled', true);
					$('#pas').prop('disabled', true);
					$('#biblio').prop('disabled', true);
					$('#etcp').prop('disabled', true);
					$('#ca').prop('disabled', true);
					$('#direccion').prop('disabled', true);
					$('#orientacion').prop('disabled', true);
					$('#bilingue').prop('disabled', true);
					$('#padres').prop('disabled', true);
					$('#dfeie').prop('disabled', true);
					$('#convivencia').prop('disabled', true);
					$('#mantenimiento').prop('disabled', true);
					$('#profesor_nocturno').prop('disabled', true);
					$('#profesor_diurno').prop('disabled', true);
				}
				else {
					$('#grupo_claustro').addClass('hidden');
                    $('#grupo_personal').addClass('hidden');
                    $('#claustro').prop('disabled',false);
                    $('#personal').prop('disabled',false);
					$('#profes').prop('disabled', false);
					$('#tutores').prop('disabled', false);
					$('#departamentos').prop('disabled', false);
					$('#equipos').prop('disabled', false);
					$('#pas').prop('disabled', false);
					$('#biblio').prop('disabled', false);
					$('#etcp').prop('disabled', false);
					$('#ca').prop('disabled', false);
					$('#direccion').prop('disabled', false);
					$('#orientacion').prop('disabled', false);
					$('#bilingue').prop('disabled', false);
					$('#padres').prop('disabled', false);
					$('#dfeie').prop('disabled', false);
					$('#convivencia').prop('disabled', false);
					$('#mantenimiento').prop('disabled', false);
					$('#profesor_nocturno').prop('disabled', false);
					$('#profesor_diurno').prop('disabled', false);
				}
			});

			// PAS
			$('#pas').change(function() {
				if(pas.checked==true) {
					$('#grupo_pas').removeClass('hidden');
				}
				else {
					$('#grupo_pas').addClass('hidden');
				}
			});

			// Biblioteca
			$('#biblio').change(function() {
				if(biblio.checked==true) {
					$('#grupo_biblioteca').removeClass('hidden');
				}
				else {
					$('#grupo_biblioteca').addClass('hidden');
				}
			});

			// DFEIE
			$('#dfeie').change(function() {
				if(dfeie.checked==true) {
					$('#grupo_dfeie').removeClass('hidden');
				}
				else {
					$('#grupo_dfeie').addClass('hidden');
				}
			});

			// Aula de Convivencia
			$('#convivencia').change(function() {
				if(convivencia.checked==true) {
					$('#grupo_aulaconv').removeClass('hidden');
				}
				else {
					$('#grupo_aulaconv').addClass('hidden');
				}
			});

			// Jefes Departamento
			$('#etcp').change(function() {
				if(etcp.checked==true) {
					$('#grupo_etcp').removeClass('hidden');
				}
				else {
					$('#grupo_etcp').addClass('hidden');
				}
			});

			// Coordinadores Área
			$('#ca').change(function() {
				if(ca.checked==true) {
					$('#grupo_coordinadores').removeClass('hidden');
				}
				else {
					$('#grupo_coordinadores').addClass('hidden');
				}
			});

			// Direccion
			$('#direccion').change(function() {
				if(direccion.checked==true) {
					$('#grupo_directivo').removeClass('hidden');
				}
				else {
					$('#grupo_directivo').addClass('hidden');
				}
			});

			// Orientación
			$('#orientacion').change(function() {
				if(orientacion.checked==true) {
					$('#grupo_orientacion').removeClass('hidden');
				}
				else {
					$('#grupo_orientacion').addClass('hidden');
				}
			});

			// Bilingüismo
			$('#bilingue').change(function() {
				if(bilingue.checked==true) {
					$('#grupo_bilingue').removeClass('hidden');
				}
				else {
					$('#grupo_bilingue').addClass('hidden');
				}
			});

			// Servicio Técnico y/o Mantenimiento
			$('#mantenimiento').change(function() {
				if(mantenimiento.checked==true) {
					$('#grupo_mantenimiento').removeClass('hidden');
				}
				else {
					$('#grupo_mantenimiento').addClass('hidden');
				}
			});

			// Padres
			$('#padres').change(function() {
				if(padres.checked==true) {
					$('#grupo_padres').removeClass('hidden');
				}
				else {
					$('#grupo_padres').addClass('hidden');
				}
			});

		// EDITOR DE TEXTO
		tinymce.init({
			selector: 'textarea#texto',
			language: 'es',
			height: 300,
			plugins: 'print preview fullpage paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars',
			imagetools_cors_hosts: ['picsum.photos'],
			menubar: 'file edit view insert format tools table help',
			toolbar: 'undo redo | bold italic underline strikethrough | fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap | fullscreen  preview save print | insertfile image media template link anchor | ltr rtl',
			toolbar_sticky: true,
			autosave_ask_before_unload: true,
			autosave_interval: "30s",
			autosave_prefix: "{path}{query}-{id}-",
			autosave_restore_when_empty: false,
			autosave_retention: "2m",
			relative_urls : false,
			remove_script_host : false,
			<?php if ($_SERVER['HTTPS'] = "On"): ?>
			document_base_url : "https://<?php echo $config['dominio']; ?>/intranet/",
			<?php else: ?>
			document_base_url : "http://<?php echo $config['dominio']; ?>/intranet/",
			<?php endif; ?>
			image_advtab: true,

			/* enable title field in the Image dialog*/
			image_title: true,
			/* enable automatic uploads of images represented by blob or data URIs*/
			automatic_uploads: true,
			/*
			URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
			images_upload_url: 'postAcceptor.php',
			here we add custom filepicker only to Image dialog
			*/
			file_picker_types: 'image',
			/* and here's our custom image picker*/
			file_picker_callback: function (cb, value, meta) {
			var input = document.createElement('input');
			input.setAttribute('type', 'file');
			input.setAttribute('accept', 'image/*');

			/*
			  Note: In modern browsers input[type="file"] is functional without
			  even adding it to the DOM, but that might not be the case in some older
			  or quirky browsers like IE, so you might want to add it to the DOM
			  just in case, and visually hide it. And do not forget do remove it
			  once you do not need it anymore.
			*/

			input.onchange = function () {
			  var file = this.files[0];

			  var reader = new FileReader();
			  reader.onload = function () {
			    /*
			      Note: Now we need to register the blob in TinyMCEs image blob
			      registry. In the next release this part hopefully won't be
			      necessary, as we are looking to handle it internally.
			    */
			    var id = 'blobid' + (new Date()).getTime();
			    var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
			    var base64 = reader.result.split(',')[1];
			    var blobInfo = blobCache.create(id, file, base64);
			    blobCache.add(blobInfo);

			    /* call the callback and populate the Title field with the file name */
			    cb(blobInfo.blobUri(), { title: file.name });
			  };
			  reader.readAsDataURL(file);
			};

			input.click();
			}
		});

	  	$('#mostrar_grupos').click(function() {
	  	mostrar_grupos();
	  });

	});
	</script>

	<script>

	bootbox.setDefaults({
	  locale: "es",
	  show: true,
	  backdrop: true,
	  closeButton: true,
	  animate: true,
	  title: "Enviar mensaje",
	});




	function checkAsunto(form)
	  {

	    // Comprobación de Asunto vacío
	    if($('#asunto').val() == "") {
	      bootbox.alert("No ha escrito nada en el asunto del formulario.");
	      $('#asunto').parent('.form-group').addClass('has-error');
	      return false;
	    }

	    // Comprobación de Grupo de destinatarios sin marcar
	    if(formulario.profes.checked == false && formulario.tutores.checked == false && formulario.departamentos.checked == false && formulario.equipos.checked == false && formulario.profesor_nocturno.checked == false && formulario.profesor_diurno.checked == false && formulario.personal.checked == false && formulario.claustro.checked == false && formulario.pas.checked == false && formulario.biblio.checked == false && formulario.convivencia.checked == false && formulario.etcp.checked == false && formulario.ca.checked == false && formulario.direccion.checked == false && formulario.orientacion.checked == false && formulario.mantenimiento.checked == false <?php if(isset($config['mod_bilingue']) && $config['mod_bilingue']): ?>&& formulario.bilingue.checked == false<?php endif; ?><?php if(stristr($_SESSION['cargo'],'1') == TRUE || stristr($_SESSION['cargo'],'2') == TRUE): ?>&& formulario.padres.checked == false<?php endif; ?>) {
			bootbox.alert("No ha seleccionado ningún grupo de destinatarios para el mensaje.");
			return false;
	    }

	    // Comprobación de destinatario vacío
	    if(formulario.personal.checked == false && formulario.claustro.checked == false && formulario.pas.checked == false && formulario.biblio.checked == false && formulario.etcp.checked == false && formulario.ca.checked == false && formulario.direccion.checked == false && formulario.orientacion.checked == false && formulario.mantenimiento.checked == false <?php if(isset($config['mod_bilingue']) && $config['mod_bilingue']): ?>&& formulario.bilingue.checked == false<?php endif; ?>) {
		    if(document.forms['formulario']['profeso[]'].selectedIndex == -1 && document.forms['formulario']['equipo[]'].selectedIndex == -1 && document.forms['formulario']['profesores_nocturnos[]'].selectedIndex == -1 && document.forms['formulario']['profesores_diurnos[]'].selectedIndex == -1 && document.forms['formulario']['tutor[]'].selectedIndex == -1 && document.forms['formulario']['departamento[]'].selectedIndex == -1 <?php if(stristr($_SESSION['cargo'],'1') == TRUE || stristr($_SESSION['cargo'],'2') == TRUE): ?>&& document.forms['formulario']['padres[]'].selectedIndex == -1<?php endif; ?>) {
		    	bootbox.alert("No ha seleccionado ningún destinatario para el mensaje.");
		    	return false;
		  	}
		}

		return true;

	  }
	</script>
</body>
</html>
