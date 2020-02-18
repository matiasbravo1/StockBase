<?php
   include('../Headers/session.php');
   include('../Headers/principal.php');
   
//CALCULA ARTICULOS QUE VENCEN DENTRO DE UN MES

//ARMA FECHA HOY MAS UN MES
$fecha = date('Y-m-j');
$nuevafecha = strtotime ( '+1 month' , strtotime ( $fecha ) ) ;

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

//ARMA FECHA HOY
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

//CONSULTA
$sql = "SELECT * FROM altas WHERE `restan` NOT LIKE '0' AND `fecha_vto` < '" . $masunmes . "' AND `fecha_vto` > '" . $hoy . "' AND `fecha_vto` NOT LIKE '0000-00-00' ORDER BY fecha_vto ASC";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

$porvencer = $result->num_rows;

//CALCULA ARTICULOS VENCIDOS


$sql = "SELECT id_alta FROM altas WHERE `restan` NOT LIKE '0' AND `fecha_vto` < '" . $hoy . "' AND `fecha_vto` NOT LIKE '0000-00-00'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.2");
}

$vencidos = $result->num_rows;

?>

<div class="container-fluid">
    <div id="primero" class="row justify-content-center mt-4">
        <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4" >
            <div class="vales z-depth-2 text-center" style="height:250px">
                <h5 class="mt-2" style="font-weight:bold">Vales</h5>
                <div class="table-responsive table-striped table-hover mx-auto" style="width:80%">
                    <table class="table table-sm table-borderless" style="border:0.5px solid green">
                        <thead>
                            <th></th>
                            <th colspan="3">Descarga</th>
                        </thead>
                        <thead>
                            <tr>
                                <th></th>
                                <th>No</th>
                                <th>Parcial</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Hoy</td>
                                <td>X</td>
                                <td>X</td>
                                <td>X</td>
                            </tr>
                            <tr>
                                <td>Semana</td>
                                <td>X</td>
                                <td>X</td>
                                <td>X</td>
                            </tr>
                            <tr>
                                <td>Mes</td>
                                <td>X</td>
                                <td>X</td>
                                <td>X</td>
                            </tr>
                            <tr>
                                <td>Año</td>
                                <td>X</td>
                                <td>X</td>
                                <td>X</td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td>X</td>
                                <td>X</td>
                                <td>X</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <div class="bajas z-depth-2 text-center" style="height:250px">
                <h5 class="mt-2" style="font-weight:bold">Vencimientos</h5>
                <div class="row justify-content-center mt-4 pt-3">
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4" >
                        <div id="vencidos" style="overflow:hidden;width:100%;height:100px;background-color:white;border:1px solid red;padding:0">
                            <p style="font-size:50px;color:black;height:74px;text-align:center;width:100%;margin:0"><?php echo $vencidos;?></p>
                            <p style="font-size:14px;width:100%;background-color:red;text-align:center;color:white;font-weight:bold;padding:2px 0px;">VENCIDOS</p>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4" >
                        <div id="por_vencer" style="overflow:hidden;width:100%;height:100px;background-color:white;border:1px solid red;padding:0">
                            <p style="font-size:50px;color:black;height:74px;text-align:center;width:100%;margin:0"><?php echo $porvencer;?></p>
                            <p style="font-size:14px;width:100%;background-color:red;text-align:center;color:white;font-weight:bold;padding:2px 0px;">POR VENCER</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <div class="stock z-depth-2 text-center" style="height:250px">
                <h5 class="mt-2" style="font-weight:bold">Stock</h5>
                <div><br><br>
                    <p>X artículos con stock menor al crítico</p>
                    <p>X artículos con stock menor al mínimo</p>
                </div>
            </div>
           
        </div>
    </div>
    <div id="segundo" class="row justify-content-center mt-4">
        <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8" >
            <div class="config z-depth-2">
                <h5 class="mt-2" style="font-weight:bold;text-align:center;">Actualizaciones</h5>
                <ul>
                  <li>13-nov: Cambio de órden de columnas en módulo Movimientos.</li>      
                  <li>13-nov: Se agregaron títulos en todos los módulos.</li>    
                  <li>13-nov: Bloqueo de campo código en Editar Artículo y alarma al modificar descripción.</li>
                  <!--li>30-oct: Etiquetas: permite dos renglones para nombre de artículo.</li>
                  <li>30-oct: Campo Código del Proveedor en Editar Remito.</li>
                  <li>Campo Código recibe valores de texto.</li>
                  <li>Se agregó item "Código del Proveedor" a artículos.</li><br>
                  <li>Campo Consumo Mensual recibe valores de texto.</li>
                  <li>Formato de etiquetas. Consultar parámetros de configuración de impresora.</li>
                  <li>Se agregó hora y usuario en impresión de vales.</li>
                  <li>Se aumentó el tamaño de letra en impresión de vales.(Si aún es chica, avisar)</li>
                  <li>En Buscar Vales se autocompleta fecha del día por defecto.</li-->
                </ul>

            </div>
        </div>
    </div>
    
    <!--MODAL------------------------->
    <button id="botonModal" type="button" class="btn btn-primary d-none" data-toggle="modal" data-target="#Modal">
    </button>
    <!-- Central Modal Small -->
    <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
      aria-hidden="true">
    
      <!-- Change class .modal-sm to change the size of the modal -->
      <div class="modal-dialog modal-xl" role="document">
    
        <div class="modal-content">
          <!--div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button></div-->
          <div class="modal-body text-center">
              <form id="form-buscar" class="stock buscador">
                <p class="h5 my-1">Buscar Artículo</p>
                <div class="form-row  justify-content-center">
                    <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                        <small>Código</small>
                        <input class="form-control" type="number" id="codigo2">
                    </div>
                    <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4 text-center">
                        <small>Descripción</small>
                        <input class="form-control" type="text" id="descripcion2">
                    </div>
                    <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                        <small>Marca</small>
                        <select id="marca2" class="browser-default custom-select">
                            <option value=""></option>
                            <?php
                                $sql = "SELECT marca FROM listas WHERE `marca` NOT LIKE ''";
                                $result = $conn->query($sql);
                                while($row = $result->fetch_assoc()) {
                                	echo '<option value="' . $row["marca"] . '">' . $row["marca"] . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                        <small>Familia</small>
                        <select id="familia2" class="browser-default custom-select">
                            <option value=""></option>
                            <?php
                                $sql = "SELECT familia FROM listas WHERE `familia` NOT LIKE ''";
                                $result = $conn->query($sql);
                                while($row = $result->fetch_assoc()) {
                                	echo '<option value="' . $row["familia"] . '">' . $row["familia"] . '</option>';
                                }
                                $conn->close();
                            ?>
                        </select>
                    </div>
                    
                    <div class="col-12 col-sm-12 col-md-1 col-lg-1 col-xl-1 text-center mt-auto mb-0">
                        <button class="btn btn-dark-green mx-0 mb-0 btn-form" type="button" onclick="buscarArticulo()">Buscar</button>
                    </div>
                </div>
            </form>      
              <div id="lista_articulos"></div>
              <button id="btn-modalClose" type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cerrar</button>              
          </div>
          <!--div class="modal-footer"></div-->
        </div>
      </div>
    </div>
    <!-- Central Modal Small -->
</div>



<!--SCRIPTS ---------------------------------------------------------------------------->
<script>
$( document ).ready(function() {
    //$('#bajas').addClass('active');
    document.title = 'StockBase 1.0';

    //Calcula Fecha de hoy local
    var d = new Date();
    var h = new Date(d.getTime() - 180 * 60 * 1000);
    var x = h.toISOString().substr(0, 10);
 
    
    //document.querySelector("#fecha").value = x;
});

$("#vencidos").click(function(){
    var win = window.open("../Headers/vencidos.php", '_blank');
    win.focus();
});
$("#por_vencer").click(function(){
    var win2 = window.open("../Headers/por_vencer.php", '_blank');
    win2.focus();
});
</script>

</body>

</html>