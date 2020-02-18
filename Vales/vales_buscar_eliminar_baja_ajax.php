<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id_baja = test_input($_POST["id_baja"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

//BUSCA DATOS DE LA BAJA
$sql = "SELECT * FROM bajas WHERE `id_baja` = '$id_baja'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.1");
}

if ($result->num_rows == 0) {
    exit("Esta baja ya no existe. Recuerde actualizar las búsquedas.");
}

$row = $result->fetch_assoc();

$id_alta = $row["id_alta"];
$cantidad = $row["cantidad"];
$id_vale = $row["id_vale"];
$codigo = $row["codigo"];

//CHEQUEA QUE EXISTA EL ALTA
$sql = "SELECT restan, entregados FROM altas WHERE `id_alta` = '$id_alta'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.2");
}

if ($result->num_rows == 0) {
    exit("La alta de este artículo ya no existe.");
}

$row = $result->fetch_assoc();

$restan = $row["restan"];
$entregados_altas = $row["entregados"];

//CHEQUEA QUE EXISTA EN TABLA ARTICULOS
$sql = "SELECT stock FROM articulos WHERE `codigo` = '$codigo'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.3");
}

if ($result->num_rows == 0) {
    exit("Este artículo ya no existe. Debe crearlo si desea eliminar esta baja.");
}

$row = $result->fetch_assoc();

$stock = $row["stock"];

//CHEQUEA QUE EL VALE NO ESTÉ FINALIZADO
$sql = "SELECT estado FROM vales WHERE `id_vale` = '$id_vale' AND `guardado` = '1'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.4");
}

if ($result->num_rows == 0) {
    exit("El vale ya no existe. Recuerde actualizar las búsquedas.");
}

$row = $result->fetch_assoc();
if ($row["estado"] == 'Finalizado'){
    exit("No puede eliminarse una baja de un vale finalizado.");
} 


//CHEQUEA QUE EL ARTICULO ESTE EN EL VALE Y GUARDA DATOS

$sql = "SELECT falta_entregar, entregados FROM articulos_vale WHERE `id_vale` = '$id_vale' AND `codigo` = '$codigo' AND `guardado` = '1'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.5");
}

if ($result->num_rows == 0) {
    exit("Este artículo no existe en este vale. Recuerde actualizar las búsquedas.");
}

$row = $result->fetch_assoc();

$falta_entregar = $row["falta_entregar"];
$entregados = $row["entregados"];

//COMIENZA ALTERACION DE TABLAS

//ALTERA TABLA ARTICULOS_VALE
$entregados_final = $entregados - $cantidad;
$falta_entregar_final = $falta_entregar + $cantidad;

$sql = "UPDATE articulos_vale SET `entregados` = '$entregados_final', `falta_entregar` = '$falta_entregar_final' WHERE `id_vale` = '$id_vale' AND `codigo` = '$codigo'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Error1");
}

function back1(){
    global $entregados, $falta_entregar, $id_vale, $codigo, $conn; 
    $sql_b = "UPDATE articulos_vale SET `entregados` = '$entregados', `falta_entregar` = '$falta_entregar' WHERE `id_vale` = '$id_vale' AND `codigo` = '$codigo'";
    $result_b = $conn->query($sql_b);
}

//SUMA EN TABLA ALTAS
$restan_final = $restan + $cantidad;
$entregados_altas_final = $entregados_altas - $cantidad;

$sql = "UPDATE altas SET `restan` = '$restan_final', `entregados` = '$entregados_altas_final' WHERE `id_alta` = '$id_alta'";
$result = $conn->query($sql);

if ($result != 1) {
    back1();
    exit("Error2");
}

function back2(){
    global $id_alta, $restan, $conn, $entregados_altas; 
    $sql_b = "UPDATE altas SET `restan` = '$restan', `entregados` = '$entregados_altas' WHERE `id_alta` = '$id_alta'";
    $result_b = $conn->query($sql_b);
}

//SUMA EN TABLA STOCK
$stock_final = $stock + $cantidad;

$sql = "UPDATE articulos SET `stock` = '$stock_final' WHERE `codigo` = '$codigo'";
$result = $conn->query($sql);

if ($result != 1) {
    back1();
    back2();
    exit("Error3");
}

function back3(){
    global $stock, $codigo, $conn; 
    $sql_b = "UPDATE articulos SET `stock` = '$stock' WHERE `codigo` = '$codigo'";
    $result_b = $conn->query($sql_b);
}

//ELIMINA LA BAJA
$sql = "DELETE FROM bajas WHERE `id_baja` ='" . $id_baja . "'";
$result = $conn->query($sql);

if ($result != 1) {
    back1();
    back2();
    back3();
    exit("Error4");
}

function back4(){
    
}

//CALCULA ESTADO VALE
$sql = "SELECT pedidos, entregados FROM articulos_vale WHERE `id_vale` = '$id_vale'";
$result = $conn->query($sql);

/*if ($result != 1) {
    back1();
    back2();
    back3();
    back4();
    exit("Error5");
}*/

if ($result->num_rows != 0) {
  
    $cant_items = $result->num_rows;

    $a = 0;
    while($row = $result->fetch_assoc()){
        if ($row["entregados"] == $row["pedidos"]){
            $a += 1;
        } else if ($row["entregados"] < $row["pedidos"] && $row["entregados"] > 0){
            $a += 0.5;
        } else if ($row["entregados"] == 0){
            $a += 0;
        }
    }
    
    if ($a == 0){
        $estado = "No";
    } else if ($a == $cant_items){
        $estado = "Total";
    } else {
        $estado = "Parcial";
    }
    
    $sql3 = "UPDATE vales SET `descarga` = '" . $estado . "' WHERE `id_vale` = '" . $id_vale . "'";
    $result3 = $conn->query($sql3);
    
    //if ($result3 != 1) {}
        
}

echo "Éxito.";

//AUDITAR
$date = new DateTime();
$date->modify('-3 hours');
$hoy = $date->format('Y-m-d');
$ahora = $date->format('H:i:s');
$user = $_SESSION['user_name'];

$sql = "INSERT INTO auditorias (fecha, hora, usuario, accion, detalles) VALUES ('$hoy','$ahora','$user','Eliminar Baja','Id Artículo: " . $id_alta . ' - Concepto: Vale - Vale: ' . $id_vale . ' - Cantidad: ' . $cantidad . " - Desde Vales')";
$result = $conn->query($sql);

$conn->close();
?>