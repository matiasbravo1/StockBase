<?php
    include ('session.php');
    session_start();
   
    //AUDITAR
    $date = new DateTime();
    $date->modify('-3 hours');
    $hoy = $date->format('Y-m-d');
    $ahora = $date->format('H:i:s');
    $user = $_SESSION['user_name'];
    
    $sql3 = "INSERT INTO auditorias (fecha, hora, usuario, accion, detalles) VALUES ('$hoy','$ahora','$user','Cierre de Sesión','Nombre y apellido: " . $user . "')";
    $result3 = $conn->query($sql3);
     //FIN AUDITAR
     
   if(session_destroy()) {
      header("Location: /Headers/login.php");
   }
?>