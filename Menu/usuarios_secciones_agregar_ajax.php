<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seccion = test_input($_POST["seccion"]);
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

if ($result->num_rows == 0) {
    exit('Error: El usuario no existe. Recuerde actualizar las búsquedas.');
}

$row = $result->fetch_assoc();
$usuario = $row["nombre"] . " " . $row["apellido"];

$sql = "SELECT * FROM secciones_usuario WHERE `id_usuario` = '$id_usuario' AND `seccion` = '$seccion'";
$result = $conn->query($sql);

if ($result->num_rows != 0) {
    exit('Error: Sección ya habilitada.');
}

$sql = "INSERT INTO secciones_usuario (id_usuario, seccion) VALUES ('$id_usuario','$seccion')";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Error: Algo anduvo mal.");
}

$sql = "SELECT * FROM secciones_usuario WHERE `id_usuario` = '$id_usuario'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Error: Algo anduvo mal.");
}

echo '<table style="border:1px solid #9933CC" class="table table-sm table-borderless table-striped table-hover">
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

echo "</tbody></table>";

//AUDITAR
    $date = new DateTime();
    $date->modify('-3 hours');
    $hoy = $date->format('Y-m-d');
    $ahora = $date->format('H:i:s');
    $user = $_SESSION['user_name'];
    
    $sql3 = "INSERT INTO auditorias (fecha, hora, usuario, accion, detalles) VALUES ('$hoy','$ahora','$user','Agregar Sección a Usuario','Usuario: " . $usuario . ' - Sección: ' . $seccion . "')";
    $result3 = $conn->query($sql3);

$conn->close();
?> 