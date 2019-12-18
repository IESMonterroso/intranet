<?php
require('../../bootstrap.php');

acl_acceso($_SESSION['cargo'], array('0', '1'));

$profe = $_SESSION['profi'];
include("../../menu.php");
?>

<div class="container">

<div class="page-header">
<h2>Administración <small> Gestión de los departamentos</small></h2>
</div>

<div class="row">
<div class="col-sm-7">
<p class="help-block" align="left">(*) Este formulario permite tanto <em>Cambiar
el nombre</em> a una Especialidad o Departamento como <em>Incorporar a
los miembros</em> de una Especialidad en otro Departamento. Para cambiar
el nombre simplemente escribe el nuevo nombre en el campo de texto; para
incorporar una Especialidad en un Departamento, selecciónalo en la lista
desplegable. Una vez realizados los cambios, haz clic en el botón <em><b>Enviar
datos</b></em>. Si actualizas los Departamentos, no te olvides
incorporar a los profesores nuevos en el Departamento correspondiente,
ya que aparecerán asociados a su <em>Especialidad de Séneca</em>.</p>
<br />
<?php
if (isset($_POST['enviar']) and $_POST['enviar'] == "Enviar datos") {
	$n_reg="";
	foreach ($_POST as $key=>$val){
		if (is_numeric(substr($key,0,1)) and strlen($val)>2) {
			$n_reg+=1;
			$tr_cambio = explode("#",$key);
			$origen = $tr_cambio[1];
			$origen = substr($origen,0,strlen($origen)-1);
			$q1 = mysqli_query($db_con, "select distinct departamento from departamentos");
			while ($q2 = mysqli_fetch_array($q1)) {
				$trasform1 = str_ireplace(" ","_",$q2[0]);
				$trasform2 = str_ireplace(".","_",$trasform1);
				if ($origen==$trasform2) {
					// Actualizamos tabla departamentos
					mysqli_query($db_con, "update departamentos set departamento = '$val' where departamento = \"$q2[0]\"");
					// Actualizamos departamento en tablas relacionadas
					$n_dep = array("inventario","mem_dep","r_departamento","calendario");
					foreach ($n_dep as $sust_dep){
						mysqli_query($db_con, "update $sust_dep set departamento = '$val' where departamento = '$q2[0]'");
					}
				}
			}
		}
		elseif (strlen($val)>2 and !($key=="enviar")){
			$n_reg+=1;
			$q1 = mysqli_query($db_con, "select distinct departamento from departamentos");
			while ($q2 = mysqli_fetch_array($q1)) {
				$trasform1 = str_ireplace(" ","_",$q2[0]);
				$trasform2 = str_ireplace(".","_",$trasform1);
				if ($key==$trasform2) {
					
					// Actualizamos tabla departamentos
					mysqli_query($db_con, "update departamentos set departamento = '$val' where departamento = '$q2[0]'");

					// Actualizamos departamento en tablas relacionadas
					$n_dep = array("inventario","mem_dep","r_departamento","calendario");
					foreach ($n_dep as $sust_dep){
						mysqli_query($db_con, "update $sust_dep set departamento = '$val' where departamento = '$q2[0]'");
					}
				}
			}
		}
	}
}
if ($n_reg>0) {
	echo '<div align="center"><div class="alert alert-success alert-block fade in">
		            <button type="button" class="close" data-dismiss="alert">&times;</button>
		El Departamento ha sido actualizado en la base de datos.
		          </div></div><br />';
}
//exit();
if ($_GET['borrar']=='1') {
	mysqli_query($db_con, "update departamentos set departamento = '' where departamento = '".preg_replace('([^A-Za-z0-9 ])', '', $_GET['departament'])."'");
	//echo "update departamentos set departamento = '' where departamento = '".$_GET['departament']."'";
	echo '<div align="center"><div class="alert alert-success alert-block fade in">
		            <button type="button" class="close" data-dismiss="alert">&times;</button>
		El Departamento ha sido borrado de la base de datos..
		          </div></div><br />';
}
?> <legend>Departamentos del Centro</legend>

