<?php

/* ----------------------------------------------------------------------
	obtenerIdCurso: La funci�n devuelve el ID del curso
 * ----------------------------------------------------------------------*/
function obtenerIdCurso($curso) {
	include_once '../../config.php';
	$result = mysql_query("SELECT idcurso FROM cursos WHERE nomcurso='$curso' LIMIT 1");
	$idcurso = mysql_fetch_array($result);
	
	if(!$curso) $idcurso[0] = 'NULL';
		
	return $idcurso[0];
}


/* ----------------------------------------------------------------------
	obtenerIdUnidad: La funci�n devuelve el ID de la unidad
 * ----------------------------------------------------------------------*/
function obtenerIdUnidad($unidad) {
include_once '../../config.php';
	$result = mysql_query("SELECT idunidad FROM unidades WHERE nomunidad='$unidad' LIMIT 1");
	$idunidad = mysql_fetch_array($result);
	
	if(!$unidad) $idunidad[0] = 'NULL';
		
	return $idunidad[0];
}


/* ----------------------------------------------------------------------
	limpiarNombreDepartamento: La funci�n elimina caracteres y devuelve
	el nombre del departamento
 * ----------------------------------------------------------------------*/
function limpiarNombreDepartamento($departamento) {
	$departamento = str_replace("(Ingl�s)","",$departamento);
	$departamento = str_replace("(Franc�s)","",$departamento);
	$departamento = str_replace("(Alem�n)","",$departamento);
	$departamento = str_replace("P.E.S.","",$departamento);
	$departamento = str_replace("P.T.F.P","",$departamento);
	$departamento = str_replace("(Secundaria)","",$departamento);
	$departamento = str_replace("Laboral Religi�n (Sec-Ere) Jor.Completa","Religi�n",$departamento);
	for($i=1;$i<21;$i++) {
		$departamento = str_replace("Contr. Lab. Religi�n (Sec-Ere) $i Horas","Religi�n",$departamento);
	}
	$departamento = trim($departamento);
	$departamento = rtrim($departamento,'.');
	
	return $departamento;
}


/* ----------------------------------------------------------------------
	obtenerIdDepartamento: La funci�n devuelve el ID del departamento
 * ----------------------------------------------------------------------*/
function obtenerIdDepartamento($departamento) {
	include_once '../../config.php';
	
	// Limpiamos el nombre del departamento
	$departamento = limpiarNombreDepartamento($departamento);
	
	$result = mysql_query("SELECT iddepartamento FROM departamentos WHERE nomdepartamento='$departamento' LIMIT 1");
	$iddepartamento = mysql_fetch_array($result);
	
	if(!$departamento) $iddepartamento[0] = 'NULL';
		
	return $iddepartamento[0];
}


/* ----------------------------------------------------------------------
	importarDatos: La funci�n crea la tabla de alumnos, sistemas de
	calificaciones y relaci�n de matr�culas de los alumnos
 * ----------------------------------------------------------------------*/

