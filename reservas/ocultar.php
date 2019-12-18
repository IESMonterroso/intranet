<?php
require('../bootstrap.php');

acl_acceso($_SESSION['cargo'], array(1));

include("../menu.php");
include("menu.php");
?>

<div class="container">

<div class="page-header">
<h2>Reservas <small> Crear y Ocultar Aulas / Dependencias del centro</small></h2>
</div>
<div class="row"><?php
if (isset($_POST['nueva'])) {
	$num = count($_POST);
	if ($num>1) {
		$abrev_nueva = preg_replace('([^A-Za-z0-9 ])', '', $_POST['abrev_nueva']);
		$nombre_nueva = preg_replace('([^A-Za-z0-9 ])', '', $_POST['nombre_nueva']);
		$texto = preg_replace('([^A-Za-z0-9 ])', '', $_POST['texto']);
		if ($_POST['nueva']=="Crear nueva Aula / Dependencia") {
			mysqli_query($db_con,"insert into nuevas values ('','$abrev_nueva','$nombre_nueva','$texto')");
			if (mysqli_affected_rows($db_con)>0) {
				$msg = "Los datos se han registrado correctamente. Las aulas / dependencias creadas aparecerán en el sistema de reservas a partir de ahora.";
			}			}

			elseif ($_POST['nueva']=="Actualizar datos del Aula / Dependencia") {
				if (is_numeric($_POST['id'])) {
					mysqli_query($db_con,"update nuevas set abrev='$abrev_nueva', nombre='$nombre_nueva', texto='$texto' where id = '".preg_replace('([^0-9])', '', $_POST['id'])."'");
				}
				else{
					$tr_h=explode(":",$_POST['id']);
					$a_aul = $tr_h[0];
					$n_aul = $tr_h[1];
					$actualiza_hor = mysqli_query($db_con,"update horw set a_aula='$abrev_nueva', n_aula='$nombre_nueva' where a_aula = '$a_aul' and n_aula = '$n_aul'");
					$actualiza_hor_faltas = mysqli_query($db_con,"update horw_faltas set a_aula='$abrev_nueva', n_aula='$nombre_nueva' where a_aula = '$a_aul' and n_aula = '$n_aul'");
					$msg = "Los datos se han actualizado correctamente. Las aulas / dependencias actualizadas aparecerán en el sistema de reservas con los nuevos datos.";
				}
			}
	}
	?> 

<br>
<div class="alert alert-success">
<p><?php echo $msg;?><p>
</div>

	<?php
}
if (isset($_POST['enviar'])) {
	$num = count($_POST);
	mysqli_query($db_con,"truncate table ocultas");
	foreach ($_POST as $valor){
		if ($valor!=="Enviar datos") {
			mysqli_query($db_con,"insert into ocultas values ('','$valor')");
		}
	}
	?> 

<br>
<div class="alert alert-success">
<p>Los datos se han registrado correctamente. Las aulas y dependencias
seleccionadas dejarán de aparecer en el sistema de reservas a partir de
ahora.<P>
</div>

	<?php
}
if (isset($_GET['eliminar'])) {
	$id = $_GET['id'];
	if(!is_numeric($id)) {
		echo("Attack detected!");
		die();
	}
	mysqli_query($db_con,"delete from nuevas where id = '$id'");
	if (mysqli_affected_rows($db_con)>0) {
		$msg = "El aula/dependencia ha sido eliminada del sistema de reservas.";
	}
	?> <br>

<div class="alert alert-success">
<p><?php echo $msg;?><P>
</div>

	<?php
}

