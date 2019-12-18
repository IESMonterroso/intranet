<?php
require('../../../bootstrap.php');

function abrevactividad($db_con, $actividad) {
	$actividad = preg_replace('([^A-Za-z0-9 ])', '', $actividad);
	$result = mysqli_query($db_con, "SELECT idactividad, nomactividad FROM actividades_seneca WHERE nomactividad = '$actividad'");
	while ($row = mysqli_fetch_array($result)) {
		$exp_nomactividad = explode('(', $row['nomactividad']);

		$exp_nomactividad = str_replace(' a ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' al ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' el ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' la ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' las ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' los ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' de ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' En ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' en ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' su ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' del ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' Del ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' con ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' que ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' y ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace('.', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(',', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace('-', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' para ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' cuando ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' caso ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' como ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' no ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' tengan ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' otros ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' determine ', ' ', $exp_nomactividad);
		$exp_nomactividad = str_replace(' correspondientes ', ' ', $exp_nomactividad);

		$nomactividad = mb_convert_case($exp_nomactividad[0], MB_CASE_TITLE, 'UTF-8');

		$abrev = "";
		for ($i = 0; $i < strlen($nomactividad); $i++) {
			if ($nomactividad[$i] == mb_convert_case($nomactividad[$i], MB_CASE_UPPER, 'UTF-8') && $nomactividad[$i] != " " && $nomactividad[$i] != ".") {
				$abrev .= mb_convert_case($nomactividad[$i], MB_CASE_UPPER, 'UTF-8');
			}
		}

		if (strlen($abrev) < 3) {
			$exp_nomactividad = explode(' ', $nomactividad);
			$abrev .= $exp_nomactividad[1][1].$exp_nomactividad[1][2];
			$abrev = mb_convert_case($abrev, MB_CASE_UPPER, 'UTF-8');
		}

		if (strlen($abrev) < 2) {
			$exp_nomactividad = explode(' ', $nomactividad);
			$abrev .= $exp_nomactividad[0][1].$exp_nomactividad[0][2];
			$abrev = mb_convert_case($abrev, MB_CASE_UPPER, 'UTF-8');
		}
	}

	return $abrev;
}

// SELECCION DE PROFESOR
if(! acl_permiso($_SESSION['cargo'], array('1')))
{
	$profesor = $_SESSION['profi'];
}
else {
	if (isset($_SESSION['mod_horarios']['profesor'])) {
		$profesor = $_SESSION['mod_horarios']['profesor'];
	}

	if ($_POST['profesor']) {
		$profesor = $_POST['profesor'];
		$profesor = preg_replace('([^A-Za-z0-9 ])', '', $profesor);
		$_SESSION['mod_horarios']['profesor'] = $profesor;
	}
}

if (isset($profesor)) {
	$result_idprofesor = mysqli_query($db_con, "SELECT `idprofesor` FROM `profesores_seneca` WHERE `nomprofesor` = '".$profesor."'");
	$row_idprofesor = mysqli_fetch_array($result_idprofesor);
	$idprofesor = $row_idprofesor['idprofesor'];
}

// MODIFICADORES DE FORMULARIO
if (isset($_GET['dia'])) $dia = urldecode($_GET['dia']);
else $dia = $_POST['dia'];

if (isset($_GET['hora'])) $hora = urldecode($_GET['hora']);
else $hora = $_POST['hora'];

if (isset($_GET['asignatura'])) $asignatura = urldecode($_GET['asignatura']);
else $asignatura = $_POST['asignatura'];

if (isset($_POST['unidad'])) {
	$unidad = substr($_POST['unidad'], 0, -1);
	$unidad_curso = $_POST['unidad'];
	$exp_unidad = explode('|', $unidad_curso);
	$unidad = $exp_unidad[0];
	$curso = $exp_unidad[1];
}
elseif (isset($_GET['unidad']) && $_GET['asignatura'] !== '25' and $_POST['unidad']=="") {
	$unidad = urldecode($_GET['unidad']);
	$unidad = preg_replace('([^A-Za-z0-9 ])', '', $unidad);
	// A partir del código de la asignatura y la unidad, descubrimos el curso...
	$result = mysqli_query($db_con, "SELECT `CURSO` FROM `materias` WHERE `GRUPO` = '$unidad' AND `CODIGO` = '$asignatura' LIMIT 1");
	if (mysqli_num_rows($result)) {
		$datos_curso = mysqli_fetch_assoc($result);
		$curso = $datos_curso['CURSO'];
	}
	else {
		// Obtenemos el curso a partir de alguna asignatura en la que imparta el profesor
		$result_curso_asignaturas = mysqli_query($db_con, "SELECT `nivel` FROM `profesores` WHERE `profesor` = '$profesor' AND `grupo` = '$unidad' LIMIT 1");
		$curso_asignaturas = mysqli_fetch_assoc($result_curso_asignaturas);
		$curso = $curso_asignaturas['nivel'];
	}

	$unidad_curso = $unidad.'|'.$curso;
}
elseif ((isset($_GET['unidad']) && $_GET['unidad'] == '')) {
	$unidad = substr($_POST['unidad'], 0, -1);
	$unidad_curso = $_POST['unidad'];
	$exp_unidad = explode('|', $unidad_curso);
	$unidad = $exp_unidad[0];
	$curso = $exp_unidad[1];
}

