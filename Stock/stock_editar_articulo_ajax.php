<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_articulo = test_input($_POST["id"]);
    $descripcion = test_input($_POST["descripcion"]);
    $codigo = test_input($_POST["codigo"]);
    $codigo_prov = test_input($_POST["codigo_prov"]);
    $marca = test_input($_POST["marca"]);
    $familia = test_input($_POST["familia"]);
    $minimo = test_input($_POST["minimo"]);
    $critico = test_input($_POST["critico"]);
    $mensual = test_input($_POST["mensual"]);
    $activo = test_input($_POST["activo"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

($activo == 'true') ? $activo = '1' : $activo = '0';

$sql = "SELECT codigo FROM articulos WHERE `codigo` = '$codigo' AND `id_articulo` <> '$id_articulo'";
$result = $conn->query($sql);
if ($result->num_rows != 0) {
    exit('<span class="badge badge-danger">Error: El código ya existe.</span>');
}

$sql = "UPDATE articulos SET `codigo` = '$codigo', `descripcion` = '$descripcion', `marca` = '$marca', `familia` = '$familia', `minimo` = '$minimo', `critico` = '$critico', `mensual` = '$mensual', `activo` = '$activo', `codigo_prov` = '$codigo_prov' WHERE `id_articulo` = '$id_articulo'";
$result = $conn->query($sql);

if ($result != 1) {
    exit('<span style="color:red">Algo anduvo mal.</span>');
}

//AUDITAR
$date = new DateTime();
$date->modify('-3 hours');
$hoy = $date->format('Y-m-d');
$ahora = $date->format('H:i:s');
$user = $_SESSION['user_name'];
        
$sql = "INSERT INTO auditorias (fecha, hora, usuario, accion, detalles) VALUES ('$hoy','$ahora','$user','Editar Artículo','Código: " . $codigo . ' - Descripción: ' . $descripcion . ' - Familia: ' . $familia . "')";
$result = $conn->query($sql);

$conn->close();

exit('<span class="badge badge-success">El artículo se ha editado con éxito.</span>');



?> 