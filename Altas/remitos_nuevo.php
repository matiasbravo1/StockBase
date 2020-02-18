<?php
   include('../Headers/session.php');
   include('../Headers/principal.php');
?>
<div class="container-fluid">
    <div id="primero" class="row justify-content-center">
        <div class="col-12 col-sm-12 col-md-12 col-lg-11 col-xl-11">

            <form id="form1" class="text-center border border-light p-3 mt-3 altas">
            
                <p class="h4 mb-3">Nuevo Remito</p>
                
                <div class="form-row mb-2 justify-content-center">
                    <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                        <small>Id Remito</small>
                        <input type="text" id="id_remito" class="form-control mx-auto" disabled>
                    </div>
                    <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-2">
                        <small>Fecha</small>
                        <input id="fecha" type="date" class="form-control mx-auto">
                    </div>
                    <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                        <small>Remito Nro.<img src="../Imagenes/dobleclick.png" height="15px" class="ml-1"></small>
                        <input type="text" id="remito_nro" class="form-control mx-auto">
                    </div>
                    <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
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
                    <div class="col-12 col-sm-12 col-md-2 col-lg-1 col-xl-1 mt-auto mb-0">
                        <button id="btn-crear" class="btn btn-primary mx-0 mb-0 btn-form" type="button" onclick="crearRemito()"><span id="loader" class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>Crear</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div id="segundo" class="row justify-content-center" style="display:none">
        <div class="col-12 col-sm-12 col-md-12 col-lg-11 col-xl-11">

            <form id="form2" class="text-center border border-light p-3 mt-3 altas">
                <div class="form-row mb-2 justify-content-center">
                    <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                        <small>Código<img src="../Imagenes/dobleclick.png" height="15px" class="ml-1"></small>
                        <input type="text" id="codigo_b" class="form-control mx-auto">
                    </div>
                    <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                        <small>Código del Proveedor<img src="../Imagenes/dobleclick.png" height="15px" class="ml-1"></small>
                        <input type="text" id="codigo_prov_b" class="form-control mx-auto">
                    </div>
                </div>
                <div class="form-row mb-2 justify-content-center">
                    <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                        <small>Código</small>
                        <input type="text" id="codigo" class="form-control mx-auto" disabled>
                    </div>
                    <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                        <small>Código del Proveedor</small>
                        <input type="text" id="codigo_prov" class="form-control mx-auto" disabled>
                    </div>
                    <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">
                        <small>Descripción</small>
                        <input id="descripcion" type="text" class="form-control mx-auto" disabled>
                    </div>
                    <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                        <small>Marca</small>
                        <input type="text" id="marca" class="form-control mx-auto" disabled>
                    </div>
                    <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                        <small>Familia</small>
                        <input type="text" id="familia" class="form-control mx-auto" disabled>
                    </div>
                </div>
                
                <div class="form-row mb-2 justify-content-center">
                    <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                        <small>Cantidad</small>
                        <input type="number" id="cantidad" class="form-control mx-auto" min="1" value="1">
                    </div>
                    <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                        <small>Lote</small>
                        <input id="lote" type="text" class="form-control mx-auto" maxlength="50">
                    </div>
                    <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                        <small>Fecha de vencimiento</small>
                        <input id="fecha_vto" type="date" class="form-control mx-auto">
                    </div>
                    <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                        <small>Etiquetas<!--img src="../Imagenes/dobleclick.png" height="15px" class="ml-1"--></small>
                        <input type="number" id="etiquetas" class="form-control mx-auto" min="0" value="0">
                    </div>
                    <div class="col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2 mt-auto mb-0">
                        <button class="btn btn-primary mx-0 mb-0 btn-form" type="button" onclick="agregar()">
                            <span id="loader3" class="spinner-border spinner-border-sm mr-1" style="display:none"  role="status" aria-hidden="true"></span>Agregar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div id="tabla"></div>
    
    <div id="botonera" class="row justify-content-center my-3" style="display:none">
        <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
            <button id="btn-guardarRemito" class="btn btn-primary btn-block" type="button" onclick="guardarRemito()"><span id="loader2" class="spinner-border spinner-border-sm mr-1" style="display:none" role="status" aria-hidden="true"></span>Guardar</button>
        </div>
        <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
            <button class="btn btn-warning btn-block" type="button" onclick="cancelarRemito()"><span id="loader4" class="spinner-border spinner-border-sm mr-1" style="display:none"  role="status" aria-hidden="true"></span>Cancelar</button>
        </div>
    </div>
    
    <div id="resultado" class="row justify-content-center"></div>
    
    <div id="botonera2" class="row justify-content-center my-3 d-none">
        <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
            <button id="btn-Imprimir" class="btn btn-primary btn-block" type="button" onclick="Imprimir()">Imprimir etiquetas</button>
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
                        <input class="form-control" type="text" id="codigo2">
                    </div>
                    <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 text-center">
                        <small>Código del Proveedor</small>
                        <input class="form-control" type="text" id="codigo_prov2">
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
                        <button class="btn btn-dark-green mx-0 mb-0 btn-form" type="button" onclick="buscarArticulo()"><span id="loader10" class="spinner-border spinner-border-sm mr-1" style="display:none"  role="status" aria-hidden="true"></span>Buscar</button>
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
    $('#altas').addClass('active');
    document.title = 'Nuevo Remito';
    
     //Calcula Fecha de hoy local
    var d = new Date();
    var h = new Date(d.getTime() - 180 * 60 * 1000);
    var x = h.toISOString().substr(0, 10);
    $("#fecha").val(x);
    
});