if (isset($_GET['dependencia'])) {$dependencia = urldecode($_GET['dependencia']);}
else {$dependencia = $_POST['dependencia'];}

// ENVIO DE FORMULARIO
if (isset($_POST['enviar'])) {
	//$dia = $_POST['dia'];
	//$hora = $_POST['hora'];
	// En este caso tratamos los dias y horas más adelante...

	// OBTENEMOS LOS DATOS DEL PROFESOR
	$result = mysqli_query($db_con, "SELECT DISTINCT `no_prof`, `c_prof` FROM `horw` WHERE `prof` = '".$profesor."'");
	if (mysqli_num_rows($result)) {
		$datos_profesor = mysqli_fetch_array($result);
		$numprofesor = $datos_profesor['no_prof'];
		$codprofesor = $datos_profesor['c_prof'];
	}
	else {
		$result2 = mysqli_query($db_con, "SELECT `idprofesor` FROM `profesores_seneca` WHERE `nomprofesor` = '".$profesor."'");
		$datos2_profesor = mysqli_fetch_array($result2);
		$codprofesor = $datos2_profesor['idprofesor'];

		$result3 = mysqli_query($db_con, "SELECT MAX(`no_prof`) FROM `horw`");
		if (mysqli_num_rows($result3)) {
			$datos3_profesor = mysqli_fetch_array($result3);
			$numprofesor = $datos3_profesor['no_prof'] + 1;
		}
		else {
			$numprofesor = 1;
		}
	}

	// OBTENEMOS DATOS DE LA ASIGNATURA
	$result = mysqli_query($db_con, "SELECT `nombre`, `abrev`, `curso` FROM `asignaturas` WHERE `codigo` = '".$_POST['asignatura']."' AND `abrev` NOT LIKE '%\_%'");
	$datos_asignatura = mysqli_fetch_array($result);
	$codasignatura = $_POST['asignatura'];
	$nomasignatura = $datos_asignatura['nombre'];
	$abrevasignatura = $datos_asignatura['abrev'];
	$curso_asignatura = $datos_asignatura['curso'];

	if ($nomasignatura == '') {
		$result_asignatura_por_codigo = mysqli_query($db_con, "SELECT DISTINCT `a_asig`, `asig` FROM `horw` WHERE `c_asig` = '".$_POST['asignatura']."'");
		if (mysqli_num_rows($result_asignatura_por_codigo)) {
			$datos_asignatura_por_codigo = mysqli_fetch_array($result_asignatura_por_codigo);
			$codasignatura = $_POST['asignatura'];
			$nomasignatura = $datos_asignatura_por_codigo['asig'];
			$abrevasignatura = $datos_asignatura_por_codigo['a_asig'];
		}
		else{
			$result = mysqli_query($db_con, "SELECT `idactividad`, `nomactividad` FROM `actividades_seneca` WHERE `idactividad` = '".$_POST['asignatura']."'");
			$datos_asignatura = mysqli_fetch_array($result);
			$codasignatura = $_POST['asignatura'];
			$nomasignatura = $datos_asignatura['nomactividad'];
			$abrevasignatura = abrevactividad($db_con, $datos_asignatura['nomactividad']);
		}
	}

	if ($codasignatura == "25") $unidad = "GU";

	// Comprobamos si es Docencia Bilingüe (código de actividad: 636) o Docencia (código de actividad: 1)
	if (isset($_POST['docencia_bilingue'])) {
		$idactividad = ($_POST['docencia_bilingue'] == 1) ? '636' : '1';
	}
	else {
		$idactividad = $codasignatura;
	}

	// OBTENEMOS DATOS DE LA DEPENDENCIA
	$result = mysqli_query($db_con, "SELECT DISTINCT `n_aula` FROM `horw` WHERE `a_aula` = '".$_POST['dependencia']."'");
	$datos_dependencia = mysqli_fetch_array($result);
	$coddependencia = $_POST['dependencia'];
	$nomdependencia = $datos_dependencia['n_aula'];

	// DIAS Y HORAS SELECCIONADAS
	$flag_registro = 0;

	for ($i = 1; $i < 6; $i++) {
		$result_horas = mysqli_query($db_con, "SELECT `hora_inicio`, `hora_fin`, `hora` FROM `tramos` ORDER BY `idjornada` ASC, `horini` ASC");
		while ($row_horas = mysqli_fetch_array($result_horas)) {
			if(isset($_POST['hora_'.$i.'_'.$row_horas['hora']]) && ! empty($_POST['hora_'.$i.'_'.$row_horas['hora']])) {
				${'hora_'.$i.'_'.$row_horas['hora']} = $_POST['hora_'.$i.'_'.$row_horas['hora']];
				$dia = $i;
				$hora = $row_horas['hora'];

				$result = mysqli_query($db_con, "INSERT INTO `horw` (`dia`, `hora`, `a_asig`, `asig`, `c_asig`, `prof`, `no_prof`, `c_prof`, `a_aula`, `n_aula`, `a_grupo`, `idactividad`) VALUES ('$dia', '$hora', '$abrevasignatura', '$nomasignatura', '$codasignatura', '$profesor', '$numprofesor', '$codprofesor', '$coddependencia', '$nomdependencia', '$unidad', '$idactividad')") or die (mysqli_error($db_con));
				mysqli_query($db_con, "INSERT INTO `horw_faltas` (`dia`, `hora`, `a_asig`, `asig`, `c_asig`, `prof`, `no_prof`, `c_prof`, `a_aula`, `n_aula`, `a_grupo`, `idactividad`) VALUES ('$dia', '$hora', '$abrevasignatura', '$nomasignatura', '$codasignatura', '$profesor', '$numprofesor', '$codprofesor', '$coddependencia', '$nomdependencia', '$unidad', '$idactividad')") or die (mysqli_error($db_con));

				$_SESSION['n_cursos'] = 1;

				$result_tprofesores = mysqli_query($db_con,"SELECT * FROM `profesores` WHERE `nivel` = '$curso_asignatura' AND `materia` = '$nomasignatura' AND `profesor` = '$profesor' AND `grupo` = '$unidad'");
				if (! mysqli_num_rows($result_tprofesores)) {
					if (! empty($curso_asignatura) && ! empty($unidad) != "") {
						mysqli_query($db_con, "INSERT INTO `profesores` (`nivel`, `materia`, `profesor`, `grupo`) VALUES ('$curso_asignatura', '$nomasignatura', '$profesor', '$unidad')") or die (mysqli_error());
					}
				}

				if (! $result) {
					$msg_error = "Error al modificar el horario. Error: ".mysqli_error($db_con);
				}
				else {
					$flag_registro = 1;
				}
			}
		}
	}

	if ($flag_registro) {
		header('Location:'.'index.php?msg_success=1');
	}
}

