<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $codigo = test_input($_POST["codigo"]);
  $codigo_prov = test_input($_POST["codigo_prov"]);
  $descripcion = test_input($_POST["descripcion"]);
  $marca = test_input($_POST["marca"]);
  $familia = test_input($_POST["familia"]);
  $stock = test_input($_POST["stock"]);
  $mensual = test_input($_POST["mensual"]);
  $activo = test_input($_POST["activo"]);
  $orden = test_input($_POST["orden"]);
  $direccion = test_input($_POST["direccion"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
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

if (empty($stock)){
    $sql_stock = NULL;
} else {
    if ($stock == "Menor o igual al crítico"){
        $sql_stock = " AND `stock` <= `critico`";        
    } else if ($stock == "Menor o igual al mínimo"){
        $sql_stock = " AND `stock` <= `minimo`";        
    } else if ($stock == "Mayor al mínimo"){
        $sql_stock = " AND `stock` > `minimo`";        
    }
}

if (empty($mensual)){
    $sql_mensual = NULL;
} else {
    $sql_mensual = " AND `mensual` LIKE '%$mensual%'";
}

if (empty($activo)){
    $sql_activo = NULL;
} else {
    ($activo == 'Sí') ? $activo = '1' : $activo = '0';
    $sql_activo = " AND `activo` = '$activo'";
}

$sql = "SELECT * FROM articulos WHERE `id_articulo` <> ''" . $sql_codigo . $sql_descripcion . $sql_marca . $sql_familia . $sql_stock . $sql_mensual . $sql_codigo_prov . $sql_activo . " ORDER BY " . $orden . " " . $direccion;
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

if ($result->num_rows == 0) {
    exit('No hay artículos con esos criterios.');
}

echo '<div class="table-responsive" style="border-radius:8px 8px 0px 0px;">
        <table class="table table-sm table-borderless table-striped table-hover" style="border:1px solid #007E33">
            <thead>
              <tr class="success-color-dark" style="color:white;">
                <th style="text-align:center;" scope="col">Código</th>
                <th style="text-align:center;" scope="col">Código Prov.</th>
                <th style="text-align:center;width:400px;" scope="col">Descripcion<img src="../Imagenes/mano.png" height="15px" class="ml-1"></th>
                <th style="text-align:center;" scope="col">Marca</th>
                <th style="text-align:center;" scope="col">Familia</th>
                <th style="text-align:center;" scope="col">Stock<img src="../Imagenes/mano.png" height="15px" class="ml-1"></th>
                <th style="text-align:center;" scope="col">Mensual</th>
                <th style="text-align:center;" scope="col">Mínimo</th>
                <th style="text-align:center;" scope="col">Crítico</th>
                <th style="text-align:center;" scope="col">Visible</th>
                <th style="text-align:center;width:30px;" scope="col"></th>
              </tr>
            </thead>
            <tbody>';

            
while($row = $result->fetch_assoc()) {
    if ($row["stock"] <= $row["minimo"]){
        $clase = "minimo-vale";
    } else {
        $clase = "normal-vale";
    }
    
    if ($row["stock"] <= $row["critico"]){
        $clase = "critico-vale";
    }
    
    ($row["activo"] == '1') ? $activo_res = 'Sí' : $activo_res = 'No';
    
	echo '<tr >
			<td style="text-align:center;">' . $row["codigo"] . '</td>
			<td style="text-align:center;">' . $row["codigo_prov"] . '</td>
			<td class="' . $clase . '"' . 'style="text-align:center;" onclick=' . '"ver_articulos(' . "'" . $row["codigo"] . "'" . ')"' . '>' . $row["descripcion"] . '</td>
			<td style="text-align:center;">' . $row["marca"] . '</td>
			<td style="text-align:center;">' . $row["familia"] . '</td>
			<td class="' . $clase . '"' . 'style="text-align:center;" onclick=' . '"ver_articulos(' . "'" . $row["codigo"] . "'" . ')"' . '>' . $row["stock"] . '</td>
			<td style="text-align:center;">' . $row["mensual"] . '</td>
			<td style="text-align:center;">' . $row["minimo"] . '</td>
			<td style="text-align:center;">' . $row["critico"] . '</td>
			<td style="text-align:center;">' . $activo_res . '</td>
			<td style="text-align:center;" onclick=' . '"editar(' . "'" . $row["id_articulo"] . "'" . ')"' . '><img style="vertical-align:middle;" src="../Imagenes/edit-icon.png"  width="15" height="15"></td>
		  </tr>';
}

echo "</tbody></table>"; 

$conn->close();
?> 