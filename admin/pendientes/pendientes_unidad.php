<?php
require('../../bootstrap.php');

if ($_POST['pdf']==1) {
	require("../../pdf/mc_table.php");
	
	class GranPDF extends PDF_MC_Table {
		function Header() {
			$this->SetTextColor(0, 122, 61);
			$this->Image( '../../img/encabezado.jpg',25,14,53,'','jpg');
			$this->SetFont('ErasDemiBT','B',10);
			$this->SetY(15);
			$this->Cell(75);
			$this->MultiCell(170, 5, 'CONSEJERÍA DE EDUCACIÓN Y DEPORTE', 0,'R', 0);
			$this->Ln(15);
		}
		function Footer() {
			$this->SetTextColor(0, 122, 61);
			$this->Image( '../../img/pie.jpg', 0, 160, 24, '', 'jpg' );
		}
	}
	
	$MiPDF = new GranPDF('L', 'mm', 'A4');
	
	$MiPDF->AddFont('NewsGotT','','NewsGotT.php');
	$MiPDF->AddFont('NewsGotT','B','NewsGotTb.php');
	$MiPDF->AddFont('ErasDemiBT','','ErasDemiBT.php');
	$MiPDF->AddFont('ErasDemiBT','B','ErasDemiBT.php');
	$MiPDF->AddFont('ErasMDBT','','ErasMDBT.php');
	$MiPDF->AddFont('ErasMDBT','I','ErasMDBT.php');
	
	$MiPDF->SetMargins(25, 20, 20);
	$MiPDF->SetDisplayMode('fullpage');
	
	$titulo = "Listado de alumnos con asignaturas pendientes";
	
	$grupos=substr($_POST['grupos'],0,-1);
	$tr_gr = explode(";",$grupos);
	
	foreach($tr_gr as  $valor) {
		$MiPDF->Addpage();
		
		$MiPDF->SetFont('NewsGotT', 'B', 12);
		$MiPDF->Multicell(0, 5, mb_strtoupper($titulo, 'UTF-8'), 0, 'C', 0 );
		$MiPDF->Ln(5);
		
		
		$MiPDF->SetFont('NewsGotT', '', 12);
		
		
		// INFORMACION
		$result = mysqli_query($db_con, "SELECT curso FROM alma WHERE unidad='$valor' LIMIT 1");
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		
		$MiPDF->SetFont('NewsGotT', 'B', 12);
		$MiPDF->Cell(17, 5, 'Unidad: ', 0, 0, 'L', 0);
		$MiPDF->SetFont('NewsGotT', '', 12);
		$MiPDF->Cell(100, 5, $valor.' ('.$row['curso'].')', 0, 1, 'L', 0 );
		
		$MiPDF->Ln(5);
		
		mysqli_free_result($result);
		
		
		// INFORME
		
		$MiPDF->SetWidths(array( 80, 165));
		$MiPDF->SetFont('NewsGotT', 'B', 12);
		$MiPDF->SetTextColor(255, 255, 255);
		$MiPDF->SetFillColor(61, 61, 61);
		
		$MiPDF->Row(array( ' Alumno/a', 'Asignaturas'), 0, 6);	
		
		$result = mysqli_query($db_con, "SELECT DISTINCT alma.claveal, CONCAT(alma.apellidos, ', ', alma.nombre) AS alumno, matriculas FROM alma, pendientes WHERE alma.unidad='$valor' and alma.claveal = pendientes.claveal ORDER BY alumno ASC");
		
		$MiPDF->SetTextColor(0, 0, 0);
		$MiPDF->SetFont('NewsGotT', '', 12);
		
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			
			$asigpend = '';
			$result_pend = mysqli_query($db_con, "SELECT DISTINCT abrev FROM pendientes JOIN asignaturas ON pendientes.codigo = asignaturas.codigo WHERE pendientes.claveal = '".$row['claveal']."' AND abrev LIKE '%\_%' ORDER BY abrev ASC");
			while ($row_pend = mysqli_fetch_array($result_pend, MYSQLI_ASSOC)) {
				$asigpend .= $row_pend['abrev'].' | ';
			}
			$asigpend = rtrim($asigpend, ' | ');
			
			$observaciones = ($row['matriculas']>1) ? ' (Rep.)' : '';
			
			$MiPDF->Row(array(' '.$row['alumno'].$observaciones, $asigpend), 1, 6);
		}
		
		mysqli_free_result($result);
	}
	
	
	// SALIDA
	
	$MiPDF->Output();
	exit();
}
else{
	include "../../menu.php";
	
	include("../informes/menu_alumno.php");
	
	foreach($_POST["select1"] as  $val) {
		$grupos.=$val.";";
	}
	
	echo '
<div class="container">

	<div class="page-header">
	  <h2 style="display: inline;">Alumnos y Grupos <small> Lista de Alumnos con asignaturas pendientes</small></h2>';
	  echo "<form class=\"pull-right\" action='pendientes_unidad.php' method='post'>";	
	  echo "<input type='hidden' name='grupos' value='".$grupos."' />";
	  echo "<input type='hidden' name='pdf' value='1' />";
	  echo "<button class='btn btn-primary pull-right' name='submit10' type='submit' formtarget='_blank'><i class='fas fa-print fa-fw'></i> Imprimir</button>";
	  echo "</form>";
	echo '
	</div>

	<div class="row">
	<div class="col-sm-10 col-sm-offset-1">';
	
	foreach($_POST["select1"] as  $valor) {
echo '<legend class="text-info" align="center"><strong>'.$valor.'</strong></legend><hr />';
		if (strstr($valor,"1")==TRUE) {
			   echo '<div align="center"><div class="alert alert-warning alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
Parece que estás intentando ver la lista de asignaturas pendientes de los alumnos de 1º ESO o 1º BACHILLERATO, y eso no es posible.
</div></div><br />';
		}
		else{	
echo "<table class='table table-striped' align='center'><thead><th>Alumno</th><th>Pendientes</th></thead><tbody>";
$val_nivel=substr($valor,0,1);
$pend = mysqli_query($db_con, "select distinct pendientes.claveal, alma.apellidos, alma.nombre, matriculas from pendientes, alma where pendientes.claveal=alma.claveal and alma.unidad = '$valor' order by apellidos, nombre");
$n1="";
while ($pendi = mysqli_fetch_array($pend)) {
	$uni = mysqli_query($db_con, "select combasi from alma where claveal = '$pendi[0]'");
	if (mysqli_num_rows($uni)>0) {}
			else{
	if ($pendi[3]>1) {
			$rep = " (Rep.)";
		}
		else{
			$rep='';
		}
	echo "<tr><td nowrap><a href='//".$config['dominio']."/intranet/admin/informes/index.php?claveal=$pendi[0]&todos=Ver Informe Completo del Alumno'>$pendi[1], $pendi[2] </a><span class='text-warning'>$rep</span></td><td>";
		$sql = "SELECT alma.claveal, apellidos, alma.nombre, alma.curso, abrev, asignaturas.curso
FROM alma,  pendientes , asignaturas
WHERE alma.claveal='".$pendi[0]."' and alma.claveal = pendientes.claveal
AND asignaturas.codigo = pendientes.codigo and abrev like '%\_%' and asignaturas.curso like '$val_nivel%' and alma.unidad not like '%P-%' ORDER BY Apellidos, Nombre";
			//echo $sql."<br>";
		$Recordset1 = mysqli_query($db_con, $sql) or die(mysqli_error($db_con));  #crea la consulata;
		if (mysqli_num_rows($Recordset1)>0) {
		while ($salida = mysqli_fetch_array($Recordset1)){	
						
			echo " $salida[4]|  ";
							
		}
		}
		echo "</td></tr>";
}
}
		echo "</tbody></table>";		
		}

	}
}

?>
</div>
</div>
</div>

	<?php include("../../pie.php"); ?>
</body>
</html>
