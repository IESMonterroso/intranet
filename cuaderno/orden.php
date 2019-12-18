<?php
require('../bootstrap.php');


$pr = $_SESSION['profi'];

include("../menu.php");
include("menu.php");

?>
<div class='container'>
<div class='row'><br>
<div class='page-header hidden-print'>
<h2 class='no_imprimir'>Cuaderno de Notas&nbsp;&nbsp;<small>Orden de las
columnas del Cuaderno</small></h2>
</div>

<?php
$pr = $_SESSION['profi'];

if (isset($_GET['asignatura'])) {
	$asignatura = $_GET['asignatura'];
	$asignatura = preg_replace('([^A-Za-z0-9 ])', '', $asignatura);
}
if (isset($_GET['curso'])) {
	$curso= $_GET['curso'].",";
	$curso = preg_replace('([^A-Za-z0-9 ])', '', $curso);
}
if (isset($_GET['dia'])) {
	$dia = $_GET['dia'];
}
if (isset($_GET['hora'])) {
	$hora = $_GET['hora'];
}
if (isset($_GET['nom_asig'])) {
	$nom_asig = $_GET['nom_asig'];
}


if (isset($_POST['enviar'])) {
	$num="";
	foreach ($_POST as $key=>$val){
		if (is_numeric($val)) {
			$tr =explode("-",$key);
			$id=$tr[1];
			if (strlen($id)>0) {
				$col= "update notas_cuaderno set orden= '$val' where id = '$id'";
				$colu = mysqli_query($db_con,$col);
				if ($colu) {
					$num+=1;
				}
			}
		}
	}
	if ($num>0) {
		echo '<div align="center"><div class="alert alert-success alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
El orden de las Columnas del Cuaderno se ha modificado correctamente.          
</div></div>';
	}
}
?>
<div class="col-sm-4 col-sm-offset-4"><?php
$curso_sin=substr($curso,0,-1);
echo "<h3 align='center' ><span class='label label-info' style='padding:8px'>$curso_sin -- Columnas del Cuaderno </span></h3><br>";

$colum= "select distinct id, nombre, orden from notas_cuaderno where profesor = '$pr' and curso = '$curso' and asignatura='$asignatura' order by orden asc";

$colum0 = mysqli_query($db_con, $colum);

$num_col=mysqli_num_rows($colum0);
$num_col=$num_col+1;

echo '<div class="well"><form action="orden.php" method="POST" id="orden" class="form-inline"><ul class="list-unstyled">';

//	if ($num_col > 1) {

while($columnas=mysqli_fetch_array($colum0)){
	$sel="";
	$id = $columnas[0];
	$nom = $columnas[1];
	$ord = $columnas[2];

	echo "<li><div class='form-group'><label for='ordi-$id'><select id='ordi-$id' name='ordi-$id' class='form-control input-sm'> ";

	for ($i=1;$i<$num_col;$i++) {
		if ($ord==$i) {	$sel = "selected";}	else{ $sel="";}
		echo "<option $sel value='$i'>$i</option>";
	}

	$mens0 = "c_nota.php?profesor=$pr&curso=$curso&dia=$dia&hora=$hora&id=$id&orden=$ord&nom_asig=$nom_asig&asignatura=$asignatura";
	echo "</select>&nbsp;&nbsp; <a href='$mens0'> $nom</a> <label></li>";
}

//}
echo "</ul>";
echo "<input name=curso value='$curso' type='hidden'>";
echo "<input name=asignatura value='$asignatura' type='hidden'>";
echo "<input name=dia value='$dia' type='hidden'>";
echo "<input name=hora value='$hora' type='hidden'>";
?> <input type="submit" name="enviar" value="Aceptar"
	class="btn btn-default">
</form>
</div>
</div>
</div>

<?php include("../pie.php"); ?>

</body>
</html>
