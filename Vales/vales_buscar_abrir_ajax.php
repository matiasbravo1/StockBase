<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id_vale = test_input($_POST["id_vale"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

//Arma Encabezado
$sql = "SELECT * FROM vales WHERE `id_vale` = '" . $id_vale . "'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

if ($result->num_rows == 0) {
    exit("Este vale ya no existe.");
}

while($row = $result->fetch_assoc()) {
    
    echo '<table style="width:100%;color:#804400;">
            <tr>
                <th style="text-align:left;width:25%;font-weight:bold;">' . '<br>Descarga: ' . $row['descarga'] . '<br>Estado: <span id="span-estado">' . $row["estado"] . '</span><img id="btn-editarEstado" src="../Imagenes/edit-icon.png" height="15px"></th>
                <th style="text-align:center;width:50%;font-weight:bold;">Vale: ' . $row['id_vale'] . '<br>Sección: ' . $row['seccion'] . '<br>Solicita: ' . $row["usuario"] . '</th>
                <th style="text-align:right;width:25%;font-weight:bold;"><br>Fecha: ' . $row['fecha'] . "<br>Hora: " . substr($row['hora'],0,5) . '</th>
            </tr>
        </table>';
    
    $observaciones = $row["observaciones"];
    
    $normal = $finalizado = $cancelado = '';
    
    if ($row["estado"] == 'Normal'){
        $normal = 'selected';
    }elseif($row["estado"] == 'Finalizado'){
        $finalizado = 'selected';
    }elseif($row["estado"] == 'Cancelado'){
        $cancelado = 'selected';
    }
    
    echo '<div class="popup" style="">
            <small style="color:#804400;">Estado</small>
            <select id="estado-popup" class="browser-default custom-select">
                <option value="Normal" ' . $normal . '>Normal</option>
                <option value="Finalizado" ' . $finalizado . '>Finalizado</option>
                <option value="Cancelado" ' . $cancelado . '>Cancelado</option>
            </select>
            <button class="btn mx-0 mb-0" style="color:white;background-color:#e69100;width:100%;padding:4px 0px" type="button" onclick="editarEstado(' . "'" . $row["id_vale"] . "'" . ')">Guardar</button>
          </div>';

}

$result->free();

//Arma Contenido
$sql = "SELECT * FROM articulos_vale WHERE `id_vale` = '" . $id_vale . "' AND `guardado` = '1' ORDER BY id_art_vale DESC";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

if ($result->num_rows == 0) {
    exit("Este vale no tiene artículos ingresados.");
}

echo '<div class="table-responsive table-hover mt-2" style="background-color:white;">
        <table class="table table-sm table-borderless mb-0" style="border:0.5px solid #b35f00">
            <thead>
              <tr style="background-color:#e69100;color:white;">
                <th style="text-align:center;" scope="col">Código</th>
                <th style="text-align:center;" scope="col">Descripción</th>
                <th style="text-align:center;" scope="col">Marca</th>
                <th style="text-align:center;" scope="col">Familia</th>
                <th style="text-align:center;" scope="col">Pedidos</th>
                <th class="danger-color-dark" style="text-align:center;" scope="col">Entregados</th>
                <th class="success-color-dark" style="text-align:center;" scope="col">Stock<img src="../Imagenes/mano.png" height="15px" class="ml-1"></th>
              </tr>
            </thead>
            <tbody>';

$a = '';

while($row = $result->fetch_assoc()) {
    
    if($row["pedidos"] == $row["entregados"]){
        $clase = 'normal-vale';
    }elseif($row["pedidos"] > $row["entregados"]){
        $clase = 'minimo-vale';
    }
    if($row["entregados"] == '0'){
        $clase = 'critico-vale';
    }
    
    $sql2 = "SELECT stock FROM articulos WHERE `codigo` = '" . $row["codigo"] . "'";
    $result2 = $conn->query($sql2);
    
    if ($result2->num_rows == '0') {
        $stock = "";
        $soloStock = "";
    }else{
        $row2 = $result2->fetch_assoc();
        $stock = $row2["stock"];
        $soloStock = 'onclick="soloStock(' . "'" . $row["codigo"] . "','" . $id_vale . "')";
    }

    echo '<tr>
		<td style="text-align:center;">' . $row["codigo"] . '</td>
		<td class="' . $clase . '" style="text-align:center;">' . $row["descripcion"] . '</td>
		<td style="text-align:center;">' . $row["marca"] . '</td>
		<td style="text-align:center;">' . $row["familia"] . '</td>
		<td style="text-align:center;">' . $row["pedidos"] . '</td>
		<td class="' . $clase . '" style="text-align:center;">' . $row["entregados"] . '</td>
		<td style="text-align:center;" ' . $soloStock . '">' . $stock . '</td>
	  </tr>';
}
    
echo "</tbody></table></div>";

if ($observaciones != ''){
    echo '<p class="mt-3" style="color:#804400;font-weight:bold;font-size:15px;">Observaciones: ' . $observaciones . '</p>'; 
}

echo '<div class="mt-3" id="mostrar-stock"></div>';


//BAJAS
$sql = "SELECT * FROM bajas WHERE `id_vale` = '" . $id_vale . "' ORDER BY fecha DESC, hora DESC";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

echo '<div class="mt-3" id="mostrar-entregados">';

if ($result->num_rows == 0) {
    echo '<center><span style="color:#CC0000;font-weight:bold;">No hay artículos descargados.</span></center></div>';
} else {


    echo '<center><span style="color:#CC0000;font-weight:bold;">Artículos Descargados</span></center>
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

    while($row = $result->fetch_assoc()) {
        
        $eliminar = "onclick=" . '"eliminarBaja(' . "'" . $row["id_baja"] . "','" . $id_vale . "'" . ')"' . '><img style="vertical-align:middle;" src="../Imagenes/icon_delete.png"  width="15" height="15"';
        
        echo '<tr>
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

    echo '</tbody></table></div>';

}

$conn->close();
?> 