<?
session_start();
include("../../config.php");
include_once('../../config/version.php');

// COMPROBAMOS LA SESION
if ($_SESSION['autentificado'] != 1) {
	$_SESSION = array();
	session_destroy();
	header('Location:'.'http://'.$dominio.'/intranet/salir.php');
	exit();
}

registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);

if(!(stristr($_SESSION['cargo'],'1') == TRUE))
{
	header("location:http://$dominio/intranet/salir.php");
	exit;
}
?>
<?php
include("../../menu.php");
?>
	<div class="container">
			<div class="page-header">
			<h2>Administraci�n <small>Importaci�n del horario con archivo DEL de Horwin</small></h2>
		</div>	
			<div class="row">
<?

$fp = fopen ( $_FILES['archivo']['tmp_name'] , "r" );
if (( $data = fgetcsv ( $fp , 1000 , "," )) !== FALSE ) {
	$num_col=count($data);
	if ($num_col<>13) {
		echo '<div align="center"><div class="alert alert-danger alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<legend>Atenci�n:</legend>
El archivo de Horwin que est�s intentando exportar contiene <strong>'.$num_col.' columnas</strong> de datos y debe contener <strong>13 columnas</strong>. Aseg�rate de que el archivo de Horwin sigue las instrucciones de la imagen, y vuelve a intentarlo.
</div></div><br />
<div align="center">
  <input type="button" value="Volver atr�s" name="boton" onClick="history.back(2)" class="btn btn-inverse" />
</div><br />';
		exit();
	}
}

// Backup
mysqli_query($db_con,"truncate table horw_seg");
mysqli_query($db_con,"insert into horw_seg select * from horw");
mysqli_query($db_con,"truncate table horw_seg_faltas");
mysqli_query($db_con,"insert into horw_seg_faltas select * from horw_faltas");

