<?php
   include('../Headers/session.php');
   include('../Headers/principal.php');
?>

<div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-dark bajas buscador">
    
      <!-- Collapse button -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#Nav2"
        aria-controls="Nav2" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    
      <!-- Collapsible content -->
      <div class="collapse navbar-collapse  justify-content-center" id="Nav2">
        <form id="form1" class="text-center">
            <p class="h4 mb-2 mt-1">Buscar Bajas</p>
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
                    <small>Sección / Solicita</small>
                    <select id="seccion" class="browser-default custom-select">
                        <option value=""></option>
                        <?php
                            $sql = "SELECT seccion FROM listas WHERE `seccion` NOT LIKE ''";
                            $result = $conn->query($sql);
                            while($row = $result->fetch_assoc()) {
                            	echo '<option value="' . $row["seccion"] . '">' . $row["seccion"] . '</option>';
                            }
                        ?>
                    </select>
                    <select id="usuario" class="browser-default custom-select">
                        <option value=""></option>
                        <?php
                            $sql = "SELECT nombre, apellido, id_usuario FROM usuarios";
                            $result = $conn->query($sql);
                            while($row = $result->fetch_assoc()) {
                                if($row["id_usuario"] == "19"){continue;}
                            	echo '<option value="' . $row["nombre"] . " " . $row["apellido"] . '">' .  $row["nombre"] . " " . $row["apellido"]  . '</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                    <small>Id Artículo</small>
                    <input class="form-control" type="text" id="id_art">
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                    <small>Concepto</small>
                    <select id="concepto" class="browser-default custom-select">
                        <option value="" selected></option>
                        <option value="Vale">Vale</option>
                        <option value="Vencimiento">Vencimiento</option>
                        <option value="Ajuste_Negativo">Ajuste Negativo</option>
                    </select>
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                    <small>Vales</small>
                    <input class="form-control" type="text" id="vale_desde" placeholder="Desde">
                    <input class="form-control" type="text" id="vale_hasta" placeholder="Hasta">
                </div>
            </div>
            <div class="form-row mt-3">
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                    <small>Código</small>
                    <input class="form-control" type="text" id="codigo">
                </div>
                <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4 text-center">
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
                <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4 text-center">
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
            </div>
            <div class="form-row mt-3">
                <div class="col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2 text-center mb-auto mt-auto">
                    <button class="btn btn-danger btn-lg mx-0 mb-0" style="color:white;width:100%;padding:4px 0px" type="button" onclick="limpiar()">Limpiar</button>
                </div>
                <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 offset-xl-2 text-center mb-auto mt-auto">
                    <button class="btn btn-danger btn-lg mx-0 mb-0" style="color:white;width:100%;padding:4px 0px" type="button" onclick="buscar()"><span id="loader" class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>Buscar</button>
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
    $('#bajas').addClass('active');
    document.title = 'Bajas';
});

function buscar(){
        $("#loader").css({"display":"inline-block"});   
        document.getElementById("tabla").innerHTML = "";
        
        ajax = objetoAjax();

		ajax.open("POST", "bajas_buscar_ajax.php", true);

		ajax.onreadystatechange = function() {

			if (ajax.readyState == 4){
                $("#loader").css({"display":"none"});   
                document.getElementById("tabla").innerHTML = (ajax.responseText);
            }
		}

		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

		ajax.send("&fecha_desde="+$("#fecha_desde").val()+"&fecha_hasta="+$("#fecha_hasta").val()+"&hora_desde="+$("#hora_desde").val()+"&hora_hasta="+$("#hora_hasta").val()+"&vale_desde="+$("#vale_desde").val()+"&vale_hasta="+$("#vale_hasta").val()+"&seccion="+$("#seccion option:selected").text()+"&concepto="+$("#concepto option:selected").text()+"&usuario="+$("#usuario option:selected").text()+"&codigo="+$("#codigo").val()+"&descripcion="+$("#descripcion").val()+"&marca="+$("#marca option:selected").text()+"&familia="+$("#familia option:selected").text()+"&id_art="+$("#id_art").val());
}
function limpiar(){
    document.getElementById("form1").reset();
}

function eliminarBaja(id_baja){

    if (confirm("Está seguro que desea eliminar esa baja? Se reincorporaría el artículo al stock.")){
    
        ajax = objetoAjax();

		ajax.open("POST", "bajas_eliminar_baja_ajax.php", true);

		ajax.onreadystatechange = function() {

			if (ajax.readyState == 4){
                if (ajax.responseText.includes("Éxito.")){
                    alert("La baja fue eliminada con éxito.");
                    buscar();
                } else {
                    alert(ajax.responseText);
                }
            }
		}

		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

		ajax.send("&id_baja="+id_baja);
    }
}
</script>

</body>

</html>