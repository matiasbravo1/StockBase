<?php
   include('../Headers/session.php');
   
   if($_SESSION["tipo"] == "Sección"){
       include('../Headers/seccion.php');
   }else{
       include('../Headers/principal.php');
   }
   
    $sql = "SELECT nombre, apellido, id_usuario FROM usuarios WHERE `usuario` = '" . $_SESSION['login_user'] . "'";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 0) {
        exit('El usuario no existe.');
    }
    
    $row = $result->fetch_assoc();

    $sql2 = "SELECT * FROM secciones_usuario WHERE `id_usuario` = '" . $row['id_usuario'] . "'";
    $result2 = $conn->query($sql2);
    if ($result2->num_rows == 0) {
        exit('<center><p style="color:red" class="mt-3">Su usuario no tiene secciones habilitadas para crear vales.</p></center>');
    }
    
    
?>
<div class="container-fluid">
    <div id="primero" class="row justify-content-center">
        <div class="col-12 col-sm-12 col-md-12 col-lg-11 col-xl-11">

            <form id="form1" class="text-center border border-light p-3 mt-3 vales">
            
                <p class="h4 mb-3">Nuevo Vale</p>
                
                <div class="form-row mb-2 justify-content-center">
                    <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                        <small>Id Vale</small>
                        <input type="text" id="id_vale" class="form-control mx-auto" disabled>
                    </div>
                    <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-2">
                        <small>Fecha</small>
                        <input id="fecha" type="date" class="form-control mx-auto" disabled>
                    </div>
                    <input type="time" id="hora" class="form-control mx-auto d-none ">
                    <input type="text" id="id_usuario" class="form-control mx-auto d-none" value="<?php echo $row["id_usuario"];?>">
                    <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                        <small>Sección</small>
                        <select id="seccion" class="browser-default custom-select">
                        <?php 
                            while($row2 = $result2->fetch_assoc()) {
                            	echo '<option value="' . $row2["seccion"] . '">' .  $row2["seccion"] . '</option>';
                            }
                        ?>
                        </select>
                    </div>
                    <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                        <small>Usuario</small>
                        <input type="text" id="usuario" class="form-control mx-auto" disabled value="<?php echo $row["nombre"] . " " . $row["apellido"];?>">
                    </div>
                    <div class="col-12 col-sm-12 col-md-2 col-lg-1 col-xl-1 mt-auto mb-0">
                        <button id="btn-crear" class="btn btn-amber mx-0 mb-0 btn-form" type="button" onclick="crearVale()"><span id="loader" class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>Crear</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div id="tercero" class="row justify-content-center" style="display:none">
        <div class="col-12 col-sm-12 col-md-12 col-lg-11 col-xl-11">
            <form id="form3" class="text-center border border-light p-3 mt-3 vales">
                <div class="form-row mb-2 justify-content-center">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <small>Observaciones</small>
                        <input type="text" id="observaciones" class="form-control mx-auto">
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div id="segundo" class="row justify-content-center" style="display:none">
        <div class="col-12 col-sm-12 col-md-12 col-lg-11 col-xl-11">

            <form id="form2" class="text-center border border-light p-3 mt-3 vales">
            
                <div class="form-row mb-2 justify-content-center">
                    <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
                        <small>Código<img src="../Imagenes/dobleclick.png" height="15px" class="ml-1"></small>
                        <input type="text" id="codigo" class="form-control mx-auto">
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
                    <div class="col-12 col-sm-12 col-md-2 col-lg-2 col-xl-2 mt-auto mb-0">
                        <button class="btn btn-amber mx-0 mb-0 btn-form" type="button" onclick="agregar()">
                            <span id="loader3" class="spinner-border spinner-border-sm mr-1" style="display:none"  role="status" aria-hidden="true"></span>Agregar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div id="tabla"></div>
    
    <div id="botonera" class="row justify-content-center my-3" style="display:none">
        <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
            <button id="btn-guardarVale" class="btn btn-primary btn-block" type="button" onclick="guardarVale()"><span id="loader2" class="spinner-border spinner-border-sm mr-1" style="display:none" role="status" aria-hidden="true"></span>Guardar</button>
        </div>
        <div class="col-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
            <button class="btn btn-warning btn-block" type="button" onclick="cancelarVale()"><span id="loader4" class="spinner-border spinner-border-sm mr-1" style="display:none"  role="status" aria-hidden="true"></span>Cancelar</button>
        </div>
    </div>
    
    <div id="resultado" class="row justify-content-center"></div>

    
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
                        <button class="btn btn-dark-green mx-0 mb-0 btn-form" type="button" onclick="buscarArticulo()"><span id="loader6" style="display:none;" class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>Buscar</button>
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
    $('#vales').addClass('active');
    document.title = 'Nuevo Vale';

    //Calcula Fecha de hoy local
    var d = new Date();
    var h = new Date(d.getTime() - 180 * 60 * 1000);
    var x = h.toISOString().substr(0, 10);
 
    document.querySelector("#fecha").value = x;
});

