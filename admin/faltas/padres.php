<?
session_start();
include("../../config.php");
if($_SESSION['autentificado']!='1')
{
session_destroy();
header("location:http://$dominio/intranet/salir.php");	
exit;
}
registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);
$tutor = $_SESSION['profi'];
include_once ("../../funciones.php"); 
if (isset($_GET['unidad'])) {$unidad = $_GET['unidad'];}elseif (isset($_POST['unidad'])) {$unidad = $_POST['unidad'];}else{$unidad="";}
if (isset($_GET['nombre'])) {$nombre = $_GET['nombre'];}elseif (isset($_POST['nombre'])) {$nombre = $_POST['nombre'];}else{$nombre="";}
if (isset($_GET['fecha12'])) {$fecha12 = $_GET['fecha12'];}elseif (isset($_POST['fecha12'])) {$fecha12 = $_POST['fecha12'];}else{$fecha12="";}
if (isset($_GET['fecha22'])) {$fecha22 = $_GET['fecha22'];}elseif (isset($_POST['fecha22'])) {$fecha22 = $_POST['fecha22'];}else{$fecha22="";}
if (isset($_GET['numero'])) {$numero = $_GET['numero'];}elseif (isset($_POST['numero'])) {$numero = $_POST['numero'];}else{$numero="";}
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

// Consulta  en curso.

foreach($nombre as $val)
{
$trozos = explode(" --> ",$val);
$claveal0 .= "claveal = '".$trozos[1]."' or ";
}
$claveal1 = substr($claveal0,0,strlen($claveal0)-4);

 mysql_query($SQLDELF);
 mysql_query($SQLDELJ);
// Creaci�n de la tabla temporal donde guardar los registros. La variable para el bucle es 10224;
 // $fechasp0=explode("-",$fecha12);
  $fechasp1=cambia_fecha($fecha12);
  $fechasp3=cambia_fecha($fecha22);
