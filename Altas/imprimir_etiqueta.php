<?php
include '../Headers/session.php';
include '../barcode-master/barcode.php';
?>

<head>
    <style>
         
        @media print {
            .etiqueta{page-break-after: always;}
            body{margin:0;margin-top:3px;margin-left:8px;}
        }
        
    </style>
</head>
<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id_alta = test_input($_GET["id_alta"]);
    $cantidad = test_input($_GET["cantidad"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$sql = "SELECT * FROM altas WHERE `id_alta` = '$id_alta'";
$result = $conn->query($sql);

if($result->num_rows == 0){
    exit("No hay etiquetas a imprimir.");
}

$generator = new barcode_generator();

/* Output directly to standard output. */
//$generator->output_image($format, $symbology, $data, $options);
$symbology = 'code-39';
$options = array("sx"=>"0.7","sy"=>"0.4","ts"=>"0","p"=>"0");

while($row = $result->fetch_assoc()) {
    
    $svg = $generator->render_svg($symbology, $row["id_alta"], $options);
    $sql2 = "SELECT * FROM remitos WHERE `id_remito` = '" . $row["id_remito"] . "'";
    $result2 = $conn->query($sql2);
    $row2 = $result2->fetch_assoc();

    for ($i = 0; $i < $cantidad; $i++){
        //echo ;
        echo '<div class="etiqueta" style="font-family: Arial, sans-serif;font-weight:bold;font-size:10px;overflow:hidden;width:5.9cm;height:2.3cm;margin-bottom:10px;padding-top:1px;">' .
        '<div style="font-size:11px;width:100%;text-align:left;overflow:hidden;padding-left:2px;padding-top:3px;padding-bottom:2px;font-weight:bold">' . $row["descripcion"] . '</div>' .
        '<div style="background-color:transparent;width:35%;float:left;text-align:center;overflow:hidden;padding-top:5px;">' . $svg . '<br><span style="font-size:20px;font-weight:bold;">' . $row["codigo"] . '</span></div>' . 
        '<div style="width:65%;float:left;overflow:hidden;text-align:left;">' . 
        'ID: ' . $row["id_alta"] . ' - ING: ' . $row2["fecha"] .
        '<br>PROV: ' . $row2["proveedor"] . 
        '<br>RMT: ' . $row2["nro_remito"] . 
        '<br>LOTE: ' . $row["lote"] . 
        '<br>FVTO: ' . $row["fecha_vto"] . 
        '</div>' . 
        '</div>';
        
    }
}

$conn->close();


?> 