$("#remito_nro").dblclick(function(){
    var proveedor = document.getElementById("proveedor").value;
    
    if (proveedor != "Ajuste Positivo" && proveedor != "Farmacia Departamento" && proveedor != "Pañol General"){
        alert("Solo calcula número correlativo para los ítems: Ajuste Positivo, Farmacia Departamento y Pañol General.");
        return;
    }
    
    ajax = objetoAjax();

	ajax.open("POST", "remitos_nuevo_correlativo_ajax.php", true);

	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4){

            $("#remito_nro").val(this.responseText);
           
        }
	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("&proveedor="+proveedor);
	
});

$("#codigo_b").dblclick(function(){$("#botonModal").click();});
$("#codigo_prov_b").dblclick(function(){$("#botonModal").click();});

$('#codigo_b').keyup(function(e){if(e.keyCode == 13){consultar();}});
//$('#codigo').blur(function(){consultar()});

$('#codigo_prov_b').keyup(function(e){if(e.keyCode == 13){consultar_prov();}});
//$('#codigo_prov').blur(function(){consultar_prov()});

/*$("#etiquetas").dblclick(function(){
    $(this).val($("#cantidad").val());
});*/

$("#cantidad").blur(function(){
    if($("#cantidad").val()==""){
        $("#cantidad").val("1")
    }
    $("#etiquetas").val($("#cantidad").val());
});

function consultar(){
    ajax = objetoAjax();

	ajax.open("POST", "consulta_codigo_ajax.php", true);

	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4){
		    if(this.responseText == "No existe código."){
		        $("#codigo").val("");
		        $("#codigo_prov").val("");
		        $("#descripcion").val("");
		        $("#marca").val("");
		        $("#familia").val("");
		    }else{
    		    myObj = JSON.parse(this.responseText);
                $("#codigo").val(myObj.codigo);
                $("#codigo_prov").val(myObj.codigo_prov);
                $("#descripcion").val(myObj.descripcion);
                $("#marca").val(myObj.marca);
    		    $("#familia").val(myObj.familia);
    		    $("#cantidad").focus();
		    }
        }
	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("&codigo="+$("#codigo_b").val());
}
function consultar_prov(){
    ajax = objetoAjax();

	ajax.open("POST", "consulta_codigo_prov_ajax.php", true);

	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4){
		    if(this.responseText == "No existe código."){
		        $("#codigo").val("");
		        $("#codigo_prov").val("");
		        $("#descripcion").val("");
		        $("#marca").val("");
		        $("#familia").val("");
		    }else if(this.responseText == "Existe más de un artículo con este código de proveedor. Utilice la opción de búsqueda haciendo doble click."){
		        alert(this.responseText);
		        
		    }else{  
    		    myObj = JSON.parse(this.responseText);
                $("#codigo").val(myObj.codigo);
                $("#codigo_prov").val(myObj.codigo_prov);
                $("#descripcion").val(myObj.descripcion);
                $("#marca").val(myObj.marca);
    		    $("#familia").val(myObj.familia);
    		    $("#cantidad").focus();
		    }
        }
	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("&codigo_prov="+$("#codigo_prov_b").val());
}
function crearRemito(){

    var fecha = document.getElementById("fecha").value;
    var remito_nro = document.getElementById("remito_nro").value;
    var proveedor = document.getElementById("proveedor").value;

    if (fecha == ''){
        alert("Fecha es requerida.");
        return;
    }
    
    $("#loader").css({"display":"inline-block"});   
    
    ajax = objetoAjax();

	ajax.open("POST", "remitos_nuevo_crear_ajax.php", true);

	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4){
            document.getElementById("id_remito").value = (ajax.responseText);
            document.getElementById("fecha").disabled = true;
            document.getElementById("remito_nro").disabled = true;
            document.getElementById("proveedor").disabled = true;
            document.getElementById("btn-crear").disabled = true;
            $("#loader").css({"display":"none"});
            $("#segundo").fadeIn();
            $("#botonera").fadeIn();
            $("#codigo").focus();

            document.getElementById("btn-guardarRemito").disabled = true;
            
        }
	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("&fecha="+fecha+"&remito_nro="+remito_nro+"&proveedor="+proveedor);
    
}