//  $fechasp11=$fechasp0[0]."-".$fechasp0[1]."-".$fechasp0[2];
//  $fechasp2=explode("-",$fecha22);
//  $fechasp3=$fechasp2[2]."-".$fechasp2[1]."-".$fechasp2[0];
//  $fechasp31=$fechasp2[0]."-".$fechasp2[1]."-".$fechasp2[2];
  if(strlen($claveal1) > 5){$alum = " and (".$claveal1.")";}else{$alum = "";}
  mysql_query("drop table faltastemp2");
  mysql_query("drop table faltastemp3");
  $SQLTEMP = "create table faltastemp2 SELECT CLAVEAL, falta, (count(*)) AS numero
  FROM  FALTAS where falta = 'F' and date(FALTAS.fecha) >= '$fechasp1' and date(FALTAS.fecha)
	<= '$fechasp3' $alum group by claveal";
  // echo $SQLTEMP."<br>";
  $resultTEMP= mysql_query($SQLTEMP);
  mysql_query("ALTER TABLE faltastemp2 ADD INDEX ( claveal ) ");
  $SQLTEMPJ = "create table faltastemp3 SELECT CLAVEAL, falta, (count(*)) AS numero
	  FROM  FALTAS where falta = 'J' and date(FALTAS.fecha) >= '$fechasp1' and date(FALTAS.fecha)
	<= '$fechasp3' group by claveal";
  $resultTEMPJ = mysql_query($SQLTEMPJ);
  	mysql_query("ALTER TABLE faltastemp3 ADD INDEX ( claveal ) ");
    $SQL0 = "SELECT CLAVEAL  FROM  faltastemp2 WHERE falta = 'F' and numero >= '$numero'";
   
  $result0 = mysql_query($SQL0);
  if(mysql_num_rows($result0)>"0"){
while  ($row0 = mysql_fetch_array($result0)):
$claveal = $row0[0];
// Justificadas
$SQLJ = "select faltastemp3.claveal, FALUMNOS.apellidos, FALUMNOS.nombre, FALUMNOS.unidad, FALUMNOS.nc, FALTAS.falta,  faltastemp3.numero from faltastemp3, FALTAS, FALUMNOS where FALUMNOS.claveal = FALTAS.claveal and faltastemp3.claveal = FALTAS.claveal and FALTAS.falta = 'J' and FALTAS.claveal = '$claveal' GROUP BY FALUMNOS.apellidos";
  	$resultJ = mysql_query($SQLJ);
  	$rowJ = mysql_fetch_array($resultJ);
// No justificadas
 $SQLF = "select faltastemp2.claveal, alma.apellidos, alma.nombre, alma.unidad, alma.matriculas,
FALTAS.falta,  faltastemp2.numero, alma.DOMICILIO, alma.CODPOSTAL, alma.LOCALIDAD,
alma.PADRE from faltastemp2, FALTAS, alma where alma.claveal = FALTAS.claveal and faltastemp2.claveal =
FALTAS.claveal and FALTAS.claveal = '$claveal' and FALTAS.falta = 'F' GROUP BY alma.apellidos";
  $resultF = mysql_query($SQLF);
//Fecha del d�a
$fhoy=date('Y-m-d');;
$fecha = formatea_fecha($fhoy);
// Bucle de Consulta.
  if ($rowF = mysql_fetch_array($resultF))
        {
$justidias = "";
$nojustidias = "";
	$padre = $rowF[10];
	$direcion =  $rowF[7];
	$localidad = $rowF[8]." ".$rowF[9];
// D�as con Faltas Justificadas
$SQL2 = "SELECT distinct FALTAS.fecha from FALTAS where FALTAS.CLAVEAL = '$claveal' and FALTAS.falta = 'J' and date(FALTAS.fecha )>= '$fechasp1' and date(FALTAS.fecha) <= '$fechasp3' order by fecha";
		$result3 = mysql_query($SQL2);
		$justi = mysql_fetch_array($result3);
		if ($justi[0] == "")
		{
		$justi1 = "Su hijo no ha justificado faltas de asistencia al Centro entre los d�as $fecha12 y $fecha22.";}
		else
		{
		$result2 = mysql_query($SQL2);
		$justi1 = "El Alumno ha justificado su falta de asistencia al Centro los siguientes d�as entre el $fechasp11 y el $fechasp31: ";
		while ($rowsql = mysql_fetch_array($result2)):
		$fechaj = explode("-", $rowsql[0]);
		$fecha2 = $fechaj[2]."-".$fechaj[1]."-".$fechaj[0];
		$justidias.=$fecha2." ";
		endwhile;
		}
		$justi2=$justidias;
// D�as con Faltas No Justificadas
$SQL3 = "SELECT distinct FALTAS.fecha from FALTAS where FALTAS.CLAVEAL = '$claveal' and FALTAS.falta = 'F' and date(FALTAS.fecha) >= '$fechasp1' and date(FALTAS.fecha) <= '$fechasp3' order by fecha";
	$justi3 = "Los d�as en los que el Alumno no ha justificado su ausencia del Centro son los siguientes:";
    $result3 = mysql_query($SQL3);
    while ($rowsql3 = mysql_fetch_array($result3)):
    $fecha3 = explode("-", $rowsql3[0]);
    $fecha4 = $fecha3[2]."-".$fecha3[1]."-".$fecha3[0];
    $nojustidias.=$fecha4." ";
endwhile;
$justi4 = $nojustidias;
	}
	
# insertamos la primera pagina del documento
$MiPDF->Addpage();
$cuerpo1="											
Muy Se�or/Sra. m�o/a:
Nos dirigimos a usted para enviarle un informe completo sobre las faltas de asistencia al Centro de su hijo/a, $rowF[2] $rowF[1], perteneciente al Grupo $rowF[3].";
	if($justi1=="Su hijo no ha justificado ninguno de los d�as en los que no ha asistido al Centro")
	{
$cuerpo2="$justi1
$justi3
";	
	}
	else
	{
$cuerpo2="$justi1
$justi2

$justi3
";	
	}
	$cuerpo3 = "Ante las reiteradas faltas de asistencia a clase de su hijo/a pongo en su conocimiento que esta situaci�n atenta contra los derechos del ni�o/a a una escolarizaci�n obligatoria y continuada.
Por tanto est� incumpliendo las obligaciones recogidas en los art�culos 154 y 269, 1 y 2 del C�digo Civil por el que los padres o tutores legales est�n obligados a cumplir los deberes legales de asistencia inherentes a la patria potestad, tutela, guarda o acogimiento familiar y en su caso ser�a de aplizaci�n lo dispuesto en el art�culo 226 del C�digo Penal.
De no tener respuesta positiva, justificando estas ausencias e incorpor�ndose su hijo/a a las clases correspondientes, nos veremos en la obligaci�n de poner esta situaci�n en conocimiento del organismo competente.

Atentamente le saluda la Direcci�n del Centro.
	";

#### Cabecera con direcci�n
	$MiPDF->SetFont('Times','',10);
	$MiPDF->SetTextColor(0,0,0);
	$MiPDF->Text(120,35,$padre);
	$MiPDF->Text(120,39,$direcion);
	$MiPDF->Text(120,43,$localidad);
	
	if(substr($codigo_postal_del_centro,0,2)=="04") $provincia_del_centro = 'Almer�a';
	if(substr($codigo_postal_del_centro,0,2)=="11") $provincia_del_centro = 'C�diz';
	if(substr($codigo_postal_del_centro,0,2)=="14") $provincia_del_centro = 'C�rdoba';
	if(substr($codigo_postal_del_centro,0,2)=="18") $provincia_del_centro = 'Granada';
	if(substr($codigo_postal_del_centro,0,2)=="21") $provincia_del_centro = 'Huelva';
	if(substr($codigo_postal_del_centro,0,2)=="23") $provincia_del_centro = 'Ja�n';
	if(substr($codigo_postal_del_centro,0,2)=="29") $provincia_del_centro = 'M�laga';
	if(substr($codigo_postal_del_centro,0,2)=="41") $provincia_del_centro = 'Sevilla';
	
	$MiPDF->Text(120,47,$provincia_del_centro);
	$MiPDF->Text(120,58,$fecha);
	
	#Cuerpo.
	$MiPDF->Ln(35);
	$MiPDF->SetFont('Times','',10);
	$MiPDF->Ln(4);
	$MiPDF->Multicell(0,4,$cuerpo1,0,'J',0);
	$MiPDF->Ln(3);
	$MiPDF->Multicell(0,4,$cuerpo2,0,'J',0);
	$MiPDF->Ln(1);
	$MiPDF->SetFont('Times','',10);
	$MiPDF->Multicell(0,4,$justi4,0,'J',0);
	$MiPDF->Ln(3);
	$MiPDF->SetFont('Times','',10);
	$MiPDF->Multicell(0,4,$cuerpo3,0,'J',0);
	$MiPDF->Ln(6);
	$MiPDF->Multicell(0,4,'En '.$localidad_del_centro.', a '.$fecha,0,'C',0);
	$MiPDF->Ln(6);
	$MiPDF->Multicell(0,4,'Jefe de Estudios:                    Sello del Centro                   Director/a:',0,'C',0);
	$MiPDF->Ln(16);
	$MiPDF->Multicell(0,4,$jefatura_de_estudios.'                                             '.$director_del_centro,0,'C',0);
	endwhile;
	}
	$MiPDF->Output();
	// Eliminar Tabla temporal
 mysql_query("DROP table `faltastemp2`");
 mysql_query("DROP table `faltastemp3`");
?>
