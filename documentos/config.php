<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed');

function ft_settings_external_load() {
  global $config, $db_con;
  $ft = array();
  $ft['settings'] = array();
  $ft['groups'] = array();
  $ft['users'] = array();
  $ft['plugins'] = array();

  # Settings - Change as appropriate. See online documentation for explanations. #
  $intranet_directorios = array('publico', 'interno', 'privado');
  if (isset($_GET['index']) && in_array($_GET['index'], $intranet_directorios)) {
    $index = $_GET['index'];
    switch ($_GET['index']) {
      default :
      case 'publico':
        $ft["settings"]["DIR"] = rtrim($config['mod_documentos_dir'], '/');
        break;
      case 'interno':
        if (! file_exists("../varios/internos")) mkdir("../varios/internos", 0777);
        if (! file_exists("../varios/internos/Proteccion de Datos")) mkdir("../varios/internos/Proteccion de Datos", 0777);
        if (! file_exists("../varios/internos/Proteccion de Datos/Contenido audiovisual de las actividades de los centros y servicios publicos")) mkdir("../varios/internos/Proteccion de Datos/Contenido audiovisual de las actividades de los centros y servicios publicos", 0777);
        if (! file_exists("../varios/internos/Proteccion de Datos/Control horario y seguimiento del personal")) mkdir("../varios/internos/Proteccion de Datos/Control horario y seguimiento del personal", 0777);
        if (! file_exists("../varios/internos/Proteccion de Datos/Videovigilancia")) mkdir("../varios/internos/Proteccion de Datos/Videovigilancia", 0777);

        if (! file_exists("../varios/internos/Proteccion de Datos/Contenido audiovisual de las actividades de los centros y servicios publicos/Autorizacion para la publicacion de imagenes de los alumnos.odt")) {
          include('autorizacionImagenes.php');
        }

        $ft["settings"]["DIR"] = "../varios/internos";
        break;
      case 'privado':
        if (! file_exists("../varios/".$_SESSION['ide'])) mkdir("../varios/".$_SESSION['ide'], 0777);
        $ft["settings"]["DIR"] = "../varios/".$_SESSION['ide'];
        break;
    }
  }
  else {
    $ft["settings"]["DIR"] = rtrim($config['mod_documentos_dir'], '/'); // Your default directory. Do NOT include a trailing slash!
  }

  $caracteres_no_permitidos = array('á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'à', 'è', 'ì', 'ò', 'ù', 'À', 'È', 'Ì', 'Ò', 'Ù', 'á', 'ë', 'ï', 'ö', 'ü', 'Ä', 'Ë', 'Ï', 'Ö', 'Ü');
  $caracteres_permitidos = array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U');
  $dir = isset($_REQUEST['dir']) ? $_REQUEST['dir'] : $ft["settings"]["DIR"];
  $depto_profesor = '/Departamentos/'.$_SESSION['dpt'];
  $depto_profesor_sin_tildes = str_replace($caracteres_no_permitidos, $caracteres_permitidos, $depto_profesor);

  //----------------------------------------------------------------------------
  //   CREACIÓN / ELIMINACIÓN DE DIRECTORIOS EN FUNCIÓN DE LA CONFIGURACIÓN
  //----------------------------------------------------------------------------

  // Biblioteca
  if (isset($config['mod_documentos_biblioteca']) && $config['mod_documentos_biblioteca']) {
    if (! file_exists($config['mod_documentos_dir'].'Biblioteca')) {
      mkdir($config['mod_documentos_dir'].'Biblioteca', 0777);
    }
  }
  else {
    rmdir($config['mod_documentos_dir'].'Biblioteca');
  }

  // Departamentos
  if (isset($config['mod_documentos_departamentos']) && $config['mod_documentos_departamentos']) {

    if (! file_exists($config['mod_documentos_dir'].'/Departamentos')) {
      mkdir($config['mod_documentos_dir'].'Departamentos', 0777);
    }
    else {
      $result = mysqli_query($db_con, "SELECT DISTINCT `departamento` FROM `departamentos` WHERE `departamento` <> 'Admin' AND `departamento` <> 'Auxiliar de Conversación' AND `departamento` <> 'Administracion' AND `departamento` <> 'Conserjeria' AND `departamento` <> 'Educador'  AND `departamento` <> 'Convenio O.N.C.E. Maestros' AND `departamento` <> 'PROFESOR ADICIONAL'  AND `departamento` <> 'Servicio Técnico y/o Mantenimiento' ORDER BY `departamento` ASC");
      while ($row = mysqli_fetch_array($result)) {

        if (file_exists($config['mod_documentos_dir'].'Departamentos/'.str_replace($caracteres_no_permitidos, $caracteres_permitidos, $row['departamento']))) {
          rename($config['mod_documentos_dir'].'Departamentos/'.str_replace($caracteres_no_permitidos, $caracteres_permitidos, $row['departamento']), $config['mod_documentos_dir'].'Departamentos/'.$row['departamento']);
        }
        else {
          mkdir($config['mod_documentos_dir'].'Departamentos/'.$row['departamento'], 0777);
        }

      }
      mysqli_free_result($result);
    }

  }
  else {
    rmdir($config['mod_documentos_dir'].'/Departamentos');
  }

  $ft["settings"]["LANG"]              = "es"; // Language. Do not change unless you have downloaded language file.
  $ft["settings"]["MAXSIZE"]           = 12582912; // Maximum file upload size - in bytes.
  $ft["settings"]["PERMISSION"]        = 0644; // Permission for uploaded files.
  $ft["settings"]["DIRPERMISSION"]     = 0777; // Permission for newly created folders.
  $ft["settings"]["HIDEFILEPATHS"]     = TRUE; // Set to TRUE to pass downloads through File Thingie.
  $ft["settings"]["SHOWDATES"]         = 'd/m/Y \a \l\a\s H:i'; // Set to a date format to display last modified date (e.g. 'Y-m-d'). See http://dk2.php.net/manual/en/function.date.php
  $ft["settings"]["FILEBLACKLIST"]     = ".DS_Store ._.DS_Store"; // Specific files that will not be shown.
  $ft["settings"]["FOLDERBLACKLIST"]   = ""; // Specifies folders that will not be shown. No starting or trailing slashes!
  $ft["settings"]["FILETYPEBLACKLIST"] = "php php5 php6 php7 phtml htm html js css bin run sh rb deb rpm"; // File types that are not allowed for upload.
  $ft["settings"]["FILETYPEWHITELIST"] = ""; // Add file types here to *only* allow those types to be uploaded.
  $ft["settings"]["LIMIT"]             = 0; // Restrict total dir file usage to this amount of bytes. Set to "0" for no limit.
  $ft["settings"]["REQUEST_URI"]       = FALSE; // Installation path. You only need to set this if $_SERVER['REQUEST_URI'] is not being set by your server.
  if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on")  {
    $ft["settings"]["HTTPS"]             = TRUE; // Change to TRUE to enable HTTPS support.
  }
  else {
    $ft["settings"]["HTTPS"]             = FALSE; // Change to TRUE to enable HTTPS support.
  }

  // Permisos Equipo Directivo
  if (acl_permiso($_SESSION['cargo'], array('1'))) {
    $ft["settings"]["UPLOAD"]            = TRUE; // Set to FALSE if you want to disable file uploads.
    $ft["settings"]["CREATE"]            = TRUE; // Set to FALSE if you want to disable file/folder/url creation.
    $ft["settings"]["FILEACTIONS"]       = TRUE; // Set to FALSE if you want to disable file actions (rename, move, delete, edit, duplicate).
    $ft["settings"]["DELETEFOLDERS"]     = TRUE; // Set to TRUE to allow deletion of non-empty folders.
    $ft["settings"]["ADVANCEDACTIONS"]   = FALSE; // Set to TRUE to enable advanced actions like chmod and symlinks.
  }
  // Permisos de todos los profesores para la carpeta de su departamento
  elseif (! acl_permiso($_SESSION['cargo'], array('1')) && (strpos($dir, $depto_profesor) === 0 || strpos($dir, $depto_profesor_sin_tildes) === 0)) {
    $ft["settings"]["UPLOAD"]            = TRUE; // Set to FALSE if you want to disable file uploads.
    $ft["settings"]["CREATE"]            = TRUE; // Set to FALSE if you want to disable file/folder/url creation.
    $ft["settings"]["FILEACTIONS"]       = TRUE; // Set to FALSE if you want to disable file actions (rename, move, delete, edit, duplicate).
    $ft["settings"]["DELETEFOLDERS"]     = TRUE; // Set to TRUE to allow deletion of non-empty folders.
    $ft["settings"]["ADVANCEDACTIONS"]   = FALSE; // Set to TRUE to enable advanced actions like chmod and symlinks.
  }
  // Permisos de todos los profesores para la carpeta Biblioteca
  elseif (acl_permiso($_SESSION['cargo'], array('c')) && ('/Biblioteca' == $dir)) {
    $ft["settings"]["UPLOAD"]            = TRUE; // Set to FALSE if you want to disable file uploads.
    $ft["settings"]["CREATE"]            = TRUE; // Set to FALSE if you want to disable file/folder/url creation.
    $ft["settings"]["FILEACTIONS"]       = TRUE; // Set to FALSE if you want to disable file actions (rename, move, delete, edit, duplicate).
    $ft["settings"]["DELETEFOLDERS"]     = TRUE; // Set to TRUE to allow deletion of non-empty folders.
    $ft["settings"]["ADVANCEDACTIONS"]   = FALSE; // Set to TRUE to enable advanced actions like chmod and symlinks.
  }
  // Permisos de todos los profesores para la carpeta interna
  elseif (! acl_permiso($_SESSION['cargo'], array('1')) && $ft["settings"]["DIR"] == "../varios/internos") {
    $ft["settings"]["UPLOAD"]            = TRUE; // Set to FALSE if you want to disable file uploads.
    $ft["settings"]["CREATE"]            = TRUE; // Set to FALSE if you want to disable file/folder/url creation.
    $ft["settings"]["FILEACTIONS"]       = TRUE; // Set to FALSE if you want to disable file actions (rename, move, delete, edit, duplicate).
    $ft["settings"]["DELETEFOLDERS"]     = TRUE; // Set to TRUE to allow deletion of non-empty folders.
    $ft["settings"]["ADVANCEDACTIONS"]   = FALSE; // Set to TRUE to enable advanced actions like chmod and symlinks.
  }
  // Permisos de todos los profesores en sus respectivas carpetas personales
  elseif (! acl_permiso($_SESSION['cargo'], array('1')) && $ft["settings"]["DIR"] == "../varios/".$_SESSION['ide']) {
    $ft["settings"]["UPLOAD"]            = TRUE; // Set to FALSE if you want to disable file uploads.
    $ft["settings"]["CREATE"]            = TRUE; // Set to FALSE if you want to disable file/folder/url creation.
    $ft["settings"]["FILEACTIONS"]       = TRUE; // Set to FALSE if you want to disable file actions (rename, move, delete, edit, duplicate).
    $ft["settings"]["DELETEFOLDERS"]     = TRUE; // Set to TRUE to allow deletion of non-empty folders.
    $ft["settings"]["ADVANCEDACTIONS"]   = FALSE; // Set to TRUE to enable advanced actions like chmod and symlinks.
  }
  else {
  // Permisos en el resto de carpetas públicas
    $ft["settings"]["UPLOAD"]            = FALSE; // Set to FALSE if you want to disable file uploads.
    $ft["settings"]["CREATE"]            = FALSE; // Set to FALSE if you want to disable file/folder/url creation.
    $ft["settings"]["FILEACTIONS"]       = FALSE; // Set to FALSE if you want to disable file actions (rename, move, delete, edit, duplicate).
    $ft["settings"]["DELETEFOLDERS"]     = FALSE; // Set to TRUE to allow deletion of non-empty folders.
    $ft["settings"]["ADVANCEDACTIONS"]   = FALSE; // Set to TRUE to enable advanced actions like chmod and symlinks.
  }

  return $ft;
}
