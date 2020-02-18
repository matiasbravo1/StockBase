<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_remito = test_input($_POST["id_remito"]);
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
    
$sql = "SELECT id_remito FROM remitos WHERE `id_remito` = '$i_remito'";
$result = $conn->query($sql);
if ($result->num_rows != 0) {
    exit('<span class="badge badge-danger">Error: El remito ya no existe. Recuerde actualizar las búsquedas.</span>');
}

$sql = "UPDATE remitos SET `nro_remito` = '$remito_nro', `fecha` = '$fecha', `proveedor` = '$proveedor' WHERE `id_remito` = '$id_remito'";
$result = $conn->query($sql);

if ($result != 1) {
    exit('<span style="color:red">Error: Algo anduvo mal.</span>');
}

include 'remitos_editar_mostrar.php';

$conn->close();

?> 