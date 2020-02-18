<?php
include '../Headers/session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id_vale = test_input($_POST["id_vale"]);
  $id_alta = test_input($_POST["id_alta"]);
  $cantidad = test_input($_POST["cantidad"]);
  $fecha = test_input($_POST["fecha"]);
  $hora = test_input($_POST["hora"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

//VALIDA INPUT CANTIDAD
if (is_numeric($cantidad) == false){
    exit("El valor debe ser numérico.");
}

if ($cantidad <= 0){
    exit("La cantidad de artículos a dar de baja no puede ser menor o igual a cero.");
}

if (strpos($cantidad, ',')){
    exit("El valor no puede contener comas.");
}

if (strpos($cantidad, '.')){
    exit("El valor no puede contener puntos.");
}

//CHEQUEA QUE HAYA STOCK SUFICIENTE
$sql = "SELECT * FROM altas WHERE `id_alta` = '$id_alta'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.1");
}

if ($result->num_rows == 0) {
    exit("Este artículo ya no existe.");
}

$row = $result->fetch_assoc();
if ($row["restan"] < $cantidad){
    exit("No hay stock suficiente de este artículo.");
}

$restan = $row["restan"];
$entregados_altas = $row["entregados"];
$codigo = $row["codigo"];
$descripcion = $row["descripcion"];
$marca = $row["marca"];
$familia = $row["familia"];
$lote = $row["lote"];
$fecha_vto = $row["fecha_vto"];

//CHEQUEA QUE EXISTA EN TABLA ARTICULOS Y HAYA SUFICIENTE STOCK
$sql = "SELECT stock FROM articulos WHERE `codigo` = '$codigo'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.2");
}

if ($result->num_rows == 0) {
    exit("Este artículo ya no existe.");
}

$row = $result->fetch_assoc();
if ($row["stock"] < $cantidad){
    exit("No hay stock suficiente de este artículo.");
}

$stock = $row["stock"];

//CHEQUEA QUE EL VALE NO ESTÉ FINALIZADO O CANCELADO
$sql = "SELECT estado FROM vales WHERE `id_vale` = '$id_vale' AND `guardado` = '1'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.3");
}

if ($result->num_rows == 0) {
    exit("El vale ya no existe. Recuerde actualizar las búsquedas.");
}

$row = $result->fetch_assoc();
if ($row["estado"] != 'Normal'){
    exit("No puede descargarse un artículo de un vale cancelado o finalizado.");
} 

//CHEQUEA QUE EL ARTICULO NO ESTE TOTALMENTE DESCARGADO O LA CANTIDAD A DESCARGAR SEA MAYOR A LA QUE EL VALE NECESITA

$sql = "SELECT falta_entregar, entregados FROM articulos_vale WHERE `id_vale` = '$id_vale' AND `codigo` = '$codigo' AND `guardado` = '1'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.4");
}

if ($result->num_rows == 0) {
    exit("Este artículo no existe en este vale.");
}

$row = $result->fetch_assoc();

if ($row["falta_entregar"] < $cantidad){
    exit("La cantidad a descargar supera la cantidad solicitada en el vale.");
}

$falta_entregar = $row["falta_entregar"];
$entregados = $row["entregados"];

//COMIENZA ALTERACION DE TABLAS

//ALTERA TABLA ARTICULOS_VALE
$entregados_final = $entregados + $cantidad;
$falta_entregar_final = $falta_entregar - $cantidad;

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

//DESCUENTA EN TABLA ALTAS
$restan_final = $restan - $cantidad;
$entregados_altas_final = $entregados_altas + $cantidad;
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

//DESCUENTA EN TABLA STOCK
$stock_final = $stock - $cantidad;

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

//CREA NUEVA BAJA
$sql = "INSERT INTO bajas (id_vale, id_alta, fecha, hora, codigo, descripcion, marca, familia, lote, fecha_vto, cantidad, concepto) VALUES ('$id_vale', '$id_alta', '$fecha', '$hora', '$codigo', '$descripcion', '$marca', '$familia', '$lote', '$fecha_vto','$cantidad','Vale')";
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

//AUDITAR
$date = new DateTime();
$date->modify('-3 hours');
$hoy = $date->format('Y-m-d');
$ahora = $date->format('H:i:s');
$seccion = $_SESSION['seccion'];
$user = $_SESSION['user_name'];

$sql = "INSERT INTO auditorias (fecha, hora, usuario, seccion, accion, detalles) VALUES ('$hoy','$ahora','$user','$seccion','Nueva Baja','Id Artículo: " . $id_alta . ' - Concepto: Vale - Vale: ' . $id_vale . ' - Cantidad: ' . $cantidad . " - Desde Vales')";
$result = $conn->query($sql);

echo "Éxito.";

$conn->close();
?>