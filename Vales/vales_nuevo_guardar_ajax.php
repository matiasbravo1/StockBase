<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_vale = test_input($_POST["id_vale"]);
    $hora = test_input($_POST["hora"]);
    $fecha = test_input($_POST["fecha"]);
    $observaciones = test_input($_POST["observaciones"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}


//PONE GUARDADO EN TABLAS
$sql = "UPDATE articulos_vale SET `guardado` = '1' WHERE `id_vale` ='" . $id_vale . "'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.1");
}

$sql2 = "UPDATE vales SET `guardado` = '1', `hora` = '$hora', `fecha` = '$fecha', `observaciones` = '$observaciones' WHERE `id_vale` ='" . $id_vale . "'";
$result2 = $conn->query($sql2);

if ($result2 != 1) {
    exit("Algo anduvo mal.2");
}

//AUDITAR
$date = new DateTime();
$date->modify('-3 hours');
$hoy = $date->format('Y-m-d');
$ahora = $date->format('H:i:s');
$user = $_SESSION['user_name'];

$sql = "INSERT INTO auditorias (fecha, hora, usuario, accion, detalles) VALUES ('$hoy','$ahora','$user','Nuevo Vale','Vale: " . $id_vale . "')";
$result = $conn->query($sql);

$conn->close();

exit('Éxito.');

?> 