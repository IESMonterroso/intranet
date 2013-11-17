<?
session_start();
include("../../config.php");
if($_SESSION['autentificado']!='1')
{
session_destroy();
header("location:http://$dominio/intranet/salir.php");	
exit;
}
//variables();
registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);
$tutor = $_SESSION['profi'];
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


if(substr($codigo_postal_del_centro,0,2)=="04") $GLOBALS['CENTRO_PROVINCIA'] = 'Almer�a';
if(substr($codigo_postal_del_centro,0,2)=="11") $GLOBALS['CENTRO_PROVINCIA'] = 'C�diz';
if(substr($codigo_postal_del_centro,0,2)=="14") $GLOBALS['CENTRO_PROVINCIA'] = 'C�rdoba';
if(substr($codigo_postal_del_centro,0,2)=="18") $GLOBALS['CENTRO_PROVINCIA'] = 'Granada';
if(substr($codigo_postal_del_centro,0,2)=="21") $GLOBALS['CENTRO_PROVINCIA'] = 'Huelva';
if(substr($codigo_postal_del_centro,0,2)=="23") $GLOBALS['CENTRO_PROVINCIA'] = 'Ja�n';
if(substr($codigo_postal_del_centro,0,2)=="29") $GLOBALS['CENTRO_PROVINCIA'] = 'M�laga';
if(substr($codigo_postal_del_centro,0,2)=="41") $GLOBALS['CENTRO_PROVINCIA'] = 'Sevilla';

# creamos la clase extendida de fpdf.php 
class GranPDF extends FPDF {
	function Header() {
		$this->Image ( '../../img/encabezado.jpg',15,15,50,'','jpg');
		$this->SetFont('ErasDemiBT','B',10);
		$this->SetY(15);
		$this->Cell(90);
		$this->Cell(80,4,'CONSEJER�A DE EDUCACI�N',0,1);
		$this->SetFont('ErasMDBT','I',10);
		$this->Cell(90);
		$this->Cell(80,4,$GLOBALS['CENTRO_NOMBRE'],0,1);
		$this->Ln(8);
	}
	function Footer() {
		$this->Image ( '../../img/pie.jpg', 10, 245, 25, '', 'jpg' );
		$this->SetY(265);
		$this->SetFont('ErasMDBT','',10);
		$this->SetTextColor(156,156,156);
		$this->Cell(70);
		$this->Cell(80,4,$GLOBALS['CENTRO_DIRECCION'],0,1);
		$this->Cell(70);
		$this->Cell(80,4,$GLOBALS['CENTRO_CODPOSTAL'].', '.$GLOBALS['CENTRO_LOCALIDAD'].' ('.$GLOBALS['CENTRO_PROVINCIA'] .')',0,1);
		$this->Cell(70);
		$this->Cell(80,4,'Tlf: '.$GLOBALS['CENTRO_TELEFONO'].'   Fax: '.$GLOBALS['CENTRO_FAX'],0,1);
		$this->Cell(70);
		$this->Cell(80,4,'Correo: '.$GLOBALS['CENTRO_CORREO'],0,1);
		$this->Ln(8);
	}
}

# creamos el nuevo objeto partiendo de la clase
$MiPDF=new GranPDF('P','mm',A4);
$MiPDF->AddFont('NewsGotT','','NewsGotT.php');
$MiPDF->AddFont('NewsGotT','B','NewsGotTb.php');
$MiPDF->AddFont('ErasDemiBT','','ErasDemiBT.php');
$MiPDF->AddFont('ErasDemiBT','B','ErasDemiBT.php');
$MiPDF->AddFont('ErasMDBT','','ErasMDBT.php');
$MiPDF->AddFont('ErasMDBT','I','ErasMDBT.php');
			$MiPDF->SetMargins(20,20,20);
			# ajustamos al 100% la visualizacion
			$MiPDF->SetDisplayMode('fullpage');
			
                     if($mes=='09'){$n_mes='Septiembre';}
                    if($mes=='10'){$n_mes='Octubra';}
                    if($mes=='11'){$n_mes='Noviembre';}
                    if($mes=='12'){$n_mes='Diciembre';}
                    if($mes=='01'){$n_mes='Enero';}
                    if($mes=='02'){$n_mes='Febrero';}
                    if($mes=='03'){$n_mes='Marzo';}
                    if($mes=='04'){$n_mes='Abril';}
                    if($mes=='05'){$n_mes='Mayo';}
                    if($mes=='06'){$n_mes='Junio';}
