<? $nums_ids=0;
foreach ($_POST as $id => $valor) {
  if (is_numeric($id) and is_numeric($valor)){
$columnas = $columnas + 1;
$num_ids +=1;
$celdas .= " id = '$id' or";
  		}
  		}
 $celdas = substr($celdas,0,strlen($celdas)-3);
	if (empty($num_ids)) {
echo '<br /><div align="center"><div class="alert alert-danger alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCI�N:</h5>
Debes marcar al menos una Columna del cuaderno para poder realizar el c�lculo.
</div></div>';
echo "<INPUT TYPE='button' VALUE='Volver al Cuaderno' onClick='history.back(-1)' style='margin-top:12px;' class='btn btn-primary'>";	
exit;			
		}
// Procesamos los datos
// Distintos c�digos de la asignatura cuando hay varios grupos en una hora.
$n_c = mysql_query("SELECT distinct  a_grupo, asig FROM  horw where prof = '$profesor' and dia = '$dia' and hora = '$hora'");
while($varias = mysql_fetch_array($n_c))
{
	if (substr($varias[0],3,2) == "Dd" ) {
	$varias[0] = substr($varias[0],0,4);
	}
	$curso_alma = mysql_query("select distinct curso from alma where unidad = '$varias[0]'");
	$curso_alma1 = mysql_fetch_row($curso_alma);
	$nombre_curso = $curso_alma1[0];
	$largo = strlen($varias[1]);
	if (strlen($varias[1]) > 10) {$nombre_asig = substr($varias[1],0,10);} else {$nombre_asig = substr($varias[1],0,6);}	
	$nombre_asig = trim($nombre_asig);
	$asig_sen0 = mysql_query("select codigo from asignaturas where curso = '$nombre_curso' and nombre like '$nombre_asig%' and abrev not like '%�'");
	while($asig_sen1 = mysql_fetch_row($asig_sen0)){
	if (strstr($asigna_a , $asig_sen1[0]) == false) 
	{
	$asigna_a .= $asig_sen1[0].",";
	}
	}
}
$asigna_c = explode(",",$asigna_a);
$asignatura0 = $asigna_c[0];
$asignatura1 = $asigna_c[1];
$asignatura2 = $asigna_c[2];
if (!(empty($asignatura1))) {
	$otras = " or combasi like '%$asignatura1:%' ";
}
if (!(empty($asignatura2))) {
	$otras .= " or combasi like '%$asignatura2:%' ";
}
// Tabla con las distintas notas_cuaderno y la mediaxx
  		
  // Todos los Grupos juntos
$n_cursos = mysql_query("SELECT distinct  a_grupo, c_asig FROM  horw where prof = '$profesor' and dia = '$dia' and hora = '$hora'");
  while($n_cur = mysql_fetch_array($n_cursos))
  {
  	$curs .= $n_cur[0].", ";
  } 
// Eliminamos el espacio
  	$curs0 = substr($curs,0,(strlen($curs)-1));
// Eliminamos la �ltima coma para el t�tulo.
	$curso_sin = substr($curs0,0,(strlen($curs0)-1));
//N�mero de columnas
	
	$col = "select distinct id, nombre, orden from notas_cuaderno where profesor = '$profesor' and curso like '%$curso%' and oculto = '0' and ($celdas)  order by orden asc";
	$col0 = mysql_query($col);
	//echo $col;
	$curso_sin = substr($curso,0,strlen($curso) - 1);
// Titulos

echo "<table align='center' class='table table-striped' style='width:auto'>"; 
echo "<thead><th style='vertical-align:bottom;background-color:#fff'>NC</th><th colspan='2' style='vertical-align:bottom;background-color:#fff'>Alumno</th>";
// N�mero de las columnas de la tabla	
	while($col20 = mysql_fetch_array($col0)){
	$ident= $col20[2];
	$id = $col20[0];
	echo "<th nowrap style='background-color:#fff'>
<div style='width:40px;height:130px;'>
<div class='Rotate-90'><span class='text-info text-lowercase'>$col20[1]</span> </div>
</div> </th>";
				
	// echo "<th><a href='#' rel='Tooltip' title='$col20[1]'>$ident</a></th>";
	}
	//echo "<th style='vertical-align:bottom;background-color:#eec'>M. Aritm&eacute;tica</th></thead><tbody>";
	echo "<th nowrap style='background-color:#fff'>
<div style='width:40px;height:130px;'>
<div class='Rotate-90'><span class='text-danger text-lowercase'><b>Media Aritm�tica</b></span> </div>
</div> </th></thead>";
	
