<?php
   include('../Headers/session.php');
   include('../Headers/seccion.php');
   
  $sql = "SELECT nombre, apellido, id_usuario FROM usuarios WHERE `usuario` = '" . $_SESSION['login_user'] . "'";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 0) {
        exit('El usuario no existe.');
    }
    
    $row = $result->fetch_assoc();

    $sql2 = "SELECT * FROM secciones_usuario WHERE `id_usuario` = '" . $row['id_usuario'] . "'";
    $result2 = $conn->query($sql2);
    if ($result2->num_rows == 0) {
        exit('<center><p style="color:red" class="mt-3">Su usuario no tiene secciones habilitadas para buscar artículos de vales.</p></center>');
    }
?>

<div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-dark vales buscador">
    
      <!-- Collapse button -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#Nav2"
        aria-controls="Nav2" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    
      <!-- Collapsible content -->
      <div class="collapse navbar-collapse justify-content-center" id="Nav2">
        <form id="form1" class="text-center">
            <p class="h4 mb-2 mt-1">Buscar Artículos de Vales</p>
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
                    <small>Vales</small>
                    <input class="form-control" type="text" id="vale_desde" placeholder="Desde">
                    <input class="form-control" type="text" id="vale_hasta" placeholder="Hasta">
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                    <small>Sección</small>
                    <select id="seccion" class="browser-default custom-select">
                        <?php 
                            while($row2 = $result2->fetch_assoc()) {
                            	echo '<option value="' . $row2["seccion"] . '">' .  $row2["seccion"] . '</option>';
                            }
                        ?>
                        </select>
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                    <small>Estado</small>
                    <select id="estado" class="browser-default custom-select">
                        <option value="" selected></option>
                        <option value="Normal">Normal</option>
                        <option value="Finalizado">Finalizado</option>
                        <option value="NormalFinalizado">Normal+Finalizado</option>
                        <option value="Cancelado">Cancelado</option>
                    </select>
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                    <small>Solicitado por</small>
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
            </div>
            <div class="form-row mt-2">
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
                    <small>Descarga</small>
                    <select id="descarga" class="browser-default custom-select">
                        <option value="" selected></option>
                        <option value="No">No</option>
                        <option value="NoParcial">No+Parcial</option>
                        <option value="Parcial">Parcial</option>
                        <option value="Total">Total</option>
                    </select>
                </div>
                <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                    <small>Stock</small>
                    <select id="stock2" class="browser-default custom-select">
                        <option value=""></option>
                        <option value="igualcero">Igual a cero</option>
                        <option value="mayorcero">Mayor a cero</option>
                    </select>
                </div>
                
                
            </div>
            <div class="form-row mt-2">
                <div class="col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2 text-center mb-auto mt-auto">
                    <button class="btn btn-lg mx-0 mb-0" style="background-color:#e69100;color:white;width:100%;padding:4px 0px" type="button" onclick="limpiar()">Limpiar</button>
                </div>
                <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 offset-xl-2 text-center mb-auto mt-auto">
                    <button class="btn btn-lg mx-0 mb-0" style="color:white;background-color:#e69100;width:100%;padding:4px 0px" type="button" onclick="buscar()"><span id="loader" class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>Buscar</button>
                </div>
            </div>
        </form>      
      </div>
    </nav>

    <div class="row justify-content-center mx-0">
        <div id="tabla" class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 vales" style="min-height:430px;">
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
            <div id="contenido"></div>
              <button type="button" class="btn btn-sm btn-secondary mt-4" data-dismiss="modal">Cerrar</button>              
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
    $('#vales').addClass('active');
    document.title = 'Buscar Artículos de Vales';
});

