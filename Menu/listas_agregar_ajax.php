<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lista = test_input($_POST["lista"]);
    $valor = test_input($_POST["valor"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$sql = "INSERT INTO listas (" . $lista . ") VALUES ('" . $valor . "')";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Error: Algo anduvo mal.");
}

//AUDITAR
    $date = new DateTime();
    $date->modify('-3 hours');
    $hoy = $date->format('Y-m-d');
    $ahora = $date->format('H:i:s');
    $user = $_SESSION['user_name'];
    
    $sql3 = "INSERT INTO auditorias (fecha, hora, usuario, accion, detalles) VALUES ('$hoy','$ahora','$user','Agregar a Lista','Lista: " . $lista . ' - Valor: ' . $valor . "')";
    $result3 = $conn->query($sql3);
    
$conn->close();
?> 