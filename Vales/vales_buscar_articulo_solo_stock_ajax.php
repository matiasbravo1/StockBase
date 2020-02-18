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

$sql2 = "SELECT falta_entregar FROM articulos_vale WHERE `id_vale` = '$id_vale' AND `codigo` = '" . $codigo . "'";
$result2 = $conn->query($sql2);
$row2 = $result2->fetch_assoc();
$cantidad = $row2["falta_entregar"];

$sql2 = "SELECT * FROM altas WHERE `codigo` = '" . $codigo . "' AND `restan` <> '0' ORDER BY `fecha_vto` ASC";
$result2 = $conn->query($sql2);

if ($result2->num_rows != 0) {
    while($row2 = $result2->fetch_assoc()) {
        
        $out .= '<tr>
            		<td style="text-align:center;">' . $row2["id_alta"] . '</td>
            		<td style="text-align:center;">' . $row2["codigo"] . '</td>
            		<td style="text-align:center;">' . $row2["descripcion"] . '</td>
            		<td style="text-align:center;">' . $row2["marca"] . '</td>
            		<td style="text-align:center;">' . $row2["familia"] . '</td>
            		<td style="text-align:center;">' . $row2["lote"] . '</td>
            		<td style="text-align:center;">' . $row2["fecha_vto"] . '</td>
            		<td style="text-align:center;">' . $row2["restan"] . '</td>
            		<td style="text-align:center;" onclick=' . '"agregarBaja(' . "'" . $id_vale . "','" . $row2["id_alta"] . "','" . $cantidad . "'" . ')"' . '><img style="vertical-align:middle;" src="../Imagenes/flecha_roja.png"  width="15" height="15"></td>
            	  </tr>';
    }
    
}

$sql = "SELECT * FROM articulos WHERE `codigo` = '$codigo'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$sql3 = "SELECT falta_entregar FROM articulos_vale WHERE `id_vale` = '$id_vale' AND `codigo` = '$codigo'";
$result3 = $conn->query($sql3);
$row3 = $result3->fetch_assoc();

echo '<center><span style="color:#FF8800;font-weight:bold;">Vale: ' . $id_vale . '&nbsp&nbsp>>&nbsp&nbspArtículo: ' . $row["codigo"] . " - " . $row["descripcion"] . " - " . $row["marca"] . " - " . $row["familia"] . '&nbsp&nbsp>>&nbsp&nbspFalta entregar: ' . $row3["falta_entregar"] . '</span></center>';
echo '<center><span style="color:#007E33;font-weight:bold;">Artículos en Stock</span></center>';
echo '<div class="table-responsive table-hover mt-2" style="background-color:white;">';
echo '<table class="table table-sm table-borderless mb-0" style="border:0.5px solid #163969">';
echo '<thead>
              <tr class="success-color-dark" style="color:white;">
                <th style="text-align:center;" scope="col">Id</th>
                <th style="text-align:center;" scope="col">Código</th>
                <th style="text-align:center;" scope="col">Descripción</th>
                <th style="text-align:center;" scope="col">Marca</th>
                <th style="text-align:center;" scope="col">Familia</th>
                <th style="text-align:center;" scope="col">Lote</th>
                <th style="text-align:center;" scope="col">Fecha Vto.</th>
                <th style="text-align:center;" scope="col">Stock</th>
                <th style="text-align:center;width:25px;" scope="col"></th>
              </tr>
            </thead>
            <tbody>';

echo $out;
    
echo "</tbody></table></div>"; 

      
$conn->close();
?> 