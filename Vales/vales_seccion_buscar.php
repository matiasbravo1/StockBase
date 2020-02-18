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
        exit('<center><p style="color:red" class="mt-3">Su usuario no tiene secciones habilitadas para buscar vales.</p></center>');
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
        <form class="text-center">
            <p class="h4 mb-2 mt-1">Buscar Vales</p>
            <div class="form-row ">
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
                <div class="col-6 col-sm-6 col-md-1 col-lg-1 col-xl-1 text-center">
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
                    <small>Descarga / Estado</small>
                    <select id="descarga" class="browser-default custom-select">
                        <option value="" selected></option>
                        <option value="No">No</option>
                        <option value="NoParcial">No+Parcial</option>
                        <option value="Parcial">Parcial</option>
                        <option value="Total">Total</option>
                    </select>
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
                <div class="col-12 col-sm-12 col-md-1 col-lg-1 col-xl-1 text-center mb-auto mt-auto">
                    <button class="btn mx-0 mb-0" style="color:white;background-color:#e69100;width:100%;padding:4px 0px" type="button" onclick="buscar()"><span id="loader" class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>Buscar</button>
                </div>
            </div>
        </form>      
      </div>
    </nav>

    <div class="row">
        <div id="lista" class="lista-vales" style="width:30%;float:left;height:430px;overflow:auto">
        </div>
        
        <div id="solapa" class="solapa" style="width:1%;height:430px;float:left;margin-right:0.5%">
            <span class="solapa-content">|</span>
        </div>  
        
        <div id="tabla" class="vales" style="width:68%;float:right;min-height:430px;padding:8px">
        </div>
    </div>



<!--MODAL------------------------->
<button id="botonModal" type="button" class="btn btn-primary d-none" data-toggle="modal" data-target="#Modal">
</button>
    <!-- Central Modal Small -->
    <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
      aria-hidden="true">
    
      <!-- Change class .modal-sm to change the size of the modal -->
      <div class="modal-dialog modal-md" role="document">
    
        <div class="modal-content">
          <!--div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button></div-->
          <div class="modal-body text-center">
              <button type="button" class="btn btn-md btn-primary btn-block" onclick="imprimirVales();">Vales y artículos con descarga incompleta y stock disponible</button>
              <button type="button" class="btn btn-md btn-primary btn-block mt-2" onclick="imprimirVales();" disabled>Vales y artículos completos sin stock</button>
              <button type="button" class="btn btn-md btn-primary btn-block mt-2" onclick="imprimirVales();" disabled>Vales, artículos y bajas completas</button>
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
    document.title = 'Buscar Vales';
});



$("#solapa").click(function(){
    if ($("#solapa").hasClass("colapsado")){
        $("#lista").animate({width: "30%"}, 500)
        $("#solapa").animate({width: "1%"}, 500)
        $("#tabla").animate({width: "68%"}, 100)
        $(this).removeClass("colapsado")
    } else {
        $("#lista").animate({width: "0%"}, 500)
        $("#solapa").animate({width: "2%"}, 500)
        $("#tabla").animate({width: "97%"}, 500)
        $(this).addClass("colapsado")
    }
    
})
/*function editar(id){
    var win = window.open("../Stock/stock_editar.php?id=" + id, '_blank');
    win.focus();
}*/

