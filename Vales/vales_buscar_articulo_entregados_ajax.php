<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $codigo = test_input($_POST["codigo"]);
  $id_vale = test_input($_POST["id_vale"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

//BAJAS
$sql = "SELECT * FROM bajas WHERE `id_vale` = '$id_vale' AND `codigo` = '$codigo' ORDER BY fecha DESC, hora DESC";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

while($row = $result->fetch_assoc()) {
        $articulo = $row["codigo"] . " - " . $row["descripcion"] . " - " . $row["marca"] . " - " . $row["familia"];
        
        $eliminar = "onclick=" . '"eliminarBaja(' . "'" . $row["id_baja"] . "'" . ')"' . '><img style="vertical-align:middle;" src="../Imagenes/icon_delete.png"  width="15" height="15"';
        
        $out .= '<tr>
            <td style="text-align:center;">' . $row["id_alta"] . '</td>
            <td style="text-align:center;">' . $row["fecha"] . '</td>
            <td style="text-align:center;">' . substr($row["hora"],0,5) . '</td>
    		<td style="text-align:center;">' . $row["codigo"] . '</td>
    		<td style="text-align:center;">' . $row["descripcion"] . '</td>
    		<td style="text-align:center;">' . $row["marca"] . '</td>
    		<td style="text-align:center;">' . $row["familia"] . '</td>
    		<td style="text-align:center;">' . $row["fecha_vto"] . '</td>
    		<td style="text-align:center;">' . $row["lote"] . '</td>
    		<td style="text-align:center;">' . $row["cantidad"] . '</td>
    		<td style="text-align:center;" ' . $eliminar . '></td>
    	  </tr>';
    }

echo '<center><span style="color:#FF8800;font-weight:bold;">Vale: ' . $id_vale . '&nbsp&nbsp>>&nbsp&nbspArtículo: '. $articulo . '</span></center>
        <center><span style="color:#CC0000;font-weight:bold;">Artículos Descargados</span></center>
        <div class="table-responsive table-hover mt-2" style="background-color:white;">
        <table class="table table-sm table-borderless mb-0" style="border:0.5px solid #b35f00">
            <thead>
              <tr class="danger-color-dark" style="color:white;">
                <th style="text-align:center;" scope="col">Id</th>
                <th style="text-align:center;" scope="col">Fecha</th>
                <th style="text-align:center;" scope="col">Hora</th>
                <th style="text-align:center;" scope="col">Código</th>
                <th style="text-align:center;" scope="col">Descripción</th>
                <th style="text-align:center;" scope="col">Marca</th>
                <th style="text-align:center;" scope="col">Familia</th>
                <th style="text-align:center;" scope="col">Fecha Vto.</th>
                <th style="text-align:center;" scope="col">Lote</th>
                <th style="text-align:center;" scope="col">Cantidad</th>
                <th style="text-align:center;" scope="col"></th>
              </tr>
            </thead>
            <tbody>';
echo $out;
echo '</tbody></table></div>';


$conn->close();
?> 