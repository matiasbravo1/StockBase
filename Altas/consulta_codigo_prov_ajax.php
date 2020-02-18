<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $codigo_prov = test_input($_POST["codigo_prov"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$sql = "SELECT * FROM articulos WHERE `codigo_prov` = '$codigo_prov'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

if ($result->num_rows > 1) {
    exit("Existe más de un artículo con este código de proveedor. Utilice la opción de búsqueda haciendo doble click.");
} else if ($result->num_rows == 1) {
    $outp = $result->fetch_assoc();
    echo json_encode($outp);
}else {
    exit("No hay artículos con este código de proveedor.");
}


$conn->close();
?> 