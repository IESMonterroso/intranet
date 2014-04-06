<? 
if(!(file_exists("../config.php")) OR filesize("../config.php")<10){
$mens_bd = "No se encuentra el fichero de configuracion. Debes crearlo en primer lugar."; 
header("location:index.php?mens_bd=1");
exit;
}
else
{
?>
<?php
session_start();
include("../config.php");
?>
<!DOCTYPE html>  
<html lang="es">  
  <head>  
    <meta charset="iso-8859-1">  
    <title>Intranet &middot; <? echo $nombre_del_centro; ?></title>  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <meta name="description" content="Intranet del <? echo $nombre_del_centro; ?>">  
    <meta name="author" content="IESMonterroso (https://github.com/IESMonterroso/intranet/)">
      
    <link href="http://<? echo $dominio;?>/intranet/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://<? echo $dominio;?>/intranet/css/otros.css" rel="stylesheet">
    <link href="http://<? echo $dominio;?>/intranet/css/bootstrap-responsive.min.css" rel="stylesheet">    
    <link href="http://<? echo $dominio;?>/intranet/css/datepicker.css" rel="stylesheet">
    <link href="http://<? echo $dominio;?>/intranet/css/DataTable.bootstrap.css" rel="stylesheet">    
    <link href="http://<? echo $dominio;?>/intranet/css/font-awesome.min.css" rel="stylesheet" >
    <link href="http://<? echo $dominio;?>/intranet/css/imprimir.css" rel="stylesheet" media="print">
    <script type="text/javascript" src="http://<? echo $dominio;?>/intranet/js/buscarAlumnos.js"></script>
</head>
<body>	
<?
// Conexion de datos
mysql_connect ($db_host, $db_user, $db_pass) or die('<br /><div align="center"><div class="alert alert-danger alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h4>ATENCI�N:</h4>
No ha sido posible conectar con el Servidor de las Bases de datos. Esto quiere decir que los datos que has escrito en la p�gina de configuraci�n (usuario y contrase�a para acceder al servidor MySql) no son correctos, o bien que el servidor de MySql no est� activado en este momento. Corrige el error e int�ntalo de nuevo.
          </div></div>');

// Creamos Base de dtos principal
mysql_query("CREATE DATABASE IF NOT EXISTS $db");
mysql_select_db ($db);

// Extructura de FALTAS

//
// Estructura de tabla para la tabla `absentismo`
//

mysql_query("CREATE TABLE IF NOT EXISTS `absentismo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `claveal` varchar(12) NOT NULL DEFAULT '',
  `mes` char(2) NOT NULL DEFAULT '',
  `numero` bigint(21) NOT NULL DEFAULT '0',
  `nivel` varchar(5) DEFAULT NULL,
  `grupo` char(1) DEFAULT NULL,
  `jefatura` text,
  `tutoria` text,
  `orientacion` text,
  PRIMARY KEY (`id`),
  KEY `claveal` (`claveal`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `actividadalumno`
//

mysql_query("CREATE TABLE IF NOT EXISTS `actividadalumno` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `claveal` varchar(12) NOT NULL DEFAULT '',
  `cod_actividad` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `actividades`
//

mysql_query("CREATE TABLE IF NOT EXISTS `actividades` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `grupos` varchar(156) NOT NULL DEFAULT '',
  `actividad` varchar(164) NOT NULL DEFAULT '',
  `descripcion` text NOT NULL,
  `departamento` varchar(48) NOT NULL DEFAULT '',
  `profesor` varchar(196) NOT NULL DEFAULT '',
  `horario` varchar(64) NOT NULL DEFAULT '',
  `fecha` date NOT NULL DEFAULT '0000-00-00',
  `hoy` date NOT NULL DEFAULT '0000-00-00',
  `confirmado` tinyint(1) NOT NULL DEFAULT '0',
  `justificacion` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `almafaltas`
//

mysql_query("CREATE TABLE IF NOT EXISTS `almafaltas` (
  `CLAVEAL` varchar(12) NOT NULL DEFAULT '',
  `NOMBRE` varchar(30) DEFAULT NULL,
  `APELLIDOS` varchar(40) DEFAULT NULL,
  `NIVEL` varchar(5) DEFAULT NULL,
  `GRUPO` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`CLAVEAL`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");


// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `alumnos`
//

mysql_query("CREATE TABLE IF NOT EXISTS `alumnos` (
  `nombre` varchar(71) DEFAULT NULL,
  `unidad` varchar(255) DEFAULT NULL,
  `claveal` varchar(8) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `AsignacionMesasTIC`
//

mysql_query("CREATE TABLE IF NOT EXISTS `AsignacionMesasTIC` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prof` varchar(50) NOT NULL DEFAULT '',
  `c_asig` varchar(6) NOT NULL DEFAULT '',
  `agrupamiento` varchar(50) NOT NULL DEFAULT '',
  `CLAVEAL` varchar(8) NOT NULL DEFAULT '',
  `no_mesa` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `CLAVEAL` (`CLAVEAL`),
  KEY `prof` (`prof`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `asignaturas`
//

mysql_query("CREATE TABLE IF NOT EXISTS `asignaturas` (
  `CODIGO` varchar(10) DEFAULT NULL,
  `NOMBRE` varchar(96) DEFAULT NULL,
  `ABREV` varchar(10) DEFAULT NULL,
  `CURSO` varchar(64) DEFAULT NULL,
  KEY `CODIGO` (`CODIGO`),
  KEY `ABREV` (`ABREV`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `ausencias`
//

mysql_query("CREATE TABLE IF NOT EXISTS `ausencias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profesor` varchar(64) NOT NULL DEFAULT '',
  `inicio` date NOT NULL DEFAULT '0000-00-00',
  `fin` date NOT NULL DEFAULT '0000-00-00',
  `horas` int(11) NOT NULL DEFAULT '0',
  `tareas` text NOT NULL,
  `ahora` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `archivo` VARCHAR( 186 ) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `cal`
//

mysql_query("CREATE TABLE IF NOT EXISTS `cal` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `eventdate` date NOT NULL DEFAULT '0000-00-00',
  `html` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `event` text NOT NULL,
  `idact` varchar(32) DEFAULT NULL,
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `eventdate` (`eventdate`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `calificaciones`
//

mysql_query("CREATE TABLE IF NOT EXISTS `calificaciones` (
  `codigo` varchar(5) NOT NULL DEFAULT '',
  `nombre` varchar(64) DEFAULT NULL,
  `abreviatura` varchar(4) DEFAULT NULL,
  `orden` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `cargos`
//

mysql_query("CREATE TABLE IF NOT EXISTS `cargos` (
  `dni` varchar(9) NOT NULL DEFAULT '',
  `cargo` varchar(8) NOT NULL DEFAULT '0',
  KEY `dni` (`dni`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `categorias`
//

mysql_query("CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `categoria` varchar(30) NOT NULL DEFAULT '',
  `apartado` varchar(30) NOT NULL DEFAULT '',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `competencias`
//

mysql_query("CREATE TABLE IF NOT EXISTS `competencias` (
  `id` int(11) NOT NULL DEFAULT '0',
  `idc` int(1) NOT NULL DEFAULT '0',
  `claveal` int(12) NOT NULL DEFAULT '0',
  `materia` int(5) NOT NULL DEFAULT '0',
  `nota` varchar(10) NOT NULL DEFAULT '1',
  `fecha` datetime DEFAULT '0000-00-00 00:00:00',
  `profesor` varchar(48) NOT NULL DEFAULT '',
  `curso` varchar(7) NOT NULL DEFAULT '',
  `grupo` varchar(6) NOT NULL DEFAULT '',
  KEY `claveal` (`claveal`),
  KEY `materia` (`materia`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `competencias_lista`
//

mysql_query("CREATE TABLE IF NOT EXISTS `competencias_lista` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(64) NOT NULL DEFAULT '',
  `abreviatura` varchar(12) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `convivencia`
//

mysql_query("CREATE TABLE IF NOT EXISTS `convivencia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `claveal` int(8) NOT NULL DEFAULT '0',
  `dia` int(1) NOT NULL DEFAULT '0',
  `hora` int(1) NOT NULL DEFAULT '0',
  `trabajo` int(1) NOT NULL DEFAULT '0',
  `fecha` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  KEY `claveal` (`claveal`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `c_profes`
//

mysql_query("CREATE TABLE IF NOT EXISTS `c_profes` (
  `id` smallint(2) NOT NULL AUTO_INCREMENT,
  `pass` varchar(48) DEFAULT NULL,
  `PROFESOR` varchar(48) DEFAULT NULL,
  `dni` varchar(9) NOT NULL DEFAULT '',
  `idea` varchar(12) NOT NULL DEFAULT '',
  `correo` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `PROFESOR` (`PROFESOR`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// Usuario admin y conntrase�a
$ya_adm = mysql_query("select * from c_profes, departamentos where departamentos.idea = c_profes.idea and (c_profes.PROFESOR='admin' or departamentos.cargo='%1%')");
if (mysql_num_rows($ya_adm)>0) {
}
else {
$adm=sha1("12345678");
mysql_query("INSERT INTO c_profes ( `pass` , `PROFESOR` , `dni`, `idea` )
VALUES (
'$adm', 'admin', '12345678', 'admin'
);");
}


// Conserjes 
if($num_conserje > '0')
{
mysql_select_db($db);
for($i=1;$i<$num_conserje+1;$i++)
{
$conserje = ${'conserje'.$i};
$dnic = ${'dnic'.$i};
mysql_query("insert into c_profes (profesor, dni, pass, idea) values ('$conserje', '$dnic', '$dnic','$conserje')");
}
}
// y Administrativos
if($num_administ > '0')
{
mysql_select_db($db);
for($i=1;$i<$num_administ+1;$i++)
{
$administ = ${'administ'.$i};
$dnia = ${'dnia'.$i};
$idea = ${'idea'.$i};
mysql_query("insert into c_profes (profesor, dni, pass, idea) values ('$administ', '$dnia', '$dnia', '$idea')");
}
}

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `datos`
//

mysql_query("CREATE TABLE IF NOT EXISTS `datos` (
  `id` int(4) NOT NULL DEFAULT '0',
  `nota` varchar(5) NOT NULL DEFAULT '',
  `ponderacion` char(3) DEFAULT NULL,
  `claveal` varchar(12) NOT NULL DEFAULT '',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `departamentos`
//

mysql_query("CREATE TABLE IF NOT EXISTS `departamentos` (
  `NOMBRE` varchar(48) NOT NULL DEFAULT '',
  `DNI` varchar(10) NOT NULL DEFAULT '',
  `DEPARTAMENTO` varchar(48) NOT NULL DEFAULT '',
  `CARGO` varchar(5) DEFAULT NULL,
  `idea` varchar(12) NOT NULL DEFAULT '',
  KEY `NOMBRE` (`NOMBRE`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");


// Usuario admin y conntrase�a
$ya_adm = mysql_query("select * from c_profes, departamentos where departamentos.idea = c_profes.idea and (c_profes.PROFESOR='admin' or departamentos.cargo='%1%')");
if (mysql_num_rows($ya_adm)>0) {
}
else {
mysql_query("insert into departamentos (nombre, dni, departamento, cargo, idea) values ('admin', '12345678', 'Admin', '1', 'admin')");
}

// Conserjes y Administrativos
if($num_conserje > '0')
{
mysql_query("delete from departamentos where cargo like '%6%'");
mysql_select_db($db);
for($i=1;$i<$num_conserje+1;$i++)
{
$conserje = ${'conserje'.$i};
$dnic = ${'dnic'.$i};
mysql_query("insert into departamentos (nombre, dni, departamento, cargo, idea) values ('$conserje', '$dnic', 'Conserjeria', '6', '$conserje')");
}
}
if($num_administ > '0')
{
mysql_query("delete from departamentos where cargo like '%7%'");
mysql_select_db($db);
for($i=1;$i<$num_administ+1;$i++)
{
$administ = ${'administ'.$i};
$idea = ${'idea'.$i};
$dnia = ${'dnia'.$i};
mysql_query("insert into departamentos (nombre, dni, departamento, cargo, idea) values ('$administ', '$dnia', 'Administracion', '7', '$idea')");
}
}

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `direcciones`
//

mysql_query("CREATE TABLE IF NOT EXISTS `direcciones` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `categoria` varchar(30) NOT NULL DEFAULT '',
  `apartado` varchar(30) NOT NULL DEFAULT '',
  `nombre` varchar(60) NOT NULL DEFAULT '',
  `http` varchar(200) NOT NULL DEFAULT '',
  `comentario` text NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `FALTAS`
//

mysql_query("CREATE TABLE IF NOT EXISTS `FALTAS` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `CLAVEAL` varchar(8) NOT NULL DEFAULT '',
  `NIVEL` char(2) DEFAULT NULL,
  `GRUPO` char(1) DEFAULT NULL,
  `NC` tinyint(2) DEFAULT NULL,
  `FECHA` date DEFAULT NULL,
  `DIA` tinyint(1) NOT NULL DEFAULT '0',
  `HORA` tinyint(1) DEFAULT NULL,
  `PROFESOR` char(2) DEFAULT NULL,
  `CODASI` varchar(5) DEFAULT NULL,
  `FALTA` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `NIVEL` (`NIVEL`),
  KEY `GRUPO` (`GRUPO`),
  KEY `NC` (`NC`),
  KEY `FECHA` (`FECHA`),
  KEY `FALTA` (`FALTA`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `FALTASJ`
//

mysql_query("CREATE TABLE IF NOT EXISTS `FALTASJ` (
  `fecha` date DEFAULT NULL,
  `claveal` varchar(8) NOT NULL DEFAULT '',
  KEY `claveal` (`claveal`),
  KEY `fecha` (`fecha`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

//
// Estructura de tabla para la tabla `FALUMNOS`
//

mysql_query("CREATE TABLE IF NOT EXISTS `FALUMNOS` (
  `CLAVEAL` char(12) NOT NULL DEFAULT '',
  `NC` double DEFAULT NULL,
  `APELLIDOS` char(30) DEFAULT NULL,
  `NOMBRE` char(24) DEFAULT NULL,
  `NIVEL` char(5) DEFAULT NULL,
  `GRUPO` char(1) DEFAULT NULL,
  KEY `CLAVEAL` (`CLAVEAL`),
  KEY `NC` (`NC`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `FechCaduca`
//

mysql_query("CREATE TABLE IF NOT EXISTS `FechCaduca` (
  `id` int(11) NOT NULL DEFAULT '0',
  `fecha` date NOT NULL DEFAULT '0000-00-00',
  `dias` int(7) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `Fechoria`
//

mysql_query("CREATE TABLE IF NOT EXISTS `Fechoria` (
  `CLAVEAL` varchar(12) NOT NULL DEFAULT '',
  `FECHA` date NOT NULL DEFAULT '0000-00-00',
  `ASUNTO` text NOT NULL,
  `NOTAS` text NOT NULL,
  `INFORMA` varchar(48) NOT NULL DEFAULT '',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grave` varchar(10) NOT NULL DEFAULT '',
  `medida` varchar(148) NOT NULL DEFAULT '',
  `expulsion` tinyint(2) NOT NULL DEFAULT '0',
  `inicio` date DEFAULT '0000-00-00',
  `fin` date DEFAULT '0000-00-00',
  `tutoria` text,
  `expulsionaula` tinyint(1) DEFAULT NULL,
  `enviado` char(1) NOT NULL DEFAULT '1',
  `recibido` char(1) NOT NULL DEFAULT '0',
  `aula_conv` tinyint(1) DEFAULT '0',
  `inicio_aula` date DEFAULT NULL,
  `fin_aula` date DEFAULT NULL,
  `horas` int(11) DEFAULT '123456',
  `confirmado` tinyint(1) DEFAULT NULL,
  `tareas` char(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `CLAVEAL` (`CLAVEAL`),
  KEY `FECHA` (`FECHA`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `festivos`
//

mysql_query("CREATE TABLE IF NOT EXISTS `festivos` (
  `fecha` date NOT NULL DEFAULT '0000-00-00',
  `nombre` varchar(64) NOT NULL DEFAULT '',
  `docentes` char(2) NOT NULL DEFAULT '',
  `ambito` varchar(10) NOT NULL DEFAULT '',
  KEY `fecha` (`fecha`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `fotocopias`
//

mysql_query("CREATE TABLE IF NOT EXISTS `fotocopias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(48) NOT NULL DEFAULT '',
  `numero` int(11) NOT NULL DEFAULT '0',
  `observaciones` text NOT NULL,
  `tipo` tinyint(1) NOT NULL DEFAULT '0',
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `FTUTORES`
//

mysql_query("CREATE TABLE IF NOT EXISTS `FTUTORES` (
  `NIVEL` char(3) NOT NULL DEFAULT '',
  `GRUPO` char(1) NOT NULL DEFAULT '',
  `TUTOR` varchar(48) NOT NULL DEFAULT '',
  `observaciones1` text NOT NULL,
  `observaciones2` text NOT NULL,
  KEY `TUTOR` (`TUTOR`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `grupos`
//

mysql_query("CREATE TABLE IF NOT EXISTS `grupos` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `profesor` varchar(48) NOT NULL DEFAULT '',
  `asignatura` int(6) NOT NULL DEFAULT '0',
  `curso` varchar(5) NOT NULL DEFAULT '',
  `alumnos` varchar(124) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `profesor` (`profesor`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `guardias`
//

mysql_query("CREATE TABLE IF NOT EXISTS `guardias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profesor` varchar(64) NOT NULL DEFAULT '',
  `profe_aula` varchar(64) NOT NULL DEFAULT '',
  `dia` tinyint(1) NOT NULL DEFAULT '0',
  `hora` tinyint(1) NOT NULL DEFAULT '0',
  `fecha` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `fecha_guardia` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `hermanos`
//

mysql_query("CREATE TABLE IF NOT EXISTS `hermanos` (
  `telefono` varchar(255) DEFAULT NULL,
  `telefonourgencia` varchar(255) DEFAULT NULL,
  `hermanos` bigint(21) NOT NULL DEFAULT '0',
  KEY `telefono` (`telefono`),
  KEY `telefonourgencia` (`telefonourgencia`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `horw`
//

mysql_query("CREATE TABLE IF NOT EXISTS `horw` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dia` char(1) NOT NULL DEFAULT '',
  `hora` char(1) NOT NULL DEFAULT '',
  `a_asig` varchar(8) NOT NULL DEFAULT '',
  `asig` varchar(64) NOT NULL DEFAULT '',
  `c_asig` varchar(30) NOT NULL DEFAULT '',
  `prof` varchar(50) NOT NULL DEFAULT '',
  `no_prof` tinyint(4) DEFAULT NULL,
  `c_prof` varchar(30) NOT NULL DEFAULT '',
  `a_aula` varchar(5) NOT NULL DEFAULT '',
  `n_aula` varchar(64) NOT NULL DEFAULT '',
  `a_grupo` varchar(10) NOT NULL DEFAULT '',
  `nivel` varchar(10) NOT NULL DEFAULT '',
  `n_grupo` varchar(10) NOT NULL DEFAULT '',
  `clase` varchar(16) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `prof` (`prof`),
  KEY `c_asig` (`c_asig`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `horw_faltas`
//

mysql_query("CREATE TABLE IF NOT EXISTS `horw_faltas` (
  `id` int(11) NOT NULL DEFAULT '0',
  `dia` char(1) NOT NULL DEFAULT '',
  `hora` char(1) NOT NULL DEFAULT '',
  `a_asig` varchar(8) NOT NULL DEFAULT '',
  `asig` varchar(64) NOT NULL DEFAULT '',
  `c_asig` varchar(30) NOT NULL DEFAULT '',
  `prof` varchar(50) NOT NULL DEFAULT '',
  `no_prof` tinyint(4) DEFAULT NULL,
  `c_prof` varchar(30) NOT NULL DEFAULT '',
  `a_aula` varchar(5) NOT NULL DEFAULT '',
  `n_aula` varchar(64) NOT NULL DEFAULT '',
  `a_grupo` varchar(10) NOT NULL DEFAULT '',
  `nivel` varchar(10) NOT NULL DEFAULT '',
  `n_grupo` varchar(10) NOT NULL DEFAULT '',
  `clase` varchar(16) NOT NULL DEFAULT '',
  KEY `prof` (`prof`),
  KEY `c_asig` (`c_asig`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `infotut_alumno`
//

mysql_query("CREATE TABLE IF NOT EXISTS `infotut_alumno` (
  `ID` smallint(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `CLAVEAL` varchar(12) NOT NULL DEFAULT '',
  `APELLIDOS` varchar(30) NOT NULL DEFAULT '',
  `NOMBRE` varchar(24) NOT NULL DEFAULT '',
  `NIVEL` varchar(5) NOT NULL DEFAULT '',
  `GRUPO` char(1) NOT NULL DEFAULT '',
  `F_ENTREV` date NOT NULL DEFAULT '0000-00-00',
  `TUTOR` varchar(40) NOT NULL DEFAULT '',
  `FECHA_REGISTRO` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`ID`),
  KEY `CLAVEAL` (`CLAVEAL`),
  KEY `APELLIDOS` (`APELLIDOS`),
  KEY `NOMBRE` (`NOMBRE`),
  KEY `NIVEL` (`NIVEL`),
  KEY `CURSO` (`GRUPO`),
  KEY `F_ENTREV` (`F_ENTREV`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `infotut_profesor`
//

mysql_query("CREATE TABLE IF NOT EXISTS `infotut_profesor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_alumno` int(11) NOT NULL DEFAULT '0',
  `profesor` varchar(48) NOT NULL DEFAULT '',
  `asignatura` varchar(64) NOT NULL DEFAULT '',
  `informe` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_alumno` (`id_alumno`,`profesor`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `inventario`
//

mysql_query("CREATE TABLE IF NOT EXISTS `inventario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clase` varchar(48) NOT NULL DEFAULT '',
  `lugar` varchar(48) NOT NULL DEFAULT '',
  `descripcion` text NOT NULL,
  `marca` varchar(32) NOT NULL DEFAULT '',
  `modelo` varchar(48) NOT NULL DEFAULT '',
  `serie` varchar(24) NOT NULL DEFAULT '',
  `unidades` int(11) NOT NULL DEFAULT '0',
  `fecha` varchar(10) NOT NULL DEFAULT '',
  `ahora` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `departamento` varchar(48) NOT NULL DEFAULT '',
  `profesor` varchar(48) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `inventario_clases`
//

mysql_query("CREATE TABLE IF NOT EXISTS `inventario_clases` (
  `id` int(11) NOT NULL auto_increment,
  `familia` varchar(64) collate latin1_spanish_ci NOT NULL default '',
  `clase` varchar(64) collate latin1_spanish_ci NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

mysql_query("INSERT INTO `inventario_clases` (`id`, `familia`, `clase`) VALUES 
(0, 'Mobiliario', 'Amarios'),
(3, 'Mobiliario', 'Estanter�as'),
(5, 'Mobiliario', 'Sillas'),
(6, 'Mobiliario', 'Mesas'),
(7, 'Mobiliario', 'Pupitre'),
(8, 'Mobiliario', 'Mesas profesorado '),
(9, 'Mobiliario', 'Otras mesas'),
(10, 'Mobiliario', 'Ficheros y archivadores'),
(11, 'Mobiliario', 'Pizarras'),
(12, 'Mobiliario', 'Otros'),
(13, 'Inform�tica y comunicaciones', 'Ordenador'),
(14, 'Inform�tica y comunicaciones', 'Monitor'),
(15, 'Inform�tica y comunicaciones', 'Impresora'),
(16, 'Inform�tica y comunicaciones', 'Esc�ner'),
(17, 'Inform�tica y comunicaciones', 'Grabadoras de CD'),
(18, 'Inform�tica y comunicaciones', 'DVD'),
(19, 'Inform�tica y comunicaciones', 'Telefono'),
(20, 'Inform�tica y comunicaciones', 'Router'),
(21, 'Inform�tica y comunicaciones', 'Switch'),
(22, 'Inform�tica y comunicaciones', 'Otros'),
(23, 'Material Audiovisual', 'Proyector de diapositivas'),
(24, 'Material Audiovisual', 'Altavoces'),
(25, 'Material Audiovisual', 'Reproductor de video'),
(26, 'Material Audiovisual', 'Proyector de video'),
(27, 'Material Audiovisual', 'Reproductor de m�sica'),
(28, 'Material Audiovisual', 'Micr�fono'),
(29, 'Material Audiovisual', 'C�mara fotogr�fica'),
(30, 'Material Audiovisual', 'C�mara de V�deo'),
(31, 'Material Audiovisual', 'Otros'),
(32, 'Material de laboratorio, talleres y departamentos', 'Mapas y cartograf�a'),
(33, 'Material de laboratorio, talleres y departamentos', 'Material variado'),
(34, 'Material deportivo', 'Porter�as'),
(35, 'Material deportivo', 'Canastas'),
(36, 'Material deportivo', 'Colchonetas'),
(37, 'Material deportivo', 'Vallas'),
(38, 'Material deportivo', 'Otros'),
(39, 'Material de papeler�a y oficina', 'Varios'),
(40, 'Botiqu�n y material de farmacia', 'Varios'),
(41, 'Extintores y material de autoprotecci�n', 'Normales'),
(42, 'Extintores y material de autoprotecci�n', 'Polvo seco (CO2)'),
(43, 'Extintores y material de autoprotecci�n', 'Otros'),
(44, 'Equipos de seguridad', 'C�maras'),
(45, 'Equipos de seguridad', 'Sensores'),
(46, 'Equipos de seguridad', 'Sirenas y timbres'),
(47, 'Equipos de seguridad', 'Otros'),
(48, 'Otros', 'Varios')");

mysql_query("CREATE TABLE  IF NOT EXISTS `inventario_lugares` (
  `id` int(11) NOT NULL auto_increment,
  `lugar` varchar(64) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE latin1_spanish_ci");

mysql_query("INSERT INTO `inventario_lugares` (`id`, `lugar`) VALUES 
(1, 'Aulas planta baja ed. Antiguo.'),
(2, 'Aulas 1� planta ed. Antiguo'),
(3, 'Aulas 2� planta ed. Antiguo'),
(4, 'Aulas m�dulo bachillerato '),
(5, 'Aulas m�dulo nuevo'),
(6, 'Audiovisuales 1'),
(7, 'Audiovisuales 2'),
(8, 'Biblioteca'),
(9, 'Bar - Cafeter�a'),
(10, 'Laboratorio o Taller de Especialidad'),
(11, 'Gimnasio'),
(12, 'Carrito N�'),
(13, 'Departamento'),
(14, 'Despacho'),
(15, 'Aseos'),
(16, 'Zona Patios'),
(17, 'Almacen'),
(18, 'Otros'),
(19, 'Conserjer�a'),
(20, 'Conserjer�a')");

//
// Estructura de tabla para la tabla `listafechorias`
//

mysql_query("CREATE TABLE IF NOT EXISTS `listafechorias` (
  `ID` int(4) NOT NULL DEFAULT '0',
  `fechoria` varchar(255) DEFAULT NULL,
  `medidas` varchar(64) DEFAULT NULL,
  `medidas2` mediumtext,
  `tipo` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// Datos de listafechorias

mysql_query("INSERT INTO `listafechorias` (`ID`, `fechoria`, `medidas`, `medidas2`, `tipo`) VALUES 
(2, 'La falta de puntualidad en la entrada a clase', 'Amonestaci�n oral', 'El alumno siempre entrar� en el aula. Caso de ser reincidente, se contactar� con la familia y se le comunicar� al tutor', 'leve'),
(4, 'La falta de asistencia a clase', 'Llamada telef�nica. Comunicaci�n escrita', 'Se contactar� con la familia para comunicar el hecho (tel�fono o SMS) Grabaci�n de la falta en el m�dulo inform�tico.  Caso de reincidencia, seguir el protocolo: a) comunicaci�n escrita, b)acuse de recibo, c) traslado del caso a Asuntos Sociales', 'leve'),
(6, 'Llevar gorra, capucha, etc en el interior del edificio', 'Amonestaci�n oral', 'Hacer que el alumno se quite la gorra o capucha, llegando, si es preciso, a requisar gorra y entregar en Jefatura para que la retire al final de la jornada.', 'leve'),
(8, 'Llevar ropa indecorosa en el Centro', 'Amonestaci�n oral. Llamada telef�nica.', 'Contactar con la familia para que aporte ropa adecuada o traslade al alumno/a a su domicilio para el oportuno cambio de indumentaria.', 'leve'),
(12, 'Mascar chicle en clase', 'Amonestaci�n oral', 'Que tire el chicle a la papelera', 'leve'),
(13, 'Llevar tel�fono m�vil, c�mara, aparatos de sonido, etc en el Centro', 'Amonestaci�n oral', 'Requisar el aparato y entregar en Jefatura para que sea retirado por la familia.', 'leve'),
(14, 'Arrojar al suelo papeles o basura en general', 'Amonestaci�n oral', 'Hacer que se retiren los objetos.  Ning�n profesor permitir� que el aula est� sucia. Si es as�, obligar al alumnado a la limpieza oportuna.', 'leve'),
(16, 'Hablar en clase', 'Amonestaci�n oral', 'Cambiar al alumno de sitio, o aislarlo en el aula o, si es reincidente,  sancionarlo con p�rdida de', 'leve'),
(18, 'Lanzar objetos, sin peligrosidad o agresividad, a un compa�ero', 'Amonestaci�n oral', 'Hacer que el compa�ero le devuelva el objeto, que el alumno solicite permiso al profesor para que �ste le permita, levant�ndose, entregar el objeto a su compa�ero.', 'leve'),
(20, 'No traer el material exigido para el desarrollo de una clase', 'Amonestaci�n oral', 'Si reincide, contactar telef�nicamente con la familia para que le aporte el material. Caso de existir alguna causa social que impida que el alumno tenga el material, solicitar la colaboraci�n del centro o de las instituciones sociales oportunas.', 'leve'),
(22, 'No realizar las actividades encomendadas por el profesor', 'Amonestaci�n oral', 'Contactar con la familia.', 'leve'),
(23, 'Beber o comer en el aula, en el transcurso de una clase', 'Amonestaci�n oral', 'Obligar a que guarde la bebida o la arroje a la basura.', 'leve'),
(24, 'Comer en el aula', 'Amonestaci�n oral', 'Obligar a que guarde la comida.', 'leve'),
(25, 'Permanecer en el pasillo entre clase y clase', 'Amonestaci�n oral', 'Repercutir la acci�n en su evaluaci�n acad�mica.', 'leve'),
(26, 'Falta de cuidado, respeto y protecci�n de los recursos personales o del Centro', 'Amonestaci�n oral', 'Pedir disculpas p�blicamente y resarcir del posible da�o a la persona o instituci�n afectada.', 'leve'),
(27, 'Interrumpir la clase indebidamente', 'Amonestaci�n oral', 'Cambiar al alumno de sitio, o aislarlo en el aula o, si es reincidente,  sancionarlo con p�rdida de recreo o permaneciendo en el aula algunos minutos al final de la jornada o  viniendo el lunes por la tarde.', 'leve'),
(29, 'No realizar aquellas tareas que son planteadas en las distintas asignaturas', 'Amonestaci�n oral', 'Contactar con la familia.', 'leve'),
(31, 'Faltas reiteradas de puntualidad o asistencia que no est�n justificadas', 'Amonestaci�n escrita', 'Seguir protocolo: a) Llamada telef�nica a la familia b) Escrito a la familia c) Escrito certificado con acuse de recibo a la familia d) Traslado del caso a Asuntos Sociales.', 'grave'),
(32, 'Conductas graves que impidan o dificulten a otros compa�eros el ejercicio del estudio', 'Amonestaci�n escrita', 'Imponer correcciones como: p�rdida de recreo; quedarse algunos minutos al final del periodo lectivo; obligarlo a que venga por la tarde (lunes); expulsarlo de clase (medida extraordinaria). El tutor tratar� el caso con Jefatura  para adoptar medidas.', 'grave'),
(34, 'Actos graves de incorrecci�n con los miembros del Centro', 'Amonestaci�n escrita', 'Imponer correcciones como: p�rdida de recreo; quedarse algunos minutos al final del periodo lectivo; obligarlo a que venga por la tarde (lunes); expulsarlo de clase  (medida extraordinaria que debe ir acompa�ada con escrito del profesor a los padres). La petici�n de excusas se considerar� un atenuante a valorar. El tutor tratar� el caso con la familia y propondr� a Jefatura medidas a adoptar.', 'grave'),
(36, 'Actos graves de indisciplina que perturben el desarrollo normal de las actividades', 'Amonestaci�n escrita', 'Imponer correcciones como: p�rdida de recreo; quedarse algunos minutos al final del periodo lectivo; obligarlo a que venga por la tarde (lunes); expulsarlo de clase (medida extraordinaria que debe ir acompa�ada con escrito del profesor a los padres). El tutor tratar� el caso con la familia y propondr� a Jefatura medidas a adoptar.', 'grave'),
(38, 'Causar da�os leves intencionados en las instalaciones o el material del centro', 'Amonestaci�n escrita', 'El tutor tratar� el caso con la familia y el alumno y familia realizar� trabajos complementarios para la comunidad y  restaurar� los da�os o pagar� los gastos de reparaci�n.', 'grave'),
(39, 'Causar da�os intencionadamente en las pertenencias de los miembros del Centro', 'Amonestaci�n escrita', 'El tutor tratar� el caso con la familia y el alumno y familia realizar� trabajos complementarios para la comunidad y  restaurar� los da�os o pagar� los gastos de reparaci�n o restituci�n.', 'grave'),
(40, 'Incitaci�n o est�mulo a la comisi�n de una falta contraria a las Normas de Convivencia', 'Amonestaci�n escrita', 'El tutor tratar� el caso con la familia y propondr� a Jefatura las medidas correctoras a adoptar.', 'grave'),
(41, 'Reiteraci�n en el mismo trimestre de cinco o m�s faltas leves', 'Amonestaci�n escrita', 'Imponer correcciones como: p�rdida de recreo; quedarse algunos minutos al final del periodo lectivo; obligarlo a que venga por la tarde (lunes); realizar trabajos para la comunidad; o estancia en el Aula de Convivencia entre 1 y 3 d�as.', 'grave'),
(42, 'Incumplimiento de la sanci�n impuesta por la Direcci�n del Centro por una falta leve', 'Amonestaci�n escrita', 'Imponer correcciones como: p�rdida de recreo; quedarse algunos minutos al final del periodo lectivo; obligarlo a que venga por la tarde (lunes); realizar trabajos para la comunidad; o estancia en el Aula de Convivencia entre 1 y 3 d�as.', 'grave'),
(45, 'Grabaci�n, a trav�s de cualquier medio, de miembros del Centro sin su autorizaci�n', 'Amonestaci�n escrita', 'Entrega de la grabaci�n y posibles copias en Jefatura de Estudios. Imponer correcciones como: p�rdida de recreo; quedarse algunos minutos al final del periodo lectivo; obligarlo a que venga por la tarde (lunes); realizar trabajos para la comunidad; o estancia en el Aula de Convivencia entre 1 y 3 d�as.', 'grave'),
(47, 'Abandonar el Centro sin autorizaci�n antes de concluir el horario escolar', 'Amonestaci�n escrita', 'Comunicaci�n urgente con la familia.  Imponer correcciones como: p�rdida de recreo; quedarse algunos minutos al final del periodo lectivo; obligarlo a que venga por la tarde (lunes); realizar trabajos para la comunidad; o estancia en el Aula de Convivencia entre 1 y 3 d�as.', 'grave'),
(49, 'Fumar en el Centro (tanto en el interior del edificio como en los patios)', 'Amonestaci�n escrita', 'Comunicaci�n urgente con la familia.  Entrega de trabajo relacionado con tabaco y salud. Si es reincidente, imponer correcciones como: p�rdida de recreo; quedarse algunos minutos al final del periodo lectivo; obligarlo a que venga por la tarde (lunes); realizar trabajos para la comunidad; o estancia en el Aula de Convivencia entre 1 y 3 d�as.', 'grave'),
(51, 'Mentir o colaborar para encubrir faltas propias o ajenas', 'Amonestaci�n escrita', 'Imponer correcciones como: p�rdida de recreo; quedarse algunos minutos al final del periodo lectivo; obligarlo a que venga por la tarde (lunes); realizar trabajos para la comunidad; o estancia en el Aula de Convivencia entre 1 y 3 d�as.', 'grave'),
(52, 'Cualquier incorrecci�n de igual gravedad que no constituya falta muy grave', 'Amonestaci�n escrita', 'Imponer correcciones como: p�rdida de recreo; quedarse algunos minutos al final del periodo lectivo; obligarlo a que venga por la tarde (lunes); realizar trabajos para la comunidad; o estancia en el Aula de Convivencia entre 1 y 3 d�as.', 'grave'),
(54, 'Actos graves de indisciplina, insultos o falta de respeto con los Profesores y personal del centro', 'Amonestaci�n escrita', 'Imponer correcciones como: estancia en el Aula de Convivencia varios d�as; expulsi�n del centro entre 1 y 3 d�as o entre 4 y 29 si es reincidente.', 'grave'),
(55, 'Las injurias y ofensas contra cualquier miembro de la comunidad educativa', 'Amonestaci�n escrita', 'Petici�n publica de disculpas. Imponer correcciones como: estancia en el Aula de Convivencia varios d�as; expulsi�n del centro entre 1 y 3 d�as o entre 4 y 29 si es reincidente', 'muy grave'),
(56, 'El acoso f�sico o moral a los compa�eros', 'Amonestaci�n escrita', 'Petici�n publica de disculpas y comunicaci�n con la familia. Si el hecho es grave, iniciar los tr�mites legales oportunos (Asuntos Sociales, Polic�a Nacional, etc.) Imponer correcciones como: estancia en el Aula de Convivencia varios d�as; o expulsi�n del centro entre 1 y 29  dependiendo de la gravedad', 'muy grave'),
(58, 'Amenazas o coacciones contra cualquier miembro de la comunidad educativa', 'Amonestaci�n escrita', 'Petici�n publica de disculpas y comunicaci�n con la familia. Si el hecho es grave, iniciar los tr�mites legales oportunos (Asuntos Sociales, Polic�a Nacional, etc.) Imponer correcciones como: estancia en el Aula de Convivencia varios d�as; o expulsi�n del centro entre 1 y 29 dependiendo de la gravedad.', 'muy grave'),
(61, 'Uso de la violencia, ofensas y actos que atenten contra la intimidad o dignidad de los miembros del Centro', 'Amonestaci�n escrita', 'Petici�n publica de disculpas y comunicaci�n con la familia. Si el hecho es grave, iniciar los tr�mites legales oportunos (Asuntos Sociales, Polic�a Nacional, etc.) Imponer expulsi�n del centro entre 1 y 29 dependiendo de la gravedad.', 'muy grave'),
(63, 'Discriminaci�n a cualquier miembro del centro, por raz�n de raza, sexo, religi�n, orientaci�n sexual, etc.', 'Amonestaci�n escrita', 'Petici�n publica de disculpas y comunicaci�n con la familia. Si el hecho es grave, iniciar los tr�mites legales oportunos (Asuntos Sociales, Polic�a Nacional, etc.) Imponer expulsi�n del centro entre 1 y 29 dependiendo de la gravedad.', 'muy grave'),
(65, 'Grabaci�n, publicidad o difusi�n de agresiones o humillaciones cometidas contra miembros del centro', 'Amonestaci�n escrita', 'Si el hecho es grave, iniciar los tr�mites legales oportunos (Asuntos Sociales, Polic�a Nacional, etc.) Imponer expulsi�n del centro entre 1 y 29 dependiendo de la gravedad.', 'muy grave'),
(66, 'Da�os graves causados en las instalaciones, materiales y documentos del centro, o en las pertenencias de sus miembros', 'Amonestaci�n escrita', 'Jefatura de Estudios tratar� el caso con la familia y el alumno y familia realizar� trabajos complementarios para la comunidad y  restaurar� los da�os o pagar� los gastos de reparaci�n o restituci�n.', 'muy grave'),
(67, 'Suplantaci�n de personalidad en actos de la vida docente y la falsificaci�n o sustracci�n de documentos acad�micos', 'Amonestaci�n escrita', 'Si el hecho es grave, iniciar los tr�mites legales oportunos (Asuntos Sociales, Polic�a Nacional, etc.) Imponer expulsi�n del centro entre 1 y 29 dependiendo de la gravedad.', 'muy grave'),
(68, 'Uso, incitaci�n al mismo o introducci�n en el centro de sustancias perjudiciales para la salud', 'Amonestaci�n escrita', 'Si el hecho es grave, iniciar los tr�mites legales oportunos (Asuntos Sociales, Polic�a Nacional, etc.).  Entrega de trabajo relacionado con el hecho y la salud. Imponer sanci�n de estancia en el Aula de Convivencia o  expulsi�n del centro entre 1 y 29 dependiendo de la gravedad.', 'muy grave'),
(70, 'Perturbaci�n grave del desarrollo de las actividades y cualquier incumplimiento grave de las normas de conducta', 'Amonestaci�n escrita', 'Imponer correcciones como: estancia en el Aula de Convivencia varios d�as; estancia de un familiar en el aula, con el alumno, durante varios d�as; o expulsi�n del centro entre 1 y 29 d�as en funci�n de la gravedad.', 'muy grave'),
(71, 'La reiteraci�n en el mismo trimestre de tres o m�s faltas graves', 'Amonestaci�n escrita', 'Imponer correcciones como: estancia en el Aula de Convivencia varios d�as; expulsi�n del centro entre 1 y 3 d�as o entre 4 y 29 si es reincidente.', 'muy grave'),
(72, 'El incumplimiento de la sanci�n impuesta por la Direcci�n por una falta grave', 'Amonestaci�n escrita', 'Imponer correcciones como: estancia en el Aula de Convivencia varios d�as; o expulsi�n del centro entre 4 y 29 d�as, seg�n gravedad del hecho.', 'muy grave'),
(73, 'Asistir al centro o a actividades programadas por el Centro en estado de embriaguez o drogado', 'Amonestaci�n escrita', 'Jefatura de Estudios tratar� el caso con la familia y el alumno.  Trabajo sobre el hecho y la salud. Derivar el caso a Dep. Orientaci�n o Asuntos Sociales si es grave. Imponer correcciones como: estancia en el Aula de Convivencia varios d�as; expulsi�n del centro entre 1 y 3 d�as o entre 4 y 29 si es reincidente', 'muy grave'),
(76, 'Cometer actos delictivos penados por nuestro Sistema Jur�dico', 'Amonestaci�n escrita', 'Jefatura tratar� el caso con la familia y, si es grave, denunciar en la Polic�a. Imponer correcciones como: estancia en el Aula de Convivencia varios d�as; estancia de un familiar en el aula, con el alumno, durante varios d�as; o expulsi�n del centro entre 1 y 29 d�as en funci�n de la gravedad', 'muy grave'),
(78, 'Cometer o encubrir hurtos', 'Amonestaci�n escrita', 'Jefatura tratar� el caso con la familia. Proceder a la devoluci�n de lo hurtado.  Realizaci�n por parte del alumno y la familia de  trabajos para la comunidad.', 'muy grave'),
(79, 'Promover el uso de bebidas alcoh�licas, sustancias psicotr�picas y material pornogr�fico', 'Amonestaci�n escrita', 'Jefatura tratar� el caso con la familia y, si es grave, denunciar en la Polic�a. Traslado del caso al Dep. de Orientaci�n o Asuntos Sociales. Trabajo sobre h�bitos saludables. Imponer correcciones como: estancia en el Aula de Convivencia varios d�as; estancia de un familiar en el aula, con el alumno, durante varios d�as; o expulsi�n del centro entre 1 y 29 d�as en funci�n de la gravedad', 'muy grave'),
(81, 'Cualquier acto grave dirigido directamente a impedir el normal desarrollo de las actividades', 'Amonestaci�n escrita', 'Jefatura tratar� el caso con la familia. Imponer correcciones como: estancia en el Aula de Convivencia varios d�as; estancia de un familiar en el aula, con el alumno, durante varios d�as; o expulsi�n del centro entre 1 y 29 d�as en funci�n de la gravedad', 'muy grave'),
(82, 'No realizar las tareas encomendadas durante el periodo de expulsi�n', 'Amonestaci�n escrita', 'Jefatura tratar� el caso con la familia. Imponer correcciones como: estancia en el Aula de Convivencia varios d�as; estancia de un familiar en el aula, con el alumno, durante varios d�as; o expulsi�n del centro entre 1 y 29 d�as en funci�n de la gravedad', 'muy grave')");


// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `maquinas`
//

mysql_query("CREATE TABLE IF NOT EXISTS `maquinas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lugar` char(3) NOT NULL DEFAULT '',
  `serie` varchar(15) NOT NULL DEFAULT '',
  `numero` int(2) DEFAULT NULL,
  `observaciones` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `materias`
//

mysql_query("CREATE TABLE IF NOT EXISTS `materias` (
  `CODIGO` varchar(10) DEFAULT NULL,
  `NOMBRE` varchar(64) DEFAULT NULL,
  `ABREV` varchar(10) DEFAULT NULL,
  `CURSO` varchar(64) DEFAULT NULL,
  `GRUPO` varchar(6) DEFAULT NULL,
  KEY `CODIGO` (`CODIGO`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `mensajes`
//

mysql_query("CREATE TABLE IF NOT EXISTS `mensajes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ahora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dni` varchar(10) NOT NULL DEFAULT '',
  `claveal` int(12) NOT NULL DEFAULT '0',
  `asunto` text NOT NULL,
  `texto` text NOT NULL,
  `ip` varchar(15) NOT NULL DEFAULT '',
  `recibidotutor` tinyint(1) NOT NULL DEFAULT '0',
  `recibidopadre` tinyint(1) NOT NULL DEFAULT '0',
  `correo` varchar(72) DEFAULT NULL,
  `nivel` char(2) NOT NULL DEFAULT '',
  `grupo` char(1) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `mens_profes`
//

mysql_query("CREATE TABLE IF NOT EXISTS `mens_profes` (
  `id_profe` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_texto` int(11) NOT NULL DEFAULT '0',
  `profesor` varchar(42) NOT NULL DEFAULT '0',
  `recibidoprofe` tinyint(1) NOT NULL DEFAULT '0',
  `recibidojefe` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_profe`),
  KEY `profesor` (`profesor`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `mens_texto`
//

mysql_query("CREATE TABLE IF NOT EXISTS `mens_texto` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ahora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `origen` varchar(42) NOT NULL DEFAULT '0',
  `asunto` text NOT NULL,
  `texto` text NOT NULL,
  `destino` text NOT NULL,
  `oculto` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `profesor` (`origen`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `nombres_maquinas`
//

mysql_query("CREATE TABLE IF NOT EXISTS `nombres_maquinas` (
  `IP` varchar(18) DEFAULT NULL,
  `MAC` varchar(24) DEFAULT NULL,
  `NOMBRE` varchar(24) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `notas`
//

mysql_query("CREATE TABLE IF NOT EXISTS `notas` (
  `claveal` varchar(12) NOT NULL DEFAULT '0',
  `notas1` varchar(200) DEFAULT NULL,
  `notas2` varchar(200) DEFAULT NULL,
  `notas3` varchar(200) DEFAULT NULL,
  `notas4` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`claveal`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `notas_cuaderno`
//

mysql_query("CREATE TABLE IF NOT EXISTS `notas_cuaderno` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profesor` varchar(48) NOT NULL DEFAULT '',
  `fecha` date NOT NULL DEFAULT '0000-00-00',
  `nombre` varchar(64) NOT NULL DEFAULT '',
  `texto` text NOT NULL,
  `texto_pond` text NOT NULL,
  `asignatura` int(6) NOT NULL DEFAULT '0',
  `curso` varchar(36) NOT NULL DEFAULT '',
  `oculto` tinyint(1) NOT NULL DEFAULT '0',
  `visible_nota` int(1) NOT NULL DEFAULT '0',
  `orden` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `profesor` (`profesor`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `partestic`
//

mysql_query("CREATE TABLE IF NOT EXISTS `partestic` (
  `parte` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `nivel` varchar(4) DEFAULT NULL,
  `grupo` char(1) DEFAULT NULL,
  `carro` char(2) DEFAULT NULL,
  `nserie` varchar(15) NOT NULL DEFAULT '',
  `fecha` date NOT NULL DEFAULT '0000-00-00',
  `hora` char(2) DEFAULT '',
  `alumno` varchar(35) DEFAULT NULL,
  `profesor` varchar(64) NOT NULL DEFAULT '',
  `descripcion` text NOT NULL,
  `estado` varchar(12) NOT NULL DEFAULT 'activo',
  `nincidencia` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`parte`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `profes`
//

mysql_query("CREATE TABLE IF NOT EXISTS `noticias` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `slug` text NOT NULL,
  `content` text NOT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `clase` varchar(48) DEFAULT NULL,
  `fechafin` date DEFAULT NULL,
  `pagina` TINYINT(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `profesores`
//

mysql_query("CREATE TABLE IF NOT EXISTS `profesores` (
  `nivel` varchar(255) DEFAULT NULL,
  `materia` varchar(255) DEFAULT NULL,
  `grupo` varchar(255) DEFAULT NULL,
  `profesor` varchar(255) DEFAULT NULL,
  KEY `profesor` (`profesor`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `recursos`
//

mysql_query("CREATE TABLE IF NOT EXISTS `recursos` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `categoria` varchar(32) NOT NULL DEFAULT '',
  `departamento` varchar(24) NOT NULL DEFAULT '',
  `subclase` varchar(64) DEFAULT NULL,
  `profesor` varchar(32) NOT NULL DEFAULT '',
  `titulo` varchar(128) NOT NULL DEFAULT '',
  `direccion` varchar(128) NOT NULL DEFAULT '',
  `descripcion` text NOT NULL,
  `nivel` varchar(48) DEFAULT NULL,
  `asignatura` varchar(32) DEFAULT NULL,
  `fecha` date NOT NULL DEFAULT '0000-00-00',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `reg_intranet`
//

mysql_query("CREATE TABLE IF NOT EXISTS `reg_intranet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profesor` varchar(48) NOT NULL DEFAULT '',
  `fecha` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ip` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `reg_paginas`
//

mysql_query("CREATE TABLE IF NOT EXISTS `reg_paginas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_reg` int(11) NOT NULL DEFAULT '0',
  `pagina` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_reg` (`id_reg`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `reg_principal`
//

mysql_query("CREATE TABLE IF NOT EXISTS `reg_principal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pagina` text NOT NULL,
  `fecha` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `dni` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `sistcal`
//

mysql_query("CREATE TABLE IF NOT EXISTS `sistcal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sistcal` varchar(5) NOT NULL DEFAULT '',
  `codigo` varchar(5) NOT NULL DEFAULT '',
  `nota` varchar(72) DEFAULT NULL,
  `abrev` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `sms`
//

mysql_query("CREATE TABLE IF NOT EXISTS `sms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `telefono` text NOT NULL,
  `mensaje` varchar(160) NOT NULL DEFAULT '',
  `profesor` varchar(48) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `tareas_alumnos`
//

mysql_query("CREATE TABLE IF NOT EXISTS `tareas_alumnos` (
  `ID` smallint(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `CLAVEAL` varchar(12) NOT NULL DEFAULT '',
  `APELLIDOS` varchar(30) NOT NULL DEFAULT '',
  `NOMBRE` varchar(24) NOT NULL DEFAULT '',
  `NIVEL` varchar(5) NOT NULL DEFAULT '',
  `GRUPO` char(1) NOT NULL DEFAULT '',
  `FECHA` date NOT NULL DEFAULT '0000-00-00',
  `FIN` date NOT NULL DEFAULT '0000-00-00',
  `DURACION` smallint(2) NOT NULL DEFAULT '3',
  `PROFESOR` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY `CLAVEAL` (`CLAVEAL`),
  KEY `APELLIDOS` (`APELLIDOS`),
  KEY `NOMBRE` (`NOMBRE`),
  KEY `NIVEL` (`NIVEL`),
  KEY `CURSO` (`GRUPO`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `tareas_profesor`
//

mysql_query("CREATE TABLE IF NOT EXISTS `tareas_profesor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_alumno` int(11) NOT NULL DEFAULT '0',
  `profesor` varchar(48) NOT NULL DEFAULT '',
  `asignatura` varchar(64) NOT NULL DEFAULT '',
  `tarea` text NOT NULL,
  `confirmado` char(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_alumno` (`id_alumno`,`profesor`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `Textos`
//

mysql_query("
CREATE TABLE IF NOT EXISTS `Textos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Autor` varchar(128) DEFAULT NULL,
  `Titulo` varchar(128) NOT NULL DEFAULT '',
  `Editorial` varchar(64) NOT NULL DEFAULT '',
  `Nivel` varchar(64) NOT NULL DEFAULT '',
  `Grupo` varchar(10) NOT NULL DEFAULT '',
  `Notas` text,
  `Departamento` varchar(48) NOT NULL DEFAULT '',
  `Asignatura` varchar(48) NOT NULL DEFAULT '',
  `Obligatorio` varchar(12) NOT NULL DEFAULT '',
  `Clase` varchar(8) NOT NULL DEFAULT 'Texto',
  `isbn` varchar(18) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `textos_alumnos`
//

mysql_query("CREATE TABLE IF NOT EXISTS `textos_alumnos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `claveal` int(12) NOT NULL DEFAULT '0',
  `materia` int(5) NOT NULL DEFAULT '0',
  `estado` char(1) NOT NULL DEFAULT '',
  `devuelto` char(1) DEFAULT '0',
  `fecha` datetime DEFAULT '0000-00-00 00:00:00',
  `curso` varchar(7) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `claveal` (`claveal`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `textos_gratis`
//

mysql_query("CREATE TABLE IF NOT EXISTS `textos_gratis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `materia` varchar(64) NOT NULL DEFAULT '',
  `isbn` int(10) NOT NULL DEFAULT '0',
  `ean` int(14) NOT NULL DEFAULT '0',
  `editorial` varchar(32) NOT NULL DEFAULT '',
  `titulo` varchar(96) NOT NULL DEFAULT '',
  `ano` year(4) NOT NULL DEFAULT '0000',
  `caducado` char(2) NOT NULL DEFAULT '',
  `importe` int(11) NOT NULL DEFAULT '0',
  `utilizado` char(2) NOT NULL DEFAULT '',
  `nivel` char(2) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `tramos`
//

mysql_query("CREATE TABLE IF NOT EXISTS `tramos` (
  `hora` int(1) NOT NULL DEFAULT '0',
  `tramo` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`hora`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `tutoria`
//

mysql_query("CREATE TABLE IF NOT EXISTS `tutoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `claveal` varchar(12) NOT NULL DEFAULT '',
  `apellidos` varchar(42) NOT NULL DEFAULT '',
  `nombre` varchar(24) NOT NULL DEFAULT '',
  `tutor` varchar(48) NOT NULL DEFAULT '',
  `nivel` char(2) NOT NULL DEFAULT '',
  `grupo` char(1) NOT NULL DEFAULT '',
  `observaciones` text NOT NULL,
  `causa` varchar(42) NOT NULL DEFAULT '',
  `accion` varchar(200) NOT NULL DEFAULT '',
  `fecha` date NOT NULL DEFAULT '0000-00-00',
  `orienta` tinyint(1) NOT NULL DEFAULT '0',
  `prohibido` tinyint(1) NOT NULL DEFAULT '0',
  `jefatura` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `claveal` (`claveal`),
  KEY `tutor` (`tutor`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `user`
//

mysql_query("CREATE TABLE IF NOT EXISTS `user` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `usuarioalumno`
//

mysql_query("CREATE TABLE IF NOT EXISTS `usuarioalumno` (
  `usuario` varchar(18) DEFAULT NULL,
  `pass` varchar(16) NOT NULL DEFAULT '',
  `nombre` varchar(48) DEFAULT NULL,
  `perfil` char(1) NOT NULL DEFAULT '',
  `unidad` varchar(5) NOT NULL DEFAULT '',
  `claveal` varchar(12) NOT NULL DEFAULT '',
  KEY `claveal` (`claveal`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// ////////////////////////////////////////////////////////

//
// Estructura de tabla para la tabla `usuarioprofesor`
//

mysql_query("CREATE TABLE IF NOT EXISTS `usuarioprofesor` (
  `usuario` varchar(16) DEFAULT NULL,
  `nombre` varchar(64) DEFAULT NULL,
  `perfil` varchar(10) DEFAULT NULL,
  KEY `usuario` (`usuario`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");


// Base de datos de Reservas

// Creamos Base de dtos principal

mysql_query("CREATE DATABASE IF NOT EXISTS reservas");
mysql_select_db ($db_reservas);

for($ci=1;$ci<$num_aula+1;$ci++){

// Tabla de Aulas

mysql_query("CREATE TABLE IF NOT EXISTS `aula".$ci."` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `eventdate` date default NULL,
  `dia` tinyint(1) NOT NULL default '0',
  `html` tinyint(1) NOT NULL default '0',
  `event1` varchar(64) default NULL,
  `event2` varchar(64) NOT NULL default '',
  `event3` varchar(64) NOT NULL default '',
  `event4` varchar(64) NOT NULL default '',
  `event5` varchar(64) NOT NULL default '',
  `event6` varchar(64) NOT NULL default '',
  `event7` varchar(64) NOT NULL default '',
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `date` (`eventdate`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// Estructura de tabla para la tabla `aulahor`

mysql_query("CREATE TABLE IF NOT EXISTS `aula".$ci."hor` (
  `dia` tinyint(1) NOT NULL default '0',
  `hora1` varchar(24) default NULL,
  `hora2` varchar(24) default NULL,
  `hora3` varchar(24) default NULL,
  `hora4` varchar(24) default NULL,
  `hora5` varchar(24) default NULL,
  `hora6` varchar(24) default NULL,
  `hora7` varchar(24) default NULL,
  KEY `dia` (`dia`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");
}

for($ci=1;$ci<$num_medio+1;$ci++){

// Tabla de Medios

mysql_query("CREATE TABLE IF NOT EXISTS `medio".$ci."` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `eventdate` date default NULL,
  `dia` tinyint(1) NOT NULL default '0',
  `html` tinyint(1) NOT NULL default '0',
  `event1` varchar(64) default NULL,
  `event2` varchar(64) NOT NULL default '',
  `event3` varchar(64) NOT NULL default '',
  `event4` varchar(64) NOT NULL default '',
  `event5` varchar(64) NOT NULL default '',
  `event6` varchar(64) NOT NULL default '',
  `event7` varchar(64) NOT NULL default '',
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `date` (`eventdate`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");

// Estructura de tabla para la tabla `mediohor`

mysql_query("CREATE TABLE IF NOT EXISTS `medio".$ci."hor` (
  `dia` tinyint(1) NOT NULL default '0',
  `hora1` varchar(24) default NULL,
  `hora2` varchar(24) default NULL,
  `hora3` varchar(24) default NULL,
  `hora4` varchar(24) default NULL,
  `hora5` varchar(24) default NULL,
  `hora6` varchar(24) default NULL,
  `hora7` varchar(24) default NULL,
  KEY `dia` (`dia`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");
}

for($ci=1;$ci<$num_carrito+1;$ci++){

// Tabla de Carritos

mysql_query("CREATE TABLE IF NOT EXISTS `carrito".$ci."` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `eventdate` date default NULL,
  `dia` tinyint(1) NOT NULL default '0',
  `html` tinyint(1) NOT NULL default '0',
  `event1` varchar(64) default NULL,
  `event2` varchar(64) NOT NULL default '',
  `event3` varchar(64) NOT NULL default '',
  `event4` varchar(64) NOT NULL default '',
  `event5` varchar(64) NOT NULL default '',
  `event6` varchar(64) NOT NULL default '',
  `event7` varchar(64) NOT NULL default '',
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `date` (`eventdate`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci");
}

// Tabla de Usuarios TIC

mysql_query("CREATE TABLE IF NOT EXISTS `usuario` (
  `profesor` varchar(48) NOT NULL default '',
  `c1` smallint(3) default NULL,
  `c2` smallint(3) default NULL,
  `c3` smallint(3) default NULL,
  `c4` smallint(3) default NULL,
  `c5` smallint(3) default NULL,
  `c6` smallint(3) default NULL,
  `c7` smallint(3) default NULL,
  `c8` smallint(3) default NULL,
  `c9` smallint(3) default NULL,
  `c10` smallint(3) default NULL,
  `c11` smallint(3) default NULL,
  `c12` smallint(6) default NULL,
  `c13` smallint(6) default NULL,
  `c14` smallint(6) default NULL,
  `c15` smallint(6) default NULL,
  PRIMARY KEY  (`profesor`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;");


echo '<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />';

echo '<div align="center"><div class="well" style="max-width:500px" align="justify">
Las Bases de datos y sus tablas han sido creadas correctamente. Ahora debes ir a la p�gina principal y continuar con la importaci�n de los datos de S�neca hacia la Intranet. Esto lo haces identific�ndote como Administrador (usuario: <em>admin</em>; Clave de acceso: <em>12345678</em>), y yendo a la p�gina de Administraci�n de la Intranet (en el Men� de la Izquierda).<br><br /><div align="center"><a href="http://'.$dominio.'/intranet/" class="btn btn-primary">Ir a la P�gina Principal</a></div>
          </div></div>';
}
?> 
