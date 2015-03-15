<?

///////////////// Datos de la configuraci�n de la Intranet

$dominio='iesmonterroso.org/';

// Personal del Equipo Directivo
$director_del_centro='Francisco Javier M�rquez';
$email_del_centro='director@iesmonterroso.org';
$jefatura_de_estudios='Juna Serrano P�rez';
$mail_jefatura='jefatura@iesmonterroso.org';
$secretario_del_centro='Lourdes Barrutia';
$mail_secretaria='instituto@iesmonterroso.org';
$vicedirector_del_centro='Francisco P�rez Gomar';
$mail_vicedirector='vicedirector@iesmonterroso.org';

//Datos del Centro
$nombre_del_centro='I.E.S. Monterroso';
$nombre_corto='Monterroso';
$codigo_del_centro='29002885';
$direccion_del_centro='C/ Santo Tom�s de Aquino, s/n';
$localidad_del_centro='Estepona';
$codigo_postal_del_centro='29680';
$telefono_del_centro='952795802';
$fax_del_centro='952804309';

// Datos del Curso actual. Deben actulaizarse al comienzo de cada curso escolar
$curso_actual='2014/15';
$inicio_curso='2014-09-15';
$fin_curso='2015-06-24';

// Directorio donde guardar las fotos originales en el servidor. Debe tener permiso de escritura.
$fotos_dir = "/opt/e-smith/fotos/";

// Directorio raiz donde se encuentra la Intranet del Centro
$raiz_dir = '/home/e-smith/files/ibays/intranet/html/';

// Directorio de los documentos
$doc_dir = '/home/e-smith/files/primary/files/';

// Variables para acceder a la Bases de Datos
$host = "localhost";
$user = "usuario"; 
$pass = "pass";
$db = "intranet";

// El Centro ha activado el m�dulo de gesti�n de la Biblioteca de la Intranet y dispone de 
// una base de datos donde se han importado los fondos de la misma. Se habilita la consulta de
// estos fondos desde la p�gina del Centro
$mod_biblio=1;

// Fin Biblioteca

// Fotos del centro. Valores posibles:1 � 0
$mod_fotos=1;

// Directorio para los Departamentos en el directorio de documentaci�n. Valores posibles:1 � 0. Si el valor es 1 hay que crear 
// en ese directorio una carpeta (departamentos) conteniendo directosios con los nombres de los distintos departamentos del Centro.
$mod_departamentos=1;

// Estilo de la web. Comenta / descomenta para cambiar el estilo, colores y tipograf�as de estas p�ginas

//$css_estilo = "bootstrap.min_cerulean.css";
//$css_estilo = "bootstrap.min_cosmo.css";
//$css_estilo = "bootstrap.min_cyborg.css";
//$css_estilo = "bootstrap.min_default.css";
//$css_estilo = "bootstrap.min_flatly.css";
//$css_estilo = "bootstrap.min_journal.css";
//$css_estilo = "bootstrap.min_metro.css";
//$css_estilo = "bootstrap.min_readable.css";
//$css_estilo = "bootstrap.min_simplex.css";
//$css_estilo = "bootstrap.min_slate.css";
//$css_estilo = "bootstrap.min_superhero.css";
$css_estilo = "bootstrap.min_united.css";

// Fin del cambio de estilo


// S�lo v�lido para nuestro Centro: IES Monterroso
if(stristr($_SERVER['SERVER_NAME'],"monterroso")==TRUE){
	$monterroso=1;
}
// Fin Monterroso


// En la p�gina principal aparece a la derecha un conjunto de enlaces a otras p�ginas del Centro.
$enlaces=array(
    "http://erasmusmonterroso.blogspot.com.es"=>"Erasmus+ con Alemania",		
    "http://iesmonterroso.org/dbiblioteca"=>"Biblioteca",
    "http://misionesdegrupo.blogspot.com.es/"=>"Misiones de grupo",
    "http://iesmonterroso2014.blogspot.com"=>"Blog viaje 2� Bachillerato",
    "http://feiemonterroso.blogspot.com.es/"=>"DFEIE",
	"http://allegralia.blogspot.com.es"=>"Blog de M�sica",
	"http://sectionfle.wordpress.com"=>"Blog de Franc�s",
	"http://feiemonterroso.blogspot.com.es/"=>"Departamento de Formaci�n",
	"http://bilimonterroso.symbaloo.com"=>"Portal de Bilinguismo",	
	"http://trinity.iesmonterroso.org"=>"Pruebas del Trinity");

// Fin enlaces


// Si el Centro dispone de una plataforma Moodle a la que enlazar $moodle=1;
// Si el Centro no dispone de una plataforma Moodle a la que enlazar $moodle=0;
$moodle=1;
$enlace_moodle = "http://www.iesmonterroso.net/moodle/";
						
?>