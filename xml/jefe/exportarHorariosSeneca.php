<?
    $directorio = "../../varios/";
	$archivo = "Importacion_horarios_Seneca.xml";
	$archivo_origen = $HorExpSen;
	
	if ($depurar==1) {
		include_once '../../menu.php';
		echo '<br />
<div class="page-header">
<h2>Administraci�n. <small> Preparaci�n de la Importaci�n del Horario a S�neca</small></h2>
</div>
<br />
<div class="container">

<div class="row">

<div class="col-sm-6 col-sm-offset-3">';
				echo "<legend align='center'>Errores y Advertencias sobre el Horario.</legend>";
		
		echo  "<div align'center><div class='alert alert-danger alert-block fade in'><br />
            <button type='button' class='close' data-dismiss='alert'>&times;</button>
El archivo generado por Horwin ha sido procesado y se ha creado una copia modificada preparada para subir a S�neca. 
<br>Los mesajes que aparecen m�s abajo indican los cambios realizados y las advertencias sobre problemas que podr�as encontrar al importar los datos a S�neca.
</div></div>";		
	}
	
	$doc = new DOMDocument('1.0', 'utf-8');
	
	$doc->load( $archivo_origen ) or die("No se ha podido leer el archivo para ser procesado.");

	$profes = $doc->getElementsByTagName( "grupo_datos");

	foreach ($profes as $materia) {
		$texto="";
		$codigos = $materia->getElementsByTagName( "dato" );

		$N_DIASEMANA=$codigos->item(0)->nodeValue;
		$X_TRAMO=$codigos->item(1)->nodeValue;
		$X_DEPENDENCIA=$codigos->item(2)->nodeValue;
		$X_UNIDAD=$codigos->item(3)->nodeValue;
		$X_OFERTAMATRIG=$codigos->item(4)->nodeValue;
		$X_MATERIAOMG=$codigos->item(5)->nodeValue;
		$F_INICIO=$codigos->item(6)->nodeValue;
		$F_FIN=$codigos->item(7)->nodeValue;
		$N_HORINI=$codigos->item(8)->nodeValue;
		$N_HORFIN=$codigos->item(9)->nodeValue;
		$X_ACTIVIDAD=$codigos->item(10)->nodeValue;

		if (strlen($X_UNIDAD)>0 and $X_UNIDAD > "3174012") {

			$un = mysql_query("select unidades.idcurso, nomcurso, nomunidad from unidades, cursos where unidades.idcurso = cursos.idcurso and idunidad = '$X_UNIDAD' order by unidades.idcurso, nomunidad");
			$uni = mysql_fetch_array($un);

			$asignatura="";

			$nombre_asig = mysql_query("select nombre from asignaturas where codigo = '$X_MATERIAOMG'");

			if ($nombre_asig) {
				$nombre_asigna= mysql_fetch_array($nombre_asig);

				$asig = mysql_query("select codigo, nombre from asignaturas  where curso = '$uni[1]' and nombre = '$nombre_asigna[0]'");


				if (mysql_num_rows($asig)>0) {

					while ($asignatur = mysql_fetch_array($asig)){
						$asignatura.=$asignatur[0].";";
						$nombre_asignatura = $asignatur[1];
					}
					if (stristr($asignatura,$X_MATERIAOMG)==FALSE) {
						if (strstr($texto,$asignatura)==FALSE) {
							$asig_corta = substr($asignatura,0,-1);
							if ($depurar==1) {
							echo  "<br /><div align'center><div class='alert alert-success alert-block fade in'><br />
            <button type='button' class='close' data-dismiss='alert'>&times;</button>
El c�digo de la asignatura <u>$X_MATERIAOMG</u> (<em>$nombre_asignatura</em>) no corresponde al Curso $uni[1], sino este c�digo: <strong>$asig_corta</strong>. <br><span clas='text-warning'>C�digo sustitu�do..</span>
</div></div>";	
							}				
							$codigos->item(5)->nodeValue = $asig_corta;
							$texto.=$asignatura.'';
						}

					}
				}
				else{
					if (strstr($texto,$X_MATERIAOMG)==FALSE) {
						if ($depurar==1) {
						echo  '<br /><div align="center"><div class="alert alert-warning alert-block fade in"><br />
            <button type="button" class="close" data-dismiss="alert">&times;</button>
No existe la asignatura <u>'.$X_MATERIAOMG.'</u> (<em>'.$nombre_asignatura.'</em>) en la tabla de asignaturas de'. $uni[1].'.
</div></div>';
						}
						$texto.=$X_MATERIAOMG.' ';
							
					}
					//echo $texto."<br>";
				}
			}
			else{
				if ($depurar==1) {
				echo  "<br /><div align'center><div class='alert alert-danger alert-block fade in'><br />
            <button type='button' class='close' data-dismiss='alert'>&times;</button>
<strong>$uni[2]</strong>: No existe ninguna asignatura con este c�digo (<u>$X_MATERIAOMG</u>) en la tabla de asignaturas de la base de datos.
</div></div>";	
				}
			}
		}

	}

	$contenido=$doc->saveXML();
	$directorio = "../../varios/";
	$archivo = "Importacion_horarios_Seneca.xml";
	$fopen = fopen($directorio.$archivo, "w");
	fwrite($fopen, $contenido);
	if ($depurar==1) {
		echo "<hr /><legend align='center'>Texto del archivo XML resultante</legend>";
	}
	header("Content-disposition: attachment; filename=$archivo");
	header("Content-type: application/octet-stream");
	readfile($directorio.$archivo);
	

?>