<?
// Copia de la Base de datos principal para mantener registro de cursos acad�micos
$curso_pasado=date('Y');
$nombre_copia=$db.$curso_pasado;
echo '<div align="center">
<div class="alert alert-info alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            A principio de cada Curso escolar se crea una copia de la base de datos principal, <strong><em>'.$db.'</em></strong>, con el a�o del curso escolar a�adido al final del nombre (en este caso <strong><em>'.$nombre_copia.'</em></strong>). A continuaci�n se vac�an las tablas adecuadas, aunque se mantienen las que contienen datos persistentes. Una vez completadas estas tareas, comienza la importaci�n de datos de alumnos.</div></div><br />';

mysql_query("CREATE DATABASE if not exists ".$nombre_copia."") or die('
<div align="center">
<div class="alert alert-danger alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>ATENCI�N:</strong>
Ha surgido un error al crear la copia de seguridad de la Base de datos de forma autom�tica. Debes crear una base de datos manualmente para recibir la copia de seguridad de los datos actuales. El nombre de la base de datos sigue el patr�n que se describe m�s arriba (<strong><em>'.$nombre_copia.'</em></strong>, en tu caso). Una vez creada la base de datos, aseg�rate que el usuario de MySQL que has registrado en la p�gina de configuraci�n tiene permiso para escribir en la nueva base de datos, y vuelve a recargar esta p�gina para completar el proceso.
</div></div><br />');

// Vaciado de tablas para comenzar cada curso
$tablas = mysql_query("show tables");
while ($tabla = mysql_fetch_array($tablas)) {
mysql_query("create table ".$nombre_copia.".".$tabla[0]." SELECT * FROM ".$db.".".$tabla[0]);
	$protegida = "";
	$intocables = array("cal", "c_profes", "inventario_clases", "inventario", "inventario_lugares", "listafechorias", "maquinas", "mensajes", "mens_profes", "mens_texto", "nombres_maquinas", "news", "partestic", "profes", "recursos", "Textos", "textos_gratis", "tramos");
	foreach ($intocables as $notocar){
		if ($tabla[0]==$notocar) {
			$protegida = "1";
		}
	}
	if (!($protegida == "1")) {
		mysql_query("truncate table $tabla[0]");
	}
}

?>