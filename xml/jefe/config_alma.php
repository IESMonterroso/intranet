<?php 

// CONFIGURACIÓN MÓDULO DE ACTUALIZACIÓN DE ALUMNOS
$campos	= array("Alumno/a"=>"Alumno/a", "Estado Matrícula"=>"ESTADOMATRICULA", "Nº Id. Escolar"=>"CLAVEAL", "DNI/Pasaporte"=>"DNI", "Dirección"=>"DOMICILIO", "Código postal"=>"CODPOSTAL", "Localidad de residencia"=>"LOCALIDAD", "Fecha de nacimiento"=>"FECHA", "Provincia de residencia"=>"PROVINCIARESIDENCIA", "Teléfono"=>"TELEFONO", "Teléfono personal alumno/a"=>"Teléfono personal alumno/a", "Teléfono de urgencia"=>"TELEFONOURGENCIA", "Correo electrónico personal alumno/a"=>"Correo electrónico personal alumno/a", "Correo Electrónico"=>"CORREO", "Curso"=>"CURSO", "Nº del expediente del centro"=>"NUMEROEXPEDIENTE", "Unidad"=>"UNIDAD", "Primer apellido"=>"apellido1", "Segundo apellido"=>"apellido2", "Nombre"=>"NOMBRE", "DNI/Pasaporte Primer turor"=>"DNITUTOR", "Primer apellido Primer tutor"=>"PRIMERAPELLIDOTUTOR", "Segundo apellido Primer tutor"=>"SEGUNDOAPELLIDOTUTOR", "Nombre Primer tutor"=>"NOMBRETUTOR", "Correo Electrónico Primer tutor"=>"CORREOTUTOR", "Teléfono Primer tutor"=>"TELEFONOTUTOR", "Sexo Primer tutor"=>"SEXOPRIMERTUTOR", "DNI/Pasaporte Segundo tutor"=>"DNITUTOR2", "Primer apellido Segundo tutor"=>"PRIMERAPELLIDOTUTOR2", "Segundo apellido Segundo tutor"=>"SEGUNDOAPELLIDOTUTOR2", "Correo Electrónico Segundo tutor"=>"CORREOTUTOR2", "Nombre Segundo tutor"=>"NOMBRETUTOR2", "Teléfono Segundo tutor"=>"TELEFONOTUTOR2", "Sexo Segundo tutor"=>"SEXOTUTOR2", "Localidad de nacimiento"=>"LOCALIDADNACIMIENTO", "Año de la matrícula"=>"ANOMATRICULA", "Nº de matrículas en este curso"=>"MATRICULAS", "Observaciones de la matrícula"=>"OBSERVACIONES", "Provincia nacimiento"=>"PROVINCIANACIMIENTO", "País de nacimiento"=>"PAISNACIMIENTO", "Edad a 31/12 del año de matrícula"=>"EDAD", "Nacionalidad"=>"NACIONALIDAD", "Sexo"=>"SEXO", "Fecha de matrícula"=>"FECHAMATRICULA", "NºSeg.Social"=>"NSEGSOCIAL");
$campos_texto	= '"Alumno/a"=>"Alumno/a", "Estado Matrícula"=>"ESTADOMATRICULA", "Nº Id. Escolar"=>"CLAVEAL", "DNI/Pasaporte"=>"DNI", "Dirección"=>"DOMICILIO", "Código postal"=>"CODPOSTAL", "Localidad de residencia"=>"LOCALIDAD", "Fecha de nacimiento"=>"FECHA", "Provincia de residencia"=>"PROVINCIARESIDENCIA", "Teléfono"=>"TELEFONO", "Teléfono personal alumno/a"=>"Teléfono personal alumno/a", "Teléfono de urgencia"=>"TELEFONOURGENCIA", "Correo electrónico personal alumno/a"=>"Correo electrónico personal alumno/a", "Correo Electrónico"=>"CORREO", "Curso"=>"CURSO", "Nº del expediente del centro"=>"NUMEROEXPEDIENTE", "Unidad"=>"UNIDAD", "Primer apellido"=>"apellido1", "Segundo apellido"=>"apellido2", "Nombre"=>"NOMBRE", "DNI/Pasaporte Primer turor"=>"DNITUTOR", "Primer apellido Primer tutor"=>"PRIMERAPELLIDOTUTOR", "Segundo apellido Primer tutor"=>"SEGUNDOAPELLIDOTUTOR", "Nombre Primer tutor"=>"NOMBRETUTOR", "Correo Electrónico Primer tutor"=>"CORREOTUTOR", "Teléfono Primer tutor"=>"TELEFONOTUTOR", "Sexo Primer tutor"=>"SEXOPRIMERTUTOR", "DNI/Pasaporte Segundo tutor"=>"DNITUTOR2", "Primer apellido Segundo tutor"=>"PRIMERAPELLIDOTUTOR2", "Segundo apellido Segundo tutor"=>"SEGUNDOAPELLIDOTUTOR2", "Correo Electrónico Segundo tutor"=>"CORREOTUTOR2", "Nombre Segundo tutor"=>"NOMBRETUTOR2", "Teléfono Segundo tutor"=>"TELEFONOTUTOR2", "Sexo Segundo tutor"=>"SEXOTUTOR2", "Localidad de nacimiento"=>"LOCALIDADNACIMIENTO", "Año de la matrícula"=>"ANOMATRICULA", "Nº de matrículas en este curso"=>"MATRICULAS", "Observaciones de la matrícula"=>"OBSERVACIONES", "Provincia nacimiento"=>"PROVINCIANACIMIENTO", "País de nacimiento"=>"PAISNACIMIENTO", "Edad a 31/12 del año de matrícula"=>"EDAD", "Nacionalidad"=>"NACIONALIDAD", "Sexo"=>"SEXO", "Fecha de matrícula"=>"FECHAMATRICULA", "NºSeg.Social"=>"NSEGSOCIAL"';
$alumnos	= 'CREATE TABLE  `alma` (
 `Alumno/a` varchar( 255 ) default NULL ,
 `ESTADOMATRICULA` varchar( 255 ) default NULL ,
 `CLAVEAL` varchar( 12 ) ,
 `DNI` varchar( 10 ) default NULL ,
 `DOMICILIO` varchar( 255 ) default NULL ,
 `CODPOSTAL` varchar( 255 ) default NULL ,
 `LOCALIDAD` varchar( 255 ) default NULL ,
 `FECHA` varchar( 255 ) default NULL ,
 `PROVINCIARESIDENCIA` varchar( 255 ) default NULL ,
 `TELEFONO` varchar( 255 ) default NULL ,
 `TELEFONOURGENCIA` varchar( 255 ) default NULL ,
 `CORREO` varchar( 64 ) default NULL ,
 `CURSO` varchar( 255 ) default NULL ,
 `NUMEROEXPEDIENTE` varchar( 255 ) default NULL ,
 `UNIDAD` varchar( 255 ) default NULL ,
 `apellido1` varchar( 255 ) default NULL ,
 `apellido2` varchar( 255 ) default NULL ,
 `NOMBRE` varchar( 30 ) default NULL ,
 `DNITUTOR` varchar( 255 ) default NULL ,
 `PRIMERAPELLIDOTUTOR` varchar( 255 ) default NULL ,
 `SEGUNDOAPELLIDOTUTOR` varchar( 255 ) default NULL ,
 `NOMBRETUTOR` varchar( 255 ) default NULL ,
 `CORREOTUTOR` varchar( 255 ) default NULL ,
 `TELEFONOTUTOR` char( 9 ) default NULL ,
 `SEXOPRIMERTUTOR` varchar( 255 ) default NULL ,
 `DNITUTOR2` varchar( 255 ) default NULL ,
 `PRIMERAPELLIDOTUTOR2` varchar( 255 ) default NULL ,
 `SEGUNDOAPELLIDOTUTOR2` varchar( 255 ) default NULL ,
 `CORREOTUTOR2` varchar( 255 ) default NULL ,
 `NOMBRETUTOR2` varchar( 255 ) default NULL ,
 `TELEFONOTUTOR2` char( 9 ) default NULL ,
 `SEXOTUTOR2` varchar( 255 ) default NULL ,
 `LOCALIDADNACIMIENTO` varchar( 255 ) default NULL ,
 `ANOMATRICULA` varchar( 4 ) default NULL ,
 `MATRICULAS` varchar( 255 ) default NULL ,
 `OBSERVACIONES` varchar( 255 ) default NULL,
 `PROVINCIANACIMIENTO` varchar( 255 ) default NULL ,
 `PAISNACIMIENTO` varchar( 255 ) default NULL ,
 `EDAD` varchar( 2 ) default NULL ,
 `NACIONALIDAD` varchar( 32 ) default NULL,
 `SEXO` varchar( 1 ) default NULL ,
 `FECHAMATRICULA` varchar( 255 ) default NULL,
 `NSEGSOCIAL` varchar( 15 ) default NULL,
 PRIMARY KEY (`CLAVEAL`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_general_ci;
ALTER TABLE `alma` ADD `Teléfono personal alumno/a` varchar(255) NULL AFTER `TELEFONO`;
ALTER TABLE `alma` ADD `Correo electrónico personal alumno/a` varchar(255) NULL AFTER `TELEFONOURGENCIA`;';


// Fin del archivo de configuración