<?php
include 'session.php';

if ($_SESSION['tipo'] == 'Sección'){
     header("location:../Headers/seccion.php");
     die();
}

if($_SESSION["tipo"] == "Pañol"){
    $visible = "d-none";
}else{
    $visible = "";
}

?>

<!DOCTYPE html>
<html lang="es-AR">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
	<title>StockBase 1.0</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="/css/mdb.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="/css/style.css" rel="stylesheet">
    
    <!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.4/moment-timezone-with-data.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <script src="objetoajax.js"></script-->

</head>

<body>
    
<!-- MENU ------------------------#000099-----------------------------secondary-color-dark-------------->
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
              <a class="dropdown-item" href="/Vales/vales_buscar.php">Buscar por vale</a>
              <a class="dropdown-item" href="/Vales/vales_buscar_articulo.php">Buscar por artículo</a>
            </div>
          </li>
          <li class="nav-item dropdown" id="stock">
            <a class="nav-link dropdown-toggle"data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">Stock</a>
            <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="/Stock/stock_nuevo.php">Nuevo artículo</a>
              <a class="dropdown-item" href="/Stock/stock_buscar_codigo.php">Buscar por código</a>
              <a class="dropdown-item" href="/Stock/stock_buscar_id.php">Buscar por id</a>
              <a class="dropdown-item" href="/Stock/stock_movimientos.php">Consulta de movimientos</a>
            </div>
          </li>
          <li class="nav-item dropdown" id="altas">
            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">Altas</a>
            <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="/Altas/remitos_nuevo.php">Nuevo remito</a>
              <a class="dropdown-item" href="/Altas/remitos_buscar.php">Buscar por remito</a>
              <a class="dropdown-item" href="/Altas/altas_buscar_id.php">Buscar por id</a>
            </div>
          </li>
          <li class="nav-item dropdown" id="bajas">
            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">Bajas</a>
            <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="/Bajas/bajas_nueva.php">Nueva</a>
              <a class="dropdown-item" href="/Bajas/bajas_buscar.php">Buscar</a>
            </div>
          </li>
        </ul>
        
        <a href="../index.php"><img src="../Imagenes/logo2.png" height="35px" class="mr-auto ml-auto"></a>
        
        <!-- Derecha -->
        <ul class="navbar-nav ml-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">Configuración</a>
            <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item <?php echo $visible;?>" href="/Menu/usuarios.php">Usuarios</a>
              <a class="dropdown-item" href="/Menu/listas.php">Listas</a>
              <a class="dropdown-item" href="/Menu/auditoria.php">Auditoría</a>
            </div>
          </li>
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