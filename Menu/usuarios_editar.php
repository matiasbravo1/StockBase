<?php
   include('../Headers/session.php');
   include('../Headers/principal.php');
   
   if($_SESSION["tipo"] != "Administrador"){
        exit('<center><p class="mt-3" style="color:red">Su usuario no está autorizado a ingresar a esta sección.</p></center>');
   }
   
   if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id_usuario = test_input($_GET["id"]);
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$sql = "SELECT * FROM usuarios WHERE `id_usuario` = '$id_usuario'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    exit('<br><div class="text-center">El usuario a editar ya no existe. Recuerde actualizar las búsquedas.</div>');
}

$row = $result->fetch_assoc();
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">

            <form id="form1" class="text-center border border-light p-3 mt-4 config" style="border-radius:8px;">
            
                <p class="h4 mb-3">Editar Usuario</p>
                
                <div class="form-row mb-3">
                    <input id="id_usuario" value="<?php echo $id_usuario;?>" type="text" class="d-none" disabled>
                    <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">
                        <small>Apellido</small>
                        <input type="text" id="apellido" value="<?php echo $row["apellido"];?>" class="form-control mx-auto" maxlength="25" autofocus required>
                    </div>
                    <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">
                        <small>Nombre</small>
                        <input type="text" id="nombre" value="<?php echo $row["nombre"];?>" class="form-control mx-auto" maxlength="25" required>                        
                    </div>
                    <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">
                        <small>Tipo de Usuario</small>
                        <?php
                                $seccion = $paniol = $admin = "";
                                if($row["tipo"] == "Sección"){
                                    $seccion = "selected";
                                }elseif($row["tipo"] == "Pañol"){
                                    $paniol = "selected";
                                }elseif($row["tipo"] == "Administrador"){
                                    $admin = "selected";
                                }
                            ?>
                        <select id="tipo" class="browser-default custom-select">
                            <option value="Sección"<?php echo $seccion;?>>Sección</option>
                            <option value="Pañol"<?php echo $paniol;?>>Pañol</option>
                            <option value="Administrador"<?php echo $admin;?>>Administrador</option>
                        </select>
                    </div>
                </div>
                <div class="form-row justify-content-center mb-4">
                    <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">
                        <small>Usuario</small>
                        <input type="text" id="usuario" value="<?php echo $row["usuario"];?>" class="form-control mx-auto" maxlength="20" required>
                    </div>
                    <div class="col-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">
                        <small>Contraseña</small>
                        <input type="text" id="clave" value="<?php echo $row["clave"];?>" class="form-control mx-auto" maxlength="20" required>                        
                    </div>
                </div>
                
                <div class="form-row justify-content-center mb-3">
                    <div class="custom-control custom-switch">
                        <?php
                            ($row["activo"] == '1') ? $activo = "checked" : $activo = '';
                        ?>
                        <input type="checkbox" class="custom-control-input" id="activo" <?php echo $activo;?> >
                        <label class="custom-control-label" for="activo">Activo</label>
                    </div>
                </div>
                
                <div class="form-row mb-2">
                    <div class="col-5">
                        <button class="btn btn-primary mt-3 btn-block" type="button" onclick="guardar()"><span id="loader" class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Guardar</button>
                    </div>
                    <div class="col-5">
                        <button class="btn btn-warning mt-3 btn-block" type="button" onclick="cancelar()">Cancelar</button>
                    </div>
                    <div class="col-2">
                        <button class="btn btn-danger mt-3 btn-block px-0" type="button" onclick="eliminar()">Eliminar</button>
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
    document.title = 'Editar Usuario';
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

	ajax.open("POST", "usuarios_editar_guardar_ajax.php", true);

	ajax.onreadystatechange = function() {

		if (ajax.readyState == 4){
		    $("#loader").css({"display":"none"});
            if (ajax.responseText.includes("Error")){
                $("#resultado").html('<span class="mt-3 badge badge-danger">'+ajax.responseText+'</span>')
            } else {
                $("#resultado").html('<span class="mt-3 badge badge-success">'+ajax.responseText+'</span><br><div class="text-center"><button class="btn btn-secondary mt-3 btn-sm" type="button" onclick="cancelar()">Cerrar</button></div>')
                $("#form1").hide();
            }
            $("#resultado").show();
        }
	}

	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

	ajax.send("&id_usuario="+$("#id_usuario").val()+"&apellido="+$("#apellido").val()+"&nombre="+$("#nombre").val()+"&tipo="+$( "#tipo option:selected" ).text()+"&usuario="+$("#usuario").val()+"&clave="+$("#clave").val()+"&activo="+$("#activo").is(':checked'));
}

function eliminar(){

    var r = confirm("Seguro que desea eliminar el usuario?");
    
    if (r == true) {
        
        ajax = objetoAjax();

    	ajax.open("POST", "usuarios_eliminar_ajax.php", true);
    
    	ajax.onreadystatechange = function() {
    
    		if (ajax.readyState == 4){
                if (ajax.responseText.includes("Error")){
                    $("#resultado").html('<span class="mt-3 badge badge-danger">'+ajax.responseText+'</span>');
                    $("#resultado").show();
                } else {
                    $("#resultado").html('<span class="mt-3 badge badge-success">'+ajax.responseText+'</span><br><div class="text-center"><button class="btn btn-secondary mt-3 btn-sm" type="button" onclick="cancelar()">Cerrar</button></div>')
                    $("#form1").hide();
                    $("#resultado").show();
                }
        	}
        }    
    	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    
    	ajax.send("&id_usuario="+$("#id_usuario").val());

    }
}

function cancelar(){
    window.close();
}

</script>
</body>

</html>