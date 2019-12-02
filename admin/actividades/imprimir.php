<?php
require('../../bootstrap.php');

if (file_exists("config.php")) {
  include("config.php");
}

$tutor = $_SESSION['profi'];

if (isset($_GET['id'])) {$id = $_GET['id'];}elseif (isset($_POST['id'])) {$id = $_POST['id'];}else{$id="";}
if (isset($_GET['eliminar'])) {$id = $_GET['eliminar'];}elseif (isset($_POST['eliminar'])) {$eliminar = $_POST['eliminar'];}else{$eliminar="";}
if (isset($_GET['enviar'])) {$enviar = $_GET['enviar'];}elseif (isset($_POST['enviar'])) {$enviar = $_POST['enviar'];}else{$enviar="";}
if (isset($_GET['crear'])) {$crear = $_GET['crear'];}elseif (isset($_POST['crear'])) {$crear = $_POST['crear'];}else{$crear="";}
if (isset($_GET['buscar'])) {$buscar = $_GET['buscar'];}elseif (isset($_POST['buscar'])) {$buscar = $_POST['buscar'];}else{$buscar="";}

if (isset($_GET['calendario'])) {$calendario = $_GET['calendario'];}elseif (isset($_POST['calendario'])) {$calendario = $_POST['calendario'];}else{$calendario="";}
if (isset($_GET['act_calendario'])) {$act_calendario = $_GET['act_calendario'];}elseif (isset($_POST['act_calendario'])) {$act_calendario = $_POST['act_calendario'];}else{$act_calendario="";}
if (isset($_GET['confirmado'])) {$confirmado = $_GET['confirmado'];}elseif (isset($_POST['confirmado'])) {$confirmado = $_POST['confirmado'];}else{$confirmado="";}
if (isset($_GET['detalles'])) {$detalles = $_GET['detalles'];}elseif (isset($_POST['detalles'])) {$detalles = $_POST['detalles'];}else{$detalles="";}
if (isset($_GET['lugar'])) {$lugar = $_GET['lugar'];}elseif (isset($_POST['lugar'])) {$lugar = $_POST['lugar'];}else{$lugar="";}
if (isset($_GET['fecha'])) {$fecha = $_GET['fecha'];}elseif (isset($_POST['fecha'])) {$fecha = $_POST['fecha'];}else{$fecha="";}
if (isset($_GET['fechafin'])) {$fechafin = $_GET['fechafin'];}elseif (isset($_POST['fechafin'])) {$fechafin = $_POST['fechafin'];}else{$fechafin="";}
if (isset($_GET['horario'])) {$horario = $_GET['horario'];}elseif (isset($_POST['horario'])) {$horario = $_POST['horario'];}else{$horario="";}
if (isset($_GET['horariofin'])) {$horariofin = $_GET['horariofin'];}elseif (isset($_POST['horariofin'])) {$horariofin = $_POST['horariofin'];}else{$horariofin="";}
if (isset($_GET['profesor'])) {$profesor = $_GET['profesor'];}elseif (isset($_POST['profesor'])) {$profesor = $_POST['profesor'];}else{$profesor="";}
if (isset($_GET['actividad'])) {$actividad = $_GET['actividad'];}elseif (isset($_POST['actividad'])) {$actividad = $_POST['actividad'];}else{$actividad="";}
if (isset($_GET['descripcion'])) {$descripcion = $_GET['descripcion'];}elseif (isset($_POST['descripcion'])) {$descripcion = $_POST['descripcion'];}else{$descripcion="";}
if (isset($_GET['observaciones'])) {$observaciones = $_GET['observaciones'];}elseif (isset($_POST['observaciones'])) {$observaciones = $_POST['observaciones'];}else{$observaciones="";}

if($fecha == $fechafin){
$fecha_act = $fecha;
}
else{
$fecha_act = $fecha.' - '.$fechafin;
}

