<?php
require('../../bootstrap.php');


if (stristr ( $_SESSION ['cargo'], '4' ) == TRUE or stristr ( $_SESSION ['cargo'], '1' ) == TRUE) { } else { $j_s = 'disabled'; }

include("../../menu.php");
echo '<div class="no_imprimir">';
include("menu.php");
echo '</div>';

   	$query = "SELECT contenido, fecha, numero, departamento FROM r_departamento WHERE id = '$id'";
   	$result = mysqli_query($db_con, $query) or die ("Error en la Consulta: $query. " . mysqli_error($db_con));
   	if (mysqli_num_rows($result) > 0)
   	{
   		$row = mysqli_fetch_object($result);
   	}
 
if ($row)
{?>
 <div align="center">
<div class="page-header">
  <h2>Actas del Departamento <small> Registro de Reuniones ( <?php  echo $row->departamento;?> )</small></h2>
</div>
</div>
<div class="container-fluid">
<div class="row">
<div class="col-sm-1"></div>
<div class="col-sm-10">
<?php
		?>

<div class="well-transparent" style="width:925px;margin:auto;">
<legend class="no_imprimir">
<?php
//fecha_actual($row->fecha);
?>
</legend>
<?php
if (!($j_s=='disabled')) {
?>
<a href="pdf.php?id=<?php echo $id; ?>&imprimir=1"  style="margin-right:20px;" class="btn btn-primary pull-right no_imprimir"> 
<i class="fa fa-print " data-bs="tooltip" title='Crear PDF del Acta para imprimir o guardar'> </i> Imprimir PDF</a>
<?php
}
?>


<?php  
			echo $row->contenido;
?>
 </div>
<br /> 
  <?php
}
else
{
?>
 <div align="center">
<div class="page-header">
  <h2>Actas del Departamento <small> Contenido de la Reuni�n ( <?php  echo $row->departamento;?> )</small></h2>
</div>

<div class="container-fluid">
<div class="row">
<div class="col-sm-4 col-sm-offset-4">
<div class="alert alert-danger alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4>ATENCI�N:</h4>Esa noticia no se encuentra en la base de datos
          </div>
<?php
}
?>
</div>
</div>
</div>
<?php include("../../pie.php");?>
</body>
</html>
