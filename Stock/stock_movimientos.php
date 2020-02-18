<?php
   include('../Headers/session.php');
   include('../Headers/principal.php');
?>

<div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-dark stock buscador">
    
      <!-- Collapse button -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#Nav2"
        aria-controls="Nav2" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    
      <!-- Collapsible content -->
      <div class="collapse navbar-collapse" id="Nav2">
        <form class="text-center mt-3">
            <p class="h4 mb-3">Movimientos</p>
            <div class="form-row justify-content-center">
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                    <small>Desde / Hasta</small>
                    <input class="form-control" type="date" id="fecha_desde">
                    <input class="form-control" type="date" id="fecha_hasta">
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                    <small>Código / Código del Proveedor</small>
                    <input class="form-control" type="text" id="codigo">
                    <input class="form-control" type="text" id="codigo_prov">
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
                    <small>Consumo Mensual</small>
                    <input class="form-control" type="text" id="mensual">
                </div>
                
            </div>
            <div class="form-row justify-content-center mt-2">
                <div class="col-12 col-sm-12 col-md-1 col-lg-1 col-xl-1 text-center mt-auto mb-0">
                    <button class="btn btn-dark-green mx-0 mb-0 btn-form" type="button" onclick="buscar()"><span id="loader" class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>Buscar</button>
                </div>
            </div>
        </form>      
      </div>
    </nav>

    <div id="general_board" class="text-center"></div>
    
    
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
    $('#stock').addClass('active');
    document.title = 'Stock';
});

/*function editar(id){
    var win = window.open("../Stock/stock_editar.php?id=" + id, '_blank');
    win.focus();
}*/

function buscar(){
        $("#loader").css({"display":"inline-block"});
        document.getElementById("general_board").innerHTML = "";
        ajax = objetoAjax();

		ajax.open("POST", "stock_movimientos_ajax.php", true);

		ajax.onreadystatechange = function() {

			if (ajax.readyState == 4){
                //document.getElementById("loader_izq").style.display = "none";
                $("#loader").css({"display":"none"});
                document.getElementById("general_board").innerHTML = (ajax.responseText);
            }
		}

		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

		ajax.send("&fecha_desde="+$("#fecha_desde").val()+"&fecha_hasta="+$("#fecha_hasta").val()+"&codigo="+$("#codigo").val()+"&codigo_prov="+$("#codigo_prov").val()+"&descripcion="+$("#descripcion").val()+"&marca="+$("#marca option:selected").text()+"&familia="+$("#familia option:selected").text()+"&mensual="+$("#mensual").val());
}
/*
function ver_articulos(id){
     //var fecha_desde = document.getElementById("fecha_desde").value;
        $("#botonModal").click();
        document.getElementById("lista_articulos").innerHTML = '<center><span id="loader" class="spinner-border spinner-border-sm mr-1" style="display:inline-block;color:green;" role="status" aria-hidden="true"></span></center>';
        ajax = objetoAjax();

		ajax.open("POST", "stock_buscar_articulos_ajax.php", true);

		ajax.onreadystatechange = function() {

			if (ajax.readyState == 4){
			    
                document.getElementById("lista_articulos").innerHTML = (ajax.responseText);
            }
		}

		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

		ajax.send("&codigo="+id);
}

function Close(){
    document.getElementById("articulos").style.display = "none";
}
*/
</script>

</body>

</html>