if (isset($_POST['actualizar'])) {
	$dia = $_POST['dia'];
	$hora = $_POST['hora'];

	// OBTENEMOS DATOS DEL PROFESOR
	$result = mysqli_query($db_con, "SELECT DISTINCT `no_prof`, `c_prof` FROM `horw` WHERE `prof` = '".$profesor."'");
	$datos_profesor = mysqli_fetch_array($result);
	$numprofesor = $datos_profesor['no_prof'];
	$codprofesor = $datos_profesor['c_prof'];

	// OBTENEMOS DATOS DE LA ASIGNATURA
	$result = mysqli_query($db_con, "SELECT `nombre`, `abrev` FROM `asignaturas` WHERE `codigo` = '".$_POST['asignatura']."' AND `abrev` NOT LIKE '%\_%'");
	$datos_asignatura = mysqli_fetch_array($result);
	$codasignatura = $_POST['asignatura'];
	$nomasignatura = $datos_asignatura['nombre'];
	$abrevasignatura = $datos_asignatura['abrev'];

	if ($nomasignatura == '') {
		$result2 = mysqli_query($db_con, "SELECT DISTINCT `a_asig`, `asig` FROM `horw` WHERE `c_asig` = '".$_POST['asignatura']."'");

		if (mysqli_num_rows($result2)) {
			$datos2_asginatura = mysqli_fetch_array($result2);
			$codasignatura = $_POST['asignatura'];
			$nomasignatura = $datos2_asginatura['asig'];
			$abrevasignatura = $datos2_asginatura['a_asig'];
		}
		else {
			$result3 = mysqli_query($db_con, "SELECT `idactividad`, `nomactividad` FROM `actividades_seneca` WHERE `idactividad` = '".$_POST['asignatura']."'");
			$datos3_asignatura = mysqli_fetch_array($result3);
			$codasignatura = $_POST['asignatura'];
			$nomasignatura = $datos3_asignatura['nomactividad'];
			$abrevasignatura = abrevactividad($db_con, $datos3_asignatura['nomactividad']);
		}
	}

	if ($codasignatura == "25") $unidad = "GU";

	// Comprobamos si es Docencia Bilingüe (código de actividad: 636) o Docencia (código de actividad: 1)
	if (isset($_POST['docencia_bilingue'])) {
		$idactividad = ($_POST['docencia_bilingue'] == 1) ? '636' : '1';
	}
	else {
		$idactividad = $codasignatura;
	}

	// OBTENEMOS DATOS DE LA DEPENDENCIA
	$result = mysqli_query($db_con, "SELECT DISTINCT `n_aula` FROM `horw` WHERE `a_aula` = '".$_POST['dependencia']."'");
	$datos_dependencia = mysqli_fetch_array($result);
	$coddependencia = $_POST['dependencia'];
	$nomdependencia = $datos_dependencia['n_aula'];

	// ACTUALIZAMOS LOS DATOS EN EL HORARIO
	$result = mysqli_query($db_con, "UPDATE `horw` SET `dia` = '$dia', `hora` = '$hora', `a_asig` = '$abrevasignatura', `asig` = '$nomasignatura', `c_asig` = '$codasignatura', `a_aula` = '$coddependencia', `n_aula` = '$nomdependencia', `a_grupo` = '$unidad', `idactividad` = '$idactividad' WHERE `dia` = '".$_GET['dia']."' AND `hora` = '".$_GET['hora']."' AND `a_grupo` = '".$_GET['unidad']."' AND `prof` = '$profesor' LIMIT 1");
	mysqli_query($db_con, "UPDATE `horw_faltas` SET `dia` = '$dia', `hora` = '$hora', `a_asig` = '$abrevasignatura', `asig` = '$nomasignatura', `c_asig` = '$codasignatura', `a_aula` = '$coddependencia', `n_aula` = '$nomdependencia', `a_grupo` = '$unidad', `idactividad` = '$idactividad' WHERE `dia` = '".$_GET['dia']."' AND `hora` = '".$_GET['hora']."' AND `a_grupo` = '".$_GET['unidad']."' AND `prof` = '$profesor' LIMIT 1");

	if (! $result) {
		$msg_error = "Error al modificar el horario. Error: ".mysqli_error($db_con);
	}
	else {

		$result_profesor = mysqli_query($db_con, "SELECT DISTINCT `prof`, `asig`, `a_grupo` FROM `horw` WHERE `prof` = '$profesor' AND `a_grupo` IN (SELECT `nomunidad` FROM `unidades`) AND `c_asig` <> '2' ORDER BY `prof` ASC");
		while ($datos_profesor = mysqli_fetch_array($result_profesor)) {
			$materia = $datos_profesor['asig'];
			$grupo = $datos_profesor['a_grupo'];
			$profesor = $datos_profesor['prof'];

			$result_cursos = mysqli_query($db_con,"SELECT DISTINCT `curso` FROM `alma` WHERE `unidad` = '$grupo'");
			$datos_cursos = mysqli_fetch_array($result_cursos);
			$nivel = $datos_cursos['curso'];

			if (! empty($nivel) && empty($grupo)) {
				mysqli_query($db_con, "INSERT INTO `profesores` (`nivel`,`materia`,`grupo`,`profesor`) VALUES ('$nivel', '$materia', '$grupo', '$profesor')");
			}
		}

		$_SESSION['msg_success'] = 1;
		header('Location:'.'index.php');
	}
}