$alumnos0 = "select numero, unidad, apellidos, nombre, jefatura, orientacion, tutoria, padre, domicilio, provinciaresidencia, telefono, fecha, alma.claveal, serv_sociales from absentismo, alma where absentismo.claveal=alma.claveal and mes='$mes'";
$alumnos1 = mysql_query($alumnos0);
while($alumno = mysql_fetch_array($alumnos1))
{
	$fecha=date('Y-m-d');	
# insertamos la primera pagina del documento
$MiPDF->Addpage();
   	$foto = '../../xml/fotos/'.$alumno[12].'.jpg';
	if (file_exists($foto)) {
$MiPDF->Image($foto,90,30,26,'','jpg');
	} 
$cuerpo0="INFORME DE ABSENTISMO ESCOLAR";
$datos10="	 Alumno: $alumno[3] $alumno[2]
		Fecha: $alumno[11]
		Curso: $alumno[1]
		Tutor/a: $alumno[7]
		Domicilio: $alumno[8] ($alumno[9])
		Tel�fono: $alumno[10]";
$cuerpo1="El Alumno no ha asistido al Centro $alumno[0] horas lectivas durante el mes de $n_mes. Las faltas de asistencia no han sido justificadas.";
	$mes = array(1=>"enero",2=>"febrero",3=>"marzo",4=>"abril",5=>"mayo",6=>"junio",7=>"julio",
                 8=>"agosto",9=>"septiembre",10=>"octubre",11=>"noviembre",12=>"diciembre");
    $dia = array("domingo", "lunes","martes","mi�rcoles","jueves","viernes","s�bado");
	$diames = date("j");
    $nmes = date("n");
    $ndia = date("w");
    $nano0 = date("Y");
	$cuerpo2 = "$diames de ".$mes[$nmes].", $nano0";
$cuerpo3="Informe de la Jefatura de Estudios";
$cuerpo33="$alumno[4]";
if (strlen($alumno[5])>0) {
$cuerpo4="Informe del Departamento de Orientaci�n";
$cuerpo44="$alumno[5]";	
}
if (strlen($alumno[6])>0) {
$cuerpo5="Informe del Tutor del Curso";
$cuerpo55="$alumno[6]";	
}
if (strlen($alumno[13])>0) {
$cuerpo6="Informe de los Servicios Sociales";
$cuerpo66="$alumno[13]";	
}
#### Cabecera con direcci�n
	$MiPDF->SetFont('Helvetica','',11);
	$MiPDF->SetTextColor(0,0,0);
	
	#Cuerpo.
	$MiPDF->Ln(45);
	$MiPDF->SetFont('Helvetica','B',10);
	$MiPDF->Multicell(0,20,$cuerpo0,0,'C',0);
	$MiPDF->SetFont('Helvetica');
	
	$MiPDF->Multicell(0,4,$datos10,0,'L',0);
	$MiPDF->Ln(3);
	$MiPDF->SetFont('Helvetica','',10);
	$MiPDF->Multicell(0,4,$cuerpo1,0,'J',0);
	$MiPDF->Ln(3);
		$MiPDF->SetFont('Helvetica','B');
	$MiPDF->Multicell(0,4,$cuerpo3,0,'C',0);
	$MiPDF->Ln(1);
		$MiPDF->SetFont('Helvetica','I');
	$MiPDF->Multicell(0,4,$cuerpo33,0,'J',0);
	$MiPDF->Ln(5);
if (strlen($alumno[5])>0) {
		$MiPDF->SetFont('Helvetica','B');
	$MiPDF->Multicell(0,4,$cuerpo4,0,'C',0);	
	$MiPDF->Ln(1);
		$MiPDF->SetFont('Helvetica','I');
	$MiPDF->Multicell(0,4,$cuerpo44,0,'J',0);
	$MiPDF->Ln(5);
}		
if (strlen($alumno[6])>0) {
	$MiPDF->SetFont('Helvetica','B');
	$MiPDF->Multicell(0,4,$cuerpo5,0,'C',0);
	$MiPDF->Ln(1);
		$MiPDF->SetFont('Helvetica','I');
	$MiPDF->Multicell(0,4,$cuerpo55,0,'J',0);
	$MiPDF->Ln(5);
	}
if (strlen($alumno[13])>0) {
	$MiPDF->SetFont('Helvetica','B');
	$MiPDF->Multicell(0,4,$cuerpo6,0,'C',0);
	$MiPDF->Ln(1);
		$MiPDF->SetFont('Helvetica','I');
	$MiPDF->Multicell(0,4,$cuerpo66,0,'J',0);
	$MiPDF->Ln(15);
	}
	$MiPDF->SetFont('Helvetica','',10);
	$MiPDF->Multicell(0,4,$cuerpo2,0,'C',0);
	$MiPDF->Ln(1);
	//unlink($temp);
	//$MiPDF->ImageDestroy($foto);
	}

$MiPDF->Output();	

?>
