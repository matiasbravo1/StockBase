<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = test_input($_POST["id_usuario"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$sql = "SELECT * FROM usuarios WHERE `id_usuario` = '$id_usuario'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    exit('Error: El usuario ya no existe. Recuerde actualizar las búsquedas.');
}
$row = $result->fetch_assoc();
$nombre = $row["nombre"];
$apellido = $row["apellido"];
$seccion = $row["seccion"];

//ELIMINA USUARIO
$sql = "DELETE FROM usuarios WHERE `id_usuario` = '$id_usuario'";
$result = $conn->query($sql);

if ($result != 1) {
    exit('Error: El usuario no se pudo eliminar. Consulte al programador.');
}

//AUDITAR
$date = new DateTime();
$date->modify('-3 hours');
$hoy = $date->format('Y-m-d');
$ahora = $date->format('H:i:s');
$user = $_SESSION['user_name'];
        
$sql = "INSERT INTO auditorias (fecha, hora, usuario, accion, detalles) VALUES ('$hoy','$ahora','$user','Eliminar Usuario','Nombre y apellido: " . $nombre . " " . $apellido . ' - Sección: ' . $seccion . "')";
$result = $conn->query($sql);

$conn->close();

exit('El usuario se ha eliminado con éxito.');



?> 