<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = test_input($_POST["codigo"]);
    $fecha_vto = test_input($_POST["fecha_vto"]);
    $id_remito = test_input($_POST["id_remito"]);
    $cantidad = test_input($_POST["cantidad"]);
    $lote = test_input($_POST["lote"]);
    $descripcion = test_input($_POST["descripcion"]);
    $marca = test_input($_POST["marca"]);
    $familia = test_input($_POST["familia"]);
    $etiquetas = test_input($_POST["etiquetas"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$sql = "SELECT * FROM articulos WHERE `codigo` = '" . $codigo . "'";
$result = $conn->query($sql);

if($result->num_rows == 0){

    echo '<center><span class="badge badge-danger mt-3">Error: El código no existe.</span></center>';

} else {
    
    $sql2 = "INSERT INTO altas (id_remito, codigo, descripcion, marca, familia, cantidad, restan, fecha_vto, lote, etiquetas, guardado) VALUES ('$id_remito','$codigo','$descripcion','$marca','$familia','$cantidad','$cantidad','$fecha_vto','$lote','$etiquetas', '0')";
    $result2 = $conn->query($sql2);
    
    if ($result2 != 1) {
        echo '<center><span class="badge badge-danger mt-3">Error: Algo anduvo mal.</span></center>';
    } else {
        echo '<center><span class="badge badge-success mt-3">El artículo fue agregado con éxito.</span></center>';
    }
}

include 'remitos_editar_mostrar.php';

$conn->close();
?> 