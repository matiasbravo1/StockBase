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
      <div class="collapse navbar-collapse justify-content-center" id="Nav2">
        <form class="text-center">
            <p class="h4 mb-2 mt-1">Buscar Remitos</p>
            <div class="form-row ">
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
                <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 text-center">
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
                
                <div class="col-12 col-sm-12 col-md-1 col-lg-1 col-xl-1 text-center mt-auto mb-0">
                    <button class="btn btn-primary mx-0 mb-0 btn-form" style="width:100%;padding:4px 0px" type="button" onclick="buscar()"><span id="loader" class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>Buscar</button>
                </div>
            </div>
        </form>      
      </div>
    </nav>

    <div class="row">
        <div id="lista" class="lista-altas" style="width:30%;float:left;height:440px;overflow:auto">
        </div>
        
        <div id="solapa" class="solapa" style="width:1%;height:440px;float:left;margin-right:0.5%">
            <span class="solapa-content">|</span>
        </div>  
        
        <div id="tabla" class="altas" style="width:68%;float:right;min-height:440px;padding:8px">
        </div>
    </div>

    
    
<!--MODAL------------------------->
<button id="botonModal" type="button" class="btn btn-primary d-none" data-toggle="modal" data-target="#Modal">
</button>
    <!-- Central Modal Small -->
    <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
      aria-hidden="true">
    
      <!-- Change class .modal-sm to change the size of the modal -->
      <div class="modal-dialog modal-lg" role="document">
    
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
    document.title = 'Buscar Remitos';
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
        ajax = objetoAjax();

		ajax.open("POST", "remitos_buscar_listar_ajax.php", true);

		ajax.onreadystatechange = function() {

			if (ajax.readyState == 4){
                $("#loader").css({"display":"none"});  
                document.getElementById("lista").innerHTML = (ajax.responseText);
            }
		}

		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

		ajax.send("&fecha_desde="+$("#fecha_desde").val()+"&fecha_hasta="+$("#fecha_hasta").val()+"&remito_nro="+$("#remito_nro").val()+"&proveedor="+$("#proveedor option:selected").text());
}
function abrirRemito(id_remito){
        document.getElementById("tabla").innerHTML = '<center><span id="loader2" style="display:inline-block" class="spinner-border spinner-border-sm mt-3" role="status" aria-hidden="true"></span></center>';
        
        $("#lista>div>table>tbody>tr").removeClass("seleccionado");
        $("#renglon"+id_remito).addClass("seleccionado");
        
        ajax = objetoAjax();

		ajax.open("POST", "remitos_buscar_abrir_ajax.php", true);

		ajax.onreadystatechange = function() {

			if (ajax.readyState == 4){
                document.getElementById("tabla").innerHTML = (ajax.responseText);
            }
		}

		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

		ajax.send("&id_remito="+id_remito);

}

function editarRemito(id_remito){
    var win = window.open("../Altas/remitos_editar.php?id_remito=" + id_remito, '_blank');
    win.focus();
}

function imprimirEtiquetas(id_alta){
    var cant = prompt("Cuantas etiquetas desea imprimir?", "1");
    
    if (cant == null || cant == "") {
      return;
    } else {
      var win = window.open("../Altas/imprimir_etiqueta.php?id_alta=" +id_alta+"&cantidad="+cant , '_blank');
      win.focus();
    }
}
function eliminarRemito(id_remito){
        if (confirm("Está seguro que desea eliminar el remito?")){
    
            ajax = objetoAjax();
    
    		ajax.open("POST", "remitos_buscar_eliminar_ajax.php", true);
    
    		ajax.onreadystatechange = function() {
    
    			if (ajax.readyState == 4){
                    document.getElementById("tabla").innerHTML = (ajax.responseText);
                }
    		}
    
    		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    
    		ajax.send("&id_remito="+id_remito);
    		
        }
}
function Close(){
    document.getElementById("articulos").style.display = "none";
}

</script>

</body>

</html>