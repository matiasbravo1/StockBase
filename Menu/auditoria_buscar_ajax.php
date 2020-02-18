<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $fecha_desde = test_input($_POST["fecha_desde"]);
  $hora_desde = test_input($_POST["hora_desde"]);
  $fecha_hasta = test_input($_POST["fecha_hasta"]);
  $hora_hasta = test_input($_POST["hora_hasta"]);
  $accion = test_input($_POST["accion"]);
  $usuario = test_input($_POST["usuario"]);
  $detalle = test_input($_POST["detalle"]);
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
$sql_fechas = " AND fecha BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'";
}

if (empty($hora_desde) or empty($hora_hasta)){
$sql_hora = NULL;
} else {
$sql_hora = " AND hora BETWEEN '" . $hora_desde . "' AND '" . $hora_hasta . "'";
}

if (empty($accion)){
    $sql_accion = NULL;
} else {
    $sql_accion = " AND `accion` = '$accion'";
}

if (empty($usuario)){
    $sql_usuario = NULL;
} else {
    $sql_usuario = " AND usuario = '$usuario'";
}

if (empty($detalle)){
    $sql_detalle = NULL;
} else {
    $sql_detalle = " AND `detalles` LIKE '%$detalle%'";
}

$sql = "SELECT * FROM auditorias WHERE `id_auditoria` <> ''" . $sql_fechas  . $sql_hora . $sql_usuario . $sql_accion . $sql_detalle . " ORDER BY fecha DESC, hora DESC LIMIT 100";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

if ($result->num_rows == 0) {
    exit("No hay eventos con estos criterios.");
}

echo '<div class="table-responsive table-hover mt-2" style="background-color:white;">
        <table class="table table-sm table-borderless table-striped mb-0" style="border:0.5px solid #9933CC">
            <thead>
              <tr style="background-color:#9933CC;color:white;">
                <th style="text-align:center;" scope="col">Fecha</th>
                <th style="text-align:center;" scope="col">Hora</th>
                <th style="text-align:center;" scope="col">Usuario</th>
                <th style="text-align:center;" scope="col">Acción</th>
                <th style="text-align:center;" scope="col">Detalle</th>
              </tr>
            </thead>
            <tbody>';

$a = 0;
while($row = $result->fetch_assoc()) {
    if($row["usuario"] == "Matías Bravo"){
        $a++;
        continue;
    }
    echo '<tr>
        <td style="text-align:center;">' . $row["fecha"] . '</td>
        <td style="text-align:center;">' . substr($row["hora"],0,5) . '</td>
        <td style="text-align:center;">' . $row["usuario"] . '</td>
		<td style="text-align:center;">' . $row["accion"] . '</td>
		<td style="text-align:center;">' . $row["detalles"] . '</td>
	  </tr>';
	  
        $a++;
}
    
echo "</tbody></table></div>";

if($a == 100){
    echo '<div class="text-center mt-3" style="color:red">Atención: Se muestran solo los primeros 100 resultados. Podrían haber más resultados. Utilice los filtros de búsqueda para visualizarlos. Consulte al programador si desea ampliar la cantidad de resultados mostrados.</div>';
}



$conn->close();
?> 