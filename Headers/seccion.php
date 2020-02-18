<?php
include 'session.php';

if ($_SESSION['tipo'] != 'Sección'){
     header("location:../Headers/central.php");
     die();
}

?>

<!DOCTYPE html>
<html lang="es-AR">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
	<title>Stock Manager 1.0</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="/css/mdb.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="/css/style.css" rel="stylesheet">

</head>

<body>
    
<!-- MENU ------------------------#000099------------------------------------------->
<div class="container-fluid">
       <!--Navbar-->
    <nav class="navbar navbar-expand-lg navbar-dark blue-gradient z-depth-1" style="border-radius: 0px 0px 8px 8px">
    
      <!-- Collapse button -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#basicExampleNav"
        aria-controls="basicExampleNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    
      <!-- Collapsible content -->
      <div class="collapse navbar-collapse" id="basicExampleNav">
    
        <!-- Links -->
        <ul class="navbar-nav mr-auto">
          <!-- Dropdown -->
          <li class="nav-item dropdown" id="vales">
            <a class="nav-link dropdown-toggle"data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">Vales</a>
            <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="/Vales/vales_nuevo.php">Nuevo vale</a>
              <a class="dropdown-item" href="/Vales/vales_seccion_buscar.php">Buscar por vale</a>
              <a class="dropdown-item" href="/Vales/vales_seccion_buscar_articulo.php">Buscar por artículo</a>
            </div>
          </li>
        </ul>
        <a href="../index.php"><img src="../Imagenes/logo2.png" height="35px" class="mr-auto ml-auto"></a>
        <!-- Derecha -->
        <ul class="navbar-nav ml-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">Usuario:&nbsp<?php echo $_SESSION['login_user'];?></a>
            <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink" style="left:auto!important;right:0!important">
              <a class="dropdown-item" href="/Headers/logout.php">Cerrar sesión</a>
            </div>
          </li>
        </ul>
        
      </div>
      <!-- Collapsible content -->

    </nav>
    <!--/.Navbar--> 
</div>



<!-- SCRIPTS -->
<!-- JQuery -->
<script type="text/javascript" src="/js/jquery-3.4.1.min.js"></script>
<!-- Bootstrap tooltips -->
<script type="text/javascript" src="/js/popper.min.js"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="/js/mdb.min.js"></script>
<script type="text/javascript" src="/js/objetoajax.js"></script>
</body>
</html>