$("#codigo").dblclick(function(){$("#botonModal").click();});
$('#codigo').keyup(function(e){if(e.keyCode == 13){consultar();}});
$('#codigo').blur(function(){consultar()});
$("#etiquetas").dblclick(function(){
    $(this).val($("#cantidad").val());
});
$("#cantidad").blur(function(){
    if($("#cantidad").val()==""){
        $("#cantidad").val("1")
    }
});



function consultar(){
    ajax = objetoAjax();

	ajax.open("POST", "consulta_codigo_ajax.php", true);

	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4){
		    if(this.responseText == "No existe código."){
		        $("#descripcion").val("");
		        $("#marca").val("");
		        $("#familia").val("");
		    }else{
    		    myObj = JSON.parse(this.responseText);
                $("#descripcion").val(myObj.descripcion);
                $("#marca").val(myObj.marca);
    		    $("#familia").val(myObj.familia);
    		    $("#cantidad").focus();
		    }
        }
	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("&codigo="+$("#codigo").val());
}

function crearVale(){

    $("#loader").css({"display":"inline-block"});   
    
    ajax = objetoAjax();

	ajax.open("POST", "vales_nuevo_crear_ajax.php", true);

	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4){
            document.getElementById("id_vale").value = (ajax.responseText);
            document.getElementById("btn-crear").disabled = true;
            document.getElementById("seccion").disabled = true;
            $("#loader").css({"display":"none"});
            $("#segundo").fadeIn();
            $("#tercero").fadeIn();
            $("#botonera").fadeIn();
            $("#codigo").focus();

            document.getElementById("btn-guardarVale").disabled = true;
            
        }
	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("&fecha="+$("#fecha").val()+"&seccion="+$("#seccion").val()+"&id_usuario="+$("#id_usuario").val()+"&usuario="+$("#usuario").val());
    
}

