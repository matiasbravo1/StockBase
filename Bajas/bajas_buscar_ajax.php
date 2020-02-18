<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $fecha_desde = test_input($_POST["fecha_desde"]);
  $hora_desde = test_input($_POST["hora_desde"]);
  $fecha_hasta = test_input($_POST["fecha_hasta"]);
  $hora_hasta = test_input($_POST["hora_hasta"]);
  $vale_desde = test_input($_POST["vale_desde"]);
  $vale_hasta = test_input($_POST["vale_hasta"]);
  $seccion = test_input($_POST["seccion"]);
  $concepto = test_input($_POST["concepto"]);
  $usuario = test_input($_POST["usuario"]);
  $codigo = test_input($_POST["codigo"]);
  $descripcion = test_input($_POST["descripcion"]);
  $marca = test_input($_POST["marca"]);
  $familia = test_input($_POST["familia"]);
  $id_art = test_input($_POST["id_art"]); 
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
$sql_fechas = " AND bajas.fecha BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'";
}

if (empty($hora_desde) or empty($hora_hasta)){
$sql_hora = NULL;
} else {
$sql_hora = " AND bajas.hora BETWEEN '" . $hora_desde . "' AND '" . $hora_hasta . "'";
}

if (empty($vale_desde) or empty($vale_hasta)){
$sql_vale = NULL;
} else {
$sql_vale = " AND bajas.id_vale BETWEEN '" . $vale_desde . "' AND '" . $vale_hasta . "'";
}

if (empty($concepto)){
    $sql_concepto = NULL;
} else {
    $sql_concepto = " AND `concepto` = '$concepto'";
}

if (empty($seccion)){
    $sql_seccion = NULL;
} else {
    $sql_seccion = " AND vales.seccion = '$seccion'";
}
if (empty($usuario)){
    $sql_usuario = NULL;
} else {
    $sql_usuario = " AND vales.usuario = '$usuario'";
}
if (empty($codigo)){
    $sql_codigo = NULL;
} else {
    $sql_codigo = " AND `codigo` = '$codigo'";
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

if (empty($id_art)){
    $sql_id_art = NULL;
} else {
    $sql_id_art = " AND `id_alta` = '$id_art'";
}


$sql = "SELECT bajas.*, vales.seccion, vales.usuario FROM bajas LEFT JOIN vales ON bajas.id_vale = vales.id_vale WHERE `id_baja` <> ''" . $sql_fechas  . $sql_hora . $sql_vale . $sql_seccion . $sql_usuario . $sql_concepto . $sql_id_art . $sql_codigo . $sql_descripcion . $sql_marca . $sql_familia . "ORDER BY bajas.fecha DESC, bajas.hora DESC LIMIT 100";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

if ($result->num_rows == 0) {
    exit("No hay artículos con estos criterios.");
}

echo '<div class="table-responsive table-hover mt-2" style="background-color:white;">
        <table class="table table-sm table-borderless table-striped mb-0" style="border:0.5px solid #b35f00">
            <thead>
              <tr  class="danger-color-dark" style="color:white;">
                <th style="text-align:center;" scope="col">Fecha</th>
                <th style="text-align:center;" scope="col">Hora</th>
                <th style="text-align:center;" scope="col">Concepto</th>
                <th style="text-align:center;" scope="col">Vale</th>
                <th style="text-align:center;" scope="col">Sección</th>
                <th style="text-align:center;" scope="col">Solicita</th>
                <th style="text-align:center;" scope="col">Id Artículo</th>
                <th style="text-align:center;" scope="col">Código</th>
                <th style="text-align:center;" scope="col">Descripción</th>
                <th style="text-align:center;" scope="col">Marca</th>
                <th style="text-align:center;" scope="col">Familia</th>
                <th style="text-align:center;" scope="col">Cantidad</th>
                <th style="text-align:center;" scope="col"></th>
              </tr>
            </thead>
            <tbody>';

$a = 0;
while($row = $result->fetch_assoc()) {

    echo '<tr>
        <td style="text-align:center;">' . $row["fecha"] . '</td>
        <td style="text-align:center;">' . substr($row["hora"],0,5) . '</td>
        <td style="text-align:center;">' . $row["concepto"] . '</td>
        <td style="text-align:center;">' . $row["id_vale"] . '</td>
		<td style="text-align:center;">' . $row["seccion"] . '</td>
		<td style="text-align:center;">' . $row["usuario"] . '</td>
		<td style="text-align:center;">' . $row["id_alta"] . '</td>
		<td style="text-align:center;">' . $row["codigo"] . '</td>
		<td style="text-align:center;">' . $row["descripcion"] . '</td>
		<td style="text-align:center;">' . $row["marca"] . '</td>
		<td style="text-align:center;">' . $row["familia"] . '</td>
		<td style="text-align:center;">' . $row["cantidad"] . '</td>
        <td style="text-align:center;" onclick="eliminarBaja(' . "'" . $row["id_baja"] . "'" . ')"' . '><img style="vertical-align:middle;" src="../Imagenes/icon_delete.png"  width="15" height="15"></td>
	  </tr>';
	  
        $a++;
}
    
echo "</tbody></table></div>";

if($a == 100){
    echo '<div class="text-center mt-3">Atención: Se muestran solo los primeros 100 resultados. Podrían haber más resultados. Utilice los filtros de búsqueda para visualizarlos. Consulte al programador si desea ampliar la cantidad de resultados mostrados.</div>';
}

$conn->close();
?> 