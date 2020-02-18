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
  $descarga = test_input($_POST["descarga"]);
  $usuario = test_input($_POST["usuario"]);
  $estado = test_input($_POST["estado"]);
  
  $codigo = test_input($_POST["codigo"]);
  $descripcion = test_input($_POST["descripcion"]);
  $marca = test_input($_POST["marca"]);
  $familia = test_input($_POST["familia"]);
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

if (empty($hora_desde) or empty($hora_hasta)){
$sql_hora = NULL;
} else {
$sql_hora = " AND `hora` BETWEEN '" . $hora_desde . "' AND '" . $hora_hasta . "'";
}

if (empty($vale_desde) or empty($vale_hasta)){
$sql_vale = NULL;
} else {
$sql_vale = " AND articulos_vale.id_vale BETWEEN '" . $vale_desde . "' AND '" . $vale_hasta . "'";
}

if (empty($seccion)){
$sql_seccion = NULL;
} else {
$sql_seccion = " AND `seccion` = '" . $seccion . "'";
}

if (empty($descarga)){
    $sql_descarga = NULL;
} else {
    if ($descarga == "No"){
        $sql_descarga = " AND `entregados` = '0'";
    }elseif($descarga == "NoParcial"){
        $sql_descarga = " AND `entregados` < `pedidos`";
    }elseif($descarga == "Parcial"){
        $sql_descarga = " AND `entregados` < `pedidos` AND `entregados` > '0'";
    }elseif($descarga == "Total"){
        $sql_descarga = " AND `entregados` = `pedidos`";
    }
}


if (empty($estado)){
    $sql_estado = NULL;
} else {
    $sql_estado = " AND `estado` = '" . $estado . "'";
}

if ($estado == "NormalFinalizado"){
    $sql_estado = " AND `estado` <> 'Cancelado'";
}

if (empty($usuario)){
    $sql_usuario = NULL;
} else {
    $sql_usuario = " AND `usuario` = '" . $usuario . "'";
}

if (empty($codigo)){
    $sql_codigo = NULL;
} else {
    $sql_codigo = " AND articulos_vale.codigo = '$codigo'";
}

if (empty($descripcion)){
    $sql_descripcion = NULL;
} else {
    $sql_descripcion = " AND articulos_vale.descripcion LIKE '%$descripcion%'";
}

if (empty($marca)){
    $sql_marca = NULL;
} else {
    $sql_marca = " AND articulos_vale.marca = '$marca'";
}

if (empty($familia)){
    $sql_familia = NULL;
} else {
    $sql_familia = " AND articulos_vale.familia = '$familia'";
}

$sql = "SELECT * FROM articulos_vale INNER JOIN vales ON vales.id_vale = articulos_vale.id_vale WHERE articulos_vale.guardado = '1'" . $sql_fechas . $sql_hora . $sql_vale . $sql_seccion . $sql_descarga . $sql_estado . $sql_usuario . $sql_codigo . $sql_descripcion . $sql_marca . $sql_familia . "ORDER BY articulos_vale.id_vale DESC LIMIT 100";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

if ($result->num_rows == 0) {
    exit("<center>No hay artículos con estos criterios.</center>");
}

echo '<div class="table-responsive table-hover mt-2" style="background-color:white;">
        <table class="table table-sm table-borderless table-striped mb-0" style="border:0.5px solid #b35f00">
            <thead>
              <tr style="background-color:#e69100;color:white;">
                <th style="text-align:center;" scope="col">Vale</th>
                <th style="text-align:center;" scope="col">Sección</th>
                <th style="text-align:center;" scope="col">Solicita</th>
                <th style="text-align:center;" scope="col">Fecha</th>
                <th style="text-align:center;" scope="col">Hora</th>
                <th style="text-align:center;" scope="col">Código</th>
                <th style="text-align:center;" scope="col">Descripción</th>
                <th style="text-align:center;" scope="col">Marca</th>
                <th style="text-align:center;" scope="col">Familia</th>
                <th style="text-align:center;" scope="col">Pedidos</th>
                <th class="danger-color-dark" style="text-align:center;" scope="col">Entregados<img src="../Imagenes/mano.png" height="15px" class="ml-1"></th>
                <th class="success-color-dark" style="text-align:center;" scope="col">Stock<img src="../Imagenes/mano.png" height="15px" class="ml-1"></th>
              </tr>
            </thead>
            <tbody>';
            
$a = 0;

while($row = $result->fetch_assoc()) {
    
    $sql2 = "SELECT stock FROM articulos WHERE `codigo` = '" . $row["codigo"] . "'";
    $result2 = $conn->query($sql2);

    if ($result2->num_rows == 0) {
        $stock_result = "0";
    }else{
        $row2 = $result2->fetch_assoc();
        $stock_result = $row2["stock"];
    }
    
    if ($stock == "igualcero" && $stock_result > '0'){
        continue;
    }
    
    if ($stock == "mayorcero" && $stock_result == '0'){
        continue;
    }
    
    if ($stock_result == '0') {
        $soloStock = "";
    }else{
        $soloStock = 'onclick="soloStock(' . "'" . $row["codigo"] . "','" . $row["id_vale"] . "')";
    }
    
    if ($row["entregados"] == '0') {
        $entregados = "";
    }else{
        $entregados = 'onclick="entregados(' . "'" . $row["codigo"] . "','" . $row["id_vale"] . "')";
    }
    
    if ($result2->num_rows == 0) {
        $stock_result = "";
    }
    
    
    if($row["pedidos"] == $row["entregados"]){
        $clase = 'normal-vale';
    }elseif($row["pedidos"] > $row["entregados"]){
        $clase = 'minimo-vale';
    }
    if($row["entregados"] == '0'){
        $clase = 'critico-vale';
    }
    

    echo '<tr>
        <td style="text-align:center;">' . $row["id_vale"] . '</td>
        <td style="text-align:center;">' . $row["seccion"] . '</td>
        <td style="text-align:center;">' . $row["usuario"] . '</td>
        <td style="text-align:center;">' . $row["fecha"] . '</td>
        <td style="text-align:center;">' . substr($row["hora"],0,5) . '</td>
		<td style="text-align:center;">' . $row["codigo"] . '</td>
		<td class="' . $clase . '" style="text-align:center;">' . $row["descripcion"] . '</td>
		<td style="text-align:center;">' . $row["marca"] . '</td>
		<td style="text-align:center;">' . $row["familia"] . '</td>
		<td style="text-align:center;">' . $row["pedidos"] . '</td>
		<td class="' . $clase . '" style="text-align:center;" ' . $entregados . '">' . $row["entregados"] . '</td>
		<td style="text-align:center;" ' . $soloStock . '">' . $stock_result . '</td>
	  </tr>';
	  
	  $a++;
}
    
echo "</tbody></table></div>";

if($a == 100){
    echo '<div class="text-center mt-3">Atención: Se muestran solo los primeros 100 resultados. Podrían haber más resultados. Utilice los filtros de búsqueda para visualizarlos. Consulte al programador si desea ampliar la cantidad de resultados mostrados.</div>';
}

$conn->close();
?> 