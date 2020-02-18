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
$row = $result->fetch_assoc();
$titulo = "Artículos de: " . $row["codigo"] . " - " . $row["descripcion"] . " - " . $row["marca"] . "<br>";

$sql = "SELECT * FROM altas INNER JOIN remitos ON remitos.id_remito = altas.id_remito WHERE altas.codigo = '$codigo' AND altas.restan <> '0' AND altas.guardado = '1' ORDER BY altas.id_alta DESC";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

if ($result->num_rows == 0) {
    exit('<div class="text-center text-secondary" style="font-weight:bold;">No hay artículos con ese código.</div>');
}

echo '<div class="text-center" style="color:green;font-weight:bold;margin-bottom:6px;">' . $titulo . '</div>';
echo '<div class="table-responsive table-striped" style="border-radius:8px 8px 0px 0px;">
        <table class="table table-sm table-borderless" style="border:0.5px solid green">
            <thead>
              <tr class="primary-color-dark" style="color:white;">
                <th style="text-align:center;" scope="col">Id</th>
                <th style="text-align:center;" scope="col">Fecha</th>
                <th style="text-align:center;" scope="col">Proveedor</th>
                <th style="text-align:center;" scope="col">Remito Nro.</th>
                <th style="text-align:center;" scope="col">Lote</th>
                <th style="text-align:center;" scope="col">Fecha Vto.</th>
                <th style="text-align:center;" scope="col">Alta</th>
                <th class="danger-color-dark" style="text-align:center;" scope="col">Baja</th>
                <th class="success-color-dark" style="text-align:center;" scope="col">Stock</th>
              </tr>
            </thead>
            <tbody>';

            
while($row = $result->fetch_assoc()) {
     
	echo '<tr>
			<td style="text-align:center;">' . $row["id_alta"] . '</td>
			<td style="text-align:center;">' . $row["fecha"] . '</td>
			<td style="text-align:center;">' . $row["proveedor"] . '</td>
			<td style="text-align:center;">' . $row["nro_remito"] . '</td>
			<td style="text-align:center;">' . $row["lote"] . '</td>
			<td style="text-align:center;">' . $row["fecha_vto"] . '</td>
			<td style="text-align:center;">' . $row["cantidad"] . '</td>
			<td style="text-align:center;">' . $row["entregados"] . '</td>
			<td style="text-align:center;">' . $row["restan"] . '</td>
		  </tr>';
}

echo "</tbody></table>"; 

$conn->close();
?> 