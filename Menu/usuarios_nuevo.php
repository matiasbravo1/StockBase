<?php
   include('../Headers/session.php');
   include('../Headers/principal.php');
   if($_SESSION["tipo"] != "Administrador"){
        exit('<center><p class="mt-3" style="color:red">Su usuario no está autorizado a ingresar a esta sección.</p></center>');
   }
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">

            <form id="form1" class="text-center border border-light p-3 mt-4 config" style="border-radius:8px;">
            
                <p class="h4 mb-3">Nuevo Usuario</p>
                
                <div class="form-row mb-3">
                    <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">
                        <small>Apellido</small>
                        <input type="text" id="apellido" class="form-control mx-auto" maxlength="25" autofocus required>
                    </div>
                    <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">
                        <small>Nombre</small>
                        <input type="text" id="nombre" class="form-control mx-auto" maxlength="25" required>
                    </div>
                    <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">
                        <small>Tipo de Usuario</small>
                        <select id="tipo" class="browser-default custom-select">
                            <option value="Sección">Sección</option>
                            <option value="Pañol">Pañol</option>
                            <option value="Administrador">Administrador</option>
                        </select>
                    </div>
                </div>
                <div class="form-row justify-content-center mb-4">
                    <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">
                        <small>Usuario</small>
                        <input type="text" id="usuario" class="form-control mx-auto" maxlength="20" required>
                    </div>
                    <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">
                        <small>Contraseña</small>
                        <input type="text" id="clave" class="form-control mx-auto" maxlength="20" required>                        
                    </div>
                </div>
                <div class="form-row justify-content-center mb-3">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="activo" checked>
                        <label class="custom-control-label" for="activo">Activo</label>
                    </div>
                </div>
                
                <div class="form-row mb-2">
                    <div class="col-6">
                        <button class="btn btn-primary mt-3 btn-block" type="button" onclick="guardar()"><span id="loader" class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Guardar</button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-warning mt-3 btn-block" type="button" onclick="cancelar()">Cancelar</button>
                    </div>
                </div>
                
            </form>
            
            <div id="resultado" class="text-center" style="display:none"></div>
        </div>
    </div>
</div>

<script>
$( document ).ready(function() {
    //$('#stock').addClass('active');
    document.title = 'Nuevo Usuario';
});

$("#nombre").focusout(function(){
    if ($(this).val() == ''){
        $("#nombre").addClass('requerido');
    }else{
        $("#nombre").removeClass('requerido');
    }
});

$("#apellido").focusout(function(){
    if ($(this).val() == ''){
        $("#apellido").addClass('requerido');
    }else{
        $("#apellido").removeClass('requerido');
    }
});

$("#usuario").focusout(function(){
    if ($(this).val() == ''){
        $("#usuario").addClass('requerido');
    }else{
        $("#usuario").removeClass('requerido');
    }
});
$("#clave").focusout(function(){
    if ($(this).val() == ''){
        $("#clave").addClass('requerido');
    }else{
        $("#clave").removeClass('requerido');
    }
});
function guardar(){
    $("#resultado").hide();    
    
    if ($("#apellido").val() == '' || $("#nombre").val() == '' || $("#usuario").val() == '' || $("#clave").val() == ''){
        alert("Todos los campos son requeridos.");
        return;
    }

    $("#loader").css({"display":"inline-block"});
    
    ajax = objetoAjax();

	ajax.open("POST", "usuarios_crear_ajax.php", true);

	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4){
		    $("#loader").css({"display":"none"});
            if (ajax.responseText.includes("Error")){
                $("#resultado").html('<span class="badge badge-danger">'+ajax.responseText+'</span>')
            } else {
                $("#resultado").html('<span class="badge badge-success">'+ajax.responseText+'</span>')
                document.getElementById("form1").reset();
                $("#apellido").focus();
            }
            $("#resultado").show();
        }
	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("&apellido="+$("#apellido").val()+"&nombre="+$("#nombre").val()+"&tipo="+$( "#tipo option:selected" ).text()+"&usuario="+$("#usuario").val()+"&clave="+$("#clave").val()+"&activo="+$("#activo").is(':checked'));
}

function cancelar(){
    window.close();
}
</script>
</body>

</html>