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
$sql_vale = " AND `id_vale` BETWEEN '" . $vale_desde . "' AND '" . $vale_hasta . "'";
}

if (empty($seccion)){
$sql_seccion = NULL;
} else {
$sql_seccion = " AND `seccion` = '" . $seccion . "'";
}

if (empty($descarga)){
    $sql_descarga = NULL;
} else {
    $sql_descarga = " AND `descarga` = '" . $descarga . "'";
}

if ($descarga == "NoParcial"){
    $sql_descarga = " AND `descarga` <> 'Total'";
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

$sql = "SELECT * FROM vales WHERE `guardado` = '1'" . $sql_fechas . $sql_hora . $sql_vale . $sql_seccion . $sql_descarga . $sql_estado . $sql_usuario . " ORDER BY fecha DESC, hora DESC";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

if ($result->num_rows == 0) {
    exit('<br><center><font style="font-weight:bold;color:#804400;">No hay vales con esos criterios.</font></center>');
}

echo '<div class="table-responsive table-hover" style="background-color:white;">
        <table class="table table-sm table-borderless mb-0">
            <thead>
              <tr class="" style="background-color:#e69100;color:white;">
              <th style="text-align:center;" scope="col">Vale</th>
                <th style="text-align:center;" scope="col">Sección</th>
                <th style="text-align:center;" scope="col">Fecha</th>
                <th style="text-align:center;" scope="col">Hora</th>
                <th style="text-align:center;" scope="col"><button type="button" style="border:none;background-color:transparent;"  onclick="imprimirModal()"><img style="vertical-align:middle;" src="../Imagenes/impresora_vale.png"  width="15" height="15"></button></th>
              </tr>
            </thead>
            <tbody>';

            
while($row = $result->fetch_assoc()) {
    if($row["descarga"] == 'No'){
        $clase = 'critico-vale';
    }elseif($row["descarga"] == 'Parcial'){
        $clase = 'minimo-vale';
    }elseif($row["descarga"] == 'Total'){
        $clase = 'normal-vale';
    }
    
    if($row["estado"] == 'Cancelado'){
        $clase = 'cancelado';
    }
    if($row["estado"] == 'Finalizado'){
        $clase = 'finalizado';
    }
    
    echo '<tr id="renglon' . $row["id_vale"] . '">
			<td class="' . $clase . '"style="text-align:center;"' . ' onclick=' . '"abrirVale(' . "'" . $row["id_vale"] . "'" . ')">' . $row["id_vale"] . '</td>
			<td class="' . $clase . '"style="text-align:center;"' . ' onclick=' . '"abrirVale(' . "'" . $row["id_vale"] . "'" . ')">' . $row["seccion"] . '</td>
			<td class="' . $clase . '"style="text-align:center;"' . ' onclick=' . '"abrirVale(' . "'" . $row["id_vale"] . "'" . ')">' . $row["fecha"] . '</td>
			<td class="' . $clase . '"style="text-align:center;"' . ' onclick=' . '"abrirVale(' . "'" . $row["id_vale"] . "'" . ')">' . substr($row["hora"],0,5) . '</td>
			<td class="' . $clase . '"style="text-align:center;"><input type="checkbox" name="imp_vale" value="' . $row["id_vale"] . '" onclick="add_remove(' . "'" . $row["id_vale"] . "'" . ')"></td>
		  </tr>';
}
    
echo "</tbody></table></div>"; 



$conn->close();
?> 