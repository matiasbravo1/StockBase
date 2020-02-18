<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $proveedor = test_input($_POST["proveedor"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$sql = "SELECT nro_remito FROM remitos WHERE `proveedor` = '$proveedor' AND `guardado` = '1'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

if ($result->num_rows == 0) {
    $conn->close();
    exit("1");
}else{
    
    $a=array();

    while($row = $result->fetch_assoc()) {
        array_push($a, $row["nro_remito"]);
    }
    
    rsort($a);
    
    $val = $a[0] + 1;
    echo $val;
}


$conn->close();
?> 