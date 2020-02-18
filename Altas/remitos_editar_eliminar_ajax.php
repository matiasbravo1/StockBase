<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_alta = test_input($_POST["id_alta"]);
    $id_remito = test_input($_POST["id_remito"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$sql = "SELECT guardado, cantidad, codigo FROM altas WHERE `id_alta` = '" . $id_alta . "'";
$result = $conn->query($sql);

if ($result->num_rows == 0){
    echo "Error: El artículo ya no existe.<br>";
    include "remitos_editar_mostrar.php";
    exit();
}

$row = $result->fetch_assoc();
$guardado = $row["guardado"];
$cantidad = $row["cantidad"];
$codigo = $row["codigo"];
$result->free();

if ($guardado == '0'){
    $sql = "DELETE FROM altas WHERE `id_alta` ='" . $id_alta . "'";
    $result = $conn->query($sql);
    
    if ($result != 1) {
        echo "Error: Algo anduvo mal.";
        include "remitos_editar_mostrar.php";
        exit();
    }
    
} else {
    
    $sql3 = "SELECT stock FROM articulos WHERE `codigo` = '" . $codigo . "'";
    $result3 = $conn->query($sql3);
    $row3 = $result3->fetch_assoc();
    $stock_inicial = $row3["stock"];
    $result3->free();
    
    $stock_final = intval($stock_inicial) - intval($cantidad);
    
    $sql3 = "UPDATE articulos SET `stock` = '" . $stock_final . "' WHERE `codigo` = '" . $codigo . "'";
    $result3 = $conn->query($sql3);
    if ($result3 != 1) {
        echo "Error: Algo anduvo mal.5";
        include "remitos_editar_mostrar.php";
        exit();
    }
    
    $sql = "DELETE FROM altas WHERE `id_alta` ='" . $id_alta . "'";
    $result = $conn->query($sql);
    
    if ($result != 1) {
        echo "Error: Algo anduvo mal.3";
        include "remitos_editar_mostrar.php";
        exit();
    }
}

include "remitos_editar_mostrar.php";

$conn->close();
?> 