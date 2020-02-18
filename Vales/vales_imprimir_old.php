<?php
include '../Headers/session.php';
?>

<!DOCTYPE html>
<html lang="es-AR">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
	<title>Stock Manager 1.0</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="/css/mdb.css" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="/css/style.css" rel="stylesheet">
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
    $sql = "SELECT * FROM vales WHERE `id_vale` = '" . $vale . "' AND `descarga` <> 'Total' AND `estado` = 'Normal'";
    $result = $conn->query($sql);

    if ($result->num_rows != 0) {
        $row = $result->fetch_assoc();
        echo '<div class="cuadros-vale">
                <div class="encabezado-vale">
                    Vale: ' . $vale . ' - ' . $row["seccion"] . ' - ' . $row["usuario"] . ' - ' . $row["fecha"] . " - " . substr($row["hora"],0,5) . '</div>';
                
        $sql2 = "SELECT * FROM articulos_vale WHERE `id_vale` = '" . $vale . "' AND `falta_entregar` <> '0'";
        $result2 = $conn->query($sql2);
        
        if ($result2->num_rows != 0) {
            while($row2 = $result2->fetch_assoc()){
                echo '<span class="articulo-vale">' .
                        $row2["falta_entregar"] . " >> " . $row2["codigo"] . " - " . $row2["descripcion"] . " - " . $row2["marca"] . ' >> </span>';
                        
                $sql3 = "SELECT * FROM altas WHERE `codigo` = '" . $row2["codigo"] . "' AND `restan` <> '0' ORDER BY fecha_vto ASC";
                $result3 = $conn->query($sql3);
                if ($result3->num_rows != 0) {
                    while($row3 = $result3->fetch_assoc()){
                        echo '<span>' .
                                $row3["id_alta"] . " - " . $row3["lote"] . " - " . $row3["fecha_vto"] . " - " . $row3["restan"] . " // ";
                    } 
                }else{
                    echo '<span>No hay stock.';
                }
                    
                echo '</span>';
                echo '<br>';    
                
            }
                
        }
            
            
    }    
    
    echo '</div>';

}

$conn->close();
?> 

<script>

window.onload = function(){
    window.print();
    window.close();
};

</script>
</body>