<form action="gest_dep.php" method="POST" name="form1">
<table class="table table-bordered table-striped table-condensed">
	<thead>
		<th>Departamento</th>
		<th>Cambiar el nombre</th>
		<th>Incorporar a Departamento</th>
		</th>
		<th></th>
	</thead>
	<tbody>
	<?php
	$dep0 = "select distinct nomdepartamento from departamentos_seneca order by nomdepartamento";
	$dep1 = mysqli_query($db_con, $dep0);
	$n_d="";
	while ($dep = mysqli_fetch_array($dep1)) {
		$n_d+=1;
		echo "<tr><td>$dep[0]</td><td><input type='text' name=\"$n_d#$dep[0]\" class='form-control' /></td>";

		echo '<td><select name="'.$dep[0].'" id="departamento" class="form-control"><option></option>';
		$profe = mysqli_query($db_con, " SELECT distinct nomdepartamento FROM departamentos_seneca where nomdepartamento not like '' order by nomdepartamento asc");
		while($filaprofe = mysqli_fetch_array($profe))
		{
			$departamen = $filaprofe[0];
			echo "<OPTION>$departamen</OPTION>";
		}
		$profe2 = mysqli_query($db_con, " SELECT distinct departamento FROM departamentos where departamento not like '' and departamento not in (SELECT distinct nomdepartamento FROM departamentos_seneca) and departamento not like 'admin' and departamento not like 'Administracion' and departamento not like 'Conserjeria' order by departamento asc");
		while($filaprofe2 = mysqli_fetch_array($profe2))
		{
			$departamen2 = $filaprofe2[0];
			echo "<OPTION>$departamen2</OPTION>";
		}
		echo "</select>
		  </td><td><a href='gest_dep.php?borrar=1&departament=$dep[0]' data-bb='confirm-delete'><i class='far fa-trash-alt fa-lg fa-fw'></i></a></td></tr>";	
	}
	?>
	</tbody>
</table>
<div align="center"><input class="btn btn-block btn-primary"
	type="submit" name="enviar" value="Enviar datos" /></div>
</form>
</div>

<div class="col-sm-5">
<p class="help-block" align="left">(*) Este formulario permite cambiar
el Departamento al que pertenece un profesor. Selecciona el Departamento
al que quieres asignar al Profesor y envía los datos. Si actualizas los
Departamentos, no te olvides incorporar a los profesores nuevos en el
Departamento correspondiente, ya que aparecerán asociados a su <em>Especialidad
de Séneca</em>.</p>
<br />
	<?php
	if (isset($_POST['cambiar']) and $_POST['cambiar'] == "Cambiar Departamento") {
		foreach ($_POST as $key=>$val){
			// Actualizamos tabla departamentos
			mysqli_query($db_con, "update departamentos set departamento = '$val' where idea = '$key'");
		}
		echo '<div align="center"><div class="alert alert-success alert-block fade in">
		            <button type="button" class="close" data-dismiss="alert">&times;</button>
		El Profesor ha sido asignado al nuevo Departamento.
		          </div></div><br />';
	}
	?> <legend>Profesores del Centro</legend>
<form action="gest_dep.php" method="POST" name="form2">
<table class="table table-bordered table-striped table-condensed">
	<thead>
		<th>Profesor</th>
		<th>Departamento</th>
	</thead>
	<tbody>
	<?php
	$prof0 = "select distinct nombre, idea from departamentos where departamento not like 'admin' and departamento not like 'Administracion' and departamento not like 'Conserjeria'  order by nombre";
	$prof1 = mysqli_query($db_con, $prof0);

	while ($prof = mysqli_fetch_array($prof1)) {
		echo "<tr><td>$prof[0]</td>";
		$act = mysqli_query($db_con, "select departamento from departamentos where idea = '$prof[1]'");
		$actual = mysqli_fetch_row($act);
		echo '<td><select name="'.$prof[1].'" id="departamento" class="form-control">';
		if (strlen($actual[0])>0) {
			echo '<option>'.$actual[0].'</option><option></option>';
		}
		else{ echo '<option></option>';}
		$profes = mysqli_query($db_con, " SELECT distinct nomdepartamento FROM departamentos_seneca where nomdepartamento not like '' order by nomdepartamento asc");
		while($filaprofes = mysqli_fetch_array($profes))
		{
			$departamens = $filaprofes[0];
			echo "<OPTION>$departamens</OPTION>";
		}
		$profe2 = mysqli_query($db_con, " SELECT distinct departamento FROM departamentos where departamento not like '' and departamento not in (SELECT distinct nomdepartamento FROM departamentos_seneca) and departamento not like 'admin' and departamento not like 'Administracion' and departamento not like 'Conserjeria' order by departamento asc");
		while($filaprofe2 = mysqli_fetch_array($profe2))
		{
			$departamen2 = $filaprofe2[0];
			echo "<OPTION>$departamen2</OPTION>";
		}
		echo "</select></td></tr>";
	}
	?>
	</tbody>
</table>
<div align="center"><input class="btn btn-block btn-primary"
	type="submit" name="cambiar" value="Cambiar Departamento" /></div>
</form>

</div>

</div>
</div>

	<?php include("../../pie.php"); ?>
	
</body>
</html>