function importarDatos() {
	include_once('../../config.php');
    $ExpGenHor = $_FILES['ExpGenHor']['tmp_name'];	
    $curso_escolar = $_POST['curso_escolar'];      	
    
	 // Cargamos el archivo XML
	$xml = simplexml_load_file($ExpGenHor);	
	// Comprobamos que se trata del archivo que necesitamos
	if($xml->attributes()->modulo != "HORARIOS" || $xml->attributes()->tipo != "E") {
		echo '<div class="row">
		  <div class="col-md-12">
		    <div class="alert alert-danger">
		      <span class="fa fa-times-circle fa-lg"></span> El archivo seleccionado no es correcto.
		    </div>
		  </div>
		</div>';
	}
	elseif(($curso =$xml->BLOQUE_DATOS->grupo_datos[0]->dato[0]) != substr($curso_escolar,0,4)) {
		echo "$curso => $curs_escolar";
		echo '<div class="row">
		  <div class="col-md-12">
		    <div class="alert alert-danger">
		      <span class="fa fa-times-circle fa-lg"></span> La informaci�n del archivo seleccionado no corresponde al curso escolar '.$curso_actual.'.
		    </div>
		  </div>
		</div>';
	}
	else { 
		
		// Obtenemos el total de registros a importar
		$total = 0;
		for ($i=1; $i<9; $i++) {
			$total += $xml->BLOQUE_DATOS->grupo_datos[$i]->attributes()->registros;
			if($i==9) $total += $xml->BLOQUE_DATOS->grupo_datos[8]->attributes()->registros;
		}
		$unid = 100/$total;
		
		/* ----------------------------------------------------------------------
			CREACI�N DE LA TABLA CURSOS
		 * ----------------------------------------------------------------------*/
mysql_query("CREATE TABLE IF NOT EXISTS `cursos` (
  `idcurso` int(12) unsigned NOT NULL,
  `nomcurso` varchar(80) COLLATE latin1_spanish_ci NOT NULL,
  PRIMARY KEY (`idcurso`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");
		
		$tabla = 'cursos'; // Descripci�n del trabajo para la barra de progreso
		
		mysql_query("TRUNCATE TABLE cursos") or die("No existe la tabla Cursos. No podemos continuar.");
		foreach ($xml->BLOQUE_DATOS->grupo_datos[1]->grupo_datos as $curso) {
			$idcurso = utf8_decode($curso->dato[0]);
			$nomcurso = utf8_decode($curso->dato[1]);
			
			$result = mysql_query("INSERT cursos (idcurso, nomcurso) VALUES ('$idcurso','$nomcurso')");
			if (!$result) echo '<span class="text-error">ERROR en la Importaci�n</span><br>';
			
			// Vac�a los b�feres de escritura de PHP
			
			
			flush();
			ob_flush();
		}
		
		
		/* ----------------------------------------------------------------------
			CREACI�N DE LA TABLA UNIDADES
		 * ----------------------------------------------------------------------*/
mysql_query("CREATE TABLE IF NOT EXISTS `unidades` (
  `idunidad` int(12) unsigned NOT NULL,
  `nomunidad` varchar(64) COLLATE latin1_spanish_ci NOT NULL,
  `idcurso` int(12) unsigned NOT NULL,
  PRIMARY KEY (`idunidad`,`idcurso`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");
		
		$tabla = 'unidades'; // Descripci�n del trabajo para la barra de progreso
		
		mysql_query("TRUNCATE TABLE unidades") or die ("No existe la tabla Unidades. No podemos continuar.");
		
		foreach ($xml->BLOQUE_DATOS->grupo_datos[7]->grupo_datos as $tramos) {
			
			$idunidad = utf8_decode($tramos->dato[0]);
			$nomunidad = utf8_decode($tramos->dato[1]);
			$idcurso = utf8_decode($tramos->dato[2]);
			
			$result = mysql_query("INSERT unidades (idunidad, nomunidad, idcurso) VALUES ('$idunidad','$nomunidad','$idcurso')");
			if (!$result) echo '<span class="text-error">ERROR '.mysql_errno().': '.mysql_error().'</span><br>';
			
			// Vac�a los b�feres de escritura de PHP
			
			
			flush();
			ob_flush();
		}
		
		
		/* ----------------------------------------------------------------------
			CREACI�N DE LA TABLA MATERIAS
		 * ----------------------------------------------------------------------*/
		mysql_query("CREATE TABLE IF NOT EXISTS `materias_seneca` (
  `idmateria` int(12) unsigned NOT NULL,
  `nommateria` varchar(80) COLLATE latin1_spanish_ci NOT NULL,
  `abrevmateria` varchar(8) COLLATE latin1_spanish_ci DEFAULT NULL,
  `idcurso` int(12) unsigned NOT NULL,
  PRIMARY KEY (`idmateria`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");
		
		$tabla = 'materias_seneca'; // Descripci�n del trabajo para la barra de progreso
		
		mysql_query("TRUNCATE TABLE materias_seneca") or die (mysql_error("No existe la tabla materias_seneca. No podemos continuar."));
		
		foreach ($xml->BLOQUE_DATOS->grupo_datos[2]->grupo_datos as $materias) {
		
			$idmateria = utf8_decode($materias->dato[1]);
			$nommateria = utf8_decode($materias->dato[2]);
			$idcurso = utf8_decode($materias->dato[0]);
			
			$result = mysql_query("SELECT nomcurso FROM cursos WHERE idcurso='$idcurso'");
			$nomcurso = mysql_fetch_array($result);
			
			$result = mysql_query("INSERT materias_seneca (idmateria, nommateria, idcurso) VALUES ('$nommateria','$idmateria','$idcurso')");
			if (!$result) echo '<span class="text-error">ERROR '.mysql_errno().': '.mysql_error().'</span><br>';
			
			// Vac�a los b�feres de escritura de PHP
			
			
			flush();
			ob_flush();
		}
		
		
		/* ----------------------------------------------------------------------
			CREACI�N DE LA TABLA ACTIVIDADES
		 * ----------------------------------------------------------------------*/
		mysql_query("CREATE TABLE IF NOT EXISTS `actividades_seneca` (
  `regactividad` char(1) COLLATE latin1_spanish_ci NOT NULL,
  `idactividad` int(12) unsigned NOT NULL,
  `nomactividad` varchar(100) COLLATE latin1_spanish_ci NOT NULL,
  `requnidadactividad` char(1) COLLATE latin1_spanish_ci NOT NULL,
  `reqmateriaactividad` char(1) COLLATE latin1_spanish_ci NOT NULL,
  PRIMARY KEY (`idactividad`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");
				
		$tabla = 'actividades_seneca'; // Descripci�n del trabajo para la barra de progreso
		
		mysql_query("TRUNCATE TABLE actividades_seneca") or die ("No existe la tabla actividades_seneca. No podemos continuar.");
	
		foreach ($xml->BLOQUE_DATOS->grupo_datos[3]->grupo_datos as $actividades) {
			
			$regular = utf8_decode($actividades->dato[0]);
			$idactividad = utf8_decode($actividades->dato[1]);
			$nomactividad = utf8_decode($actividades->dato[2]);
			$requnidad = utf8_decode($actividades->dato[3]);
			$reqmateria = utf8_decode($actividades->dato[4]);
			
			$result = mysql_query("INSERT actividades_seneca (regactividad, idactividad, nomactividad, requnidadactividad, reqmateriaactividad) VALUES ('$regular',$idactividad,'$nomactividad','$requnidad','$reqmateria')");
			if (!$result) echo '<span class="text-error">ERROR '.mysql_errno().': '.mysql_error().'</span><br>';
			
			// Vac�a los b�feres de escritura de PHP
			
			
			flush();
			ob_flush();
		}
		
		
		/* ----------------------------------------------------------------------
			CREACI�N DE LA TABLA DEPENDENCIAS
		 * ----------------------------------------------------------------------*/
		mysql_query("CREATE TABLE IF NOT EXISTS `dependencias` (
  `iddependencia` int(12) unsigned NOT NULL,
  `nomdependencia` varchar(30) COLLATE latin1_spanish_ci NOT NULL,
  `descdependencia` varchar(80) COLLATE latin1_spanish_ci DEFAULT NULL,
  `reservadependencia` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`iddependencia`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");
				
		$tabla = 'dependencias'; // Descripci�n del trabajo para la barra de progreso
		
		if($truncate) mysql_query("TRUNCATE TABLE dependencias") or die (mysql_error("No existe la tabla dependencias. No podemos continuar."));
	
		foreach ($xml->BLOQUE_DATOS->grupo_datos[4]->grupo_datos as $actividades) {
			
			$iddependencia = utf8_decode($actividades->dato[0]);
			$nomdependencia = utf8_decode($actividades->dato[1]);
			
			$result = mysql_query("INSERT dependencias (iddependencia, nomdependencia, descdependencia, reservadependencia) VALUES ('$iddependencia','$nomdependencia','$nomdependencia',0)");
			
			if(mysql_errno()==1062) mysql_query("UPDATE dependencias SET nomdependecia='$nomdependencia' WHERE iddependencia='$iddependencia'");
			elseif(mysql_errno()!=0) echo '<span class="text-error">ERROR '.mysql_errno().': '.mysql_error().'</span><br>';
			
			// Vac�a los b�feres de escritura de PHP
			
			
			flush();
			ob_flush();
		}
		
		
		/* ----------------------------------------------------------------------
			CREACI�N DE LA TABLA TRAMOS
		 * ----------------------------------------------------------------------*/
		
		mysql_query("CREATE TABLE IF NOT EXISTS tramos (
  `tramo` int(6) unsigned NOT NULL,
  `hora` varchar(80) COLLATE latin1_spanish_ci NOT NULL,
  `horini` int(4) unsigned NOT NULL,
  `horfin` int(4) unsigned NOT NULL,
  PRIMARY KEY (`tramo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");
		mysql_query("truncate TABLE tramos");				
		
		$tabla = 'tramos'; // Descripci�n del trabajo para la barra de progreso
				
		foreach ($xml->BLOQUE_DATOS->grupo_datos[6]->grupo_datos as $tramos) {
		
			$idtramo = utf8_decode($tramos->dato[0]);
			$nomtramo = utf8_decode($tramos->dato[1]);
			$horini = utf8_decode($tramos->dato[2]);
			$horfin = utf8_decode($tramos->dato[3]);
			
			$result = mysql_query("INSERT tramos (tramo, hora, horini, horfin) VALUES ('$idtramo','$nomtramo','$horini','$horfin')");
			if (!$result) echo '<span class="text-error">ERROR '.mysql_errno().': '.mysql_error().'</span><br>';
			
			// Vac�a los b�feres de escritura de PHP
			
			
			flush();
			ob_flush();
		}
		
		
		/* ----------------------------------------------------------------------
			CREACI�N DE LA TABLA PROFESORES
		 * ----------------------------------------------------------------------*/
		mysql_query("CREATE TABLE IF NOT EXISTS `profesores_seneca` (
  `idprofesor` int(9) unsigned NOT NULL,
  `ape1profesor` varchar(20) COLLATE latin1_spanish_ci NOT NULL,
  `ape2profesor` varchar(20) COLLATE latin1_spanish_ci NOT NULL,
  `nomprofesor` varchar(20) COLLATE latin1_spanish_ci NOT NULL,
  `deptoprofesor` varchar(80) COLLATE latin1_spanish_ci NOT NULL,
  `correoprofesor` varchar(80) COLLATE latin1_spanish_ci DEFAULT NULL,
  `telefonoprofesor` char(9) COLLATE latin1_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`idprofesor`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");
		
		$tabla = 'profesores_seneca'; // Descripci�n del trabajo para la barra de progreso
		
		//mysql_query("TRUNCATE TABLE profesores_seneca") or die (mysql_error("No existe la tabla profesores_seneca. No podemos continuar."));
	
		foreach ($xml->BLOQUE_DATOS->grupo_datos[8]->grupo_datos as $tramos) {
		
			$idprofesor = utf8_decode($tramos->dato[0]);
			$ape1profesor = utf8_decode($tramos->dato[3]);
			$ape2profesor = utf8_decode($tramos->dato[4]);
			$nomprofesor = utf8_decode($tramos->dato[5]);
			$deptoprofesor = limpiarNombreDepartamento(utf8_decode($tramos->dato[2]));
			
			$result = mysql_query("INSERT profesores_seneca (idprofesor, ape1profesor, ape2profesor, nomprofesor, deptoprofesor) VALUES ($idprofesor,'$ape1profesor','$ape2profesor','$nomprofesor','$deptoprofesor')");
			
			if(mysql_errno()==1062) mysql_query("UPDATE profesores_seneca SET ape1profesor='$ape1profesor', ape2profesor='$ape2profesor', nomprofesor='$nomprofesor', deptoprofesor='$deptoprofesor' WHERE idprofesor=$idprofesor");
			elseif(mysql_errno()!=0) echo '<span class="text-error">ERROR '.mysql_errno().': '.mysql_error().'</span><br>';
			
			// Vac�a los b�feres de escritura de PHP
			flush();
			ob_flush();
		}
		
			
		/* ----------------------------------------------------------------------
			CREACI�N DE LA TABLA DEPARTAMENTOS
		 * ----------------------------------------------------------------------*/
mysql_query("CREATE TABLE IF NOT EXISTS `departamentos_seneca` (
  `iddepartamento` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `nomdepartamento` varchar(80) COLLATE latin1_spanish_ci NOT NULL,
  PRIMARY KEY (`iddepartamento`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=1000");
			
		$tabla = 'departamentos_seneca'; // Descripci�n del trabajo para la barra de progreso
		
		mysql_query("TRUNCATE TABLE departamentos_seneca") or die (mysql_error("No existe la tabla Departamentos. No podemos continuar."));
			
		// A�ade el departamento para personal no docente
		$personal_no_docente = "Personal de Administraci�n y Servicios";
		mysql_query("INSERT into departamentos_seneca (nomdepartamento) values('$personal_no_docente')");
		mysql_query("INSERT into departamentos_seneca (nomdepartamento) select distinct deptoprofesor from profesores_seneca");
	}		
	                	
}
?>