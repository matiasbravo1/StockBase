<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = test_input($_POST["codigo"]);
    $fecha_vto = test_input($_POST["fecha_vto"]);
    $id_remito = test_input($_POST["id_remito"]);
    $cantidad = test_input($_POST["cantidad"]);
    $lote = test_input($_POST["lote"]);
    $descripcion = test_input($_POST["descripcion"]);
    $marca = test_input($_POST["marca"]);
    $familia = test_input($_POST["familia"]);
    $etiquetas = test_input($_POST["etiquetas"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$sql = "SELECT * FROM articulos WHERE `codigo` = '" . $codigo . "'";
$result = $conn->query($sql);

if($result->num_rows == 0){

    echo '<center><span class="badge badge-danger mt-3">Error: El código no existe.</span></center>';

} else {
    
    $sql2 = "INSERT INTO altas (id_remito, codigo, descripcion, marca, familia, cantidad, restan, fecha_vto, lote, etiquetas, guardado) VALUES ('$id_remito','$codigo','$descripcion','$marca','$familia','$cantidad','$cantidad','$fecha_vto','$lote','$etiquetas', '0')";
    $result2 = $conn->query($sql2);
    
    if ($result2 != 1) {
        echo '<center><span class="badge badge-danger mt-3">Error: Algo anduvo mal.</span></center>';
    } else {
        echo '<center><span class="badge badge-success mt-3">El artículo fue agregado con éxito.</span></center>';
    }
}

$sql = "SELECT * FROM altas WHERE `id_remito` = '$id_remito' ORDER BY id_alta DESC";
$result = $conn->query($sql);

$out = '<div class="table-responsive table-striped table-hover mt-3" style="border-radius:8px 8px 0px 0px;">
        <table class="table table-sm table-borderless" style="border:0.5px solid #163969">
            <thead>
              <tr class="" style="background-color:#0d47a1;color:white;">
                <th style="text-align:center;" scope="col">Id</th>
                <th style="text-align:center;" scope="col">Código</th>
                <th style="text-align:center;" scope="col">Descripción</th>
                <th style="text-align:center;" scope="col">Marca</th>
                <th style="text-align:center;" scope="col">Familia</th>
                <th style="text-align:center;" scope="col">Lote</th>
                <th style="text-align:center;" scope="col">Fecha Vto.</th>
                <th style="text-align:center;" scope="col">Cantidad</th>
                <th style="text-align:center;" scope="col">Etiquetas</th>
                <th style="text-align:center;width:40px" scope="col"></th>
              </tr>
            </thead>
            <tbody>';

            
while($row = $result->fetch_assoc()) {
    
	$out = $out . '<tr>
			<td style="text-align:center;">' . $row["id_alta"] . '</td>
			<td style="text-align:center;">' . $row["codigo"] . '</td>
			<td style="text-align:center;">' . $row["descripcion"] . '</td>
			<td style="text-align:center;">' . $row["marca"] . '</td>
			<td style="text-align:center;">' . $row["familia"] . '</td>
			<td style="text-align:center;">' . $row["lote"] . '</td>
			<td style="text-align:center;">' . $row["fecha_vto"] . '</td>
			<td style="text-align:center;">' . $row["cantidad"] . '</td>
			<td style="text-align:center;">' . $row["etiquetas"] . '</td>
			<td style="text-align:center;" onclick=' . '"eliminarArticulo(' . "'" . $row["id_alta"] . "'" . ')"' . '><img style="vertical-align:middle;" src="../Imagenes/icon_delete.png"  width="15" height="15"></td>
		  </tr>';
}
    
$out = $out . "</tbody></table>"; 

echo $out;

$conn->close();
?> 