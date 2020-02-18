<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_art_vale = test_input($_POST["id_art_vale"]);
    $id_vale = test_input($_POST["id_vale"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$sql = "DELETE FROM articulos_vale WHERE `id_art_vale` ='" . $id_art_vale . "'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

$sql = "SELECT * FROM articulos_vale WHERE `id_vale` = '$id_vale' ORDER BY id_art_vale DESC";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    exit("Sin articulos.");
}

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