<div class="well well-large">
   <a href="./notas/"><li class="nav-header">Acceso para Alumnos<i class="icon icon-user icon-large pull-right"> </i></li></a>  
   <hr />                 
   <p>
                        Los alumnos y sus padres pueden acceder a las p�ginas personales del alumno mediante la clave privada que proporciona el Centro. En esas paginas se puede encontrar informaci�n personalizada sobre los siguientes aspectos:</p>
                        <a href="./notas/">
                        <li>Profesores</li>
                        <li>Libros de Texto</li>
                        <li>Horario</li>
                        <li>Calificaciones</li> 
                        <li>Actividades</li> 
                        <li>Foto del alumno</li> 
                        <li>Convivencia Escolar</li>
                        <li>Faltas de Asistencia</li>
                        <li>Mensajer�a</li>
                      	</a>
  </div>   

<div class="well well-large">
 <li class="nav-header">Otras P&aacute;ginas<i class="icon icon-external-link icon-large pull-right"> </i></li>
 <hr />
 <ul class="unstyled">	
	 <? foreach($enlaces as $index=>$valor){
     	echo '<li><a href="'.$index.'">'.$valor.'</a></li>';
     }
     ?>				
 </ul>
</div>