function limpiar(){
    document.getElementById("form1").reset();
}
function buscar(){
        document.getElementById("tabla").innerHTML = "";
        $("#loader").css({"display":"inline-block"});  
        
        ajax = objetoAjax();

		ajax.open("POST", "vales_buscar_articulo_ajax.php", true);

		ajax.onreadystatechange = function() {

			if (ajax.readyState == 4){
                //document.getElementById("loader_izq").style.display = "none";
                document.getElementById("tabla").innerHTML = (ajax.responseText);
                $("#loader").css({"display":"none"});  
            }
		}

		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

		ajax.send("&fecha_desde="+$("#fecha_desde").val()+"&hora_desde="+$("#hora_desde").val()+"&fecha_hasta="+$("#fecha_hasta").val()+"&hora_hasta="+$("#hora_hasta").val()+"&vale_desde="+$("#vale_desde").val()+"&vale_hasta="+$("#vale_hasta").val()+"&seccion="+$("#seccion").val()+"&descarga="+$("#descarga option:selected").val()+"&estado="+$("#estado option:selected").val()+"&usuario="+$("#usuario option:selected").text()+"&codigo="+$("#codigo").val()+"&descripcion="+$("#descripcion").val()+"&marca="+$("#marca").val()+"&familia="+$("#familia").val()+"&stock="+$("#stock2 option:selected").val());
}

function soloStock(codigo, id_vale){
        $("#botonModal").click();        
        document.getElementById("contenido").innerHTML = '<center><span id="loader" style="display:inline-block;color:green;" class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span></center>';
        
        ajax = objetoAjax();

		ajax.open("POST", "vales_buscar_articulo_solo_stock_ajax.php", true);

		ajax.onreadystatechange = function() {

			if (ajax.readyState == 4){
                document.getElementById("contenido").innerHTML = ajax.responseText;
            }
		}

		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

		ajax.send("&codigo="+codigo+"&id_vale="+id_vale);
    
}

/*function agregarBaja(id_vale, id_alta){
    var d = new Date();
    var h = new Date(d.getTime() - 180 * 60 * 1000);
    var fecha = h.toISOString().substr(0, 10);
    
    var hora = new Date().toLocaleTimeString();
    
    var cant = prompt("Cuantas unidades de este artículo desea descargar?", "1");
    
    if (cant == null || cant == "") {
      return;
    } else {
        ajax = objetoAjax();

		ajax.open("POST", "vales_buscar_baja_ajax.php", true);

		ajax.onreadystatechange = function() {

			if (ajax.readyState == 4){
                if (ajax.responseText.includes("Éxito.")){
                    $("#botonModal").click();
                    buscar();
                } else {
                    alert(ajax.responseText);
                }
            }
		}

		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

		ajax.send("&id_alta="+id_alta+"&id_vale="+id_vale+"&cantidad="+cant+"&fecha="+fecha+"&hora="+hora);
    }
}*/

function entregados(codigo, id_vale){
        $("#botonModal").click();        
        document.getElementById("contenido").innerHTML = '<center><span id="loader" style="display:inline-block;color:red;" class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span></center>';
        
        ajax = objetoAjax();

		ajax.open("POST", "vales_buscar_articulo_entregados_ajax.php", true);

		ajax.onreadystatechange = function() {

			if (ajax.readyState == 4){
                document.getElementById("contenido").innerHTML = ajax.responseText;
            }
		}

		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

		ajax.send("&codigo="+codigo+"&id_vale="+id_vale);
    
}

/*function eliminarBaja(id_baja){
    if (confirm("Está seguro que desea eliminar esa baja? Se reincorporaría el artículo al stock.")){
    
        ajax = objetoAjax();

		ajax.open("POST", "vales_buscar_eliminar_baja_ajax.php", true);

		ajax.onreadystatechange = function() {

			if (ajax.readyState == 4){
                if (ajax.responseText.includes("Éxito.")){
                    $("#botonModal").click();
                    buscar();
                } else {
                    alert(ajax.responseText);
                }
            }
		}

		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

		ajax.send("&id_baja="+id_baja);
    }
}*/
</script>

</body>

</html>