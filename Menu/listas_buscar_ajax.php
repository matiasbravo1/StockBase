<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lista = test_input($_POST["lista"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$sql = "SELECT id, " . $lista . " AS lista FROM listas WHERE `" . $lista . "` NOT LIKE ''";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Error: Algo anduvo mal.");
}


$class = '';

if($lista == "seccion"){
    $titulo = "Secciones";
    $class = 'd-none';
}
if($lista == "marca"){
    $titulo = "Marcas";
}
if($lista == "proveedor"){
    $titulo = "Proveedores";
}
if($lista == "familia"){
    $titulo = "Familias";
    $class = 'd-none';
}

echo '<div class="table-responsive" style="border-radius:8px 8px 0px 0px;">
        <table class="table table-sm table-borderless table-striped table-hover">
            <thead>
              <tr style="color:white;background-color:#9933CC">
                <th style="text-align:center;" scope="col">' . $titulo . '</th>
                <th style="text-align:center;width:30px;" scope="col"></th>
              </tr>
            </thead>
            <tbody style="background-color:white;">';

            
while($row = $result->fetch_assoc()) {
    if($lista != "seccion" && $lista != "familia"){
        $onclick = 'borrar(' . "'" . $row["id"] . "'" . ')';
    }else{
        $onclick = '';
    }
    
	echo '<tr>
			<td style="text-align:center;">' . $row["lista"] . '</td>
			<td style="text-align:center;" onclick="' . $onclick . '"><img class="' . $class . '" style="vertical-align:middle;" src="../Imagenes/icon_delete.png"  width="15" height="15"></td>
		  </tr>';
}

echo "</tbody></table></div>"; 

$conn->close();
?>