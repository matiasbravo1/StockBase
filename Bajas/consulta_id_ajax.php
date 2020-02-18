<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id_alta = test_input($_POST["id_alta"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$sql = "SELECT * FROM altas WHERE `id_alta` = '$id_alta' AND `restan` <> '0' AND `guardado` = '1'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Error: Algo anduvo mal.");
}

if ($result->num_rows == 0) {
    exit("Error: No existe el artículo o ya no tiene stock disponible.");
}    

$row = $result->fetch_assoc();

//Consulta en que vales fue pedido el artículo
$sql2 = "SELECT id_vale FROM articulos_vale WHERE `codigo` = '" . $row["codigo"] . "' AND `falta_entregar` <> '0' AND `guardado` = '1'";
$result2 = $conn->query($sql2);

if ($result2 != 1) {
    exit("Error: Algo anduvo mal.2");
}

if ($result2->num_rows == 0) {
    $vale = '<input value="" type="text" id="id_vale" class="form-control mx-auto" disabled>';
} 

/*if ($result2->num_rows == 1) {
    $row2 = $result2->fetch_assoc();    
    $vale = '<input value="' . $row2["id_vale"] . '" type="text" id="id_vale" class="form-control mx-auto" disabled>';
}*/
    
if ($result2->num_rows > 0) {

    $vale = '<select id="id_vale" class="browser-default custom-select">
                <option value="" selected></option>';
    while($row2 = $result2->fetch_assoc()){
        $vale .= '<option value="' . $row2["id_vale"] . '">' . $row2["id_vale"] . '</option>';
    }
    $vale .= '</select>';

}    
echo '<div class="col-12 col-sm-12 col-md-12 col-lg-11 col-xl-11">
        <form id="form2" class="text-center border border-light p-3 mt-3 bajas">
            <div class="form-row mb-2 justify-content-center">
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                    <small>Id Artículo</small>
                    <input value="' . $row["id_alta"] . '" type="number" id="id_alta" class="form-control mx-auto" disabled>
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                    <small>Código</small>
                    <input value="' . $row["codigo"] . '" type="number" id="codigo" class="form-control mx-auto" disabled>
                </div>
                <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">
                    <small>Descripción</small>
                    <input value="' . $row["descripcion"] . '" id="descripcion" type="text" class="form-control mx-auto" disabled>
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                    <small>Marca</small>
                    <input value="' . $row["marca"] . '" type="text" id="marca" class="form-control mx-auto" disabled>
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                    <small>Familia</small>
                    <input value="' . $row["familia"] . '" type="text" id="familia" class="form-control mx-auto" disabled>
                </div>
            </div> 
            <div class="form-row mb-2 justify-content-center">
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                    <small>Lote</small>
                    <input value="' . $row["lote"] . '" type="text" id="lote" class="form-control mx-auto" disabled>
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                    <small>Fecha Vto.</small>
                    <input value="' . $row["fecha_vto"] . '" type="date" id="fecha_vto" class="form-control mx-auto" disabled>
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                    <small>Concepto</small>
                    <select id="concepto" class="browser-default custom-select">
                        <option value="Vale" selected>Vale</option>
                        <option value="Vencimiento">Vencimiento</option>
                        <option value="Ajuste_Negativo">Ajuste Negativo</option>
                    </select>
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                    <small>Cantidad</small>
                    <input value="1" id="cantidad" type="number" min="1" max="' . $row["restan"] . '" class="form-control mx-auto" >
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                    <small id="id_vale_label">Vale</small>' . $vale . '
                    
                </div>
                <div class="col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2 mt-auto mb-0">
                        <button id="btn-crear" class="btn btn-danger mx-0 mb-0 btn-form" type="button" onclick="crearBaja()"><span id="loader" class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>Crear</button>
                    </div>
            </div>
        </form>
    </div>';



$conn->close();
?> 