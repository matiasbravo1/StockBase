<?php
   include('../Headers/session.php');
   include('../Headers/principal.php');
?>

<div class="container-fluid">
    <div id="primero" class="row justify-content-center">
        <div class="col-12 col-sm-12 col-md-12 col-lg-11 col-xl-11">

            <form id="form1" class="text-center border border-light p-3 mt-3 bajas">
            
                <p class="h4 mb-3">Nueva Baja</p>
                <div class="form-row mb-2 justify-content-center">
                    <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                        <small>Id Artículo<img src="../Imagenes/dobleclick.png" height="15px" class="ml-1"><img src="../Imagenes/barcode.png" height="15px" class="ml-1"></small>
                        <input type="text" id="id_alta_buscar" class="form-control mx-auto">
                    </div>
                    <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2 d-none">
                        <input type="text" id="auxiliar" class="form-control mx-auto">
                    </div>
                </div>
            </form>
        
        </div>
    </div>
    <div id="segundo" class="row justify-content-center">
    
    </div>
    <div id="resultado" class="row justify-content-center">
    </div>
    <div id="tercero" class="row justify-content-center mt-3">
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
                        <button class="btn btn-dark-green mx-0 mb-0 btn-form" type="button" onclick="buscarArticulo()"><span id="loader4" class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true" style="display:none"></span>Buscar</button>
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
    $('#bajas').addClass('active');
    document.title = 'Nueva Baja';

    //Calcula Fecha de hoy local
    var d = new Date();
    var h = new Date(d.getTime() - 180 * 60 * 1000);
    var x = h.toISOString().substr(0, 10);
 
    mostrarBajas();
    //document.querySelector("#fecha").value = x;
});

$("#id_alta_buscar").dblclick(function(){
    $("#lista_articulos").html("");
    $("#botonModal").click();});
$('#id_alta_buscar').keyup(function(e){if(e.keyCode == 13){consultar();}});
//$('#id_alta_buscar').blur(function(){consultar()});





function consultar(){
    
    $("#resultado").hide();
    
    ajax = objetoAjax();

	ajax.open("POST", "consulta_id_ajax.php", true);

	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4){
		    if(this.responseText.includes("Error")){
		        alert(this.responseText);
		        $("#segundo").hide();
		    }else{
		        $("#segundo").show();
                $("#segundo").html(this.responseText);
                $("#cantidad").focus();
                $("#concepto").change(function(){
                    if($("#concepto").val()=="Vale"){
                        $("#id_vale").show();
                        $("#id_vale_label").show();
                    }else{
                        $("#id_vale").hide();
                        $("#id_vale_label").hide();
                    }
                })
		    }
        }
	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("&id_alta="+$("#id_alta_buscar").val());
	
}

function crearBaja(){
    if ($("#concepto").val() == "Vale" && $("#id_vale").val() == ""){
        alert("Número de vale es requerido.");
        return;
    }
    
    if ($("#cantidad").val() == ""){
        alert("Debe indicar la cantidad a dar de baja.");
        return;
    }
    
    var d = new Date();
    var h = new Date(d.getTime() - 180 * 60 * 1000);
    var fecha = h.toISOString().substr(0, 10);
    
    var hora = new Date().toLocaleTimeString();
    
    $("#loader").css({"display":"inline-block"});   
    
    ajax = objetoAjax();
    
    ajax.open("POST", "bajas_crear_ajax.php", true);
    
	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4){
            if (ajax.responseText.includes("Éxito.")){
                $("#loader").css({"display":"none"});
                $("#id_alta_buscar").focus();
                $("#id_alta_buscar").val("");
                $("#segundo").hide();
                $("#resultado").html('<span class="badge badge-success mt-3">La baja fue creada con éxito.</span>');
                $("#resultado").show();
                mostrarBajas();
            }else{
                $("#loader").css({"display":"none"});
                alert(this.responseText);
		    }
            
        }
	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("&fecha="+fecha+"&hora="+hora+"&id_vale="+$("#id_vale").val()+"&id_alta="+$("#id_alta").val()+"&cantidad="+$("#cantidad").val()+"&concepto="+$("#concepto").val());
    
}

function buscarArticulo(){
    $("#loader4").css({"display":"inline-block"});  
    document.getElementById("lista_articulos").innerHTML = "";
    
    var codigo = document.getElementById("codigo2").value;
    var descripcion = document.getElementById("descripcion2").value;
    var marca = document.getElementById("marca2").value;
    var familia = document.getElementById("familia2").value;
    
    ajax = objetoAjax();

	ajax.open("POST", "buscar_articulos_ajax.php", true);

	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4){
            //document.getElementById("loader_izq").style.display = "none";
            $("#loader4").css({"display":"none"});  
            document.getElementById("lista_articulos").innerHTML = (ajax.responseText);
            
        }
	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("&codigo="+codigo+"&descripcion="+descripcion+"&marca="+marca+"&familia="+familia);
    
}
function completar(id_alta){
    $("#id_alta_buscar").val(id_alta);
    $("#btn-modalClose").click();
    $("#lista_articulos").html("");
    consultar();
}

function mostrarBajas(){
    ajax = objetoAjax();

	ajax.open("POST", "bajas_mostrar_ajax.php", true);

	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4){
            
                $("#tercero").html(ajax.responseText);
            
        }
	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send();
}

function eliminarBaja(id_baja){
    $("#resultado").hide();
    if (confirm("Está seguro que desea eliminar esa baja? Se reincorporaría el artículo al stock.")){
    
        ajax = objetoAjax();

		ajax.open("POST", "bajas_eliminar_baja_ajax.php", true);

		ajax.onreadystatechange = function() {

			if (ajax.readyState == 4){
                if (ajax.responseText.includes("Éxito.")){
                    $("#resultado").html('<span class="badge badge-success mt-3">La baja fue eliminada con éxito.</span>');
                    $("#resultado").fadeIn();
                    mostrarBajas();
                } else {
                    $("#resultado").html('<span class="badge badge-danger mt-3">'+ajax.responseText+'</span>');
                    $("#resultado").fadeIn();
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