     
    <div class="container">  
    
    <div class="navbar navbar-fixed-top no_imprimir visible-phone visible-tablet">
  <div class="navbar-inner2">
    <div class="container-fluid">
    <div class="convive">
      <a class="btn btn-default" data-toggle="collapse" data-target=".convive .nav-collapse" style="float:right">
        <span class="icon-list"></span>
      </a>
      <a class="brand" href="http://<? echo $dominio;?>/intranet/index0.php" style="color:#2c2c2c">Actas del Departamento</a>
      <div id="convive" class="nav-collapse collapse">
        <ul class="nav nav-pills">
 <li><a href="add.php">Nueva Acta</a></li> 
 <?
          if (strstr($_SESSION['cargo'],"1") == TRUE) {
          	?>
          	<li><a href="index_admin.php">Todas las Actas</a></li>
          	<?
          }
          ?>                    
          <form method="post" action="buscar.php" class="form-search" style="margin-top:5px;">
<input type="search" name="expresion" id="exp" class="search-query" />
<button type="submit" class="btn btn-primary"> <i class="icon icon-white icon-search"> </i> Buscar en las Actas</button>
    </form>
    </ul>
      </div><!--/.nav-collapse -->
      </div>
    </div>
  </div>
</div>

        <div class="subnav subnav-fixed hidden-phone hidden-tablet">
          <ul class="nav nav-pills">
 <li><a href="add.php">Nueva Acta</a></li>                 		
 <?
          if (strstr($_SESSION['cargo'],"1") == TRUE) {
          	?>
          	<li><a href="index_admin.php">Todas las Actas</a></li>
          	<?
          }
          ?>
          <form method="post" action="buscar.php" class="form-search" style="margin-top:5px;">
<input type="search" name="expresion" id="exp" class="search-query" />
<button type="submit" class="btn btn-primary"> <i class="icon icon-white icon-search"> </i> Buscar en las Actas</button>
    </form>
    </ul>
        </div>
        </div>
