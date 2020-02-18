<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = test_input($_POST["id_usuario"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$sql = "SELECT * FROM usuarios WHERE `id_usuario` = '$id_usuario'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$usuario = $row["nombre"] . " " . $row["apellido"];

$sql = "SELECT * FROM secciones_usuario WHERE `id_usuario` = '$id_usuario'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Error: Algo anduvo mal.");
}

echo '<center><h5 style="color:#9933CC">Usuario: ' . $usuario . '</h5></center>';



echo '<div id="tabla-secciones" class="table-responsive" style="border-radius:8px 8px 0px 0px;">
        <table style="border:1px solid #9933CC" class="table table-sm table-borderless table-striped table-hover">
            <thead>
              <tr style="color:white;background-color:#9933CC">
                <th style="text-align:center;" scope="col">Secciones Habilitadas</th>
                <th style="text-align:center;width:30px;" scope="col"></th>
              </tr>
            </thead>
            <tbody style="background-color:white;">';

            
while($row = $result->fetch_assoc()) {
    
	echo '<tr>
			<td style="text-align:center;">' . $row["seccion"] . '</td>
			<td style="text-align:center;" onclick="eliminarSeccion(' . "'" . $row["id_secc_user"] . "'" . ')"><img style="vertical-align:middle;" src="../Imagenes/icon_delete.png"  width="15" height="15"></td>
		  </tr>';
}

echo "</tbody></table></div>";

echo '<form>
            <div class="form-row mt-3 mb-4">
                <div class="col-8 col-sm-8 col-md-8 col-lg-8 col-xl-8 text-center">
                <select id="seccion" class="browser-default custom-select">
                        <option value=""></option>';
                    
$sql2 = "SELECT seccion FROM listas WHERE `seccion` NOT LIKE ''";
$result2 = $conn->query($sql2);
while($row2 = $result2->fetch_assoc()) {
	echo '<option value="' . $row2["seccion"] . '">' . $row2["seccion"] . '</option>';
}

echo '</select></div>
<div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4 text-center mb-auto mt-auto">
                    <button class="btn mx-0 mb-1" style="color:white;background-color:#9933CC;width:100%;padding:4px 0px" type="button" onclick="agregarSeccion(' . "'" . $id_usuario . "'" . ')"><span id="loader" class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>Agregar</button>
                </div>
                </div></form>';
                
$conn->close();
?>