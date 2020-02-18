<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $apellido = test_input($_POST["apellido"]);
    $nombre = test_input($_POST["nombre"]);
    $tipo = test_input($_POST["tipo"]);
    $usuario = test_input($_POST["usuario"]);
    $clave = test_input($_POST["clave"]);
    $activo = test_input($_POST["activo"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

($activo == 'true') ? $activo = '1' : $activo = '0';

$sql = "SELECT id_usuario FROM usuarios WHERE `usuario` = '$usuario'";
$result = $conn->query($sql);
if ($result->num_rows != 0) {
    exit('Error: El nombre de usuario ya existe.');
}

$sql = "INSERT INTO usuarios (apellido, nombre, tipo, usuario, clave, activo) VALUES ('$apellido','$nombre','$tipo','$usuario','$clave','$activo')";
$result = $conn->query($sql);

if ($result != 1) {
    exit('Error: Algo anduvo mal.');
}

//AUDITAR
$date = new DateTime();
$date->modify('-3 hours');
$hoy = $date->format('Y-m-d');
$ahora = $date->format('H:i:s');
$user = $_SESSION['user_name'];
        
$sql = "INSERT INTO auditorias (fecha, hora, usuario, accion, detalles) VALUES ('$hoy','$ahora','$user','Nuevo Usuario','Nombre y apellido: " . $nombre . " " . $apellido . ' - Tipo: ' . $tipo . "')";
$result = $conn->query($sql);

$conn->close();

exit('El usuario se ha creado con éxito.');

?> 