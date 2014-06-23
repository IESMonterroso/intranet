<?
include ("../../config.php");
require_once("../../pdf/dompdf_config.inc.php"); 
?>
<?
$n_preg=15;
// Miembros
//$depto=$_SESSION ['dpt'];

$campos=array('p1','p2','p3','p4','p5','p6','p7','p8','p9','p10','p11','p12','p13','p14','p15','p16','p17','p18','p19','p20');
#Preguntas
$pregunta[1]='1.1. Composici�n.';
$nota[1]='Miembros del Departamento, reparto de asignaturas, etc...';
$pregunta[2]='1.2. Reuniones del Departamento.';
$nota[2]='N�mero de reuniones aprox., asuntos y acuerdos m�s importantes.';
$pregunta[3]='1.3. Consideraciones generales.';
$nota[3]='Valoraci�n general del funcionamiento del Departamento.';
$pregunta[4]='2. An�lisis y propuestas de mejora de los resultados acad�micos.';
$nota[4]='';
$pregunta[5]='3. Seguimiento de la programaci�n.';
$nota[5]='An�lisis y descripci�n del grado de consecuci�n de los objetivos propuestos en la programaci�n por asignatura y grupo.';
$pregunta[6]='4.1.�Se han aplicado y revisado los criterios de evaluaci�n de cada asignatura?, �C�mo?. Indica los avances y dificultades m�s significativas al respecto.';
$nota[6]='';
$pregunta[7]='4.2.�Se concretaron objetivos y contenidos m�nimos al principio de curso? En caso afirmativo, �qu� consecuencia ha tenido esta medida?';
$nota[7]='';
$pregunta[8]='5.1. Alumnos con materias pendientes de otros cursos.';
$nota[8]='Metodolog�a. An�lisis de los resultados y propuestas de mejora.';
$pregunta[9]='5.2. Adaptaciones curriculares.';
$nota[9]='�Ha sido necesario aplicar alguna ACIs? En caso afirmativo, indicar alumno, nivel, asignatura y resultado.';
$pregunta[10]='6. Proyecto TIC.';
$nota[10]='Aplicaci�n de las TIC en el aula, valoraci�n y sugerencias.';
$pregunta[11]='7. PLan de Lectura';
$nota[11]='Acciones referentes al plan de lectura y valoraci�n.';
$pregunta[12]='8. Actividades complementarias y extraescolares.';
$nota[12]='Valoraci�n.';
$pregunta[13]='9. Material necesario.';
$nota[13]='Dentro de las circunstancias que rodean a nuestro Centro, �qu� material consideras que es necesario para poder impartir mejor tu(s) asignatura(s) el curso que viene?';
$pregunta[14]='10. Formaci�n';
$nota[14]='Propuestas de formaci�n.';
$pregunta[15]='11. Propuestas y comentarios generales.';
$nota[15]='Usa este espacio para cualquier cuesti�n que no est� contemplada en los puntos anteriores.';
$pregunta[16]='Esta es la 16� pregunta';
$nota[16]='';
$pregunta[17]='Esta es la 17� pregunta';
$nota[17]='';
$pregunta[18]='Esta es la 18� pregunta';
$nota[18]='';
$pregunta[19]='Esta es la 19� pregunta';
$nota[19]='';
$pregunta[20]='Esta es la 20� pregunta';
$nota[20]='';

if (isset($_POST['zprofe'])){$profe = $_POST['zprofe'];}

##########################
# Actualizaci�n de datos
##########################
# Se comprueba si hay env�o y se actualiza el registro correspondiente con update


##########################
# Fin Actualizaci�n de datos
###########################

###############
# Lectura de los datos de la memoria
##############

$sqlmem="SELECT * FROM mem_dep WHERE departamento='".$_GET['depto']."'";
//echo $sqlmem;
$datos_memoria= mysql_query($sqlmem);
$memoria = mysql_fetch_array($datos_memoria);

# Se le asigna a los campo un valor m�s manejable
for ( $i = 1 ; $i <= $n_preg ; $i ++) {
$p[$i]=$memoria[$i+1];
}
if ($memoria[1]!=''){$profe=$memoria[1];}

$html .= '<html><body>';
$html.='<script type="text/php"> 
 if ( isset($pdf) ) {

          $font = Font_Metrics::get_font("helvetica", "bold");
          $pdf->page_text(542, 775, "P�gina: {PAGE_NUM} de {PAGE_COUNT}", $font, 6, array(0,0,0));

        }
</script> '; 
$html.=  '<div style=" font: 8pt Helvetica, Arial, sans-serif; width: 720px; align: center;">';
#Cabecera
$html.=  '<div align=center><h1>' . $nombre_del_centro . '</h1><hr style="color:#eee; height:1px; width:720px;">';
$html.=  '<h2>Memoria final del Departamento<br /> '.$depto.'</h2>';
$html.=  '<h3>Curso: '.$curso_actual.'</h3><hr style="color:#eee; height:1px; width:720px;"></div>';

for ($i=1; $i<=$n_preg; $i++){
if ($i==1) {$html.=  "<p style='font-size:15px'>"."1. Aspectos organizativos del departamento"."</p>";}
if ($i==6) {$html.=  "<p style='font-size:15px'>"."4. Criterios de Evaluaci�n."."</p>";}
if ($i==8) {$html.=  "<p style='font-size:15px'>"."5. Medidas de atenci�n a la diversidad."."</p>";}
$html.=  "<p style='font-size:13px'>".$pregunta[$i]."</p>";
$html.=  "<p style='font-size:11px'>".$nota[$i]."</p>";
$html.=  '<div style="border:1px solid #aaa; padding: 10px; width:695px;">';
$html.=  $p[$i].'</div><br>';
}

####################
# Fin de la lectura de datos de la memoria
####################

$html.=  '<table style="border:0px; padding: 1px;font: 8pt Arial, Helvetica, sans-serif;"><tr><td style="border:0px; padding: 10px;">';

$html.=  '</td></tr></table>';

# Firma del tutor/a
include_once '../../funciones.php';
$html.=  '<div align=center><br><br>'.$localidad_del_centro.', '.formatea_fecha(date("Y/m/d"));
$html.=  '<br><br><br><br>';
$html.=  'Fdo.: '.$memoria[1];

$html.=  '</div></div></body></html>';




$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("Memoria del Departamento de $depto.pdf", array("Attachment" => 0));
mysql_close();
?>
