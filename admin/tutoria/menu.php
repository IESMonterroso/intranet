<?
$activo1="";
$activo2="";
if (strstr($_SERVER['REQUEST_URI'],'global.php')==TRUE) {$activo1 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],'tutor.php')==TRUE){ $activo2 = ' class="active" ';}

if (isset($_GET['tutor'])) {
	$tutor = $_GET['tutor'];
}
elseif (isset($_POST['tutor'])) {
$tr = explode(" ==> ",$_POST['tutor']);
$tutor = $tr[0];
}
else{$tutor = $_SESSION['profi'];}

  	$tutor2 = mysql_query("SELECT  nivel, grupo FROM FTUTORES where tutor = '$tutor'");
 	$ftutor = mysql_fetch_array($tutor2);
	$nivel = $ftutor[0];
	$grupo = $ftutor[1];

?>
<div class="container">  
	<ul class="nav nav-tabs">
<li<? echo $activo1;?>><a href="global.php?tutor=<? echo $tutor;?>">Resumen General</a></li>
      <li<? echo $activo2;?>><a href="tutor.php?tutor=<? echo $tutor;?>" >Nueva Acci&oacute;n tutorial</a></li>
      <?    if ($mod_sms) {?>
      <li><a href="../../sms/index.php?nivel=<? echo $nivel;?>&grupo=<? echo $grupo;?>">Enviar SMS</a></li>
      <? }?>
       <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Consultas <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href="../cursos/ccursos.php?submit1=1&nivel=<? echo $nivel;?>&grupo=<? echo $grupo;?>">Lista del Grupo</a></li>
          <li><a  href="../fotos/grupos.php?curso=<? echo $nivel;?>-<? echo $grupo;?>">Fotos del Grupo</a></li>
          <li><a  href="../fotos/fotos.php?nivel=<? echo $nivel;?>&grupo=<? echo $grupo;?>">Registrar Fotos</a></li>
                    <li><a  href="../../xml/jefe/form_carnet.php">Crear Carnet del Alumno</a></li>
          <li><a  href="../datos/datos.php?nivel=<? echo $nivel;?>&grupo=<? echo $grupo;?>">Datos de los Alumnos</a></li>
          <li><a  href="absentismo.php?tutor=<? echo $tutor;?>" >Alumnos Absentistas</a></li>
          <li><a  href="http://<? echo $dominio; ?>/intranet/upload/index.php?&direction=0&order=&directory=programaciones/orientacion" >Materiales de Orientaci�n</a></li>
        </ul>
      </li>
      <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Informes <b class="caret"></b></a>
        <ul class="dropdown-menu">
          <li><a href="../informes/cinforme.php?nivel=<? echo $nivel;?>&grupo=<? echo $grupo;?>">Informe de un Alumno</a></li>
          <li><a href="../infotutoria/index.php" style="border-top:none;border-left:none;">Informes de Tutor�a</a></li>
          <li><a href="../tareas/index.php" style="border-top:none;border-left:none;">Informes de Tareas</a></li>
          <li><a  href="memoria.php?nivel=<? echo $nivel;?>&tutor=<? echo $tutor;?>&grupo=<? echo $grupo;?>"  >Memoria de Tutor�a</a></li>
        </ul>
      </li>
      <?
  if(substr($nivel,1,1) == "E"){
  ?>
      <li><a  href="../libros/libros.php?nivel=<? echo $nivel;?>&amp;grupo=<? echo $grupo;?>&amp;tutor=1">Libros de Texto</a></li>
      <? } ?>
    </ul>
</div>