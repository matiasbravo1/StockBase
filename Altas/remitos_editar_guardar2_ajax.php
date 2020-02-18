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

//SUMA CANTIDADES EN STOCK
$sql = "SELECT codigo, cantidad FROM altas WHERE `id_remito` ='" . $id_remito . "' AND `guardado` = '0'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Error: Algo anduvo mal.3");
}

//ACA FALTARÍA VERIFICAR QUE LOS ARTICULOS TODAVIA EXISTEN EN STOCK

if ($result->num_rows != 0){
    
    while($row = $result->fetch_assoc()) {
        $sql3 = "SELECT stock FROM articulos WHERE `codigo` = '" . $row["codigo"] . "'";
        $result3 = $conn->query($sql3);
        $row3 = $result3->fetch_assoc();
        
        $stock_inicial = $row3["stock"];
        $result3->free();
        
        $stock_final = $stock_inicial + $row["cantidad"];
        
        $sql3 = "UPDATE articulos SET `stock` = '" . $stock_final . "' WHERE `codigo` = '" . $row["codigo"] . "'";
        $result3 = $conn->query($sql3);
        if ($result3 != 1) {
            echo "Error: Algo anduvo mal. Codigo:" . $row["codigo"];
        }
    
    }
    
    //PONE GUARDADO EN TABLAS REMITOS Y ALTAS
    $sql = "UPDATE altas SET `guardado` = '1' WHERE `id_remito` ='" . $id_remito . "' AND `guardado` = '0'";
    $result = $conn->query($sql);
    
    if ($result != 1) {
        echo "Error: Algo anduvo mal.1" . $row["codigo"];
    }
    
}

//AUDITAR
$date = new DateTime();
$date->modify('-3 hours');
$hoy = $date->format('Y-m-d');
$ahora = $date->format('H:i:s');
$user = $_SESSION['user_name'];

$sql = "SELECT * FROM remitos WHERE `id_remito` = '" . $id_remito . "'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$nro_remito = $row["nro_remito"];
$proveedor = $row["proveedor"];
$fecha_remito = $row["fecha"];
        
$sql = "INSERT INTO auditorias (fecha, hora, usuario, accion, detalles) VALUES ('$hoy','$ahora','$user','Editar Remito','Remito Nro. " . $nro_remito . ' - Proveedor: ' . $proveedor . ' - Fecha: ' . $fecha_remito . "')";
$result = $conn->query($sql);

$conn->close();

?> 