if (isset($_GET['editar'])) {
	$id = $_GET['id'];
	if (is_numeric($_GET['id'])) {
		$ya = mysqli_query($db_con,"select * from nuevas where id = '$id'");
		if (mysqli_num_rows($ya)>0) {
			$ya_id = mysqli_fetch_array($ya);
			$abrev_nueva = $ya_id[1];
			$nombre_nueva =  $ya_id[2];
			$texto = $ya_id[3];
		}
	}
	else{
		$tr_h=explode(":",$id);
		$a_aul = $tr_h[0];
		$n_aul = $tr_h[1];
		$ya = mysqli_query($db_con,"select a_aula, n_aula from horw where a_aula = '$a_aul' and n_aula = '$n_aul'");
		if (mysqli_num_rows($ya)>0) {
			$ya_id = mysqli_fetch_array($ya);
			$abrev_nueva = $ya_id[0];
			$nombre_nueva =  $ya_id[1];
		}
	}


}
?>
<div class="col-sm-5 col-sm-offset-1">
<h3>Ocultar Aulas / Dependencias</h3>
<p class="help-block text-justify well">A través de esta página puedes
seleccionar los espacios del centro que quedan fuera del sistema de
reservas. Marca la casilla de aquellas dependencias que quieres ocultar
y envía los datos. A partir de ese momento las dependencias elegidas
quedarán ocultas en la selección de aulas del sistema de reservas.</p>
<form action="ocultar.php" method="post">
<table class="table table-striped">
<?php
echo "<thead><th colspan=3>Aulas en el Horario</th><th></th></thead>";
$aulas = mysqli_query($db_con,"select distinct a_aula, n_aula from horw where a_aula NOT LIKE 'G%' AND a_aula NOT LIKE '' order by a_aula");
while ($aula = mysqli_fetch_array($aulas)) {
	$check="";
	$abrev0 = $aula[0];
	$nombre0 = $aula[1];
	$ya = mysqli_query($db_con,"select * from ocultas where aula = '$abrev0'");
	if (mysqli_num_rows($ya)>0) {
		$check = " checked";
	}

	echo "<tr>";
	echo "<td><input type='checkbox' name='$abrev0' value='$abrev0' $check/></td>";
	echo "<td>$abrev0</td><td>$nombre0</td>";
	echo "<td>$nombre_nueva0 <span class='pull-right'><a href='ocultar.php?editar=1&id=$abrev0:$nombre0'><span class='far fa-edit fa-fw fa-lg' data-bs='tooltip' title='Editar'></span></a></td>";
	echo "</tr>";
}
?>
<?php
$aulas_nueva = mysqli_query($db_con,"select distinct abrev, nombre, id from nuevas order by abrev");
echo "<thead><th colspan=3>Aulas fuera del Horario</th><th></th></thead>";
while ($aula_nueva = mysqli_fetch_array($aulas_nueva)) {
	$check="";
	$abrev_nueva0 = $aula_nueva[0];
	$nombre_nueva0 = $aula_nueva[1];
	$id_nueva0 = $aula_nueva[2];
	$ya_nueva = mysqli_query($db_con,"select * from ocultas where aula = '$abrev_nueva0'");
	if (mysqli_num_rows($ya_nueva)>0) {
		$check = " checked";
	}

	echo "<tr>";
	echo "<td><input type='checkbox' name='$abrev_nueva0' value='$abrev_nueva0' $check/></td>";
	echo "<td>$abrev_nueva0</td>
	<td>$nombre_nueva0 <span class='pull-right'></td>
	<td><a href='ocultar.php?editar=1&id=$id_nueva0'><span class='far fa-edit fa-fw fa-lg' data-bs='tooltip' title='Editar'></span></a>
	<a href='ocultar.php?id= $id_nueva0&eliminar=1' data-bb='confirm-delete'><span class='far fa-trash-alt fa-fw fa-lg' data-bs='tooltip' title='Eliminar'></span></a></td>";
	echo "</tr>";
}
?>
	<tr>



		<td colspan="4">



		<center><input type="submit" name="enviar" value="Enviar datos"
			class="btn btn-default"></center>
		</td>



	</tr>
	</tbody>
</table>
</form>
</div>
<div class="col-sm-5">
<h3>Crear y editar Aula / Dependencia</h3>
<p class="help-block text-justify well">Si el Centro no ha importado el
Horario en la Base de datos, o bien si quieres poder reservar una
dependencia o aula que no aparece en el Horario, es posible crear aulas
para introducirlas en el sistema de reservas. Crea las aulas rellenando
los datos en el formulario para que la misma aparezca en la lista de
reservas.</p>
<form action="ocultar.php" method="post">
<div class="form-group"><label>Abreviatura</label> <input
	class="form-control" type="text" maxlength="5" name="abrev_nueva"
	value="<?php echo $abrev_nueva;?>"
	placeholder="5 caracteres como máximo"></div>
<div class="form-group"><label>Nombre del Aula</label> <input
	class="form-control" type="text" name="nombre_nueva"
	value="<?php echo $nombre_nueva;?>"></div>
<?php if (is_numeric($id)) { ?>
	<div class="form-group"><label>Observaciones</label> <textarea
	class="form-control" name="texto"><?php echo $texto;?></textarea></div>
<?php } ?>
<?php
if ($id) {
	?> <input type="hidden" name="id" value="<?php echo $id;?>"> <input
	class="btn btn-default" type="submit" name="nueva"
	value="Actualizar datos del Aula / Dependencia" /> <?php
}
else{
	?> <input class="btn btn-default" type="submit" name="nueva"
	value="Crear nueva Aula / Dependencia" /> <?php
}
?></form>


</div>
</div>
</div>
<?php include("../pie.php");?>

</body>
</html>









