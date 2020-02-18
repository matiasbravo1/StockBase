<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_vale = test_input($_POST["id_vale"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$sql = "DELETE FROM articulos_vale WHERE `id_vale` ='" . $id_vale . "'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.1");
}

$sql2 = "DELETE FROM vales WHERE `id_vale` ='" . $id_vale . "'";
$result2 = $conn->query($sql2);

if ($result2 != 1) {
    exit("Algo anduvo mal.2");
}

$conn->close();

exit('Éxito.');

?> 