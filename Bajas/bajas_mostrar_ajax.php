<?php
include '../Headers/session.php';

//BAJAS
$sql = "SELECT * FROM bajas ORDER BY fecha DESC, hora DESC LIMIT 10";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

if ($result->num_rows == 0) {
    echo '<center><span style="color:#CC0000;font-weight:bold;">No hay artículos descargados.</span></center></div>';
} else {


    echo '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><center><span style="color:#CC0000;font-weight:bold;">Últimos 10 Artículos Descargados</span></center>
        <div class="table-responsive table-hover mt-2" style="background-color:white;">
        <table class="table table-sm table-borderless table-striped mb-0" style="border:0.5px solid #b35f00">
            <thead>
              <tr class="danger-color-dark" style="color:white;">
                <th style="text-align:center;" scope="col">Fecha</th>
                <th style="text-align:center;" scope="col">Hora</th>
                <th style="text-align:center;" scope="col">Concepto</th>
                <th style="text-align:center;" scope="col">Vale</th>
                <th style="text-align:center;" scope="col">Id</th>
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
        
        $eliminar = "onclick=" . '"eliminarBaja(' . "'" . $row["id_baja"] . "'" . ')"' . '><img style="vertical-align:middle;" src="../Imagenes/icon_delete.png"  width="15" height="15"';
        
        echo '<tr>
            <td style="text-align:center;">' . $row["fecha"] . '</td>
            <td style="text-align:center;">' . substr($row["hora"],0,5) . '</td>
            <td style="text-align:center;">' . $row["concepto"] . '</td>
            <td style="text-align:center;">' . $row["id_vale"] . '</td>
            <td style="text-align:center;">' . $row["id_alta"] . '</td>
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