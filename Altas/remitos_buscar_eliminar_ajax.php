<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_remito = test_input($_POST["id_remito"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$sql = "SELECT * FROM remitos WHERE `id_remito` = '$id_remito'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    exit('<span class="badge badge-danger">Error: El remito no existe. Recuerde actualizar las búsquedas.</span>');
}
$row = $result->fetch_assoc();
$nro_remito = $row["nro_remito"];
$fecha_remito = $row["fecha"];
$proveedor = $row["proveedor"];

$sql = "SELECT id_alta FROM altas WHERE `id_remito` = '$id_remito' AND `cantidad` <> `restan`";
$result = $conn->query($sql);
if ($result->num_rows != 0) {
    exit('<span class="badge badge-danger">Error: No se pueden eliminar remitos que contienen artículos que han sido entregados.</span>');
}

//RESTA CANTIDADES EN STOCK
$sql = "SELECT id_alta, codigo, cantidad FROM altas WHERE `id_remito` ='" . $id_remito . "'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.3");
}

$array = array();

while($row = $result->fetch_assoc()) {
    $sql3 = "SELECT stock FROM articulos WHERE `codigo` = '" . $row["codigo"] . "'";
    $result3 = $conn->query($sql3);
    $row3 = $result3->fetch_assoc();
    $stock_inicial = $row3["stock"];
    $result3->free();
    
    $stock_final = $stock_inicial - $row["cantidad"];
    
    $sql3 = "UPDATE articulos SET `stock` = '" . $stock_final . "' WHERE `codigo` = '" . $row["codigo"] . "'";
    $result3 = $conn->query($sql3);
    if ($result3 != 1) {
       echo "Algo anduvo mal. Codigo:" . $row["codigo"];
    } else {
        array_push($array, $row["id_alta"]);
    }

}

//BORRA DE ALTAS SOLO LOS QUE SE PUDIERON DESCONTAR DEL STOCK
foreach ($array as $value){
    $sql = "DELETE FROM altas WHERE `id_alta` = '$value'";
    $result = $conn->query($sql);
    
}

//CHEQUEA QUE ESTÉ TODO BORRADO ANTES DE BORRAR EL REMITO
$sql = "SELECT id_alta FROM altas WHERE `id_remito` ='" . $id_remito . "'";
$result = $conn->query($sql);

if ($result->num_rows != 0) {
    exit('<span class="badge badge-danger">Error: El remito no se pudo eliminar porque no todos sus artículos pudieron descontarse del stock o eliminarse.</span>');
} else {
    $sql = "DELETE FROM remitos WHERE `id_remito` = '$id_remito'";
    $result = $conn->query($sql);
    echo '<center><span class="badge badge-success">El remito se ha eliminado con éxito.</span></center>';
}


//AUDITAR
$date = new DateTime();
$date->modify('-3 hours');
$hoy = $date->format('Y-m-d');
$ahora = $date->format('H:i:s');
$user = $_SESSION['user_name'];

$sql = "INSERT INTO auditorias (fecha, hora, usuario, accion, detalles) VALUES ('$hoy','$ahora','$user','Eliminar Remito','Remito Nro. " . $nro_remito . ' - Proveedor: ' . $proveedor . ' - Fecha: ' . $fecha_remito . "')";
$result = $conn->query($sql);

$conn->close();

?> 