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

$sql = "DELETE FROM altas WHERE `id_remito` ='" . $id_remito . "'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.1");
}

$sql2 = "DELETE FROM remitos WHERE `id_remito` ='" . $id_remito . "'";
$result2 = $conn->query($sql2);

if ($result2 != 1) {
    exit("Algo anduvo mal.2");
}

$conn->close();

exit('Éxito.');

?> 