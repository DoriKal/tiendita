<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="#" style="color:#FFF">Configuración</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li><a href="../../Principal.php" style="color:#FFF">Inicio</a></li>
              <!-- <li><a href="iva.php" style="color:#FFF">IVA</a></li> -->
              <li><a href="Deposito.php" style="color:#FFF">Sucursales</a></li>
              <li><a href="departamento.php" style="color:#FFF">Clasificación</a></li>
              <li><a href="unidad.php" style="color:#FFF">Unidad/Tipo</a></li>
              <li><a href="ubicacion.php" style="color:#FFF">Ubicacion</a></li>
              <li><a href="empresa.php" style="color:#FFF">Datos Empresa</a></li>
            </ul>
            <ul class="nav pull-right">
                <li class="dropdown">
              		<a href="#" style="color:#FFF" class="dropdown-toggle" data-toggle="dropdown">
                    	Hola! <?php echo $_SESSION['user_name']; ?> <b class="caret"></b>
                    </a>
                	<ul class="dropdown-menu">
	                    <li><a href="../../perfil.php"><i class="icon-user"></i> Mi Perfil</a></li>
                      	<li class="divider"></li>
                      	<li><a href="../../php_cerrar.php"><i class="icon-off"></i> Salir</a></li>
                    </ul>
                </li>
          	</ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
<!-- /container -->