function buscarArticulo(){
    document.getElementById("lista_articulos").innerHTML = "";
    $("#loader10").css({"display":"inline-block"});   
    var codigo = document.getElementById("codigo2").value;
    var codigo_prov = document.getElementById("codigo_prov2").value;
    var descripcion = document.getElementById("descripcion2").value;
    var marca = document.getElementById("marca2").value;
    var familia = document.getElementById("familia2").value;
    
    ajax = objetoAjax();

	ajax.open("POST", "buscar_articulos_ajax.php", true);

	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4){
            $("#loader10").css({"display":"none"});   
            document.getElementById("lista_articulos").innerHTML = (ajax.responseText);
            
        }
	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("&codigo="+codigo+"&codigo_prov="+codigo_prov+"&descripcion="+descripcion+"&marca="+marca+"&familia="+familia);
    
}
function completar(codigo, codigo_prov, descripcion, marca, familia){
    $("#codigo").val(codigo);
    $("#codigo_prov").val(codigo_prov);
    document.getElementById("lista_articulos").innerHTML = "";
    $("#descripcion").val(descripcion);
    $("#marca").val(marca);
    $("#familia").val(familia);
    $("#btn-modalClose").click();
    $("#cantidad").focus();
    document.getElementById("lista_articulos").innerHTML = "";
    document.getElementById("form-buscar").reset();
}

function agregar(){
    
    if ($("#descripcion").val() == ''){
        alert("Descripción es requerida.")
        return
    }
    
    $("#loader3").css({"display":"inline-block"});
        
    ajax = objetoAjax();

	ajax.open("POST", "remitos_nuevo_agregar_ajax.php", true);

	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4){
            document.getElementById("tabla").innerHTML = (ajax.responseText);
            document.getElementById("form2").reset();
            $("#codigo").focus();
            if (ajax.responseText.includes("icon_delete.png")){
                document.getElementById("btn-guardarRemito").disabled = false;
            } else {
                document.getElementById("btn-guardarRemito").disabled = true;
            }
            $("#loader3").css({"display":"none"});
        }
	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("&id_remito="+$("#id_remito").val()+"&codigo="+$("#codigo").val()+"&descripcion="+$("#descripcion").val()+"&marca="+$("#marca").val()+"&familia="+$("#familia").val()+"&cantidad="+$("#cantidad").val()+"&fecha_vto="+$("#fecha_vto").val()+"&lote="+$("#lote").val()+"&etiquetas="+$("#etiquetas").val());
    
}
function eliminarArticulo(id_alta){
    var id_remito = document.getElementById("id_remito").value;
    
    ajax = objetoAjax();

	ajax.open("POST", "remitos_nuevo_eliminar_ajax.php", true);

	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4){

            if (ajax.responseText == "Sin articulos."){
                document.getElementById("tabla").innerHTML = "";
            } else {
            document.getElementById("tabla").innerHTML = (ajax.responseText);
            }
            if (ajax.responseText.includes("icon_delete.png")){
                document.getElementById("btn-guardarRemito").disabled = false;
            } else {
                document.getElementById("btn-guardarRemito").disabled = true;
            }
        }
	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("&id_alta="+id_alta+"&id_remito="+id_remito);
}


function guardarRemito(){
    
    $("#loader2").css({"display":"inline-block"});
    
    var id_remito = document.getElementById("id_remito").value;
    
    ajax = objetoAjax();

	ajax.open("POST", "remitos_nuevo_guardar_ajax.php", true);

	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4){
            if (ajax.responseText=="Éxito."){
                document.getElementById("tabla").style.display = "none";
                document.getElementById("primero").style.display = "none";
                document.getElementById("segundo").style.display = "none";
                document.getElementById("botonera").style.display = "none";
                $("#botonera2").removeClass("d-none");
                $("#resultado").html('<span class="badge badge-success mt-3">El remito fue creado con éxito.</span>');
            } else {
                $("#loader").css({"display":"none"});
                $("#resultado").html('<span class="badge badge-danger mt-3">Error: '+ajax.responseText+'</span>');
            }

        }
	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("&id_remito="+id_remito);
}

function Imprimir(){
    var win = window.open("../Altas/imprimir_etiquetas.php?id_remito=" + $("#id_remito").val(), '_blank');
    win.focus();
}

function cancelarRemito(){
    if (confirm("Seguro que desea CANCELAR el remito?")){
        
        $("#loader4").css({"display":"inline-block"});
        
        var id_remito = document.getElementById("id_remito").value;
        
        ajax = objetoAjax();
    
    	ajax.open("POST", "remitos_nuevo_cancelar_ajax.php", true);
    
    	ajax.onreadystatechange = function() {
    
    		if (ajax.readyState == 4){
                if (ajax.responseText=="Éxito."){
                    document.getElementById("tabla").style.display = "none";
                    document.getElementById("primero").style.display = "none";
                    document.getElementById("segundo").style.display = "none";
                    document.getElementById("botonera").style.display = "none";
                    $("#resultado").html('<span class="badge badge-success mt-3">El remito fue cancelado con éxito.</span>');
                } else {
                    $("#resultado").html('<span class="badge badge-danger mt-3">Error: '+ajax.responseText+'</span>');
                }
                    $("#loader4").css({"display":"none"});
            }
    	}
    
    	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    
    	ajax.send("&id_remito="+id_remito);
    }
}

</script>

</body>

</html>