<?php
   include('../Headers/session.php');
   include('../Headers/principal.php');
?>

<div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-dark altas buscador">
    
      <!-- Collapse button -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#Nav2"
        aria-controls="Nav2" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    
      <!-- Collapsible content -->
      <div class="collapse navbar-collapse  justify-content-center" id="Nav2">
        <form id="form1" class="text-center">
            <p class="h4 mb-2 mt-1">Buscar Altas por Id</p>
            <div class="form-row">
                <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 text-center">
                    <small>Desde</small>
                    <input class="form-control" type="date" id="fecha_desde">
                </div>
                <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 text-center">
                    <small>Hasta</small>
                    <input class="form-control" type="date" id="fecha_hasta">
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                    <small>Remito Nro.</small>
                    <input class="form-control" type="text" id="remito_nro">
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                    <small>Proveedor</small>
                    <select id="proveedor" class="browser-default custom-select">
                        <option value=""></option>
                        <?php
                            $sql = "SELECT proveedor FROM listas WHERE `proveedor` NOT LIKE ''";
                            $result = $conn->query($sql);
                            while($row = $result->fetch_assoc()) {
                            	echo '<option value="' . $row["proveedor"] . '">' . $row["proveedor"] . '</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                    <small>Id</small>
                    <input class="form-control" type="number" id="id_art">
                </div>
            </div>
            <div class="form-row mt-3">
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                    <small>Código</small>
                    <input class="form-control" type="text" id="codigo">
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                    <small>Descripción</small>
                    <input class="form-control" type="text" id="descripcion">
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                    <small>Marca</small>
                    <select id="marca" class="browser-default custom-select">
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
                    <select id="familia" class="browser-default custom-select">
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
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                    <small>Stock</small>
                    <select id="stock1" class="browser-default custom-select">
                        <option value=""></option>
                        <option value="igualcero">Igual a cero</option>
                        <option value="mayorcero">Mayor a cero</option>
                    </select>
                </div>
                <!--div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                    <small>Vencidos</small>
                    <select id="vencido" class="browser-default custom-select">
                        <option value=""></option>
                        <option value="igualcero">Igual a cero</option>
                        <option value="mayorcero">Mayor a cero</option>
                    </select>
                </div-->
            </div>
            <div class="form-row mt-3">
                <div class="col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2 text-center mb-auto mt-auto">
                    <button class="btn btn-primary btn-lg mx-0 mb-0" style="color:white;width:100%;padding:4px 0px" type="button" onclick="limpiar()">Limpiar</button>
                </div>
                <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 offset-xl-2 text-center mb-auto mt-auto">
                    <button class="btn btn-primary btn-lg mx-0 mb-0" style="color:white;width:100%;padding:4px 0px" type="button" onclick="buscar()"><span id="loader" class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>Buscar</button>
                </div>
            </div>
        </form>      
      </div>
    </nav>

    <div id="tabla" class="text-center"></div>
    
    
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
              <div id="lista_articulos"></div>
              <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cerrar</button>              
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
    $('#altas').addClass('active');
    document.title = 'Altas';
});

function buscar(){
        $("#loader").css({"display":"inline-block"});   
        document.getElementById("tabla").innerHTML = "";
        
        ajax = objetoAjax();

		ajax.open("POST", "altas_buscar_id_ajax.php", true);

		ajax.onreadystatechange = function() {

			if (ajax.readyState == 4){
                $("#loader").css({"display":"none"});   
                document.getElementById("tabla").innerHTML = (ajax.responseText);
            }
		}

		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

		ajax.send("&fecha_desde="+$("#fecha_desde").val()+"&fecha_hasta="+$("#fecha_hasta").val()+"&remito_nro="+$("#remito_nro").val()+"&proveedor="+$("#proveedor option:selected").text()+"&codigo="+$("#codigo").val()+"&descripcion="+$("#descripcion").val()+"&marca="+$("#marca option:selected").text()+"&familia="+$("#familia option:selected").text()+"&id_art="+$("#id_art").val()+"&stock="+$("#stock1 option:selected").val());
}
function limpiar(){
    document.getElementById("form1").reset();
}
</script>

</body>

</html>