<?php
require('../../bootstrap.php');
	
include("../../menu.php");
include("../menu.php");
if (isset($_GET['profesor'])) {$profesor = $_GET['profesor'];} elseif (isset($_POST['profesor'])) {$profesor = preg_replace('([^A-Za-z0-9 ])', '', $_POST['profesor']);}
if (isset($_GET['year'])) {$year = $_GET['year'];}elseif (isset($_POST['year'])) {$year = $_POST['year'];}
if (isset($_GET['month'])) {$month = $_GET['month'];}elseif (isset($_POST['month'])) {$month = $_POST['month'];}
if (isset($_GET['today'])) {$today = $_GET['today'];}elseif (isset($_POST['today'])) {$today = $_POST['today'];}else{$today="";}
if (isset($_GET['alumno'])) {$alumno = $_GET['alumno'];}elseif (isset($_POST['alumno'])) {$alumno = $_POST['alumno'];}else{$alumno="";}
if (isset($_GET['unidad'])) {$unidad = $_GET['unidad'];}elseif (isset($_POST['unidad'])) {$unidad = $_POST['unidad'];}else{$unidad="";}
if (isset($_GET['falta'])) {$falta = $_GET['falta'];}elseif (isset($_POST['falta'])) {$falta = $_POST['falta'];}else{$falta="";}
?>

<div class="container">
<div class="row">

<div class="page-header">
  <h2 style="display:inline">Faltas de Asistencia <small> Justificar faltas</small></h2>
</div>
  
    <?php
// Se presenta la estructura de las tablas del formulario.
include("estructura.php");
?>
</form>
</div>
</div>
<?php 
mysqli_close();
include("../../pie.php"); ?>
</body>
</html>
