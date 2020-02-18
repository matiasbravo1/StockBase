<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = test_input($_POST["id"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$sql = "SELECT * FROM listas WHERE `id` = '$id'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    exit('Error: El valor ya no existe. Recuerde actualizar las búsquedas.');
}
$row = $result->fetch_assoc();
$proveedor = $row["proveedor"];
$familia = $row["familia"];
$marca = $row["marca"];

//ELIMINA USUARIO
$sql = "DELETE FROM listas WHERE `id` = '$id'";
$result = $conn->query($sql);

if ($result != 1) {
    exit('Error: El valor no se pudo eliminar. Consulte al programador.');
}

//AUDITAR
    $date = new DateTime();
    $date->modify('-3 hours');
    $hoy = $date->format('Y-m-d');
    $ahora = $date->format('H:i:s');
    $user = $_SESSION['user_name'];
    
    $sql3 = "INSERT INTO auditorias (fecha, hora, usuario, accion, detalles) VALUES ('$hoy','$ahora','$user','Eliminar de Lista','Proveedor: " . $proveedor . ' - Marca: ' . $marca . ' - Familia: ' . $familia . "')";
    $result3 = $conn->query($sql3);
    
$conn->close();

exit('El valor se ha eliminado con éxito.');

?> 