<?php
require('../../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

$empleados = array();

$actividades_seneca_noregular = array('661','156','289','286','284','5','346','285','36','780','96');

if (isset($_POST['idempleado'])) {
	$post_idempleado = $_POST['idempleado'];
	$post_idempleado = preg_replace('([^A-Za-z0-9 ])', '', $post_idempleado);
	$result_empleados = mysqli_query($db_con, "SELECT DISTINCT `c_prof` FROM `horw` WHERE `c_prof` = '$post_idempleado' LIMIT 1");

}
else {
	$result_empleados = mysqli_query($db_con, "SELECT DISTINCT `c_prof` FROM `horw` WHERE `c_prof` <> '' ORDER BY `prof` ASC");
}

while ($row_empleados = mysqli_fetch_array($result_empleados)) {
	$idempleado = $row_empleados['c_prof'];

	$horario_regular = array();
	$horario_noregular = array();
	$noregular_aux = array();

	$result = mysqli_query($db_con, "SELECT `dia`, `hora`, `c_asig`, `prof`, `a_aula`, `a_grupo`, `idactividad` FROM `horw` WHERE `c_prof` = '$idempleado' ORDER BY `dia` ASC, `hora` ASC");
	while ($row = mysqli_fetch_array($result)) {
		$num_dia = $row['dia'];
		$id_tramo = '';
		$id_dependencia = '';
		$id_unidad = '';
		$id_curso = '';
		$codigo_asignatura = '';
		$tramo_horini = '';
		$tramo_horfin = '';
		$id_actividad = $row['idactividad'];

		$result_empleado = mysqli_query($db_con, "SELECT `fechatoma`, `fechacese` FROM `departamentos` WHERE `nombre` = '".$row["prof"]."' LIMIT 1");
		$row_empleado = mysqli_fetch_array($result_empleado);
		$fecha_toma_posesion_exp = explode('-', $row_empleado['fechatoma']);
		$fecha_toma_posesion = $fecha_toma_posesion_exp['2'].'/'.$fecha_toma_posesion_exp['1'].'/'.$fecha_toma_posesion_exp['0'];

		$fecha_cese_posesion_exp = explode('-', $row_empleado['fechacese']);
		$fecha_cese_posesion = $fecha_cese_posesion_exp['2'].'/'.$fecha_cese_posesion_exp['1'].'/'.$fecha_cese_posesion_exp['0'];

		$result_tramo = mysqli_query($db_con, "SELECT `tramo`, `horini`, `horfin`, `hora_inicio`, `hora_fin` FROM `tramos` WHERE `hora` = '".$row["hora"]."' LIMIT 1");
		$row_tramo = mysqli_fetch_array($result_tramo);
		$id_tramo = $row_tramo['tramo'];
		$tramo_horini = $row_tramo['horini'];
		$tramo_horfin = $row_tramo['horfin'];

		$result_unidad = mysqli_query($db_con, "SELECT `idunidad` FROM `unidades` WHERE `nomunidad` = '".$row["a_grupo"]."'");
		$row_unidad = mysqli_fetch_array($result_unidad);

		// Comprobamos si la actividad requiere unidad y no es de tipo Docencia
		$result_actividad_seneca = mysqli_query($db_con, "SELECT `idactividad` FROM `actividades_seneca` WHERE `idactividad` = $id_actividad AND `requnidadactividad` = 'S' AND `nomactividad` NOT LIKE 'Docencia%' LIMIT 1");
		if (mysqli_num_rows($result_actividad_seneca)) {
			$result_unidad_curso = mysqli_query($db_con, "SELECT `idunidad`, `idcurso` FROM `unidades` WHERE `nomunidad` = '".$row["a_grupo"]."' LIMIT 1");
			if (mysqli_num_rows($result_unidad_curso)) {
				$row_unidad_curso = mysqli_fetch_array($result_unidad_curso);
				$id_curso = $row_unidad_curso['idcurso'];
				$id_unidad = $row_unidad_curso['idunidad'];
			}
		}
		else {
			$result_curso_por_asignatura = mysqli_query($db_con, "SELECT `idcurso` FROM `materias_seneca` WHERE `idmateria` = '".$row["c_asig"]."'  LIMIT 1");
			if (mysqli_num_rows($result_curso_por_asignatura)) {
				$row_curso_por_asignatura = mysqli_fetch_array($result_curso_por_asignatura);
				$id_curso = $row_curso_por_asignatura['idcurso'];
				$codigo_asignatura = $row['c_asig'];
			}

			$result_unidad_por_curso = mysqli_query($db_con, "SELECT `idunidad` FROM `unidades` WHERE `nomunidad` = '".$row["a_grupo"]."' AND `idcurso` = '".$id_curso."'");
			$row_unidad_por_curso = mysqli_fetch_array($result_unidad_por_curso);
			if ($row_unidad['idunidad'] != $row_unidad_por_curso['idunidad']) {
				$id_unidad = $row_unidad_por_curso['idunidad'];
			}
			else {
				$id_unidad = $row_unidad['idunidad'];
			}
		}

		$result_dependencia = mysqli_query($db_con, "SELECT `iddependencia` FROM `dependencias` WHERE `nomdependencia` = '".$row["a_aula"]."'");
		if (mysqli_num_rows($result_dependencia)) {
				$row_dependencia = mysqli_fetch_array($result_dependencia);
				$id_dependencia = $row_dependencia['iddependencia'];
		}

		$fecha_inicio_exp = explode('-', $config['curso_inicio']);
		$fecha_inicio = $fecha_inicio_exp['2'].'/'.$fecha_inicio_exp['1'].'/'.$fecha_inicio_exp['0'];

		$fecha_fin_exp = explode('-', $config['curso_fin']);
		$fecha_fin = $fecha_fin_exp['2'].'/'.$fecha_fin_exp['1'].'/'.$fecha_fin_exp['0'];

		if (in_array($row['c_asig'], $actividades_seneca_noregular) && ($row['a_grupo'] == "" || $row['a_aula'] == "")) {

			if (! in_array($row['c_asig'], $noregular_aux)) {
				array_push($noregular_aux, $row['c_asig']);

				$noregular = array(
					'codigo_actividad' 	=> $row['c_asig'],
					'numero_minutos'		=> ($row_tramo['horfin'] - $row_tramo['horini']),
				);

				array_push($horario_noregular, $noregular);
			}
			else {
				$clave_noregularaux = array_search($row['c_asig'], array_column($horario_noregular, 'codigo_actividad'));
				$horario_noregular[$clave_noregularaux]['numero_minutos'] += ($row_tramo['horfin'] - $row_tramo['horini']);
			}

		}
		else {

			$regular = array(
				'dia_semana'					=> $num_dia,
				'tramo_horario'				=> $id_tramo,
				'codigo_dependencia'	=> $id_dependencia,
				'codigo_unidad'				=> $id_unidad,
				'codigo_curso'				=> $id_curso,
				'codigo_asignatura'		=> $codigo_asignatura,
				'fecha_inicio'				=> '01/09/'.substr($config['curso_inicio'], 0, 4),
				'fecha_fin'						=> '31/08/'.(substr($config['curso_inicio'], 0, 4) + 1),
				'hora_inicio'					=> $tramo_horini,
				'hora_fin'						=> $tramo_horfin,
				'codigo_actividad' 		=> $id_actividad
			);

			array_push($horario_regular, $regular);
		}

	}
	unset($regular);
	unset($noregular);

	$empleado = array(
		'idempleado' 						=> $idempleado,
		'fecha_toma_posesion' 	=> $fecha_toma_posesion,
		'horario_no_regular'		=> $horario_noregular,
		'horario_regular'				=> $horario_regular,
	);
	array_push($empleados, $empleado);
}
unset($empleado);

// Generamos el archivo XML
$num_empleados = count($empleados);
if ($num_empleados) {
	if ($num_empleados > 1) {
		$filename = 'ExportacionHorario.xml';
	}
	else {
		$filename = 'ExportacionHorario_'.$idempleado.'.xml';
	}

	if (file_exists($filename)) unlink($filename);
	if (!$fp = fopen($filename, 'w+')) {
		die ("Error: No se puede crear o abrir el archivo ".$filename);
	}
	else {

		fwrite($fp, '<?xml version="1.0" encoding="iso-8859-1"?>
		<SERVICIO modulo="HORARIOS" tipo="I" autor="'.$config['centro_denominacion'].'" fecha="'.date('d/m/Y H:i:s').'">
			<BLOQUE_DATOS>
				<grupo_datos seq="ANNO_ACADEMICO">
					<dato nombre_dato="C_ANNO">'.substr($config['curso_actual'], 0, 4).'</dato>
				</grupo_datos>
				<grupo_datos seq="HORARIOS_NO_REGULARES" registros="'.$num_empleados.'">');
					$cont_empleado = 1;
					foreach ($empleados as $empleado):
						fwrite($fp, '
						<grupo_datos seq="HORARIO_NO_REGULAR_PROFESOR_'.$cont_empleado.'" registros="'.count($empleado['horario_no_regular']).'">
							<dato nombre_dato="X_EMPLEADO">'.$empleado['idempleado'].'</dato>
							<dato nombre_dato="F_TOMAPOS">'.$empleado['fecha_toma_posesion'].'</dato>');
							$i = 1;
							foreach ($empleado['horario_no_regular'] as $horario):
							fwrite($fp,'
							<grupo_datos seq="ACTIVIDAD_'.$i.'">
								<dato nombre_dato="X_ACTIVIDAD">'.$horario['codigo_actividad'].'</dato>
								<dato nombre_dato="N_MINSEN">'.$horario['numero_minutos'].'</dato>
							</grupo_datos>');
							$i++;
							endforeach;
							unset($horario);
							unset($i);
						fwrite($fp, '
						</grupo_datos>');
						$cont_empleado++;
					endforeach;
				fwrite($fp, '
				</grupo_datos>
				<grupo_datos seq="HORARIOS_REGULARES" registros="'.$num_empleados.'">');
				$cont_empleado = 1;
				foreach ($empleados as $empleado):
					fwrite($fp, '
					<grupo_datos seq="HORARIO_REGULAR_PROFESOR_'.$cont_empleado.'" registros="'.count($empleado['horario_regular']).'">
						<dato nombre_dato="X_EMPLEADO">'.$empleado['idempleado'].'</dato>
						<dato nombre_dato="F_TOMAPOS">'.$empleado['fecha_toma_posesion'].'</dato>');
						$i = 1;
						foreach ($empleado['horario_regular'] as $horario):
						fwrite($fp, '
						<grupo_datos seq="ACTIVIDAD_'.$i.'">
							<dato nombre_dato="N_DIASEMANA">'.$horario['dia_semana'].'</dato>
							<dato nombre_dato="X_TRAMO">'.$horario['tramo_horario'].'</dato>
							<dato nombre_dato="X_DEPENDENCIA">'.$horario['codigo_dependencia'].'</dato>
							<dato nombre_dato="X_UNIDAD">'.$horario['codigo_unidad'].'</dato>
							<dato nombre_dato="X_OFERTAMATRIG">'.$horario['codigo_curso'].'</dato>
							<dato nombre_dato="X_MATERIAOMG">'.$horario['codigo_asignatura'].'</dato>
							<dato nombre_dato="F_INICIO">'.$horario['fecha_inicio'].'</dato>
							<dato nombre_dato="F_FIN">'.$horario['fecha_fin'].'</dato>
							<dato nombre_dato="N_HORINI">'.$horario['hora_inicio'].'</dato>
							<dato nombre_dato="N_HORFIN">'.$horario['hora_fin'].'</dato>
							<dato nombre_dato="X_ACTIVIDAD">'.$horario['codigo_actividad'].'</dato>
						</grupo_datos>');
						$i++;
						endforeach;
					fwrite($fp, '
					</grupo_datos>');
					$cont_empleado++;
				endforeach;
				fwrite($fp, '
				</grupo_datos>
			</BLOQUE_DATOS>
		</SERVICIO>');

		fclose($fp);

		if (is_file($filename)) {
			$size = filesize($filename);
			if (function_exists('mime_content_type')) {
				$type = mime_content_type($filename);
			} else if (function_exists('finfo_file')) {
				$info = finfo_open(FILEINFO_MIME);
				$type = finfo_file($filename);
				finfo_close($info);
			}
			if ($type == '') {
				$type = "application/force-download";
			}
			// Set Headers
			header("Content-Type: $type");
			header("Content-Disposition: attachment; filename=$filename");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: " . $size);
			// Download File
			readfile($filename);
			unlink($filename);
		}

	}
}
else {
	die ('Error: No hay empleados candidatos para la exportación de horarios.');
}
?>
