<?php
   include('../Headers/session.php');
   include('../Headers/principal.php');
?>

<div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-dark config buscador">
    
      <!-- Collapse button -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#Nav2"
        aria-controls="Nav2" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    
      <!-- Collapsible content -->
      <div class="collapse navbar-collapse  justify-content-center" id="Nav2">
        <form id="form1">
            <div class="form-row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-center">
                    <p class="h4 mb-3">Auditoría</p>
                </div>
            </div>
            <div class="form-row">
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                    <small>Desde</small>
                    <input class="form-control" type="date" id="fecha_desde">
                    <input class="form-control" type="time" id="hora_desde">
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                    <small>Hasta</small>
                    <input class="form-control" type="date" id="fecha_hasta">
                    <input class="form-control" type="time" id="hora_hasta">
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                    <small>Usuario</small>
                    <select id="usuario" class="browser-default custom-select">
                        <option value=""></option>
                        <?php
                            $sql = "SELECT * FROM usuarios";
                            $result = $conn->query($sql);
                            while($row = $result->fetch_assoc()) {
                            	if($row["id_usuario"] == '19'){continue;}
                            	echo '<option value="' . $row["nombre"] . " " . $row["apellido"] . '">' .  $row["nombre"] . " " . $row["apellido"]  . '</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                    <small>Acción</small>
                    <select id="accion" class="browser-default custom-select">
                        <option value=""></option>
                        <option value="">Eliminar Remito</option>
                        <option value="">Editar Remito</option>
                        <option value="">Nuevo Remito</option>
                        <option value="">Nueva Baja</option>
                        <option value="">Eliminar Baja</option>
                        <option value="">Nuevo Usuario</option>
                        <option value="">Editar Usuario</option>
                        <option value="">Eliminar Usuario</option>
                        <option value="">Nuevo Artículo</option>
                        <option value="">Editar Artículo</option>
                        <option value="">Eliminar Artículo</option>
                        <option value="">Estado Vale</option>
                        <option value="">Nuevo Vale</option>
                        <option value="">Inicio de Sesión</option>
                        <option value="">Cierre de Sesión</option>
                        <option value="">Agregar Sección a Usuario</option>
                        <option value="">Eliminar Sección a Usuario</option>
                    </select>
                </div>
                <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4 text-center">
                    <small>Detalle</small>
                    <input class="form-control" type="text" id="detalle">
                </div>
            </div>
            <div class="form-row mt-3">
                <div class="col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2 text-center mb-auto mt-auto">
                    <button class="btn btn-lg mx-0 mb-0" style="background-color:#9933CC;color:white;width:100%;padding:4px 0px" type="button" onclick="limpiar()">Limpiar</button>
                </div>
                <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 offset-xl-2 text-center mb-auto mt-auto">
                    <button class="btn btn-lg mx-0 mb-0" style="background-color:#9933CC;color:white;width:100%;padding:4px 0px" type="button" onclick="buscar()"><span id="loader" class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>Buscar</button>
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
    //$('#bajas').addClass('active');
    document.title = 'Auditoría';
});

function buscar(){
        document.getElementById("tabla").innerHTML = "";
        $("#loader").css({"display":"inline-block"});   
        
        ajax = objetoAjax();

		ajax.open("POST", "auditoria_buscar_ajax.php", true);

		ajax.onreadystatechange = function() {

			if (ajax.readyState == 4){
                $("#loader").css({"display":"none"});   
                document.getElementById("tabla").innerHTML = (ajax.responseText);
            }
		}

		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

		ajax.send("&fecha_desde="+$("#fecha_desde").val()+"&fecha_hasta="+$("#fecha_hasta").val()+"&hora_desde="+$("#hora_desde").val()+"&hora_hasta="+$("#hora_hasta").val()+"&accion="+$("#accion option:selected").text()+"&usuario="+$("#usuario option:selected").text()+"&detalle="+$("#detalle").val());
}
function limpiar(){
    document.getElementById("form1").reset();
}

</script>

</body>

</html>