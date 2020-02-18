<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id_art = test_input($_POST["id_art"]);
  $codigo = test_input($_POST["codigo"]);
  $descripcion = test_input($_POST["descripcion"]);
  $marca = test_input($_POST["marca"]);
  $familia = test_input($_POST["familia"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
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

$sql = "SELECT * FROM altas WHERE `guardado` = '1' AND `restan` <> '0'" . $sql_id . $sql_codigo . $sql_descripcion . $sql_marca . $sql_familia . "ORDER BY id_alta DESC LIMIT 100";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

if ($result->num_rows == 0) {
    exit('<div class="text-center mt-3" style="color:#007E33;font-weight:bold;">No hay artículos con esos criterios.</div>');
}

echo '<div class="table-responsive" style="border-radius:8px 8px 0px 0px;">
        <table class="table table-sm table-borderless table-striped table-hover" style="border:1px solid #007E33">
            <thead>
              <tr class="primary-color-dark" style="color:white;">
                <th style="text-align:center;" scope="col">Id</th>
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