// Tabla para cada Grupo
  $curso0 = "SELECT distinct  a_grupo, c_asig, asig FROM  horw where prof = '$profesor' and dia = '$dia' and hora = '$hora'";
  $curso20 = mysql_query($curso0);
  while ($curso11 = mysql_fetch_array($curso20))
    {
	$curso = $curso11[0];
	$asignatura = $curso11[1];
	$nombre = $curso11[2];
// N�mero de Columnas para crear la tabla
	$num_col = 4 + $num_ids;
	$nivel_curso = substr($curso,0,1);			
	
//	Problemas con Diversificaci�n (4E-Dd)
			$profe_div = mysql_query("select * from profesores where grupo = '$curso'");
			if (mysql_num_rows($profe_div)<1) {		
				
				$div = $curso;
				$grupo_div = mysql_query("select distinct unidad from alma where unidad like '$nivel_curso%' and (combasi like '%25204%' or combasi LIKE '%25226%')");
				$grupo_diver = mysql_fetch_row($grupo_div);
				$curso = $grupo_diver[0];
			}
			
	mysql_select_db($db);
	$hay0 = "select alumnos from grupos where profesor='$profesor' and asignatura = '$asignatura' and curso = '$curso'";
	$hay1 = mysql_query($hay0);
	$hay = mysql_fetch_row($hay1);	
	
	if(mysql_num_rows($hay1) == "1"){
	$seleccionados = substr($hay[0],0,strlen($hay[0])-1);
	$t_al = explode(",",$seleccionados);
	$todos = " and (nc = '300'";
	foreach($t_al as $cadauno){
	$todos .=" or nc = '$cadauno'";
	}
	$todos .= ")";
	}	
	mysql_select_db($db);
	$mediaaprobados=0;
	$mediasuspensos=0;
    $mediatotal=0;
	
// Alumnos para presentar que tengan esa asignatura en combasi
$resul = "select distinctrow FALUMNOS.CLAVEAL, FALUMNOS.NC, FALUMNOS.APELLIDOS, FALUMNOS.NOMBRE, alma.MATRICULAS, alma.combasi from FALUMNOS, alma WHERE FALUMNOS.CLAVEAL = alma.CLAVEAL and alma.unidad = '$curso' and (combasi like '%$asignatura0:%' $otras) ".$todos ." order by NC";
  $result = mysql_query ($resul);
  $t_alumnos += mysql_num_rows ($result);
        while($row = mysql_fetch_array($result))
		{  		
		$claveal = $row[0];            	
echo "<tr><td>$row[1]</td><td colspan='2' nowrap>$row[2], $row[3]</td>";
  	
// Si hay datos escritos rellenamos la casilla correspondiente
	$colu10 = "select distinct id from notas_cuaderno where ";
	 foreach ($_POST as $id => $valor) {
  		if (is_numeric($id) and is_numeric($valor)){
  		$colu10 .= " id = '$id' or"; 
  		$n_1 = "1";
  		}
  		}
  	$colu10 = substr($colu10,0,strlen($colu10)-2);
	$colu10 .= "  and profesor = '$profesor' and curso like '%$curso%' and oculto = '0'order by id";
	$colu20 = mysql_query($colu10);
	$suma = "";
	while($colus10 = mysql_fetch_array($colu20)){
	$id = $colus10[0];
	$dato0 = mysql_query("select nota from datos where claveal = '$claveal' and id = '$id'");
	$dato1 = mysql_fetch_array($dato0);
	$suma += $dato1[0];
	if ($dato1[0]==''){$dato1[0]='0';} 
	if ($dato1[0]<'5'){ $color = ' class="text-danger" ';}else{ $color = ' class="text-success" ';} 
echo "<td $color>$dato1[0]</td>";}
$media = $suma / $num_ids;
	$mediatotal+=$media;
    if($media <> 0){
	if($media < 5 ){$mediasuspensos+=1;}
	else{$mediaaprobados+=1;}
					}
echo "<td class='text-danger' style='font-weight:bold;background-color:#eee'>";
if ($media == "" ) {
	$media = "0";
					}

redondeo($media);
echo "</td>";			 
mysql_select_db($db);
echo "</tr>"; 
        }  
$toti+=$mediatotal; 
$m_sus+=$mediasuspensos;
$m_ap+=$mediaaprobados;
	}
	$i=0;
	foreach ($_POST as $id => $valor2) {
if (is_numeric($id)){
$i+=1;
	$aprobados[$i]=0;
	$suspensos[$i]=0;
	$sumanotas[$i]=0;

$est=mysql_query("select nota from datos where id='$id'");	
while ($esta=mysql_fetch_array($est)){
	
	
		if(($esta[0] < 5) or ($esta[0]=='')){$suspensos[$i]+=1;  $sumanotas[$i]+=$esta[0];}
	else{$aprobados[$i]+=1;  $sumanotas[$i]+=$esta[0]; 
	     	
		}
				}
	
	}}
	
	//media del grupo
	echo "<tr class='info'><td align='left' colspan='3' style='font-weight:bold;'>Media del Grupo</td>";
	for($j = 1;$j<=$i;$j++) {
	$x_total=$sumanotas[$j]/$t_alumnos;
	$x=$sumanotas[$j]/($aprobados[$j]+$suspensos[$j]);
           // redondeamos o truncamos con la funcion redondeo
	echo "<td align='left'>";redondeo($x_total);  echo"</td>";
							}
	$fin=$toti/($m_ap+$m_sus);
	$fin_total=$toti/($t_alumnos);

    echo "<td style='font-weight:bold'>"; redondeo($fin_total);  echo"</td>";
	
	echo "</tr><tr class='success'><td colspan='3' align='left' style='font-weight:bold;'>Aprobados</td>";
	for($j = 1;$j<=$i;$j++) {
	echo "<td align='center'>$aprobados[$j]</td>";
	$pap=($mediaaprobados/($t_alumnos))*100;
							}
    echo "<td align='center' style='font-weight:bold'>$mediaaprobados => "; redondeo($pap); echo"%</td>";
	echo "</tr><tr class='danger'><td colspan='3' style='font-weight:bold;'>Suspensos</td>";
	for($j = 1;$j<=$i;$j++) {
	  $t_s1=$t_alumnos-$aprobados[$j];
	echo "<td align='center'>$t_s1 </td>";
		$t_s= $t_alumnos - $mediaaprobados;

	$psus=($t_s/($t_alumnos))*100;	
						}
    echo "<td align='center' style='font-weight:bold'>$t_s => "; redondeo($psus);  echo"%</td>";
	echo "</tr>";	
echo '</table>';
echo "<br /><INPUT TYPE='button' VALUE='Volver al Cuaderno' onClick='history.back(-1)' class='btn btn-primary'>";	
?>
</div>