function buscar(){
        $("#loader").css({"display":"inline-block"});  
        document.getElementById("lista").innerHTML = "";
        lista = [];
        
        ajax = objetoAjax();

		ajax.open("POST", "vales_buscar_listar_ajax.php", true);

		ajax.onreadystatechange = function() {

			if (ajax.readyState == 4){
                $("#loader").css({"display":"none"});  
                document.getElementById("lista").innerHTML = (ajax.responseText);
            }
		}

		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

		ajax.send("&fecha_desde="+$("#fecha_desde").val()+"&hora_desde="+$("#hora_desde").val()+"&fecha_hasta="+$("#fecha_hasta").val()+"&hora_hasta="+$("#hora_hasta").val()+"&vale_desde="+$("#vale_desde").val()+"&vale_hasta="+$("#vale_hasta").val()+"&seccion="+$("#seccion").val()+"&descarga="+$("#descarga option:selected").val()+"&estado="+$("#estado option:selected").val()+"&usuario="+$("#usuario option:selected").text());
}
function abrirVale(id_vale){
        document.getElementById("tabla").innerHTML = '<center><span id="loader2" style="display:inline-block" class="spinner-border spinner-border-sm mt-3" role="status" aria-hidden="true"></span></center>';
    
        $("#lista>div>table>tbody>tr").removeClass("seleccionado_vales");
        $("#renglon"+id_vale).addClass("seleccionado_vales");
        
        ajax = objetoAjax();

		ajax.open("POST", "vales_buscar_abrir_ajax.php", true);

		ajax.onreadystatechange = function() {

			if (ajax.readyState == 4){
                document.getElementById("tabla").innerHTML = (ajax.responseText);
                /*$("#btn-editarEstado").click(function(e) {
                      $(".popup").css({left: e.pageX});
                      $(".popup").css({top: e.pageY});
                      $(".popup").show();
                });*/
            }
		}

		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

		ajax.send("&id_vale="+id_vale);

}

/*function editarEstado(id_vale){
        ajax = objetoAjax();

		ajax.open("POST", "vales_buscar_editar_estado_ajax.php", true);

		ajax.onreadystatechange = function() {

			if (ajax.readyState == 4){
                if (ajax.responseText.includes("Error")){
                    alert(ajax.responseText);
                }else{
                    document.getElementById("span-estado").innerHTML = (ajax.responseText);
                    $(".popup").hide();
                }
            }
		}

		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

		ajax.send("&id_vale="+id_vale+"&estado="+$("#estado-popup option:selected").val());

}*/

function soloStock(codigo, id_vale){
        document.getElementById("mostrar-stock").innerHTML = '<center><span id="loader2" style="color:green;display:inline-block" class="spinner-border spinner-border-sm mt-3" role="status" aria-hidden="true"></span></center>';
        
        ajax = objetoAjax();

		ajax.open("POST", "vales_buscar_solo_stock_ajax.php", true);

		ajax.onreadystatechange = function() {

			if (ajax.readyState == 4){
                document.getElementById("mostrar-stock").innerHTML = ajax.responseText;
                $("#mostrar-stock").show();
            }
		}

		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

		ajax.send("&codigo="+codigo+"&id_vale="+id_vale);
    
}
function ocultarStock(){
    $("#mostrar-stock").hide();
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
                    abrirVale(id_vale);    
                } else {
                    alert(ajax.responseText);
                }
            }
		}

		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

		ajax.send("&id_alta="+id_alta+"&id_vale="+id_vale+"&cantidad="+cant+"&fecha="+fecha+"&hora="+hora);
    }
}

function eliminarBaja(id_baja, id_vale){
    if (confirm("Está seguro que desea eliminar esa baja? Se reincorporaría el artículo al stock.")){
    
        ajax = objetoAjax();

		ajax.open("POST", "vales_buscar_eliminar_baja_ajax.php", true);

		ajax.onreadystatechange = function() {

			if (ajax.readyState == 4){
                if (ajax.responseText.includes("Éxito.")){
                    abrirVale(id_vale);    
                } else {
                    alert(ajax.responseText);
                }
            }
		}

		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

		ajax.send("&id_baja="+id_baja);
    }
}

//FUNCION IMPRIMIR
var lista = [];

function add_remove(id_vale){
    if(lista.includes(id_vale)){
        for( var i = 0; i < lista.length; i++){ 
            if ( lista[i] === id_vale) {
                lista.splice(i, 1); 
                i--;
            }
        }
    }else{
        lista.push(id_vale);
    }
    
}
function imprimirModal(){
    if(lista.length == 0){
        //$('input[name=imp_vale]').prop('checked', true);
        $('input[name=imp_vale]').click();
    }else{
        $("#botonModal").click();
    }
}

function imprimirVales(){
        $("#botonModal").click();
        var win = window.open("../Vales/vales_imprimir.php?lista=" + lista.toString(), '_blank');
        win.focus();
}*/
</script>

</body>

</html>