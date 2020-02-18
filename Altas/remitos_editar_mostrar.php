<?php

$sql = "SELECT * FROM altas WHERE `id_remito` = '$id_remito' ORDER BY id_alta DESC";
$result = $conn->query($sql);

$out = '<div class="table-responsive table-striped table-hover mt-3" style="border-radius:8px 8px 0px 0px;">
        <table class="table table-sm table-borderless" style="border:0.5px solid #163969">
            <thead>
              <tr class="" style="background-color:#0d47a1;color:white;">
                <th style="text-align:center;" scope="col">Id</th>
                <th style="text-align:center;" scope="col">Código</th>
                <th style="text-align:center;" scope="col">Descripción</th>
                <th style="text-align:center;" scope="col">Marca</th>
                <th style="text-align:center;" scope="col">Familia</th>
                <th style="text-align:center;" scope="col">Lote</th>
                <th style="text-align:center;" scope="col">Fecha Vto.</th>
                <th style="text-align:center;" scope="col">Cantidad</th>
                <th style="text-align:center;" scope="col">Etiquetas</th>
                <th style="text-align:center;width:40px" scope="col"></th>
              </tr>
            </thead>
            <tbody>';

            
while($row = $result->fetch_assoc()) {
    if($row["cantidad"] == $row["restan"]){

        $eliminar = "onclick=" . '"eliminarArticulo(' . "'" . $row["id_alta"] . "'" . ')"' . '><img style="vertical-align:middle;" src="../Imagenes/icon_delete.png"  width="15" height="15"';

    } else {
        $eliminar = "";
    }
    
	$out = $out . '<tr>
			<td style="text-align:center;">' . $row["id_alta"] . '</td>
			<td style="text-align:center;">' . $row["codigo"] . '</td>
			<td style="text-align:center;">' . $row["descripcion"] . '</td>
			<td style="text-align:center;">' . $row["marca"] . '</td>
			<td style="text-align:center;">' . $row["familia"] . '</td>
			<td style="text-align:center;">' . $row["lote"] . '</td>
			<td style="text-align:center;">' . $row["fecha_vto"] . '</td>
			<td style="text-align:center;">' . $row["cantidad"] . '</td>
			<td style="text-align:center;">' . $row["etiquetas"] . '</td>
			<td style="text-align:center;" ' . $eliminar . '></td>
		  </tr>';
}
    
$out = $out . "</tbody></table></div>"; 

echo $out;

?> 