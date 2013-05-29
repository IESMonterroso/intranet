<?
include("/opt/e-smith/conf_principal.php");
include_once ("../../funciones.php");
require("../../pdf/pdf_js.php");
//require("../pdf/mc_table.php");

class PDF_AutoPrint extends PDF_JavaScript
{
function AutoPrint($dialog=false)
{
    //Open the print dialog or start printing immediately on the standard printer
    $param=($dialog ? 'true' : 'false');
    $script="print($param);";
    $this->IncludeJS($script);
}

function AutoPrintToPrinter($server, $printer, $dialog=false)
{
    //Print on a shared printer (requires at least Acrobat 6)
    $script = "var pp = getPrintParams();";
    if($dialog)
        $script .= "pp.interactive = pp.constants.interactionLevel.full;";
    else
        $script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
    $script .= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
    $script .= "print(pp);";
    $this->IncludeJS($script);
}
}
define ( 'FPDF_FONTPATH', '../../pdf/fontsPDF/' );
# creamos el nuevo objeto partiendo de la clase ampliada
$MiPDF = new PDF_AutoPrint();
$MiPDF->SetMargins ( 20, 20, 20 );
# ajustamos al 100% la visualización
$MiPDF->SetDisplayMode ( 'fullpage' );
// Consulta  en curso. 
$connection = mysql_connect($db_host,$db_user,$db_pass) or die ("Imposible conectar con la Base de datos");
mysql_select_db($db) or die ("Imposible seleccionar base de datos!");
if (substr($curso, 0, 1) == '1') {
	$mas = ", colegio";
}
$n_curso = substr($curso, 0, 1);
$result0 = mysql_query ( "select distinct id_matriculas from matriculas_bach_temp, matriculas_bach where id=id_matriculas order by curso".$mas.", letra_grupo, apellidos, nombre" );
while ($id_ar = mysql_fetch_array($result0)) {
$id = $id_ar[0];
$result = mysql_query("select * from matriculas_bach where id = '$id'");
if ($datos_ya = mysql_fetch_object ( $result )) {

$naci = explode("-",$datos_ya->nacimiento);
$nacimiento = "$naci[2]-$naci[1]-$naci[0]";
$apellidos = $datos_ya->apellidos; $id = $datos_ya->id; $nombre = $datos_ya->nombre; $nacido = $datos_ya->nacimiento; $provincia = $datos_ya->provincia; $domicilio = $datos_ya->domicilio; $localidad = $datos_ya->localidad; $dni = $datos_ya->dni; $padre = $datos_ya->padre; $dnitutor = $datos_ya->dnitutor; $madre = $datos_ya->madre; $dnitutor2 = $datos_ya->dnitutor2; $telefono1 = $datos_ya->telefono1; $telefono2 = $datos_ya->telefono2; $colegio = $datos_ya->colegio; $correo = $datos_ya->correo; $otrocolegio = $datos_ya->otrocolegio; $letra_grupo = $datos_ya->letra_grupo; $religion = $datos_ya->religion; $observaciones = $datos_ya->observaciones; $promociona = $datos_ya->promociona; $transporte = $datos_ya->transporte; $ruta_este = $datos_ya->ruta_este; $ruta_oeste = $datos_ya->ruta_oeste; $sexo = $datos_ya->sexo; $hermanos = $datos_ya->hermanos; $nacionalidad = $datos_ya->nacionalidad; $claveal = $datos_ya->claveal; $curso = $datos_ya->curso;  $itinerario1 = $datos_ya->itinerario1; $itinerario2 = $datos_ya->itinerario2; $optativa1 = $datos_ya->optativa1; $optativa2 = $datos_ya->optativa2; $optativa2b1 = $datos_ya->optativa2b1; $optativa2b2 = $datos_ya->optativa2b2; $optativa2b3 = $datos_ya->optativa2b3; $optativa2b4 = $datos_ya->optativa2b4; $optativa2b5 = $datos_ya->optativa2b5; $optativa2b6 = $datos_ya->optativa2b6; $optativa2b7 = $datos_ya->optativa2b7; $optativa2b8 = $datos_ya->optativa2b8; $optativa2b9 = $datos_ya->optativa2b9; $optativa2b10 = $datos_ya->optativa2b10; $repetidor = $datos_ya->repite;$revisado = $datos_ya->revisado; $confirmado = $datos_ya->confirmado; $grupo_actual = $datos_ya->grupo_actual;	

	$apellidos = "Apellidos del Alumno: ". $apellidos;
	 $nombre= "Nombre: ".$nombre;
	 $nacido= "Nacido en: ".$nacido;
	 $provincia= "Provincia de: ".$provincia;
	 $fecha_nacimiento= "Fecha de Nacimiento: $nacimiento";
	 $domicilio= "Domicilio: ".$domicilio;
	 $localidad= "Localidad: ".$localidad;
	 $dni= "DNI del alumno: ".$dni;
	 $padre= "Apellidos y nombre del Tutor legal 1: ".$padre;
	 $dnitutor= "DNI: ".$dnitutor;
	 $madre= "Apellidos y nombre del Tutor legal 2: ".$madre;
	 $dnitutor2= "DNI: ".$dnitutor2;
	 $telefono1= "Tel�fono Casa: ".$telefono1;
	 $telefono2= "Tel�fono M�vil: ".$telefono2;
	 $telefonos="$telefono1\n   $telefono2";

	 if ($datos_ya->colegio == "Otro Centro") { $colegio= "Centro de procedencia:  ".$datos_ya->otrocolegio; }else{	 $colegio= "Centro de procedencia:  ".$datos_ya->colegio; }
	 $correo= "Correo electr�nico de padre o madre: ".$datos_ya->correo;
	 // Optativas y refuerzos
	 $n_curso = substr($curso, 0, 1);
	 $n_curso2 = $n_curso-1;
	 
	 
// Asignaturas y Modalidades
$it11 = array("Bachillerato de Ciencias y Tecnolog�a", "V�a de Ciencias e Ingenier�a", "V�a de Ciencias de la Naturaleza y la Salud", "Ciencias y Tecnolog�a");
$it12 = array("Bachillerato de Humanidades y Ciencias Sociales", "V�a de Humanidades", "V�a de Ciencias Sociales", "Humanidades y Ciencias Sociales");
$opt11=array("DBT11" => "Dibujo T�cnico", "TIN11" => "Tecnolog�a", "BYG11"=>"Biolog�a y Geolog�a");
$opt12=array("GRI12-LAT12" => "Griego, Lat�n", "GRI12-ECO12" => "Griego, Econom�a", "MCS12-ECO12"=>"Matem�ticas de Ciencias Sociales, Econom�a", "MCS12-LAT12"=>"Matem�ticas de Ciencias Sociales, Lat�n");

$it21 = array("Bachillerato de Ciencias y Tecnolog�a", "V�a de Ciencias e Ingenier�a", "V�a de Ciencias de la Naturaleza y la Salud", "Ciencias y Tecnolog�a");
$it22 = array("Bachillerato de Humanidades y Ciencias Sociales", "V�a de Humanidades", "V�a de Ciencias Sociales", "Humanidades y Ciencias Sociales");
$opt21=array("FIS21_DBT21" => "F�sica, Dibujo T�cnico", "FIS21_TIN21" => "F�sica, Tecnolog�a", "FIS21_QUI21" => "F�sica, Qu�mica", "BIO21_QUI21" => "Biolog�a, Qu�mica");
$opt22=array("HAR22_LAT22_GRI22" => "Historia del Arte, Lat�n, Griego", "HAR22_LAT22_MCS22" => "Historia del Arte, Lat�n, Matem�ticas de las C. Sociales", "HAR22_ECO22_GRI22" => "Historia del Arte, Econom�a, Griego", "HAR22_ECO22_MCS22" => "Historia del Arte, Econom�a, Matem�ticas de las C. Sociales", "GEO22_ECO22_MCS22" => "Geograf�a, Econom�a, Matem�ticas de las C. Sociales", "GEO22_ECO22_GRI22" => "Geograf�a, Econom�a, Griego", "GEO22_LAT22_MCS22" => "Geograf�a, Lat�n, Matem�ticas de las C. Sociales", "GEO22_LAT22_GRI22" => "Geograf�a, Lat�n, Griego");
$opt23 =array("ingles_25" => "Ingl�s 2� Idioma","aleman_25" => "Alem�n 2� Idioma", "frances_25" => "Franc�s 2� Idioma", "tic_25" => "T.I.C.", "ciencias_25" => "Ciencias de la Tierra y Medioambientales", "musica_25" => "Historia de la M�sica y la Danza", "literatura_25" => "Literatura Universal", "edfisica_25"=>"Educaci�n F�sica", "estadistica_25"=>"Estad�stica", "salud_25"=>"Introducci�n a las Ciencias de la Salud");
	 
	 $observaciones= "OBSERVACIONES: ".$observaciones;
	 $texto_exencion= "El alumno solicita la exenci�n de la Asignatura Optativa";
	 $texto_bilinguismo= "El alumno solicita participar en el Programa de Bilinguismo";
	 $fecha_total = $fecha;
	 $texto_transporte = "Transporte escolar: $ruta_este$ruta_oeste.";
	 if ($hermanos == '' or $hermanos == '0') { $hermanos = ""; }
	 
}
$fech = explode(" ",$fecha_total);
$fecha = $fech[0];
//$hoy = formatea_fecha($fech[0]);
$an = substr($curso_actual,0,4);
$an1 = $an+1;
$hoy = formatea_fecha(date('Y-m-d'));
$titulo_documentacion = "DOCUMENTACI�N NECESARIA PARA LA MATRICULACI�N";
$documentacion = "1. Fotocopia del DNI. Si el alumno no dispone de DNI, una fotocopia del Libro de Familia o Certificado de Nacimiento. El alumnado extranjero deber� entregar una fotocopia del Pasaporte y Tarjeta de Residencia.
2. El alumnado procedente de otros Institutos o de Colegios no adscritos a nuestro Centro deben aportar el Certificado de expediente acad�mico..
3. Los alumnos que se matriculen a partir de 3� de ESO tienen que abonar 3 euros para la cuota obligatoria del Seguro Escolar.
4. Cuota voluntaria de 12 euros para la Asociaci�n de Padres y Madres del Centro.
";
$datos_junta = "PROTECCI�N DE DATOS.\n En cumplimiento de lo dispuesto en la Ley Org�nica 15/1999, de 13 de Diciembre, de Protecci�n de Datos de Car�cter Personal, la Consejer�a de Educaci�n le informa que los datos personales obtenidos mediante la cumplimentaci�n de este formulario y dem�s documentaci�n que se adjunta van a ser incorporados, para su tratamiento, al fichero 'S�neca. Datos personales y acad�micos del alumnado', con la finalidad de recoger los datos personales y acad�micos del alumnado que cursa estudios en centros dependientes de la Conserjer�a de Educaci�n, as� como de las respectivas unidades familiares.\n De acuerdo con lo previsto en la Ley, puede ejercer los derechos de acceso, rectificaci�n, cancelaci�n y oposici�n dirigiendo un escrito a la Secretar�a General T�cnica de la Conserjer�a de Educaci�n de la Junta de Andaluc�a en Avda. Juan Antonio de Vizarr�n, s/n, Edificio Torretriana 41071 SEVILLA";

	
// Formulario de la junta	
for($i=1;$i<3;$i++){
	$MiPDF->Addpage ();
	#### Cabecera con direcci�n
	$MiPDF->SetFont ( 'Times', 'B', 10  );
	$MiPDF->SetTextColor ( 0, 0, 0 );
	$MiPDF->SetFillColor(230,230,230);
	$MiPDF->Image ( '../../imag/encabezado2.jpg', 10, 10, 180, '', 'jpg' );
	$MiPDF->Ln ( 8 );
	$titulo2 = "MATR�CULA DE ". $n_curso."� DE BACHILLERATO";
	$MiPDF->Multicell ( 0, 4, $titulo2, 0, 'C', 0 );

	$MiPDF->Ln ( 8 );
	$MiPDF->SetFont ( 'Times', '', 7 );
	$MiPDF->Cell(21,6,"N� MATR�CULA: ",0);
	$MiPDF->Cell(24,6,"",1);
	$adv = "         ANTES DE FIRMAR ESTE IMPRESO, COMPRUEBE QUE CORRESPONDE A LA
	        ETAPA EDUCATIVA EN LA QUE DESEA REALIZAR LA MATR�CULA.
	        ESTA MATR�CULA EST� CONDICIONADA A LA COMPROBACI�N DE LOS DATOS,  DE CUYA
	        VERACIDAD SE RESPONSABILIZA LA PERSONA FIRMANTE. ";
	$MiPDF->MultiCell(120, 3, $adv,0,'L',0);
	$MiPDF->Ln ( 5 );
	$MiPDF->SetFont ( 'Times', '', 10 );
	$MiPDF->Cell(5,6,"1",1,0,'C',1);
	$MiPDF->Cell(163,6,"DATOS PERSONALES DEL ALUMNO",1,0,'C',1);
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(84,5,"APELLIDOS",0,0,"C");
	$MiPDF->Cell(84,5,"NOMBRE",0,0,"C");
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(84,5,$datos_ya->apellidos,1,0,'C');
	$MiPDF->Cell(84,5,$datos_ya->nombre,1,0,'C');
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(40,5,"FECHA NACIMIENTO",0,0,"C");
	$MiPDF->Cell(26,5,"DNI/NIE",0,0,"C");
	$MiPDF->Cell(26,5,"TEL�FONO",0,0,"C");
	$MiPDF->Cell(35,5,"NACIONALIDAD",0,0,"C");
	$MiPDF->Cell(21,5,"HERMANOS",0,0,"C");
	$MiPDF->Cell(20,5,"SEXO",0,0,"C");
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(40,5,$nacimiento,1,0,'C');
	$MiPDF->Cell(26,5,$datos_ya->dni,1,0,'C');
	$MiPDF->Cell(26,5,$datos_ya->telefono1,1,0,'C');
	$MiPDF->Cell(35,5,$nacionalidad,1,0,'C');
	$MiPDF->Cell(21,5,$hermanos,1,0,'C');
	$MiPDF->Cell(20,5,$sexo,1,0,'C');
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(56,5,"DOMICILIO",0,0,"C");
	$MiPDF->Cell(56,5,"LOCALIDAD",0,0,"C");
	$MiPDF->Cell(28,5,"COD. POSTAL",0,0,"C");
	$MiPDF->Cell(28,5,"PROVINCIA",0,0,"C");
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(56,5,$datos_ya->domicilio,1,0,'C');
	$MiPDF->Cell(56,5,$datos_ya->localidad,1,0,'C');
	$MiPDF->Cell(28,5,"29680",1,0,'C');
	$MiPDF->Cell(28,5,"M�laga",1,0,'C');
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(168,5,"CORREO ELECTR�NICO DE CONTACTO",0,0,'C');
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(168,5,$datos_ya->correo,1,0,'C');
	
	$MiPDF->Ln ( 9 );
	$MiPDF->Cell(5,6,"2",1,0,'C',1);
	$MiPDF->Cell(163,6,"DATOS DE LOS REPRESENTANTES LEGALES DEL ALUMNO",1,0,'C',1);
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(140,5,"APELLIDOS Y NOMBRE DEL REPRESENTANTE LEGAL 1(con quien �ste convive)",0,0,"C");
	$MiPDF->Cell(28,5,"DNI/NIE",0,0,"C");
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(140,5,$datos_ya->padre,1,0,'C');
	$MiPDF->Cell(28,5,$datos_ya->dnitutor,1,0,'C');
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(140,5,"APELLIDOS Y NOMBRE DEL REPRESENTANTE LEGAL 2",0,0,"C");
	$MiPDF->Cell(28,5,"DNI/NIE",0,0,"C");
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(140,5,$datos_ya->madre,1,0,'C');
	$MiPDF->Cell(28,5,$datos_ya->dnitutor2,1,0,'C');

	
	$MiPDF->Ln ( 9 );
	$MiPDF->Cell(5,6,"3",1,0,'C',1);
	$MiPDF->Cell(163,6,"DATOS DE MATR�CULA",1,0,'C',1);
	$MiPDF->Ln ( 8 );
	$MiPDF->Cell(76,5,"CENTRO DOCENTE EN EL QUE SE MATRICULA",0,0,"C");
	$MiPDF->Cell(46,5,"LOCALIDAD",0,0,"C");
	$MiPDF->Cell(46,5,"CODIGO",0,0,"C");
	$MiPDF->Ln ( 5 );
	$MiPDF->Cell(76,5,"I.E.S. Monterroso",1,0,'C');
	$MiPDF->Cell(46,5,"Estepona",1,0,'C');
	$MiPDF->Cell(46,5,"29002885",1,0,'C');
	$MiPDF->Ln ( 8 );
	//echo $itinerario;
	if ($n_curso == '4') { $extra="4ESO (It. $itinerario)";}else{$extra=$curso;}
	$MiPDF->Cell(78,5,"CURSO EN QUE SE MATRICULA",0,0,"C");
	$MiPDF->Cell(90,5,"MATERIAS DE LA MODALIDAD",0,0,"C");
	$MiPDF->Ln ( 5 );
	
	$opt="";
	if ($n_curso=="1") {
		$mod_registro = ${it1.$itinerario1}[3];
		for ($i = 1; $i < 3; $i++) {		
			foreach (${opt1.$i} as $key=>$val){
				if ($optativa1 == $key) {
				$opt = "$val";				
				}
			}
		}

	}
	if ($n_curso=="2") {
		$mod_registro = ${it2.$itinerario2}[3];
		for ($i = 1; $i < 3; $i++) {		
			foreach (${opt2.$i} as $key=>$val){
				if ($optativa2 == $key) {
				$opt = "$val";				
				}
			}
		}
// Optativas extra de 2 de bach.
	$n_z="";
	$opt_2b = "";
			foreach ($opt23 as $key=>$val){
				$n_z+=1;		
				$opt_b = mysql_query("select optativa2b$n_z from matriculas_bach where id = '$id'");
				$o_b = mysql_fetch_array($opt_b);
				$reduce = substr($key,0,-3);
				$opt_2b .= $o_b[0].". ".$val."; ";				
				}
			}
		//$opt_o = "\nOptativas de Bachillerato".$opt_2b;
	$MiPDF->Cell(78,5,$n_curso."� DE BACH. ( ".$mod_registro." )",1,0,'C');
	$MiPDF->MultiCell(90,5,$opt,1);
	$MiPDF->Ln ( 2 );
	$MiPDF->Cell(165,5,"MATERIAS OPTATIVAS",0,0,"C");
	$MiPDF->Ln ( 5 );
	$MiPDF->MultiCell(168,5,$opt_2b,1);
	$MiPDF->Ln ( 5 );
	$f_hoy = "        En Estepona, a ".$hoy;
	$sello = "                                  Sello del Centro";
	$firma_centro = "                                El/La Funcionario/a";
	$firma_padre= "  Firma del representante o Guardador legal 1";
	$MiPDF->Cell(84,8,$firma_padre,0);	
	$MiPDF->Cell(84, 8, $firma_centro,0);
	$MiPDF->Ln ( 20 );
	$MiPDF->Cell(84, 8, $f_hoy,0);
	$MiPDF->Cell(84, 8, $sello,0);
	$MiPDF->Ln ( 9 );
	$nota = "NOTA: Para la primera matriculaci�n del alumnado en el centro docente se aportar� documento acreditativo de la fecha de nacimimiento del alumno/a y documento de estar en posesi�n de los requisitos acad�micos establecidos en la legislaci�n vigente.";
	$MiPDF->SetFont ( 'Times', 'B', 8 );
	$MiPDF->MultiCell(168,5,$nota,0);
	$MiPDF->SetFont ( 'Times', '', 7 );
	$MiPDF->Ln ( 3 );		
	$MiPDF->MultiCell(168, 3, $datos_junta,1,'L',1);
}

	# insertamos la pagina de documentaci�n
	$MiPDF->Addpage ();
	$MiPDF->SetFont ( 'Times', '', 10  );
	$MiPDF->SetTextColor ( 0, 0, 0 );
	$MiPDF->SetFillColor(230,230,230);
	$MiPDF->Multicell ( 0, 4, $titulo_documentacion, 0, 'C', 0 );
	$MiPDF->Ln ( 4 );
	$MiPDF->Multicell ( 0, 6, $documentacion, 0, 'L', 0 );
	include("autorizaciones_bach.php");
	}
   $MiPDF->AutoPrint(true);     
   $MiPDF->Output ();

?>