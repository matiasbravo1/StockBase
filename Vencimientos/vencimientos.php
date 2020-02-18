<?php
include('session.php');
include('header.php');

$anio = date("Y");
$mes = date("n");
if (strlen($mes) == 1) {
    $mes = "0" . $mes;
}
$dia = date("j");
if (strlen($dia) == 1) {
    $dia = "0" . $dia;
}
$hoy = $anio . "-" . $mes . "-" . $dia;

$sql = "SELECT * FROM altas WHERE `restan` NOT LIKE '0' AND `fecha_vto` < '" . $hoy . "' AND `fecha_vto` NOT LIKE '0000-00-00' AND `vencido` = '0'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

if ($result->num_rows == 0) {
    echo '<center><font style="color:#f44336;font-weight:bold;"><br>NO HAY ARTÍCULOS VENCIDOS<br><br></font></center>';
} else {
echo '<center><font style="color:#f44336;font-weight:bold;"><br>ARTÍCULOS VENCIDOS<br></font>';
echo '<table class="w3-table-all w3-hoverable" style="width:90%;">
        <tr style="border: 1px solid black;background-color:#f44336;color:white;">
            <th style="text-align:center;">Id</th>
            <th style="text-align:center;">Código</th>
            <th style="text-align:center;">Descripción</th>
            <th style="text-align:center;">Marca</th>
            <th style="text-align:center;">Fecha Vto.</th>
            <th style="text-align:center;">Lote</th>
            <th style="text-align:center;">Cantidad</th>
            <th style="text-align:center;">Marcar como Vencido</th>
        </tr>';

while($row = $result->fetch_assoc()) {
    
	echo '<tr style="border: 1px solid black;">
			<td style="text-align:center;">' . $row["id_alta"] . '</td>
			<td style="text-align:center;">' . $row["codigo"] . '</td>
			<td style="text-align:center;">' . $row["descripcion"] . '</td>
			<td style="text-align:center;">' . $row["marca"] . '</td>
			<td style="text-align:center;">' . $row["fecha_vto"] . '</td>
			<td style="text-align:center;">' . $row["lote"] . '</td>
			<td style="text-align:center;">' . $row["restan"] . '</td>
			<td style="text-align:center;"><input style="vertical-align:middle;" type="image" src="flecha_roja.png" onclick=' . '"marcarVencido(' . "'" . $row["id_alta"] . "'" . ')" ' . 'width="15" height="15"></td>
		 </tr>';
		
}

echo '</table></center>';
}

//CALCULA ARTICULOS QUE VENCEN DENTRO DE UN MES
$fecha = date('Y-m-j');
$nuevafecha = strtotime ( '+1 month' , strtotime ( $fecha ) ) ;
//$nuevafecha = date ( 'Y-m-j' , $nuevafecha );

$anio = date('Y', $nuevafecha);
$mes = date('n', $nuevafecha);
if (strlen($mes) == 1) {
    $mes = "0" . $mes;
}
$dia = date('j', $nuevafecha);
if (strlen($dia) == 1) {
    $dia = "0" . $dia;
}
$masunmes = $anio . "-" . $mes . "-" . $dia;

$sql = "SELECT * FROM altas WHERE `restan` NOT LIKE '0' AND `fecha_vto` < '" . $masunmes . "' AND `fecha_vto` > '" . $hoy . "' AND `fecha_vto` NOT LIKE '0000-00-00' AND `vencido` = '0' ORDER BY fecha_vto ASC";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

if ($result->num_rows == 0) {
    echo '<center><font style="color:#ff6600;font-weight:bold;"><br>NO HAY ARTÍCULOS POR VENCER DENTRO DEL PRÓXIMO MES</font></center>';
} else {
echo '<br><center><font style="color:#ff6600;font-weight:bold;">ARTÍCULOS QUE VENCEN DENTRO DEL PRÓXIMO MES<br></font>';
echo '<table class="w3-table-all w3-hoverable" style="width:90%;">
        <tr style="border: 1px solid black;background-color:#ff6600;color:white;">
            <th style="text-align:center;">Id</th>
            <th style="text-align:center;">Código</th>
            <th style="text-align:center;">Descripción</th>
            <th style="text-align:center;">Marca</th>
            <th style="text-align:center;">Fecha Vto.</th>
            <th style="text-align:center;">Lote</th>
            <th style="text-align:center;">Cantidad</th>
        </tr>';

while($row = $result->fetch_assoc()) {
    
	echo '<tr style="border: 1px solid black;">
			<td style="text-align:center;">' . $row["id_alta"] . '</td>
			<td style="text-align:center;">' . $row["codigo"] . '</td>
			<td style="text-align:center;">' . $row["descripcion"] . '</td>
			<td style="text-align:center;">' . $row["marca"] . '</td>
			<td style="text-align:center;">' . $row["fecha_vto"] . '</td>
			<td style="text-align:center;">' . $row["lote"] . '</td>
			<td style="text-align:center;">' . $row["restan"] . '</td>
		 </tr>';
		
}

echo '</table></center>';
}

$conn->close();
?>

<!--div style="margin:6px 6px 4px 6px;border:solid 1px #1f4720;padding:6px;background-color:#b8e0b9;color:#1f4720;">
<strong>&nbspCódigo:&nbsp</strong><input style="width:100px;" class="w3-input" type="text" id="codigo">
<strong>&nbspDescripción:&nbsp</strong><input style="width:300px;" class="w3-input" type="text" id="descripcion">
<strong>&nbspMarca:&nbsp</strong><input style="width:100px;" class="w3-input" type="text" id="marca">
<strong>&nbspFamilia:&nbsp</strong><input style="width:100px;" class="w3-input" type="text" id="familia">
<strong>&nbspStock:&nbsp</strong><input style="width:80px;" class="w3-input" type="text" id="stock">
&nbsp&nbsp<input class="w3-btn w3-round" style="padding-left:12px;padding-right:12px;background-color:#4CAF50;color:white;" id="buscar" type="button" value="Buscar" onclick="stock_general();">
&nbsp&nbsp&nbsp&nbsp<input class="w3-btn w3-round" style="padding-left:12px;padding-right:12px;background-color:#4CAF50;color:white;" id="imprimir" type="button" value="Imprimir" onclick="imprimir_stock();">
</div>

<div id="general_board"></div>

<div id="articulos" class="w3-modal">

    <div class="w3-modal-content" style="overflow: auto;padding-top:20px;padding-bottom:12px;width:90%;">
        <div id="lista_articulos">
            
        </div>
        <center><input class="w3-btn w3-red w3-round" style="padding-left:12px;padding-right:12px;margin-top:10px;" id="buscar" type="button" value="Cerrar" onclick="Close();"></center>
    </div>
</div-->


<!--SCRIPTS ---------------------------------------------------------------------------->
<script>
$( document ).ready(function() {
/*document.getElementById("btn-stock").style.backgroundColor = "#4CAF50";
document.getElementById("btn-stock").style.fontWeight = "900";
document.getElementById("btn-stock").style.color = "white";*/
document.title = 'Vencimientos';
});
function marcarVencido(id_alta){
    ajax = objetoAjax();

		ajax.open("POST", "vencimientos_marcar_ajax.php", true);

		ajax.onreadystatechange = function() {

			if (ajax.readyState == 4){
                //document.getElementById("loader_izq").style.display = "none";
                location.reload();
            }
		}

		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

		ajax.send("&id_alta="+id_alta);
}

function Close(){
    document.getElementById("articulos").style.display = "none";
}

</script>

</body>

</html>