function buscarArticulo(){
    $("#loader6").css({"display":"inline-block"});  
    document.getElementById("lista_articulos").innerHTML = "";
    
    var codigo = document.getElementById("codigo2").value;
    var descripcion = document.getElementById("descripcion2").value;
    var marca = document.getElementById("marca2").value;
    var familia = document.getElementById("familia2").value;
    
    ajax = objetoAjax();

	ajax.open("POST", "buscar_articulos_ajax.php", true);

	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4){
            $("#loader6").css({"display":"none"}); 
            document.getElementById("lista_articulos").innerHTML = (ajax.responseText);
            
        }
	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("&codigo="+codigo+"&descripcion="+descripcion+"&marca="+marca+"&familia="+familia);
    
}
function completar(codigo, descripcion, marca, familia){
    $("#codigo").val(codigo);
    $("#descripcion").val(descripcion);
    $("#marca").val(marca);
    $("#familia").val(familia);
    $("#btn-modalClose").click();
    $("#lote").focus();
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

	ajax.open("POST", "vales_nuevo_agregar_ajax.php", true);

	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4){
            if(ajax.responseText.includes("Error")){
                
                $("#loader3").css({"display":"none"});
                alert(ajax.responseText);
                
            }else{
                
                document.getElementById("tabla").innerHTML = (ajax.responseText);
                document.getElementById("form2").reset();
                $("#codigo").focus();
                $("#loader3").css({"display":"none"});
                
                if (ajax.responseText.includes("icon_delete.png")){
                    document.getElementById("btn-guardarVale").disabled = false;
                } else {
                    document.getElementById("btn-guardarVale").disabled = true;
                }
            }
        }
	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("&id_vale="+$("#id_vale").val()+"&codigo="+$("#codigo").val()+"&descripcion="+$("#descripcion").val()+"&marca="+$("#marca").val()+"&familia="+$("#familia").val()+"&cantidad="+$("#cantidad").val());
    
}
function eliminarArticulo(id_art_vale){
    
    ajax = objetoAjax();

	ajax.open("POST", "vales_nuevo_eliminar_ajax.php", true);

	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4){

            if (ajax.responseText == "Sin articulos."){
                document.getElementById("tabla").innerHTML = "";
            } else {
            document.getElementById("tabla").innerHTML = (ajax.responseText);
            }
            if (ajax.responseText.includes("icon_delete.png")){
                document.getElementById("btn-guardarVale").disabled = false;
            } else {
                document.getElementById("btn-guardarVale").disabled = true;
            }
        }
	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("&id_art_vale="+id_art_vale+"&id_vale="+$("#id_vale").val());
}


function guardarVale(){
    var d = new Date();
    var h = new Date(d.getTime() - 180 * 60 * 1000);
    var x = h.toISOString().substr(0, 10);
    
    var ahora = new Date().toLocaleTimeString();
    
    $("#loader2").css({"display":"inline-block"});
    
    ajax = objetoAjax();

	ajax.open("POST", "vales_nuevo_guardar_ajax.php", true);

	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4){
            if (ajax.responseText=="Éxito."){
                document.getElementById("tabla").style.display = "none";
                document.getElementById("primero").style.display = "none";
                document.getElementById("segundo").style.display = "none";
                document.getElementById("tercero").style.display = "none";
                document.getElementById("botonera").style.display = "none";
                $("#resultado").html('<span class="badge badge-success mt-3">El vale fue creado con éxito.</span>');
            } else {
                $("#loader").css({"display":"none"});
                $("#resultado").html('<span class="badge badge-danger mt-3">Error: '+ajax.responseText+'</span>');
            }

        }
	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("&id_vale="+$("#id_vale").val()+"&hora="+ahora+"&fecha="+x+"&observaciones="+$("#observaciones").val());
}

function cancelarVale(){
    if (confirm("Seguro que desea CANCELAR el vale?")){
        
        $("#loader4").css({"display":"inline-block"});
        
        ajax = objetoAjax();
    
    	ajax.open("POST", "vales_nuevo_cancelar_ajax.php", true);
    
    	ajax.onreadystatechange = function() {
    
    		if (ajax.readyState == 4){
                if (ajax.responseText=="Éxito."){
                    document.getElementById("tabla").style.display = "none";
                    document.getElementById("primero").style.display = "none";
                    document.getElementById("segundo").style.display = "none";
                    document.getElementById("tercero").style.display = "none";
                    document.getElementById("botonera").style.display = "none";
                    $("#resultado").html('<span class="badge badge-success mt-3">El vale fue cancelado con éxito.</span>');
                } else {
                    $("#resultado").html('<span class="badge badge-danger mt-3">Error: '+ajax.responseText+'</span>');
                }
                    $("#loader4").css({"display":"none"});
            }
    	}
    
    	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    
    	ajax.send("&id_vale="+$("#id_vale").val());
    }
}

</script>

</body>

</html>