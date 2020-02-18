<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $codigo = test_input($_POST["codigo"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$sql = "SELECT * FROM articulos WHERE `codigo` = '$codigo'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

if ($result->num_rows != 0) {
    $outp = $result->fetch_assoc();
    echo json_encode($outp);
}else{
    exit("No existe código.");
}


$conn->close();
?> 