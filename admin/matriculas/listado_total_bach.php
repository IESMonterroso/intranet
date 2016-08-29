<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

require_once('../../pdf/class.ezpdf.php');
$pdf =& new Cezpdf('a4');
$pdf->selectFont('../../pdf/fonts/Helvetica.afm');
$pdf->ezSetCmMargins(1,1,1.5,1.5);
$tot = mysqli_query($db_con, "select distinct curso, grupo_actual from matriculas_bach where grupo_actual != '' order by curso, grupo_actual");
while($total = mysqli_fetch_array($tot)){
# hasta aquí lo del pdf
$options_center = array(
				'justification' => 'center'
			);
$options_right = array(
				'justification' => 'right'
			);
$options_left = array(
				'justification' => 'left'
					);
	$curso = $total[0];
	$grupo_actual = $total[1];

		$sqldatos="SELECT concat(apellidos,', ',nombre),";
		if ($curso=="1BACH") {
		$sqldatos.="itinerario1, optativa1, idioma1, idioma2, ";
		$num_opc = 5;
		}
		else{
		$sqldatos.="itinerario2, optativa2, optativa2b1, optativa2b2, optativa2b3, optativa2b4, optativa2b5, optativa2b6, optativa2b7, optativa2b8, ";	
		$num_opc = 14;
		}
		$sqldatos .= "religion, bilinguismo FROM matriculas_bach WHERE curso = '$curso' and grupo_actual='".$grupo_actual[0]."' ORDER BY apellidos, nombre";
//echo $sqldatos;
$lista= mysqli_query($db_con, $sqldatos );
$nc=0;
unset($data);
while($datatmp = mysqli_fetch_array($lista)) { 
	$bil = "";
    if($datatmp['bilinguismo']=="Si"){$bil = " (Bil.)";}
	$religion = "";
	for ($i = 0; $i < $num_opc; $i++) {
		if ($datatmp[$i]=="0") {
			$datatmp[$i]="";
		}
	}

$nc+=1;

if ($curso=="2BACH") {
for ($i = 3; $i < 11; $i++) {
		if ($datatmp[$i]=="1") {
			$datatmp[$i]="X";
		}
		else{
			$datatmp[$i]="";
		}
	}		
		if (strstr($datatmp['religion'],"Cat")==TRUE) {
			$religion ="X";
		}
		
	$opt = '
	
	Itinerarios: 1 => Ciencias de la Salud y Tecnol�gico; 2 => Humanidades y Ciencias Sociales
	
	Optativas Itin. 1: 1 => Tecnolog�a Industrial; 2 => Ciencias de la Tierra y del Medio Ambiente; 3 => Psicolog�a; 4 => Geolog�a; 5 => TIC II; 6 => Alem�n 2� Idioma; 7 => Franc�s 2� Idioma; 8 => Ingl�s 2� Idioma;
	Optativas Itin. 2: 1 => Tecnolog�a Industrial; 2 => Ciencias de la Tierra y del Medio Ambiente; 3 => Psicolog�a; 4 => Geolog�a; 5 => TIC; 6 => Alem�n 2� Idioma; 7 => Franc�s 2� Idioma; 8 => Ingl�s 2� Idioma;
	Optativas Itin. 3: 1 => TIC II; 2 => Alem�n 2� Idioma; 3 => Franc�s 2� Idioma; 4 => Ingl�s 2� Idioma;
	Optativas Itin. 4: 1 => TIC II; 2 => Fundamentos de Administracci�n y Gesti�n; 3 => Alem�n 2� Idioma; 4 => Franc�s 2� Idioma; 5 => Ingl�s 2� Idioma;
	';
	$optas = $datatmp[2];
	if (stristr($optas, "Econom")==TRUE) { $optas = "ECO"; }elseif(stristr($optas, "Grieg")==TRUE){ $optas = "GRI"; }
	$data[] = array(
				'num'=>$nc,
				'nombre'=>$datatmp[0].$bil,
				'c1'=>$religion,
				'c2'=>$datatmp['itinerario2'],
				'c3'=>$optas,
				'c4'=>$datatmp[3],
				'c5'=>$datatmp[4],
				'c6'=>$datatmp[5],
				'c7'=>$datatmp[6],
				'c8'=>$datatmp[7],
				'c9'=>$datatmp[8],
				'c10'=>$datatmp[9],
				'c11'=>$datatmp[10],
				);
	$titles = array(
				'num'=>'<b>N�</b>',
				'nombre'=>'<b>Alumno</b>',
				'c1'=>'Rel.Cat.',
				'c2'=>'It2',
				'c3'=>'Opt2',
				'c4'=>'1',
				'c5'=>'2',
				'c6'=>'3',
				'c7'=>'4',
				'c8'=>'5',
				'c9'=>'6',
				'c10'=>'7',
				'c11'=>'8',
			);
}
if ($curso=="1BACH") {

		if (strstr($datatmp['religion'],"Cat")==TRUE) {
			$religion ="R. Cat.";
		}
		elseif (strstr($datatmp['religion'],"Isl")==TRUE) {
			$religion ="R. Isl.";
		}
		elseif (strstr($datatmp['religion'],"Eva")==TRUE) {
			$religion ="R. Evan.";
		}
		elseif (strstr($datatmp['religion'],"Valo")==TRUE) {
			$religion ="E. Ciud.";
		}
		$opt = '
	
	Itinerarios de 1� de Bachillerato: 1 => Ciencias e Ingenier�a y Arquitectura; 2 => Ciencias y Ciencias de la Salud"; 3 => Humanidades; 4 => Ciencias Sociales y Jur�dicas
	
	Optativas:
	CC => Cultura Cient�fica; TIC => Tecnolog�as de la Informaci�n y Comunicaci�n; HMC => Historia del Mundo Contempor�neo.; LUN => Literatura Universal;
		';
	$optas = str_replace("1","",$datatmp[2]);
	$optas = str_replace("2","",$optas);
	$optas = str_replace("3","",$optas);
	$optas = str_replace("4","",$optas);
	$data[] = array(
				'num'=>$nc,
				'nombre'=>$datatmp[0].$bil,
				'c1'=>$religion,
				'c2'=>$datatmp[1],
				'c3'=>$optas,
				'c4'=>$datatmp[3],
				'c5'=>$datatmp[4],
				);
	$titles = array(
				'num'=>'<b>N�</b>',
				'nombre'=>'<b>Alumno</b>',
				'c1'=>'Religi�n',
				'c2'=>'It1',
				'c3'=>'Opt1',
				'c4'=>'Idioma1',
				'c5'=>'Idioma2',
			);
}
}

$options = array(
				'textCol' => array(0.2,0.2,0.2),
				'innerLineThickness'=>0.5,
				'outerLineThickness'=>0.7,
				'showLines'=> 2,
				'shadeCol'=>array(0.9,0.9,0.9),
				'xOrientation'=>'center',
				'width'=>500
			);
$txttit = "Lista del Grupo $curso-$grupo_actual[0]\n";
$txttit.= $config['centro_denominacion'].". Curso ".$config['curso_actual'].".\n";
	
$pdf->ezText($txttit, 13,$options_center);
$pdf->ezTable($data, $titles, '', $options);
$pdf->ezText($opt, '10', $options);

$pdf->ezText("\n", 10);
$pdf->ezText("<b>Fecha:</b> ".date("d/m/Y"), 10,$options_right);

$pdf->ezNewPage();
}
$pdf->ezStream();
?>