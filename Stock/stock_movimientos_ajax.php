<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $fecha_desde = test_input($_POST["fecha_desde"]);
  $fecha_hasta = test_input($_POST["fecha_hasta"]);
  $codigo = test_input($_POST["codigo"]);
  $codigo_prov = test_input($_POST["codigo_prov"]);
  $descripcion = test_input($_POST["descripcion"]);
  $marca = test_input($_POST["marca"]);
  $familia = test_input($_POST["familia"]);
  $mensual = test_input($_POST["mensual"]);
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
$sql_fechas = " AND remitos.fecha BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'";
}

if (empty($fecha_desde) or empty($fecha_hasta)){
$sql_fechas_bajas = NULL;
} else {
$sql_fechas_bajas = " AND `fecha` BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'";
}

if (empty($codigo)){
    $sql_codigo = NULL;
} else {
    $sql_codigo = " AND `codigo` = '$codigo'";
}

if (empty($codigo_prov)){
    $sql_codigo_prov = NULL;
} else {
    $sql_codigo_prov = " AND `codigo_prov` = '$codigo_prov'";
}

if (empty($descripcion)){
    $sql_descripcion = NULL;
} else {
    $sql_descripcion = " AND `descripcion` LIKE '%$descripcion%'";
}

if (empty($marca)){
    $sql_marca = NULL;
} else {
    $sql_marca = " AND `marca` = '$marca'";
}

if (empty($familia)){
    $sql_familia = NULL;
} else {
    $sql_familia = " AND `familia` = '$familia'";
}

if (empty($mensual)){
    $sql_mensual = NULL;
} else {
    $sql_mensual = " AND `mensual` LIKE '%$mensual%'";
}

$sql = "SELECT * FROM articulos WHERE `id_articulo` <> ''" . $sql_codigo . $sql_descripcion . $sql_marca . $sql_familia . $sql_mensual . $sql_codigo_prov;
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

if ($result->num_rows == 0) {
    exit('No hay artículos con esos criterios.');
}

if ($fecha_desde == ''){
    echo '<center><p style="color:#007E33;font-weight:bold;" class="mt-3">Período no especificado.</p></center>';
}else{
    echo '<center><p style="color:#007E33;font-weight:bold;" class="mt-3">Período del ' . $fecha_desde . ' al ' . $fecha_hasta . '</p></center>';
}

echo '<div class="table-responsive" style="border-radius:8px 8px 0px 0px;">
        <table class="table table-sm table-borderless table-striped table-hover" style="border:1px solid #007E33">
            <thead>
              <tr class="success-color-dark" style="color:white;">
                <th style="text-align:center;" scope="col">Código</th>
                <th style="text-align:center;" scope="col">Código Prov.</th>
                <th style="text-align:center;width:500px;" scope="col">Descripcion</th>
                <th style="text-align:center;" scope="col">Marca</th>
                <th style="text-align:center;" scope="col">Familia</th>
                <th style="text-align:center;" scope="col">Cons. Mensual</th>
                <th class="primary-color-dark" style="text-align:center;" scope="col">Altas</th>
                <th class="danger-color-dark" style="text-align:center;" scope="col">Bajas</th>
                <th style="text-align:center;" scope="col">Stock Actual</th>
              </tr>
            </thead>
            <tbody>';

            
while($row = $result->fetch_assoc()) {
    
    //SUMA ALTAS
    $sql2 = "SELECT SUM(cantidad) AS total_altas FROM altas INNER JOIN remitos ON altas.id_remito = remitos.id_remito WHERE altas.guardado = '1' AND altas.codigo = '" . $row["codigo"] . "'" . $sql_fechas;
    $result2 = $conn->query($sql2);
    $row2 = $result2->fetch_assoc();
    $altas = $row2["total_altas"];
    
    if($altas == NULL){
        $altas = '0';
    }
    
    //SUMA BAJAS
    $sql2 = "SELECT SUM(cantidad) AS total_bajas FROM bajas WHERE `codigo` = '" . $row["codigo"] . "'" . $sql_fechas_bajas;
    $result2 = $conn->query($sql2);
    $row2 = $result2->fetch_assoc();
    $bajas = $row2["total_bajas"];

    if($bajas == NULL){
        $bajas = '0';
    }
    
	echo '<tr >
			<td style="text-align:center;">' . $row["codigo"] . '</td>
			<td style="text-align:center;">' . $row["codigo_prov"] . '</td>
			<td style="text-align:center;">' . $row["descripcion"] . '</td>
			<td style="text-align:center;">' . $row["marca"] . '</td>
			<td style="text-align:center;">' . $row["familia"] . '</td>
			<td style="text-align:center;">' . $row["mensual"] . '</td>
			<td style="text-align:center;">' . $altas . '</td>
			<td style="text-align:center;">' . $bajas . '</td>
			<td style="text-align:center;">' . $row["stock"] . '</td>
		  </tr>';
}

echo "</tbody></table>"; 

$conn->close();
?> 