<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $proveedor = test_input($_POST["proveedor"]);
    $fecha = test_input($_POST["fecha"]);
    $remito_nro = test_input($_POST["remito_nro"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
    
$sql = "INSERT INTO remitos (fecha, nro_remito, proveedor) VALUES ('" . $fecha . "','" . $remito_nro . "','" . $proveedor . "')";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

$last_id = $conn->insert_id;

echo $last_id;

$conn->close();
?> 