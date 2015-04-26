<?
session_start();
include("../../config.php");
include_once('../../config/version.php');

// COMPROBAMOS LA SESION
if ($_SESSION['autentificado'] != 1) {
	$_SESSION = array();
	session_destroy();
	
	if(isset($_SERVER['HTTPS'])) {
	    if ($_SERVER["HTTPS"] == "on") {
	        header('Location:'.'https://'.$dominio.'/intranet/salir.php');
	        exit();
	    } 
	}
	else {
		header('Location:'.'http://'.$dominio.'/intranet/salir.php');
		exit();
	}
}

if($_SESSION['cambiar_clave']) {
	if(isset($_SERVER['HTTPS'])) {
	    if ($_SERVER["HTTPS"] == "on") {
	        header('Location:'.'https://'.$dominio.'/intranet/clave.php');
	        exit();
	    } 
	}
	else {
		header('Location:'.'http://'.$dominio.'/intranet/clave.php');
		exit();
	}
}


registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);


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
if (isset($_GET['fecha'])) {$fecha = $_GET['fecha'];}elseif (isset($_POST['fecha'])) {$fecha = $_POST['fecha'];}else{$fecha="";}
if (isset($_GET['horario'])) {$horario = $_GET['horario'];}elseif (isset($_POST['horario'])) {$horario = $_POST['horario'];}else{$horario="";}
if (isset($_GET['profesor'])) {$profesor = $_GET['profesor'];}elseif (isset($_POST['profesor'])) {$profesor = $_POST['profesor'];}else{$profesor="";}
if (isset($_GET['actividad'])) {$actividad = $_GET['actividad'];}elseif (isset($_POST['actividad'])) {$actividad = $_POST['actividad'];}else{$actividad="";}
if (isset($_GET['descripcion'])) {$descripcion = $_GET['descripcion'];}elseif (isset($_POST['descripcion'])) {$descripcion = $_POST['descripcion'];}else{$descripcion="";}
if (isset($_GET['observaciones'])) {$observaciones = $_GET['observaciones'];}elseif (isset($_POST['observaciones'])) {$observaciones = $_POST['observaciones'];}else{$observaciones="";}

include_once ("../../funciones.php"); 
// PDF
$fecha2 = date('Y-m-d');
$hoy = formatea_fecha($fecha);

include("../../pdf/fpdf.php");
define('FPDF_FONTPATH','../../pdf/font/');

// Variables globales para el encabezado y pie de pagina
$GLOBALS['CENTRO_NOMBRE'] = $nombre_del_centro;
$GLOBALS['CENTRO_DIRECCION'] = $direccion_del_centro;
$GLOBALS['CENTRO_CODPOSTAL'] = $codigo_postal_del_centro;
$GLOBALS['CENTRO_LOCALIDAD'] = $localidad_del_centro;
$GLOBALS['CENTRO_TELEFONO'] = $telefono_del_centro;
$GLOBALS['CENTRO_FAX'] = $fax_del_centro;
$GLOBALS['CENTRO_CORREO'] = $email_del_centro;


if(substr($codigo_postal_del_centro,0,2)=="04") $GLOBALS['CENTRO_PROVINCIA'] = 'Almería';
if(substr($codigo_postal_del_centro,0,2)=="11") $GLOBALS['CENTRO_PROVINCIA'] = 'Cádiz';
if(substr($codigo_postal_del_centro,0,2)=="14") $GLOBALS['CENTRO_PROVINCIA'] = 'Córdoba';
if(substr($codigo_postal_del_centro,0,2)=="18") $GLOBALS['CENTRO_PROVINCIA'] = 'Granada';
if(substr($codigo_postal_del_centro,0,2)=="21") $GLOBALS['CENTRO_PROVINCIA'] = 'Huelva';
if(substr($codigo_postal_del_centro,0,2)=="23") $GLOBALS['CENTRO_PROVINCIA'] = 'Jaén';
if(substr($codigo_postal_del_centro,0,2)=="29") $GLOBALS['CENTRO_PROVINCIA'] = 'Málaga';
if(substr($codigo_postal_del_centro,0,2)=="41") $GLOBALS['CENTRO_PROVINCIA'] = 'Sevilla';

