<?
session_start();
include("../../../config.php");
if($_SESSION['autentificado']!='1')
{
session_destroy();
header("location:http://$dominio/intranet/salir.php");	
exit;
}
registraPagina($_SERVER['REQUEST_URI']);
?>
<?
$conn = mysql_connect($db_host, $db_user, $db_pass) or die("Could not connect to database!");

$fecha = $_POST['fecha']; 
if (isset($_POST['tipo'])) { $tipo = $_POST['tipo']; }
elseif (isset($_GET['tipo'])) { $tipo = $_GET['tipo']; }
else{$tipo="";}
if (isset($_POST['titulo'])) { $titulo = $_POST['titulo']; }
elseif (isset($_GET['titulo'])) { $titulo = $_GET['titulo']; }
else{$titulo="";}
if (isset($_POST['observaciones'])) { $observaciones = $_POST['observaciones']; }
elseif (isset($_GET['observaciones'])) { $observaciones = $_GET['observaciones']; }
else{$observaciones="";}
if (isset($_POST['calendario'])) { $calendario = $_POST['calendario']; }
elseif (isset($_GET['calendario'])) { $calendario = $_GET['calendario']; }
else{$calendario="";}

if (isset($_POST['grupos'])) { 
foreach ($grupos as $grup){
$tr_gr = explode(" => ",$grup);	
$grupo.=$tr_gr[0]."; ";
$materia.=$tr_gr[1]."; ";
}
}
//$grupo = substr($grupo,0,-2);
//$materia = substr($materia,0,-2);
$event_found = "";
if (isset($_POST['id']) and strlen($_POST['id'])>0) { 
  //UPDATE
    $postQuery = "UPDATE `diario` SET fecha = '".$fecha."', grupo = '".$grupo."', materia = '$materia', tipo = '$tipo', titulo = '".$titulo."', observaciones = '".$observaciones."', calendario = '".$calendario."' where id='$id'";
    $postExec = mysql_query($postQuery) or die("Could not Post UPDATE diario Event to database!");
	header("Location: index.php?id=$id&mens=actualizar");

} else {
  //INSERT
    $postQuery = "INSERT INTO diario (fecha,grupo,materia,tipo,titulo,observaciones,calendario,profesor) VALUES ('".$fecha."','".$grupo."','".$materia."','$tipo','".$titulo."','".$observaciones."','".$calendario."','".$_SESSION['profi']."')";
    $postExec = mysql_query($postQuery);
    header("Location: index.php?mens=insertar");
}
//echo $postQuery;

mysql_close($conn);
?>
gust")
	{$monthlong = "Agosto";}
    elseif ($monthlo