$horario = substr($horario,0,-3);
$horariofin = substr($horariofin,0,-3);
if($horario == $horariofin){
$horario_act = $horario;
}
else{
$horario_act = $horario.' - '.$horariofin;
}

// PDF
$fecha2 = date('Y-m-d');
$hoy = formatea_fecha($fecha);

include("../../pdf/fpdf.php");
define('FPDF_FONTPATH','../../pdf/font/');

# creamos la clase extendida de fpdf.php
class GranPDF extends FPDF {
	function Header() {
    global $config;

		$this->SetTextColor(0, 122, 61);
		$this->Image( '../../img/encabezado.jpg',25,14,53,'','jpg');
		$this->SetFont('ErasDemiBT','B',10);
		$this->SetY(15);
		$this->Cell(75);
		$this->Cell(80,5,'CONSEJERÍA DE EDUCACIÓN Y DEPORTE',0,1);
		$this->SetFont('ErasMDBT','I',10);
		$this->Cell(75);
		$this->Cell(80,5,$config['centro_denominacion'],0,1);
		$this->SetTextColor(255, 255, 255);
	}
	function Footer() {
    global $config;
    
		$this->SetTextColor(0, 122, 61);
		$this->Image( '../../img/pie.jpg', 0, 245, 25, '', 'jpg' );
		$this->SetY(275);
		$this->SetFont('ErasMDBT','',8);
		$this->Cell(75);
		$this->Cell(80,4,$config['centro_direccion'].'. '.$config['centro_codpostal'].', '.$config['centro_localidad'].' ('.$config['centro_provincia'] .')',0,1);
		$this->Cell(75);
		$this->Cell(80,4,'Telf: '.$config['centro_telefono'].' '.(($config['centro_fax']) ? '   Fax: '.$config['centro_fax'] : ''),0,1);
		$this->Cell(75);
		$this->Cell(80,4,'Correo-e: '.$config['centro_email'],0,1);
		$this->SetTextColor(255, 255, 255);
	}
}

			# creamos el nuevo objeto partiendo de la clase
			$MiPDF=new GranPDF('P','mm','A4');
# creamos el nuevo objeto partiendo de la clase
$MiPDF=new GranPDF('P','mm','A4');
$MiPDF->AddFont('NewsGotT','','NewsGotT.php');
$MiPDF->AddFont('NewsGotT','B','NewsGotTb.php');
$MiPDF->AddFont('ErasDemiBT','','ErasDemiBT.php');
$MiPDF->AddFont('ErasDemiBT','B','ErasDemiBT.php');
$MiPDF->AddFont('ErasMDBT','','ErasMDBT.php');
$MiPDF->AddFont('ErasMDBT','I','ErasMDBT.php');


$MiPDF->SetMargins(25,20,20);
$MiPDF->SetDisplayMode('fullpage');


  $fecha1 = explode("-",$fecha);
  $dia = $fecha[0];
  $mes = $fecha[1];
  $ano = $fecha[2];