# creamos la clase extendida de fpdf.php 
class GranPDF extends FPDF {
	function Header() {
		$this->SetTextColor(0, 122, 61);
		$this->Image( '../../img/encabezado.jpg',25,14,53,'','jpg');
		$this->SetFont('ErasDemiBT','B',10);
		$this->SetY(15);
		$this->Cell(75);
		$this->Cell(80,5,'CONSEJER�A DE EDUCACI�N, CULTURA Y DEPORTE',0,1);
		$this->SetFont('ErasMDBT','I',10);
		$this->Cell(75);
		$this->Cell(80,5,$GLOBALS['CENTRO_NOMBRE'],0,1);
		$this->SetTextColor(255, 255, 255);
	}
	function Footer() {
		$this->SetTextColor(0, 122, 61);
		$this->Image( '../../img/pie.jpg', 0, 245, 25, '', 'jpg' );
		$this->SetY(275);
		$this->SetFont('ErasMDBT','',8);
		$this->Cell(75);
		$this->Cell(80,4,$GLOBALS['CENTRO_DIRECCION'].'. '.$GLOBALS['CENTRO_CODPOSTAL'].', '.$GLOBALS['CENTRO_LOCALIDAD'].' ('.$GLOBALS['CENTRO_PROVINCIA'] .')',0,1);
		$this->Cell(75);
		$this->Cell(80,4,'Telf: '.$GLOBALS['CENTRO_TELEFONO'].'   Fax: '.$GLOBALS['CENTRO_FAX'],0,1);
		$this->Cell(75);
		$this->Cell(80,4,'Correo-e: '.$GLOBALS['CENTRO_CORREO'],0,1);
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
  foreach($_POST as $key => $value)
  { 
//  echo "$key --> $value<br>";
if(is_numeric(trim($key))){

$alumnos0 = "select alma.nombre, alma.apellidos, padre, domicilio, codpostal, localidad, provinciaresidencia, NC, dnitutor, alma.unidad, alma.matriculas, alma.telefono, alma.telefonourgencia from alma, FALUMNOS where alma.claveal = FALUMNOS.claveal and alma.claveal = '$value'";
$alumnos1 = mysqli_query($db_con, $alumnos0);
while($alumno = mysqli_fetch_array($alumnos1))
{
	mysqli_query($db_con,"delete from actividadalumno where claveal='$value' and cod_actividad='$id'");
	mysqli_query($db_con, "insert into actividadalumno (claveal,cod_actividad) values ('".$value."','".$id."')");
	# insertamos la primera pagina del documento
	$MiPDF->Addpage();
	
	$autorizacion = "D./D�a. $alumno[2] con D.N.I $alumno[8], como representante legal de $alumno[0] $alumno[1], alumno/a de la unidad $alumno[9], asume la responsabilidad de que su hijo/a participe en la siguiente Actividad Complementaria y Extraescolar e igualmente autoriza a los profesores/as responsables a tomar cuantas medidas sean necesarias para conseguir un desarrollo adecuado de la actividad programada.";
	
	$alergias = "Al mismo tiempo indico que mi hijo/a:
	__ Necesita tratamiento m�dico o medicaci�n espec�fica.
	__ Es al�rgico a alg�n tipo de comidas.
	
	Observaciones:
	";

	// INFORMACION DE LA CARTA
	$MiPDF->SetY(45);
	$MiPDF->SetFont ( 'NewsGotT', '', 10 );
	$MiPDF->Cell(75, 5, 'Fecha:  '.date('d.m.Y'), 0, 0, 'L', 0 );
	$MiPDF->Cell(75, 5, $alumno[2], 0, 1, 'L', 0 );
	$MiPDF->Cell(75, 12, 'Ref.:     Act/'.$id, 0, 0, 'L', 0 );
	$MiPDF->Cell(75, 5, $alumno[3], 0, 1, 'L', 0 );
	$MiPDF->Cell(75, 0, '', 0, 0, 'L', 0 );
	$MiPDF->Cell(75, 5, $alumno[4].' '.mb_strtoupper($alumno[6], 'iso-8859-1'), 0, 1, 'L', 0 );
	$MiPDF->Cell(0, 12, 'Asunto: '.$actividad, 0, 1, 'L', 0 );
	$MiPDF->Ln(7);
	
	$MiPDF->Multicell(0, 5, $autorizacion, 0, 'L', 0);
	$MiPDF->Ln(5);
	
	#Cuerpo.
	$MiPDF->SetFont('NewsGotT','B',10);
	$MiPDF->Cell(30, 8, 'Fecha: ', 0, 0, 'L');
	
	$MiPDF->SetFont('NewsGotT','',10);
	$MiPDF->Cell(130, 8, $fecha, 0, 1, 'L');
	
	$MiPDF->SetFont('NewsGotT','B',10);
	$MiPDF->Cell(30, 8, 'Horario: ', 0, 0, 'L');
	
	$MiPDF->SetFont('NewsGotT','',10);
	$MiPDF->Cell(130, 8, $horario, 0, 1, 'L');
	
	$MiPDF->SetFont('NewsGotT','B',10);
	$MiPDF->Cell(30, 8, 'Actividad: ', 0, 0, 'L');
	
	$MiPDF->SetFont('NewsGotT','',10);
	$MiPDF->Cell(130, 8, $actividad, 0, 1, 'L');
	
	$MiPDF->SetFont('NewsGotT','B',10);
	$MiPDF->Cell(30, 8, 'Descripci�n: ', 0, 0, 'L');
	
	$MiPDF->SetFont('NewsGotT','',10);
	$MiPDF->SetY($MiPDF->GetY()+1);
	$MiPDF->SetX($MiPDF->GetX()+30);
	$MiPDF->MultiCell(130, 8, $descripcion, 0, 'L' , 0);
	
	$MiPDF->SetFont('NewsGotT','B',10);
	$MiPDF->Cell(30, 8, 'Observaciones: ', 0, 0, 'L');

	$MiPDF->SetFont('NewsGotT','',10);
	$MiPDF->SetY($MiPDF->GetY()+1);
	$MiPDF->SetX($MiPDF->GetX()+30);
	$MiPDF->MultiCell(130, 8, $observaciones, 0, 'L' , 0);
	
	$MiPDF->SetFont('NewsGotT','B',10);
	$MiPDF->Cell(30, 8, 'Profesor/es: ', 0, 0, 'L');
	
	$MiPDF->SetFont('NewsGotT','',10);
	$MiPDF->SetY($MiPDF->GetY()+1.5);
	$MiPDF->SetX($MiPDF->GetX()+30);
	$MiPDF->MultiCell(130, 5, $profesor, 0, 'L' , 0);
	$MiPDF->Ln(5);
	
		
	// EJEMPLAR PARA EL PROFESOR
	$txt_acuse = "D./D�a. $alumno[2] con D.N.I $alumno[8], como representante legal de $alumno[0] $alumno[1], alumno/a de la unidad $alumno[9], autoriza a su hijo/a a participar en la actividad $actividad con referencia Act/".$id.".";
	
	$MiPDF->Line(20, $MiPDF->GetY(), 190, $MiPDF->GetY());
	$MiPDF->Ln(3);
	
	$MiPDF->SetFont('NewsGotT', 'B', 10);
	$MiPDF->Multicell(0, 5, 'RECORTE POR LA L�NEA Y ENTREGUE ESTE DOCUMENTO AL PROFESOR RESPONSABLE', 0, 'L', 0 );
	$MiPDF->Ln(3);
	
	$MiPDF->SetFont('NewsGotT', '', 10);
	$MiPDF->Multicell(0, 5, $txt_acuse, 0, 'L', 0 );
	$MiPDF->Ln(3);
	
	$MiPDF->SetFont('NewsGotT','B',10);
	$MiPDF->Cell(55, 8, 'Tel�fonos de contacto con la familia:', 0, 0, 'L');
	$MiPDF->SetFont('NewsGotT', '', 10);
	$MiPDF->Cell(110, 8, $alumno[11] . ' / ' . $alumno[12], 0, 1, 'L');
	$MiPDF->Ln(3);
	
	$MiPDF->SetFont('NewsGotT','B',10);
	$MiPDF->MultiCell(0, 8, 'Informaci�n m�dica (marque con una X):', 0, 'L', 0);
	
	$MiPDF->SetFont('NewsGotT','',10);
	$MiPDF->Cell(10, 8, '____', 0, 0, 'L');
	$MiPDF->Cell(150, 8, ' Necesita tratamiento m�dico o medicaci�n espec�fica.', 0, 1, 'L');
	
	$MiPDF->Cell(10, 8, '____', 0, 0, 'L');
	$MiPDF->Cell(150, 8, ' Es al�rgico a alg�n tipo de comida.', 0, 1, 'L');
	
	$MiPDF->SetFont('NewsGotT','',8);
	$MiPDF->MultiCell(0, 8, 'En el caso de haber marcado alguna opci�n, anote en el reverso del documento las circunstancias que concurren.', 0, 'L', 0);
	$MiPDF->Ln(5);
	
	$MiPDF->SetFont('NewsGotT','',10);
	$MiPDF->Cell (90, 5, '', 0, 0, 'C', 0 );
	$MiPDF->Cell (55, 5, 'Representante legal', 0, 1, 'C', 0 );
	$MiPDF->Cell (55, 20, '', 0, 0, 'C', 0 );
	$MiPDF->Cell (55, 20, '', 0, 1, 'C', 0 );
	$MiPDF->SetFont('NewsGotT', '', 10);
	$MiPDF->Cell (90, 5, '', 0, 0, 'C', 0 );
	$MiPDF->Cell (55, 5, 'Fdo. '.$alumno[2], 0, 1, 'C', 0 );
	
	
	
	}
}
}
$MiPDF->Output();	

?>
