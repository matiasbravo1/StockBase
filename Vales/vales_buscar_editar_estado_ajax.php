<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_vale = test_input($_POST["id_vale"]);
    $estado = test_input($_POST["estado"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
if ($estado == 'Cancelado'){
    $sql = "SELECT id_baja FROM bajas WHERE `id_vale` = '$id_vale'";
    $result = $conn->query($sql);
    if ($result->num_rows != 0){
        exit('Error: No se puede marcar como Cancelado un vale con bajas.');
    }
}

$sql = "UPDATE vales SET `estado` = '$estado' WHERE `id_vale` = '$id_vale'";
$result = $conn->query($sql);

if ($result != 1) {
    exit('Error.');
}

echo $estado;

//AUDITAR
$date = new DateTime();
$date->modify('-3 hours');
$hoy = $date->format('Y-m-d');
$ahora = $date->format('H:i:s');
$user = $_SESSION['user_name'];

$sql = "INSERT INTO auditorias (fecha, hora, usuario, accion, detalles) VALUES ('$hoy','$ahora','$user','Estado Vale','Vale: " . $id_vale . ' - Nuevo estado: ' . $estado . "')";
$result = $conn->query($sql);

$conn->close();

?> 