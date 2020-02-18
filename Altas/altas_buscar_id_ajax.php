<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id_art = test_input($_POST["id_art"]);
  $codigo = test_input($_POST["codigo"]);
  $descripcion = test_input($_POST["descripcion"]);
  $marca = test_input($_POST["marca"]);
  $familia = test_input($_POST["familia"]);
  $fecha_desde = test_input($_POST["fecha_desde"]);
  $fecha_hasta = test_input($_POST["fecha_hasta"]);
  $remito_nro = test_input($_POST["remito_nro"]);
  $proveedor = test_input($_POST["proveedor"]);
  $stock = test_input($_POST["stock"]);
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

if (empty($id_art)){
$sql_id = NULL;
} else {
$sql_id = " AND `id_alta` = '" . $id_art . "'";
}


if (empty($codigo)){
$sql_codigo = NULL;
} else {
$sql_codigo = " AND `codigo` = '" . $codigo . "'";
}


if (empty($descripcion)){
$sql_descripcion = NULL;
} else {
$sql_descripcion = " AND `descripcion` LIKE '%" . $descripcion . "%'";
}

if (empty($marca)){
$sql_marca = NULL;
} else {
$sql_marca = " AND `marca` = '" . $marca . "'";
}

if (empty($familia)){
$sql_familia = NULL;
} else {
$sql_familia = " AND `familia` = '" . $familia . "'";
}

//falta vencido y stock
if (empty($stock)){
    $sql_stock = NULL;
} else {
    if($stock == "igualcero"){
        $sql_stock = " AND `restan` = '0'";
    }elseif($stock == "mayorcero"){
        $sql_stock = " AND `restan` > '0'";
    }
}


$sql = "SELECT * FROM altas INNER JOIN remitos ON altas.id_remito = remitos.id_remito WHERE altas.guardado = '1'" . $sql_fechas . $sql_remito . $sql_proveedor . $sql_id . $sql_codigo . $sql_descripcion . $sql_marca . $sql_familia . $sql_stock . "ORDER BY id_alta DESC LIMIT 100";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

if ($result->num_rows == 0) {
    exit('<div class="text-center mt-3" style="color:#007E33;font-weight:bold;">No hay artículos con esos criterios.</div>');
}

echo '<div class="table-responsive" style="border-radius:8px 8px 0px 0px;">
        <table class="table table-sm table-borderless table-striped table-hover" style="border:1px solid #0d47a1">
            <thead>
              <tr class="primary-color-dark" style="color:white;">
                <th style="text-align:center;" scope="col">Id</th>
                <th style="text-align:center;" scope="col">Fecha</th>
                <th style="text-align:center;" scope="col">Remito</th>
                <th style="text-align:center;" scope="col">Proveedor</th>
                <th style="text-align:center;" scope="col">Código</th>
                <th style="text-align:center;" scope="col">Descripción</th>
                <th style="text-align:center;" scope="col">Marca</th>
                <th style="text-align:center;" scope="col">Familia</th>
                <th style="text-align:center;" scope="col">Fecha Vto.</th>
                <th style="text-align:center;" scope="col">Lote</th>
                <th style="text-align:center;" scope="col">Alta</th>
                <th class="danger-color-dark" style="text-align:center;" scope="col">Baja</th>
                <th class="success-color-dark" style="text-align:center;" scope="col">Stock</th>
              </tr>
            </thead>
            <tbody>';

$a = 0;            
while($row = $result->fetch_assoc()) {
     
	echo '<tr>
			<td style="text-align:center;">' . $row["id_alta"] . '</td>
			<td style="text-align:center;">' . $row["fecha"] . '</td>
			<td style="text-align:center;">' . $row["nro_remito"] . '</td>
			<td style="text-align:center;">' . $row["proveedor"] . '</td>
			<td style="text-align:center;">' . $row["codigo"] . '</td>
			<td style="text-align:center;">' . $row["descripcion"] . '</td>
			<td style="text-align:center;">' . $row["marca"] . '</td>
			<td style="text-align:center;">' . $row["familia"] . '</td>
			<td style="text-align:center;">' . $row["fecha_vto"] . '</td>
			<td style="text-align:center;">' . $row["lote"] . '</td>
			<td style="text-align:center;">' . $row["cantidad"] . '</td>
			<td style="text-align:center;">' . $row["entregados"] . '</td>
			<td style="text-align:center;">' . $row["restan"] . '</td>
		  </tr>';
	$a++;
}

echo "</tbody></table></div>"; 
if($a == 100){
    echo '<div class="text-center mt-3" style="color:red">Atención: Se muestran solo los primeros 100 resultados. Podrían haber más resultados. Utilice los filtros de búsqueda para visualizarlos. Consulte al programador si desea ampliar la cantidad de resultados mostrados.</div>';
}
$conn->close();
?> 