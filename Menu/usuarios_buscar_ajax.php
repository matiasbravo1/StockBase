<?php
include '../Headers/session.php';

$sql = "SELECT * FROM usuarios";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

if ($result->num_rows == 0) {
    exit('No hay usuarios registrados.');
}

echo '<div class="table-responsive" style="border-radius:8px 8px 0px 0px;">
        <table class="table table-sm table-borderless table-striped table-hover">
            <thead>
              <tr style="color:white;background-color:#9933CC">
                <th style="text-align:center;" scope="col">Apellido</th>
                <th style="text-align:center;" scope="col">Nombre</th>
                <th style="text-align:center;" scope="col">Usuario</th>
                <th style="text-align:center;" scope="col">Contraseña</th>
                <th style="text-align:center;" scope="col">Tipo</th>
                <th style="text-align:center;" scope="col">Secciones</th>
                <th style="text-align:center;" scope="col">Activo</th>
                <th style="text-align:center;width:30px;" scope="col"></th>
              </tr>
            </thead>
            <tbody style="background-color:white;">';

            
while($row = $result->fetch_assoc()) {
    if ($row["id_usuario"] == "19"){
        continue;
    }
    
    if ($row["activo"] == 0){
        $activo = "No";
    }else{
        $activo = "Sí";
    }
    
	echo '<tr>
			<td style="text-align:center;">' . $row["apellido"] . '</td>
			<td style="text-align:center;">' . $row["nombre"] . '</td>
			<td style="text-align:center;">' . $row["usuario"] . '</td>
			<td style="text-align:center;"><span class="mr-2" style="display:none" id="clave' . $row["id_usuario"] . '">' . $row["clave"] . '</span><button type="button" style="background-color:transparent;border:none" onclick="toggleClave(' . "'clave" . $row["id_usuario"] . "'" . ')"><img src="../Imagenes/ojo_violeta.png" height="22px"></button></td>
			<td style="text-align:center;">' . $row["tipo"] . '</td>
			<td style="text-align:center;"><button type="button" style="background-color:transparent;border:none" onclick="verSecciones(' . "'" . $row["id_usuario"] . "'" . ')"><img src="../Imagenes/ojo_violeta.png" height="22px"></button></td>
			<td style="text-align:center;">' . $activo . '</td>
			<td style="text-align:center;" onclick=' . '"editar(' . "'" . $row["id_usuario"] . "'" . ')"' . '><img style="vertical-align:middle;" src="../Imagenes/edit-icon.png"  width="15" height="15"></td>
		  </tr>';
}

echo "</tbody></table></div>"; 

$conn->close();
?> 