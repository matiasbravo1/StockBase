<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_articulo = test_input($_POST["id"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

//REVISA QUE TENGA STOCK = 0
$sql = "SELECT stock, codigo FROM articulos WHERE `id_articulo` = '$id_articulo'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    exit('<span class="badge badge-danger">Error: El artículo no existe.</span>');
}

$row = $result->fetch_assoc();
if ($row["stock"] != '0'){
    exit('<span class="badge badge-danger">Error: No se puede eliminar un artículo con stock distinto de cero.</span>');
}

$codigo = $row["codigo"];
$descripcion = $row["descripcion"];
$familia = $row["familia"];

//REVISA QUE NO ESTÉ PENDIENTE EN UN VALE
$sql = "SELECT articulos_vale.id_art_vale FROM articulos_vale INNER JOIN vales ON articulos_vale.id_vale = vales.id_vale WHERE articulos_vale.codigo = '$codigo' AND articulos_vale.pedidos <> articulos_vale.entregados AND vales.estado = 'Normal'";
$result = $conn->query($sql);

if ($result != 1) {
    exit('<span style="color:red">Error1.</span>');
}

if ($result->num_rows != 0) {
    exit('<span class="badge badge-danger">Error: El artículo no se puede eliminar porque está pendiente en un vale con estado Normal.</span>');
}


//ELIMINA ARTICULO
$sql = "DELETE FROM articulos WHERE `id_articulo` = '$id_articulo'";
$result = $conn->query($sql);

if ($result != 1) {
    exit('<span style="color:red">Error: El artículo no se pudo eliminar. Consulte al programador.</span>');
}

//AUDITAR
$date = new DateTime();
$date->modify('-3 hours');
$hoy = $date->format('Y-m-d');
$ahora = $date->format('H:i:s');
$user = $_SESSION['user_name'];
        
$sql = "INSERT INTO auditorias (fecha, hora, usuario, accion, detalles) VALUES ('$hoy','$ahora','$user','Eliminar Artículo','Código: " . $codigo . ' - Descripción: ' . $descripcion . ' - Familia: ' . $familia . "')";
$result = $conn->query($sql);

$conn->close();

exit('<span class="badge badge-success">El artículo se ha eliminado con éxito.</span>');

?> 