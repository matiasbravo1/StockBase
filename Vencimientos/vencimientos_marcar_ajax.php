<?php
include 'session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_alta = test_input($_POST["id_alta"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

//RESTA CANTIDADES EN STOCK
$sql = "SELECT restan, codigo FROM altas WHERE `id_alta` ='" . $id_alta . "'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

$row = $result->fetch_assoc();

$sql2 = "SELECT stock FROM articulos WHERE `codigo` ='" . $row["codigo"] . "'";
$result2 = $conn->query($sql2);

if ($result2 != 1) {
    exit("Algo anduvo mal.2");
}

$stock_final = $row2["stock"] - $row["restan"];

$sql3 = "UPDATE articulos SET `stock` = '" . $stock_final . "' WHERE `codigo` = '" . $row["codigo"] . "'";
$result3 = $conn->query($sql3);
if ($result3 != 1) {
    exit("Algo anduvo mal.3");
}

//PONE  VENCIDO EN ALTAS
$sql = "UPDATE altas SET `vencido` = '1' WHERE `id_alta` ='" . $id_alta . "'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.4");
}

$conn->close();
?> 