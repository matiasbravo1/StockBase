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
/*$sql = "SELECT * FROM altas WHERE `restan` NOT LIKE '0' AND `fecha_vto` < '" . $masunmes . "' AND `fecha_vto` > '" . $hoy . "' AND `fecha_vto` NOT LIKE '0000-00-00' ORDER BY fecha_vto ASC";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

$porvencer = $result->num_rows;*/

//CALCULA ARTICULOS VENCIDOS


$sql = "SELECT * FROM altas WHERE `restan` NOT LIKE '0' AND `fecha_vto` < '" . $hoy . "' AND `fecha_vto` NOT LIKE '0000-00-00'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.2");
}

?>

<div class="container-fluid">
   
        
<?php
if ($result->num_rows == 0) {
    echo '<div class="text-center mt-3" style="color:#007E33;font-weight:bold;">No hay artículos vencidos.</div>';
}else{

    echo ' <div id="primero" class="row justify-content-center mt-4 mx-2">
        <p class="h4 mb-3" style="color:red;font-weight:bold">Artículos Vencidos</p>
        <div class="table-responsive" style="border-radius:8px 8px 0px 0px;">
        <table class="table table-sm table-borderless table-striped table-hover" style="border:1px solid #007E33">
            <thead>
              <tr class="primary-color-dark" style="color:white;">
                <th style="text-align:center;" scope="col">Id</th>
                <th style="text-align:center;" scope="col">Código</th>
                <th style="text-align:center;" scope="col">Descripción</th>
                <th style="text-align:center;" scope="col">Marca</th>
                <th style="text-align:center;" scope="col">Familia</th>
                <th style="text-align:center;" scope="col">Fecha Vto.</th>
                <th style="text-align:center;" scope="col">Lote</th>
                <th class="success-color-dark" style="text-align:center;" scope="col">Stock</th>
              </tr>
            </thead>
            <tbody>';

            
    while($row = $result->fetch_assoc()) {
    
    	echo '<tr>
    			<td style="text-align:center;">' . $row["id_alta"] . '</td>
    			<td style="text-align:center;">' . $row["codigo"] . '</td>
    			<td style="text-align:center;">' . $row["descripcion"] . '</td>
    			<td style="text-align:center;">' . $row["marca"] . '</td>
    			<td style="text-align:center;">' . $row["familia"] . '</td>
    			<td style="text-align:center;">' . $row["fecha_vto"] . '</td>
    			<td style="text-align:center;">' . $row["lote"] . '</td>
    			<td style="text-align:center;">' . $row["restan"] . '</td>
    		  </tr>';
    }

    echo "</tbody></table></div></div>"; 

}
?>
        
    
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
    document.title = 'Vencidos';

    //Calcula Fecha de hoy local
    var d = new Date();
    var h = new Date(d.getTime() - 180 * 60 * 1000);
    var x = h.toISOString().substr(0, 10);
 
    
    //document.querySelector("#fecha").value = x;
});


</script>

</body>

</html>