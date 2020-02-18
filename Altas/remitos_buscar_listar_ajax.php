<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $fecha_desde = test_input($_POST["fecha_desde"]);
  $fecha_hasta = test_input($_POST["fecha_hasta"]);
  $remito_nro = test_input($_POST["remito_nro"]);
  $proveedor = test_input($_POST["proveedor"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if (empty($fecha_desde) or empty($fecha_hasta)){
$sql_fechas = NULL;
} else {
$sql_fechas = " AND `fecha` BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'";
}

if (empty($remito_nro)){
$sql_remito = NULL;
} else {
$sql_remito = " AND `nro_remito` = '" . $remito_nro . "'";
}

if (empty($proveedor)){
    $sql_proveedor = NULL;
} else {
    $sql_proveedor = " AND `proveedor` = '" . $proveedor . "'";
}


$sql = "SELECT * FROM remitos WHERE `guardado` = '1'" . $sql_fechas . $sql_remito . $sql_proveedor . " ORDER BY fecha DESC";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

if ($result->num_rows == 0) {
    exit('<br><center><font style="font-weight:bold;color:#0d47a1;">No hay remitos con esos criterios.</font></center>');
}

echo '<div class="table-responsive table-hover" style="background-color:white;">
        <table class="table table-sm table-borderless mb-0">
            <thead>
              <tr class="" style="background-color:#0d47a1;color:white;">
                <th style="text-align:center;" scope="col">Fecha</th>
                <th style="text-align:center;" scope="col">Remito</th>
                <th style="text-align:center;" scope="col">Proveedor</th>
              </tr>
            </thead>
            <tbody>';

            
while($row = $result->fetch_assoc()) {
    
    echo '<tr id="renglon' . $row["id_remito"] . '" onclick=' . '"abrirRemito(' . "'" . $row["id_remito"] . "'" . ')">
			<td style="text-align:center;">' . $row["fecha"] . '</td>
			<td style="text-align:center;">' . $row["nro_remito"] . '</td>
			<td style="text-align:center;">' . $row["proveedor"] . '</td>
		  </tr>';
}
    
echo "</tbody></table></div>"; 



$conn->close();
?> 