mysqli_query($db_con,"truncate table horw");
mysqli_query($db_con,"ALTER TABLE  `horw` CHANGE  `asig`  `asig` VARCHAR( 128 ) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL DEFAULT  ''
");
// Claveal primaria e �ndice

while (( $data = fgetcsv ( $fp , 1000 , "," )) !== FALSE ) {
	// Mientras hay l�neas que leer... si necesitamos a�dir s�lo las clases hay que hacer aqu� un if ($data[9]!='')
	$sql="INSERT INTO horw (dia,hora,a_asig,asig,c_asig,prof,no_prof,c_prof,a_aula,n_aula,a_grupo,nivel,n_grupo) ";
	$sql.=" VALUES ( ";
	foreach ($data as $indice=>$clave){
		$sql.="'".trim($clave)."', ";
	}
	$sql=substr($sql,0,strlen($sql)-2);
	$sql.=" )";
	//echo $sql."<br>";
	mysqli_query($db_con,$sql) or die ('<div align="center"><div class="alert alert-danger alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCI�N:</h5>
No se han podido insertar los datos en la tabla <strong>Horw</strong>. Ponte en contacto con quien pueda resolver el problema.
</div></div><br />
<div align="center">
  <input type="button" value="Volver atr�s" name="boton" onClick="history.back(2)" class="btn btn-inverse" />
</div>');	
}
fclose ( $fp );

// Eliminamos el Recreo como 4� Hora.
$recreo = "DELETE FROM horw WHERE hora ='4'";
mysqli_query($db_con,$recreo);
$hora4 = "UPDATE  horw SET  hora =  '4' WHERE  hora = '5'";
mysqli_query($db_con,$hora4);
$hora5 = "UPDATE  horw SET  hora =  '5' WHERE  hora = '6'";
mysqli_query($db_con,$hora5);
$hora6 = "UPDATE  horw SET  hora =  '6' WHERE  hora = '7'";
mysqli_query($db_con,$hora6);
mysqli_query($db_con,"OPTIMIZE TABLE  `horw`");

// Eliminamos Nivel y Grupo (obsoleto)
mysqli_query($db_con,"update horw set n_grupo='', nivel=''");

// Cambiamos los numeros de Horw para dejarlos en orden alfab�tico.
$hor = mysqli_query($db_con, "select distinct prof from horw order by prof");
while($hor_profe = mysqli_fetch_array($hor)){
	$np+=1;
	$sql = "update horw set no_prof='$np' where prof = '$hor_profe[0]'";
	//echo "$sql<br>";
	$sql1 = mysqli_query($db_con, $sql);
}

// Limpiez de codigos
$h1 = mysqli_query($db_con, "select id, c_asig, a_grupo, asig, unidades.idcurso, nomcurso from horw, unidades, cursos where a_grupo=nomunidad and unidades.idcurso=cursos.idcurso and a_grupo not like ''");
while ($h2 = mysqli_fetch_array($h1)) {
	$id_horw = $h2[0];
	$curso = $h2[5];
	$cod = $h2[1];
	$nombre_asignatura = $h2[3];
	
	
// Primera pasada	
	$asig = mysqli_query($db_con, "select codigo, nombre from asignaturas where curso = '$curso' and curso not like '' and nombre = '$nombre_asignatura' and codigo not like '2' and abrev not like '%\_%'");
if (mysqli_num_rows($asig)>0) {
	$asignatur = mysqli_fetch_array($asig);
	$asignatura=$asignatur[0];
	if (!($asignatura==$cod)) {
		$codasi = $asignatura;
		mysqli_query($db_con, "update horw set c_asig = '$codasi' where id = '$id_horw'");
		//echo "update horw set c_asig = '$codasi' where id = '$id_horw'<br>";
	}
	else{
		$codasi="";
	}	
}

// Segunda pasada	
	$asig2 = mysqli_query($db_con, "select codigo, nombre from asignaturas where curso = '$curso' and curso not like '' and (codigo not like '2' and codigo = '$cod') and abrev not like '%\_%'");
if (mysqli_num_rows($asig2)>0) {
	$asignatur2 = mysqli_fetch_array($asig2);
	$asignatura2=$asignatur2[0];
	$nombre_asig2=$asignatur2[1];
	if ($asignatura2==$cod) {
		$codasi2 = $asignatura2;
		mysqli_query($db_con, "update horw set c_asig = '$codasi2', asig='$nombre_asig2' where id = '$id_horw'");
	}
	else{
		$codasi2="";
	}
	
}


}

	// Metemos a los profes en la tabla profesores hasta que el horario se haya exportado a S�neca y consigamos los datos reales de los mismos
	$tabla_profes =mysqli_query($db_con,"select * from profesores");
	if (mysql_num_rows($tabla_profes) > 0) {}
	else{
		// Recorremos la tabla Profesores bajada de S�neca
		$pro =mysqli_query($db_con,"select distinct asig, a_grupo, prof from horw where a_grupo like '1%' or a_grupo like '2%' or a_grupo like '3%' or a_grupo like '4%' order by prof");
		while ($prf =mysqli_fetch_array($pro)) {
			$materia = $prf[0];
			$grupo = $prf[1];
			$profesor = $prf[2];
			$niv =mysqli_query($db_con,"select distinct curso from alma where unidad = '$grupo'");
			$nive =mysqli_fetch_array($niv);
			$nivel = $nive[0];

			mysqli_query($db_con,"INSERT INTO  profesores (
`nivel` ,
`materia` ,
`grupo` ,
`profesor`
) VALUES ('$nivel', '$materia', '$grupo', '$profesor')");
		}
	}
	
// Horw para Faltas
mysqli_query($db_con, "drop table horw_faltas");
mysqli_query($db_con, "create table horw_faltas select * from horw");
mysqli_query($db_con, "delete from horw_faltas where a_grupo = ''");

	// Tutores
	$tabla_tut =mysqli_query($db_con,"select * from FTUTORES");
	if(mysql_num_rows($tabla_tut) > 0){}
	else{
		mysql_query("insert into FTUTORES (nivel, grupo, tutor) select distinct nivel, n_grupo, prof from horw where a_asig like '%TUT%'");
	}
	?>
	<div class="alert alert-success alert-block fade in" >
            <button type="button" class="close" data-dismiss="alert">&times;</button>
El Horario ha sido importado correctamente.
</div></div><br />
<div align="center">
  <input type="button" value="Volver atr�s" name="boton" onClick="history.back(2)" class="btn btn-inverse" />
</div><br />
	</div>
	</div>
	<? include("../../pie.php");?>