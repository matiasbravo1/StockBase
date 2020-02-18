<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

$sql = "SELECT * FROM articulos WHERE `activo` = '1'" . $sql_codigo . $sql_descripcion . $sql_marca . $sql_familia . " ORDER BY id_articulo ASC";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

if ($result->num_rows == 0) {
    exit('<center><font style="font-weight:bold;color:#9933CC;">No hay artículos con esos criterios.</font></center>');
}

echo '<div class="table-responsive table-striped table-hover" style="border-radius:8px 8px 0px 0px;">
        <table class="table table-sm table-borderless" style="border:0.5px solid green">
            <thead>
              <tr class="success-color-dark" style="color:white;">
                <th style="text-align:center;" scope="col">Código</th>
                <th style="text-align:center;" scope="col">Descripción</th>
                <th style="text-align:center;" scope="col">Marca</th>
                <th style="text-align:center;" scope="col">Familia</th>
                <th style="text-align:center;" scope="col">Stock</th>
              </tr>
            </thead>
            <tbody>';

            
while($row = $result->fetch_assoc()) {
    
	echo '<tr onclick=' . '"completar(' . "'" . $row["codigo"] . "','" . $row["descripcion"] . "','" . $row["marca"] . "','" . $row["familia"]. "'" . ')">
			<td style="text-align:center;">' . $row["codigo"] . '</td>
			<td style="text-align:center;">' . $row["descripcion"] . '</td>
			<td style="text-align:center;">' . $row["marca"] . '</td>
			<td style="text-align:center;">' . $row["familia"] . '</td>
			<td style="text-align:center;">' . $row["stock"] . '</td>
		  </tr>';
}

echo "</tbody></table>"; 

$conn->close();
?> 