if (isset($_POST['eliminar'])) {
	$dia = $_GET['dia'];
	$hora = $_GET['hora'];
	$unidad_curso = $_GET['unidad'];
	$asig = $_GET['asignatura'];
	$exp_unidad = explode('|', $unidad_curso);
	$unidad = $exp_unidad[0];

	$result = mysqli_query($db_con, "DELETE FROM `horw` WHERE `dia` = '$dia' AND `hora` = '$hora' AND `a_grupo` = '$unidad' AND `prof` = '$profesor' LIMIT 1");
	mysqli_query($db_con, "DELETE FROM `horw_faltas` WHERE `dia` = '$dia' AND `hora` = '$hora' AND `a_grupo` = '$unidad' AND `prof` = '$profesor' LIMIT 1");

	// Borramos datos de las tablas y profesores si el grupo ( materia desaparece completamente del horario.
	$control_profesores = mysqli_query($db_con, "SELECT * FROM `horw` WHERE  `a_grupo` = '$unidad' AND `prof` = '$profesor' and c_asig = '$asig'");
	if (!mysqli_num_rows($control_profesores)>0) {
	mysqli_query($db_con, "DELETE FROM `profesores` WHERE `grupo` = '$unidad' AND `profesor` = '$profesor' and materia like (select distinct nombre from asignaturas where codigo = '$asig' limit 1) LIMIT 1");
	mysqli_query($db_con, "DELETE FROM `grupos` WHERE `curso` = '$unidad' AND `profesor` = '$profesor' and asignatura like '$asig' LIMIT 1");
		}


	if (! $result) {
		$msg_error = "Error al modificar el horario. Error: ".mysqli_error($db_con);
	}
	else {
		$_SESSION['msg_success'] = 1;
		header('Location:'.'index.php');
	}
}

