<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = test_input($_POST["codigo"]);
    $id_vale = test_input($_POST["id_vale"]);
    $cantidad = test_input($_POST["cantidad"]);
    $descripcion = test_input($_POST["descripcion"]);
    $marca = test_input($_POST["marca"]);
    $familia = test_input($_POST["familia"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

//SE FIJA QUE NO ESTÉ PEDIDO Y PENDIENTE EN OTRO VALE
$sql = "SELECT * FROM articulos_vale INNER JOIN vales ON articulos_vale.id_vale = vales.id_vale WHERE `codigo` = '" . $codigo . "' AND `seccion` = '" . $_SESSION['seccion'] . "' AND articulos_vale.guardado = '1' AND `falta_entregar` <> '0' AND `estado` = 'Normal'";
$result = $conn->query($sql);
if ($result->num_rows != 0){
    exit("Error: Este artículo está pendiente en otro vale.");
}


//SE FIJA QUE NO ESTÉ PEDIDO EN ESTE VALE
$sql = "SELECT id_art_vale FROM articulos_vale WHERE `id_vale` = '$id_vale' AND `codigo` = '$codigo'";
$result = $conn->query($sql);
if ($result->num_rows != 0){
    exit("Error: Este artículo ya está pedido en este vale.");
}

//SE FIJA QUE EL ARTICULO EXISTA Y ESTÉ ACTIVO PARA VALES
$sql = "SELECT * FROM articulos WHERE `codigo` = '" . $codigo . "' AND `activo` = '1'";
$result = $conn->query($sql);

if($result->num_rows == 0){
    exit('Error: El código no existe o no está habilitado para ser usado en nuevo vale.');
} 
 
//AGREGA ARTÍCULO
$sql2 = "INSERT INTO articulos_vale (id_vale, codigo, descripcion, marca, familia, pedidos, entregados, falta_entregar, guardado) VALUES ('$id_vale','$codigo','$descripcion','$marca','$familia','$cantidad','0', '$cantidad','0')";
$result2 = $conn->query($sql2);

if ($result2 != 1) {
    echo '<center><span class="badge badge-danger mt-3">Algo anduvo mal.</span></center>';
} else {
    echo '<center><span class="badge badge-success mt-3">El artículo fue agregado con éxito.</span></center>';
}


//MUESTRA ARTICULOS DE ESTE VALE
$sql = "SELECT * FROM articulos_vale WHERE `id_vale` = '$id_vale' ORDER BY id_art_vale DESC";
$result = $conn->query($sql);

$out = '<div class="table-responsive table-striped table-hover mt-3" style="border-radius:8px 8px 0px 0px;">
        <table class="table table-sm table-borderless" style="border:0.5px solid #ffa000">
            <thead>
              <tr class="" style="background-color:#ffa000;color:white;">
                <th style="text-align:center;" scope="col">Código</th>
                <th style="text-align:center;" scope="col">Descripción</th>
                <th style="text-align:center;" scope="col">Marca</th>
                <th style="text-align:center;" scope="col">Familia</th>
                <th style="text-align:center;" scope="col">Cantidad</th>
                <th style="text-align:center;width:40px" scope="col"></th>
              </tr>
            </thead>
            <tbody>';

            
while($row = $result->fetch_assoc()) {
    
	$out = $out . '<tr>
			<td style="text-align:center;">' . $row["codigo"] . '</td>
			<td style="text-align:center;">' . $row["descripcion"] . '</td>
			<td style="text-align:center;">' . $row["marca"] . '</td>
			<td style="text-align:center;">' . $row["familia"] . '</td>
			<td style="text-align:center;">' . $row["pedidos"] . '</td>
			<td style="text-align:center;" onclick=' . '"eliminarArticulo(' . "'" . $row["id_art_vale"] . "'" . ')"' . '><img style="vertical-align:middle;" src="../Imagenes/icon_delete.png"  width="15" height="15"></td>
		  </tr>';
}
    
$out = $out . "</tbody></table>"; 

echo $out;

$conn->close();
?> 