<?php

include 'config.php';
session_start();

if(isset($_SESSION['login_user']) && isset($_SESSION['tipo'])){
    if ($_SESSION['tipo'] != 'Sección'){
        header("location:central.php");
        die();
    } else {
        header("location:seccion.php");
        die();
    }
}   

   
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      
      $myusername = mysqli_real_escape_string($conn,$_POST['username']);
      $mypassword = mysqli_real_escape_string($conn,$_POST['password']); 
      
      $sql = "SELECT * FROM usuarios WHERE `usuario` = '" . $myusername . "' AND `clave` = '" . $mypassword . "'";
      $result = mysqli_query($conn,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      //$active = $row['active'];
      
      $count = mysqli_num_rows($result);
      //echo $count;
      // If result matched $myusername and $mypassword, table row must be 1 row
      $error = "";
      
      if($count == 1) {
         if($row["activo"]=='1'){
             $_SESSION['login_user'] = $myusername;
             $_SESSION['user_name'] = $row["nombre"] . " " . $row["apellido"];
             $_SESSION['tipo'] = $row["tipo"];
             
             //AUDITAR
            $date = new DateTime();
            $date->modify('-3 hours');
            $hoy = $date->format('Y-m-d');
            $ahora = $date->format('H:i:s');
            $user = $_SESSION['user_name'];
            
            $sql3 = "INSERT INTO auditorias (fecha, hora, usuario, accion, detalles) VALUES ('$hoy','$ahora','$user','Inicio de Sesión','Nombre y apellido: " . $user . "')";
            $result3 = $conn->query($sql3);
             //FIN AUDITAR
             
             if ($row["tipo"] != "Sección"){
                header("location:central.php");
             } else {
                 header("location:seccion.php");
             }
             
             
         }else{
             $error = "Usuario inactivo.";
         }
      }else {
         $error = "Nombre de usuario o contraseña incorrectos.";
      }
   }
   
?>

<html lang="es-AR">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=10" />
	<title>StockBase 1.0</title>
	<link href="../css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="../css/mdb.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="../css/style.css" rel="stylesheet">

</head>
   
<body>

    <div class="container-fluid">
        <div class="row justify-content-center mt-4">
            <div class="col-11 col-sm-11 col-md-5 col-lg-5 col-xl-5 text-center">
                <form style="background-color:#e6e6e6" class="text-center border border-light p-5 z-depth-3" action="" method="post">
    
                    <img src="../Imagenes/logo1.png" width="70%" class="mb-4">

                    <input type="text" name="username" class="form-control mb-4" placeholder="Usuario">
    
                    <input type="password" name="password" class="form-control mb-4" placeholder="Contraseña">
                
                    <button class="btn btn-info btn-block my-4" type="submit">Iniciar Sesión</button>
                
                </form>
                <?php
                if ($error != ""){
                echo '<span class="badge badge-danger mt-3">' . $error . '</span>';
                }
                ?>
            </div>
        </div>
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