include("../../../menu.php");
?>


<div class="container">

	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2>Administración <small>Modificación de horarios</small></h2>
	</div>


	<?php if(isset($msg_error)): ?>
	<div class="alert alert-danger">
		<?php echo $msg_error; ?>
	</div>
	<?php endif; ?>

	<?php if(isset($_SESSION['msg_success']) && $_SESSION['msg_success']): ?>
	<div class="alert alert-success">
		El horario ha sido modificado.
	</div>
	<?php unset($_SESSION['msg_success']); ?>
	<?php endif; ?>


	<!-- SCAFFOLDING -->
	<div class="row">

		<!-- COLUMNA IZQUIERDA -->
		<div class="col-sm-5">

			<div class="well">

				<form enctype="multipart/form-data" method="post" action="">
					<fieldset>
						<legend>Horario regular</legend>

						<?php if (stristr($_SESSION['cargo'],'1') == TRUE): ?>
						<div class="form-group">
						  <label for="profesor">Profesor/a</label>
						  <select class="form-control" id="profesor" name="profesor" onchange="submit()">
						  	<option value=""></option>
						  	<?php $result = mysqli_query($db_con, "SELECT `nombre`, `departamento` FROM `departamentos` WHERE `departamento` <> 'Admin' AND `departamento` <> 'Administracion' AND `departamento` <> 'Conserjeria' AND `departamento` <> 'Educador' AND `departamento` <> 'Servicio Técnico y/o Mantenimiento' ORDER BY `nombre` ASC"); ?>
						  	<?php while ($row = mysqli_fetch_array($result)): ?>
						  	<option value="<?php echo $row['nombre']; ?>" <?php echo (isset($profesor) && $row['nombre'] == $profesor) ? 'selected' : ''; ?>><?php echo $row['nombre']; ?></option>
						  	<?php endwhile; ?>
						  </select>
						</div>

						<hr>
						<?php else: ?>
						<input type="hidden" name="profesor" value="<?php echo $profesor; ?>">
						<?php endif; ?>

						<div class="form-group">
						  <label for="unidad">Unidad</label>
						  <select class="form-control" id="unidad" name="unidad" onchange="submit()">
						  	<option value=""></option>
						  	<?php $result = mysqli_query($db_con, "SELECT `unidades`.`nomunidad`, `cursos`.`nomcurso` FROM `unidades` JOIN `cursos` ON `unidades`.`idcurso` = `cursos`.`idcurso` ORDER BY `unidades`.`idunidad` ASC"); ?>
						  	<?php while ($row = mysqli_fetch_array($result)): ?>
						  	<option value="<?php echo $row['nomunidad'].'|'.$row['nomcurso']; ?>" <?php echo (isset($unidad_curso) && $row['nomunidad'].'|'.$row['nomcurso'] == $unidad_curso) ? 'selected' : ''; ?>><?php echo $row['nomunidad'].' ('.$row['nomcurso'].')'; ?></option>
						  	<?php endwhile; ?>
						  </select>
						</div>

						<div class="form-group">
						  <label for="asignatura">Asignatura / Actividad</label>
						  <select class="form-control" id="asignatura" name="asignatura">
						 	<option value=""></option>
						 		<?php if ($unidad):
						 		if (stristr($curso, "Bachillerato") == FALSE && stristr($curso, "E.S.O.") == FALSE) {
						 			$extra_unidad = "";
						 			$extra_curso = "AND `curso` LIKE '%".substr($curso, 1)."%'";
						 		}
						 		else{
						 			$extra_unidad = "AND `grupo` = '$unidad'";
						 			$extra_curso = "AND `curso` = '$curso'";
						 		}
						 		?>
						  	<optgroup label="Asignaturas">
						  		<?php $result = mysqli_query($db_con, "SELECT `codigo`, `nombre`, `abrev`, `curso` FROM `materias` WHERE `codigo` <> '' AND `abrev` NOT LIKE '%\_%' $extra_curso $extra_unidad ORDER BY `curso` ASC, `nombre` ASC"); ?>
				  		  	<?php while ($row = mysqli_fetch_array($result)): ?>
				  		  	<option value="<?php echo $row['codigo']; ?>" <?php echo (isset($asignatura) && $row['codigo'] == $asignatura) ? 'selected' : ''; ?>><?php echo $row['curso'].' - '.$row['nombre'].' ('.$row['abrev'].')'; ?></option>
				  		  	<?php endwhile; ?>
					  		</optgroup>
					  		<?php endif; ?>
						  	<optgroup label="Actividades">
						  		<?php if ($unidad): ?>
							  	<?php $result = mysqli_query($db_con, "SELECT DISTINCT `idactividad`, `nomactividad` FROM `actividades_seneca` WHERE `requnidadactividad` = 'S' AND `nomactividad` NOT LIKE 'Docencia%' ORDER BY `nomactividad` ASC"); ?>
							  	<?php else: ?>
							  	<?php $result = mysqli_query($db_con, "SELECT DISTINCT `idactividad`, `nomactividad` FROM `actividades_seneca` WHERE `requnidadactividad` = 'N' AND `nomactividad` NOT LIKE 'Docencia%' ORDER BY `nomactividad` ASC"); ?>
							  	<?php endif; ?>
							  	<?php while ($row = mysqli_fetch_array($result)): ?>
							  	<option value="<?php echo $row['idactividad']; ?>" <?php echo (isset($asignatura) && $row['idactividad'] == $asignatura) ? 'selected' : ''; ?>><?php echo $row['nomactividad']; ?></option>
							  	<?php endwhile; ?>
							  	<?php if (!$unidad): ?>
							  	<option value="GUC">Servicio de Guardia (Aula de Convivencia)</option>
							  	<?php endif; ?>
						  	</optgroup>
						  </select>
						</div>

						<?php if (isset($unidad) && ! empty($unidad)): ?>
						<div class="form-group">
							<div class="checkbox">
								<label for="docencia_bilingue">
									<input type="checkbox" id="docencia_bilingue" name="docencia_bilingue" value="1"<?php echo (isset($_GET['docencia_bilingue']) && $_GET['docencia_bilingue'] == 1) ? ' checked' : ''; ?>> <strong>Docencia bilingüe</strong>
								</label>
							</div>
						</div>
						<?php endif; ?>

						<?php $arrdias = array(1=>'Lunes',2=>'Martes',3=>'Miércoles',4=>'Jueves',5=>'Viernes'); ?>
						<?php if (isset($dia) && ! empty($dia)): ?>
						<div class="form-group">
						  <label for="dia">Día de la semana</label>
						  <select class="form-control" id="dia" name="dia">
						  	<option value=""></option>
						  	<?php foreach ($arrdias as $numdia => $nomdia): ?>
						  	<option value="<?php echo $numdia; ?>" <?php echo (isset($dia) && $numdia == $dia) ? 'selected' : ''; ?>><?php echo $nomdia; ?></option>
						  	<?php endforeach; ?>
						  </select>
						</div>
						<?php endif; ?>

						<div class="form-group">
						  <?php if ((isset($dia) && ! empty($dia)) && (isset($hora) && ! empty($hora))): ?>
						  <label for="hora">Hora</label>
						  <select class="form-control" id="hora" name="hora">
						  	<option value=""></option>
						  	<?php $result_horas = mysqli_query($db_con,"SELECT `hora_inicio`, `hora_fin`, `hora` FROM `tramos` ORDER BY `idjornada` ASC, `horini` ASC"); ?>
							<?php while ($horas = mysqli_fetch_array($result_horas)): ?>
							<option value="<?php echo $horas['hora']; ?>" <?php echo (isset($hora) && $horas['hora'] == $hora) ? 'selected' : ''; ?>><?php echo $horas['hora_inicio'].' - '.$horas['hora_fin'].' ('.$horas['hora'].')'; ?></option>
							<?php endwhile; ?>
						  </select>
						  <?php else: ?>
						  <label>Días de la semana y horas</label>
						  <div class="row">
							<?php foreach ($arrdias as $numdia => $nomdia): ?>
							<div class="col-sm-20">
								<label><small><?php echo $nomdia; ?></small></label>
								<?php $i_sep = 1; ?>
								<?php $jornada_aux = ""; ?>
								<?php $result_horas = mysqli_query($db_con,"SELECT `hora_inicio`, `hora_fin`, `hora`, `idjornada` FROM `tramos` ORDER BY `idjornada` ASC, `horini` ASC"); ?>
								<?php while ($horas = mysqli_fetch_array($result_horas)): ?>
								<?php if ($i_sep > 1 && $jornada_aux != $horas['idjornada']): ?>
								<hr style="border: 1px solid #000;">
								<?php endif; ?>
								<div class="checkbox">
									<label>
										<input type="checkbox" name="hora_<?php echo $numdia; ?>_<?php echo $horas['hora']; ?>"> <?php echo $horas['hora']; ?><?php echo ($horas['hora'] != 'R' && $horas['hora'] != 'Rn') ? 'ª' : ''; ?>
									</label>
								</div>
								<?php $jornada_aux = $horas['idjornada']; ?>
								<?php $i_sep++; ?>
								<?php endwhile; ?>
							</div>
							<?php endforeach; ?>
						  </div>
						  <?php endif; ?>
						</div>

						<div class="form-group">
						  <label for="dependencia">Aula</label>
						  <?php $ocultar_dependencias_seneca = TRUE; ?>
						  <select class="form-control" id="dependencia" name="dependencia">
						  	<option value=""></option>
						  	<?php $result = mysqli_query($db_con, "SELECT DISTINCT `a_aula`, `n_aula` FROM `horw` WHERE `a_aula` <> `n_aula` AND `n_aula` <> '' ORDER BY `n_aula`"); $aulas_hor = mysqli_num_rows($result);?>
						  	<?php $result2 = mysqli_query($db_con, "SELECT `nomdependencia`, `descdependencia` FROM `dependencias` ORDER BY `nomdependencia` ASC"); $aulas_dep = mysqli_num_rows($result2);?>
						  	<?php if($aulas_hor): ?>
						  	<?php if($aulas_hor >= $aulas_dep) $ocultar_dependencias_seneca = FALSE; ?>
						  	<optgroup label="Aulas registradas en Horw">
							  	<?php while ($row = mysqli_fetch_array($result)): ?>
							  	<option value="<?php echo $row['a_aula']; ?>" <?php echo (isset($dependencia) && $row['a_aula'] == $dependencia) ? 'selected' : ''; ?>><?php echo $row['n_aula']; ?></option>
							  	<?php endwhile; ?>
						  	</optgroup>
						  	<?php endif; ?>

						  	<?php if($ocultar_dependencias_seneca): ?>
						  	<?php $result = mysqli_query($db_con, "SELECT `nomdependencia`, `descdependencia` FROM `dependencias` ORDER BY `nomdependencia` ASC"); ?>
					  		<?php if(mysqli_num_rows($result)): ?>
					  		<optgroup label="Aulas registradas en Séneca">
						  	  	<?php while ($row = mysqli_fetch_array($result)): ?>
						  	  	<option value="<?php echo $row['nomdependencia']; ?>" <?php echo (isset($dependencia) && $row['nomdependencia'] == $dependencia) ? 'selected' : ''; ?>><?php echo $row['descdependencia']; ?></option>
						  	  	<?php endwhile; ?>
					  		</optgroup>
					  		<?php endif; ?>
					  		<?php endif; ?>
						  </select>
						</div>

						<br>

					  	<?php if (isset($_GET['dia']) && isset($_GET['hora'])): ?>
					  	<button type="submit" class="btn btn-primary" name="actualizar">Actualizar</button>
					  	<button type="submit" class="btn btn-danger" name="eliminar">Eliminar</button>
					  	<a href="index.php" class="btn btn-default">Nuevo</a>
					  	<?php else: ?>
					  	<button type="submit" class="btn btn-primary" name="enviar">Añadir</button>
					  	<a class="btn btn-default" href="../../index.php">Volver</a>
					  	<?php endif; ?>

				  </fieldset>
				</form>

			</div><!-- /.well -->

		</div><!-- /.col-sm-5 -->


		<div class="col-sm-7">

			<h3><?php echo $profesor; ?></h3>

			<div class="table-responsive">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>&nbsp;</th>
							<th>Lunes</th>
							<th>Martes</th>
							<th>Miércoles</th>
							<th>Jueves</th>
							<th>Viernes</th>
						</tr>
					</thead>
					<tbody>
					<?php $i_sep = 1; ?>
					<?php $jornada_aux = ""; ?>
					<?php $thoras = array(); ?>
					<?php $result_horas = mysqli_query($db_con,"SELECT `hora`, `hora_inicio`, `hora_fin`, `idjornada` FROM `tramos` ORDER BY `idjornada` ASC, `horini` ASC"); ?>
					<?php while ($row = mysqli_fetch_array($result_horas)): ?>
					<?php array_push($thoras, $row); ?>
					<?php endwhile; ?>

					<?php foreach($thoras as $thora): ?>
						<?php if ($i_sep > 1 && $jornada_aux != $thora['idjornada']): ?>
						<tr style="border-top: 2px solid #000;">
						<?php else: ?>
						<tr>
						<?php endif; ?>
							<th>
							<?php echo ($thora['hora'] != 'R' && $thora['hora'] != 'Rn') ? $thora['hora'].'ª' : $thora['hora']; ?>
							<hr style="margin: 5px 0;">
							<small><?php echo substr($thora['hora_inicio'], 0, 5); ?><br><?php echo substr($thora['hora_fin'], 0, 5); ?></small>
							</th>
							<?php for($i = 1; $i < 6; $i++): ?>
							<?php $result = mysqli_query($db_con, "SELECT DISTINCT `a_asig`, `asig`, `c_asig`, `a_grupo`, `a_aula`, `n_aula`, `idactividad` FROM `horw` WHERE `prof` = '$profesor' AND `dia` = '$i' AND `hora` = '".$thora['hora']."'"); ?>
							<td width="20%">
					 			<?php while($row = mysqli_fetch_array($result)): ?>
					 			<abbr data-bs="tooltip" title="<?php echo $row['asig']; ?>"><?php echo $row['a_asig']; ?></abbr> <?php echo ($row['idactividad'] == '636') ? '<sup><span class="badge" style="font-size: 0.75em;">Bilingüe</span></sup>' : ''; ?><br>
					 			<?php echo (!empty($row['n_aula']) && $row['n_aula'] != 'Sin asignar o sin aula' && $row['n_aula'] != ' ' || $row['a_aula'] != ' ') ? '<abbr class="pull-right text-danger" data-bs="tooltip" title="'.$row['n_aula'].'">'.$row['a_aula'].'</abbr>' : ''; ?>
					 			<?php echo (!empty($row['a_grupo'])) ? '<span class="text-warning">'.$row['a_grupo'].'</span>' : ''; ?><br>
					 			<a href="index.php?dia=<?php echo $i; ?>&hora=<?php echo $thora['hora']; ?>&unidad=<?php echo $row['a_grupo']; ?>&asignatura=<?php echo $row['c_asig']; ?><?php echo ($row['idactividad'] == '636') ? '&docencia_bilingue=1' : ''; ?>&dependencia=<?php echo $row['a_aula']; ?>"><span class="far fa-edit fa-fw fa-lg"></span></a>
				 				<?php echo '<hr>'; ?>
					 			<?php endwhile; ?>
					 			<?php mysqli_free_result($result); ?>
					 		</td>
					 		<?php endfor; ?>
					 	</tr>
						<?php $jornada_aux = $thora['idjornada']; ?>
						<?php $i_sep++; ?>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<?php if (acl_permiso($_SESSION['cargo'], array('1'))): ?>
			<?php if (isset($idprofesor) && ! empty($idprofesor)): ?>
			<form action="exportar.php" method="POST" style="display: inline-block;">
				<input type="hidden" name="idempleado" value="<?php echo $idprofesor; ?>">
				<button type="submit" class="btn btn-info" name="exportar">Exportar horario individual <span class="badge">BETA</span></button>
			</form>
			<a class="btn btn-info" href="exportar.php">Exportar horarios <span class="badge">BETA</span></a>
			<?php else: ?>
			<div class="alert alert-info">
				<strong>Aviso:</strong> No se ha encontrado el código de empleado en la base de datos.
			</div>
			<?php endif; ?>
			<?php endif; ?>

		</div><!-- /.col-sm-7 -->

	</div><!-- /.row -->

</div><!-- /.container -->

<?php include("../../../pie.php"); ?>

</body>
</html>
