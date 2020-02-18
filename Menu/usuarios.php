<?php
   include('../Headers/session.php');
   include('../Headers/principal.php');
   
   if($_SESSION["tipo"] != "Administrador"){
        exit('<center><p class="mt-3" style="color:red">Su usuario no está autorizado a ingresar a esta sección.</p></center>');
   }
       
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-10">
            <div id="primero" class="text-center border border-light p-3 mt-4 config" style="border-radius:8px;">
                <button style="position:absolute;left:26px;top:40px;border:none;background-color:transparent;" type="button" onclick="nuevoUsuario()"><img src="../Imagenes/mas_violeta.png" width="40px"></button>        
                <p class="h4 mb-3">Usuarios</p>
                <div id="segundo"></div>
            </div>    
            
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-12 col-sm-12 col-md-3 col-lg-2 col-xl-2">
            
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
              <div id="contenido"></div>
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
    buscar();
    
    //$('#c').addClass('active');
    document.title = 'Usuarios';
});

function toggleClave(id){
    //alert(id);
    $("#"+id).toggle();
}

function editar(id){
    var win = window.open("../Menu/usuarios_editar.php?id=" + id, '_blank');
    win.focus();
}
function nuevoUsuario(){
    var win = window.open("../Menu/usuarios_nuevo.php");
    win.focus();
}
function buscar(){
        
        ajax = objetoAjax();

		ajax.open("POST", "usuarios_buscar_ajax.php", true);

		ajax.onreadystatechange = function() {

			if (ajax.readyState == 4){
                //document.getElementById("loader_izq").style.display = "none";
                document.getElementById("segundo").innerHTML = (ajax.responseText);
            }
		}

		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

		ajax.send();
}
function verSecciones(id){
     //var fecha_desde = document.getElementById("fecha_desde").value;
        $("#botonModal").click();
        document.getElementById("contenido").innerHTML = '<center><span id="loader" class="spinner-border spinner-border-sm mr-1" style="display:inline-block;color:#9933CC;" role="status" aria-hidden="true"></span></center>';
        ajax = objetoAjax();

		ajax.open("POST", "usuarios_ver_secciones_ajax.php", true);

		ajax.onreadystatechange = function() {

			if (ajax.readyState == 4){
			    
                document.getElementById("contenido").innerHTML = (ajax.responseText);
            }
		}

		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

		ajax.send("&id_usuario="+id);
}
function agregarSeccion(id_usuario){
    
    if($("#seccion").val() == ''){
        alert("Sección es requerida.");
        return;
    }
          
    ajax = objetoAjax();

	ajax.open("POST", "usuarios_secciones_agregar_ajax.php", true);

	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4){
            if (ajax.responseText.includes("Error")){
                alert(ajax.responseText);
            } else {
                document.getElementById("tabla-secciones").innerHTML = ajax.responseText; 
            }
        }
	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("&seccion="+$("#seccion").val()+"&id_usuario="+id_usuario);
}
function eliminarSeccion(id_secc_user){
    ajax = objetoAjax();

	ajax.open("POST", "usuarios_secciones_eliminar_ajax.php", true);

	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4){
            if (ajax.responseText.includes("Error")){
                alert(ajax.responseText);
            } else {
                document.getElementById("tabla-secciones").innerHTML = ajax.responseText;  
            }
        }
	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("&id_secc_user="+id_secc_user);
}
</script>

</body>

</html>