<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

$sql = "SELECT codigo FROM articulos WHERE `codigo` = '$codigo'";
$result = $conn->query($sql);
if ($result->num_rows != 0) {
    exit('<span class="badge badge-danger">Error: El código ya existe.</span>');
}

$sql = "INSERT INTO articulos (codigo, descripcion, marca, familia, minimo, critico, mensual, activo, codigo_prov) VALUES ('$codigo','$descripcion','$marca','$familia','$minimo','$critico','$mensual','$activo','$codigo_prov')";
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
        
$sql = "INSERT INTO auditorias (fecha, hora, usuario, accion, detalles) VALUES ('$hoy','$ahora','$user','Nuevo Artículo','Código: " . $codigo . ' - Descripción: ' . $descripcion . ' - Familia: ' . $familia . "')";
$result = $conn->query($sql);

$conn->close();

exit('<span class="badge badge-success">El artículo se ha creado con éxito.</span>');



?> 