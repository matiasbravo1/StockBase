<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seccion = test_input($_POST["seccion"]);
    $fecha = test_input($_POST["fecha"]);
    $id_usuario = test_input($_POST["id_usuario"]);
    $usuario = test_input($_POST["usuario"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$sql = "INSERT INTO vales (fecha, seccion, id_usuario, usuario, descarga, estado, guardado) VALUES ('$fecha','$seccion','$id_usuario','$usuario','No', 'Normal', '0')";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

$last_id = $conn->insert_id;

echo $last_id;

$conn->close();
?> 