// Borramos registros anteriores
 mysqli_query($db_con,"delete from actividadalumno where cod_actividad='$id'");

  foreach($_POST as $key => $value)
  {
//  echo "$key --> $value<br>";
if(is_numeric(trim($key))){

$alumnos0 = "SELECT nombre, apellidos, padre, domicilio, codpostal, localidad, provinciaresidencia, dnitutor, unidad, matriculas, telefono, telefonourgencia FROM alma WHERE claveal = '$value'";
$alumnos1 = mysqli_query($db_con, $alumnos0);
while($alumno = mysqli_fetch_array($alumnos1))
{
	mysqli_query($db_con, "INSERT INTO actividadalumno (claveal,cod_actividad) VALUES ('".$value."','".$id."')");
	# insertamos la primera pagina del documento
	$MiPDF->Addpage();

	$autorizacion = "D./Dña. ".$alumno['padre']." con D.N.I ".$alumno['dnitutor'].", como representante legal de ".$alumno['nombre']." ".$alumno['apellidos'].", alumno/a de la unidad ".$alumno['unidad'].", asume la responsabilidad de que su hijo/a participe en la siguiente Actividad Complementaria y Extraescolar e igualmente autoriza a los profesores/as responsables a tomar cuantas medidas sean necesarias para conseguir un desarrollo adecuado de la actividad programada. El abajo firmante también autoriza de forma expresa e inequívoca la difusión de imágenes/vídeos en la web y redes sociales del Centro con fines didácticos y/o publicitarios.";

	$alergias = "Al mismo tiempo indico que mi hijo/a:
	__ Necesita tratamiento médico o medicación específica.
	__ Es alérgico a algún tipo de comidas.

	Observaciones:
	";

	// INFORMACION DE LA CARTA
	$MiPDF->SetY(45);
	$MiPDF->SetFont ( 'NewsGotT', '', 10 );
	$MiPDF->Cell(75, 5, 'Fecha:  '.date('d.m.Y'), 0, 0, 'L', 0 );
	$MiPDF->Cell(75, 5, $alumno['padre'], 0, 1, 'L', 0 );
	$MiPDF->Cell(75, 8, 'Ref.:     Act/'.$id, 0, 0, 'L', 0 );
	$MiPDF->Cell(75, 5, $alumno['domicilio'], 0, 1, 'L', 0 );
	$MiPDF->Cell(75, 0, '', 0, 0, 'L', 0 );
	$MiPDF->Cell(75, 5, $alumno['codpostal'].' '.$alumno['localidad'].', '.mb_strtoupper($alumno['provinciaresidencia'], 'UTF-8'), 0, 1, 'L', 0 );
	$MiPDF->Cell(0, 0, 'Asunto: '.$actividad, 0, 1, 'L', 0 );
	$MiPDF->Ln(7);

	$MiPDF->Multicell(0, 5, $autorizacion, 0, 'L', 0);
	$MiPDF->Ln(5);

	#Cuerpo.
	$MiPDF->SetFont('NewsGotT','B',10);
	$MiPDF->Cell(30, 5, 'Lugar: ', 0, 0, 'L');

	$MiPDF->SetFont('NewsGotT','',10);
	$MiPDF->Cell(130, 5, $lugar, 0, 1, 'L');

	$MiPDF->SetFont('NewsGotT','B',10);
	$MiPDF->Cell(30, 5, 'Fecha: ', 0, 0, 'L');

	$MiPDF->SetFont('NewsGotT','',10);
	$MiPDF->Cell(130, 5, $fecha_act, 0, 1, 'L');

	$MiPDF->SetFont('NewsGotT','B',10);
	$MiPDF->Cell(30, 5, 'Horario: ', 0, 0, 'L');

	$MiPDF->SetFont('NewsGotT','',10);
	$MiPDF->Cell(130, 5, $horario_act, 0, 1, 'L');

	$MiPDF->SetFont('NewsGotT','B',10);
	$MiPDF->Cell(30, 5, 'Actividad: ', 0, 0, 'L');

	$MiPDF->SetFont('NewsGotT','',10);
	$MiPDF->Cell(130, 5, $actividad, 0, 1, 'L');

	$MiPDF->SetFont('NewsGotT','B',10);
	$MiPDF->Cell(30, 5, 'Descripción: ', 0, 0, 'L');

	$MiPDF->SetFont('NewsGotT','',10);
	$MiPDF->SetY($MiPDF->GetY()+1);
	$MiPDF->SetX($MiPDF->GetX()+30);
	$MiPDF->MultiCell(130, 5, $descripcion, 0, 'L' , 0);

	$MiPDF->SetFont('NewsGotT','B',10);
	$MiPDF->Cell(30, 8, 'Observaciones: ', 0, 0, 'L');

	$MiPDF->SetFont('NewsGotT','',10);
	$MiPDF->SetY($MiPDF->GetY()+1);
	$MiPDF->SetX($MiPDF->GetX()+30);
	$MiPDF->MultiCell(130, 5, $observaciones, 0, 'L' , 0);

	$MiPDF->SetFont('NewsGotT','B',10);
	$MiPDF->Cell(30, 8, 'Profesor/es: ', 0, 0, 'L');

	$MiPDF->SetFont('NewsGotT','',10);
	$MiPDF->SetY($MiPDF->GetY()+1.5);
	$MiPDF->SetX($MiPDF->GetX()+30);
	$MiPDF->MultiCell(130, 5, $profesor, 0, 'L' , 0);
	$MiPDF->Ln(5);


	// EJEMPLAR PARA EL PROFESOR
	$txt_acuse = "D./Dña. ".$alumno['padre']." con D.N.I ".$alumno['dnitutor'].", como representante legal de ".$alumno['nombre']." ".$alumno['apellidos'].", alumno/a de la unidad ".$alumno['unidad'].", autoriza a su hijo/a a participar en la actividad $actividad con referencia Act/".$id.".";

	$MiPDF->Line(20, $MiPDF->GetY(), 190, $MiPDF->GetY());
	$MiPDF->Ln(3);

	$MiPDF->SetFont('NewsGotT', 'B', 10);
	$MiPDF->Multicell(0, 5, 'RECORTE POR LA LÍNEA Y ENTREGUE ESTE DOCUMENTO AL PROFESOR RESPONSABLE', 0, 'L', 0 );
	$MiPDF->Ln(3);

	$MiPDF->SetFont('NewsGotT', '', 10);
	$MiPDF->Multicell(0, 5, $txt_acuse, 0, 'L', 0 );
	$MiPDF->Ln(3);

	$MiPDF->SetFont('NewsGotT','B',10);
	$MiPDF->Cell(55, 8, 'Teléfonos de contacto con la familia:', 0, 0, 'L');
	$MiPDF->SetFont('NewsGotT', '', 10);
	$MiPDF->Cell(110, 8, $alumno['telefono'] . ' / ' . $alumno['telefonourgencia'], 0, 1, 'L');
	$MiPDF->Ln(3);

	$MiPDF->SetFont('NewsGotT','B',10);
	$MiPDF->MultiCell(0, 8, 'Información médica (marque con una X):', 0, 'L', 0);

	$MiPDF->SetFont('NewsGotT','',10);
	$MiPDF->Cell(10, 8, '____', 0, 0, 'L');
	$MiPDF->Cell(150, 8, ' Necesita tratamiento médico o medicación específica.', 0, 1, 'L');

	$MiPDF->Cell(10, 8, '____', 0, 0, 'L');
	$MiPDF->Cell(150, 8, ' Es alérgico a algún tipo de comida.', 0, 1, 'L');

	$MiPDF->SetFont('NewsGotT','',8);
	$MiPDF->MultiCell(0, 8, 'En el caso de haber marcado alguna opción, anote en el reverso del documento las circunstancias que concurren.', 0, 'L', 0);
	$MiPDF->Ln(5);

	$MiPDF->SetFont('NewsGotT','',10);
	$MiPDF->Cell (90, 5, '', 0, 0, 'C', 0 );
	$MiPDF->Cell (55, 5, 'Representante legal', 0, 1, 'C', 0 );
	$MiPDF->Cell (55, 20, '', 0, 0, 'C', 0 );
	$MiPDF->Cell (55, 20, '', 0, 1, 'C', 0 );
	$MiPDF->SetFont('NewsGotT', '', 10);
	$MiPDF->Cell (90, 5, '', 0, 0, 'C', 0 );
	$MiPDF->Cell (55, 0, 'Fdo. '.$alumno['padre'], 0, 1, 'C', 0 );



	}
}
}
$MiPDF->Output();

?>
