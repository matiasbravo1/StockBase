<?php
include '../Headers/session.php';
?>

<!DOCTYPE html>
<html lang="es-AR">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
	<title>StockBase 1.0</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="/css/mdb.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="/css/style.css" rel="stylesheet">
    <style>
        td, th {font-size:16px!important;}
    </style>
</head>
<body>

<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
  $lista = test_input($_GET["lista"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$vales = explode(",", $lista);
sort($vales);

foreach($vales as $vale){
    $sql = "SELECT * FROM vales WHERE `id_vale` = '" . $vale . "'";
    $result = $conn->query($sql);

    if ($result->num_rows != 0) {
        $row = $result->fetch_assoc();
        
        echo '<table style="width:100%;">
            <thead>
              <tr style="border-top: 1px solid black;border-bottom: 1px solid black;">
                <th class="pl-2" style="text-align:left;width:25%;font-weight:bold;background-color:#d9d9d9">Vale Nro: ' . $vale . '</th>
                <th style="text-align:center;width:50%;font-weight:bold;background-color:#d9d9d9">Sección: ' . $row["seccion"] . ' - ' . $row["usuario"] . '</th>
                <th class="pr-2" style="text-align:right;width:25%;font-weight:bold;background-color:#d9d9d9">Fecha: ' . $row["fecha"] . ' - ' . substr($row["hora"],0,5) . '</th>
              </tr>
            </thead></table>';
            
        echo '<table style="width:100%">
            <thead>
              <tr style="font-style: italic;">
                <th style="text-align:center;width:10%;">Cód. Art.</th>
                <th style="text-align:center;width:40%;">Descripción</th>
                <th style="text-align:center;width:10%;">Pedido</th>
                <th style="text-align:center;width:10%;">Falta entregar</th>
                <th style="text-align:center;width:10%;">Stock</th>
                <th style="text-align:center;width:20%;">Notas/Lote</th>
              </tr>
            </thead>';
            
        $sql2 = "SELECT * FROM articulos_vale WHERE `id_vale` = '" . $vale . "'";
        $result2 = $conn->query($sql2);
        
        if ($result2->num_rows != 0) {
            echo '<tbody>';
            
            while($row2 = $result2->fetch_assoc()){
                $sql3 = "SELECT * FROM articulos WHERE `codigo` = '" . $row2["codigo"] . "'";
                $result3 = $conn->query($sql3);
                $row3 = $result3->fetch_assoc();
                
                echo '
                      <tr>
                        <td style="text-align:center;">' . $row2["codigo"] . '</th>
                        <td style="text-align:left;padding-left:15px;">' . $row2["descripcion"] . '</th>
                        <td style="text-align:center;">' . $row2["pedidos"] . '</th>
                        <td style="text-align:center;">' . $row2["falta_entregar"] . '</th>
                        <td style="text-align:center;">' . $row3["stock"] . '</th>
                        <td style="widt:15%;"></th>
                      </tr>';
                
            }
            
            echo '</tbody>';
           
        }
        
        echo '</table>';
            
    }    

}

$conn->close();
?> 

<script>

/*window.onload = function(){
    window.print();
    window.close();
};*/

</script>
</body>