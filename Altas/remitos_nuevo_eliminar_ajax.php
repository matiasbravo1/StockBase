<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_alta = test_input($_POST["id_alta"]);
    $id_remito = test_input($_POST["id_remito"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$sql = "DELETE FROM altas WHERE `id_alta` ='" . $id_alta . "'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

$sql = "SELECT * FROM altas WHERE `id_remito` = '" . $id_remito . "' ORDER BY id_alta DESC";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    exit("Sin articulos.");
}

echo '<div class="table-responsive table-striped table-hover mt-3" style="border-radius:8px 8px 0px 0px;">
        <table class="table table-sm table-borderless" style="border:0.5px solid #163969">
            <thead>
              <tr class="" style="background-color:#163969;color:white;">
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
    
	echo '<tr>
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

echo "</tbody></table>";
$conn->close();
?> 