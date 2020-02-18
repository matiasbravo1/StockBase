<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id_remito = test_input($_POST["id_remito"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

//Arma Encabezado
$sql = "SELECT * FROM remitos WHERE `id_remito` = '" . $id_remito . "'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

if ($result->num_rows == 0) {
    exit("Este remito ya no existe.");
}

while($row = $result->fetch_assoc()) {
    
    echo '<table style="width:100%;color:#0d47a1;">
            <tr>
                <th style="text-align:left;width:25%;font-weight:bold;">Remito Nro. ' . $row['nro_remito'] . '</th>
                <th style="text-align:center;width:50%;font-weight:bold;">Proveedor: ' . $row['proveedor'] . '</th>
                <th style="text-align:right;width:25%;font-weight:bold;">Fecha: ' . $row['fecha'] . '</th>
            </tr>
        </table>';

}
$result->free();

//Arma Contenido
$sql = "SELECT * FROM altas WHERE `id_remito` = '" . $id_remito . "' AND `guardado` = '1' ORDER BY id_alta DESC";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

if ($result->num_rows == 0) {
    exit("Este remito no tiene artículos ingresados.");
}

echo '<div class="table-responsive table-hover mt-2 " style="background-color:white;">
        <table class="table table-sm table-borderless mb-0" style="border:0.5px solid #163969">
            <thead>
              <tr class="primary-color-dark" style="color:white;">
                <th style="text-align:center;" scope="col">Id</th>
                <th style="text-align:center;" scope="col">Código</th>
                <th style="text-align:center;" scope="col">Descripción</th>
                <th style="text-align:center;" scope="col">Marca</th>
                <th style="text-align:center;" scope="col">Familia</th>
                <th style="text-align:center;" scope="col">Lote</th>
                <th style="text-align:center;" scope="col">Fecha Vto.</th>
                <th style="text-align:center;" scope="col">Alta</th>
                <th class="danger-color-dark" style="text-align:center;" scope="col">Baja</th>
                <th class="success-color-dark" style="text-align:center;" scope="col">Stock</th>
                <th style="text-align:center;width:25px;" scope="col"></th>
              </tr>
            </thead>
            <tbody>';

$a = '';

while($row = $result->fetch_assoc()) {
    
    if ($row["entregados"] != 0){
        $a = "disabled";
    }
    
    echo '<tr>
		<td style="text-align:center;">' . $row["id_alta"] . '</td>
		<td style="text-align:center;">' . $row["codigo"] . '</td>
		<td style="text-align:center;">' . $row["descripcion"] . '</td>
		<td style="text-align:center;">' . $row["marca"] . '</td>
		<td style="text-align:center;">' . $row["familia"] . '</td>
		<td style="text-align:center;">' . $row["lote"] . '</td>
		<td style="text-align:center;">' . $row["fecha_vto"] . '</td>
		<td style="text-align:center;">' . $row["cantidad"] . '</td>
		<td style="text-align:center;">' . $row["entregados"] . '</td>
		<td style="text-align:center;">' . $row["restan"] . '</td>
		<td style="text-align:center;" onclick=' . '"imprimirEtiquetas(' . "'" . $row["id_alta"] . "'" . ')"' . '><img style="vertical-align:middle;" src="../Imagenes/impresora.png"  width="15" height="15"></td>
	  </tr>';
}
    
echo "</tbody></table></div>"; 
echo '<center>
            <button class="btn btn-primary mt-4 btn-md" type="button" onclick="editarRemito(' . "'" . $id_remito . "'" . ')">Editar</button>
            <button class="btn btn-danger mt-4 btn-md" type="button" onclick="eliminarRemito(' . "'" . $id_remito . "'" . ')"' . $a . '>Eliminar</button>
        </center>';
      
$conn->close();
?> 